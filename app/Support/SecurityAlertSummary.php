<?php

namespace App\Support;

use App\Models\SecurityAuditEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class SecurityAlertSummary
{
    private const WARNING_SEVERITIES = ['warning', 'error', 'critical', 'alert', 'emergency'];

    private const CRITICAL_SEVERITIES = ['critical', 'alert', 'emergency'];

    public static function available(): bool
    {
        return Schema::hasTable('security_audit_events');
    }

    public static function actionableCount(int $hours = 24): int
    {
        if (! self::available()) {
            return 0;
        }

        return self::windowQuery($hours)
            ->whereIn('severity', self::WARNING_SEVERITIES)
            ->count();
    }

    public static function summary(int $hours = 24): array
    {
        if (! self::available()) {
            return [
                'available' => false,
                'window_hours' => $hours,
                'total_events' => 0,
                'actionable_events' => 0,
                'critical_events' => 0,
                'failed_logins' => 0,
                'unauthorized_access' => 0,
                'upload_rejections' => 0,
                'turnstile_failures' => 0,
                'recent_events' => collect(),
                'top_ips' => collect(),
            ];
        }

        $windowQuery = self::windowQuery($hours);

        $topIps = self::topIps($hours);
        $recentEvents = self::recentEvents($hours);

        return [
            'available' => true,
            'window_hours' => $hours,
            'total_events' => (clone $windowQuery)->count(),
            'actionable_events' => (clone $windowQuery)->whereIn('severity', self::WARNING_SEVERITIES)->count(),
            'critical_events' => (clone $windowQuery)->whereIn('severity', self::CRITICAL_SEVERITIES)->count(),
            'failed_logins' => (clone $windowQuery)->whereIn('event_type', [
                'auth.login_failed',
                'auth.login_blocked',
                'auth.login_rate_limited',
                'auth.account_locked',
            ])->count(),
            'unauthorized_access' => (clone $windowQuery)->where('event_type', 'auth.unauthorized_access')->count(),
            'upload_rejections' => (clone $windowQuery)->where('event_type', 'files.upload_rejected')->count(),
            'turnstile_failures' => (clone $windowQuery)->where('event_type', 'bot.turnstile_failed')->count(),
            'recent_events' => $recentEvents,
            'top_ips' => $topIps,
            'suggested_actions' => self::suggestedActions($windowQuery, $topIps, $recentEvents),
        ];
    }

    private static function windowQuery(int $hours): Builder
    {
        return SecurityAuditEvent::query()
            ->where('created_at', '>=', now()->subHours(max(1, $hours))->format('Y-m-d H:i:s'));
    }

    private static function recentEvents(int $hours): Collection
    {
        return self::windowQuery($hours)
            ->whereIn('severity', self::WARNING_SEVERITIES)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();
    }

    private static function suggestedActions(Builder $windowQuery, Collection $topIps, Collection $recentEvents): Collection
    {
        $actions = collect();

        // 1. Suggest blocking IPs with high concentrations of actionable events
        foreach ($topIps as $ip) {
            if ($ip->critical_events > 0 || $ip->actionable_events >= 3) {
                $actions->push([
                    'type' => 'block_ip',
                    'label' => 'Block IP',
                    'description' => "{$ip->ip_address} triggered {$ip->actionable_events} warning+ events ({$ip->critical_events} critical).",
                    'ip_address' => $ip->ip_address,
                    'count' => $ip->actionable_events,
                    'severity' => $ip->critical_events > 0 ? 'critical' : 'warning',
                ]);
            }
        }

        // 2. Suggest reviewing accounts that were permanently locked
        $lockedAccounts = (clone $windowQuery)
            ->where('event_type', 'auth.account_locked')
            ->whereNotNull('actor_user_id')
            ->select('actor_user_id', 'actor_login')
            ->selectRaw('COUNT(*) as event_count')
            ->groupBy('actor_user_id', 'actor_login')
            ->orderByDesc('event_count')
            ->limit(5)
            ->get();

        foreach ($lockedAccounts as $account) {
            $actions->push([
                'type' => 'review_account',
                'label' => 'Review Locked Account',
                'description' => "Account '{$account->actor_login}' (ID: {$account->actor_user_id}) was permanently locked.",
                'user_id' => $account->actor_user_id,
                'login' => $account->actor_login,
                'count' => (int) $account->event_count,
                'severity' => 'critical',
            ]);
        }

        // 3. Suggest reviewing login blocks
        $blockedLogins = (clone $windowQuery)
            ->whereIn('event_type', ['auth.login_blocked', 'auth.login_rate_limited'])
            ->whereNotNull('actor_user_id')
            ->select('actor_user_id', 'actor_login')
            ->selectRaw('COUNT(*) as event_count')
            ->groupBy('actor_user_id', 'actor_login')
            ->orderByDesc('event_count')
            ->limit(5)
            ->get();

        foreach ($blockedLogins as $account) {
            // Skip if already added as locked account for same user
            if ($actions->contains(fn ($a) => $a['type'] === 'review_account' && ($a['user_id'] ?? null) == $account->actor_user_id)) {
                continue;
            }
            $actions->push([
                'type' => 'review_login',
                'label' => 'Review Login Blocks',
                'description' => "Account '{$account->actor_login}' (ID: {$account->actor_user_id}) had {$account->event_count} blocked/rate-limited login attempts.",
                'user_id' => $account->actor_user_id,
                'login' => $account->actor_login,
                'count' => (int) $account->event_count,
                'severity' => 'warning',
            ]);
        }

        return $actions->values();
    }

    private static function topIps(int $hours): Collection
    {
        $warningList = "'".implode("','", self::WARNING_SEVERITIES)."'";
        $criticalList = "'".implode("','", self::CRITICAL_SEVERITIES)."'";

        return self::windowQuery($hours)
            ->select('ip_address')
            ->selectRaw('COUNT(*) as total_events')
            ->selectRaw("SUM(CASE WHEN severity IN ({$warningList}) THEN 1 ELSE 0 END) as actionable_events")
            ->selectRaw("SUM(CASE WHEN severity IN ({$criticalList}) THEN 1 ELSE 0 END) as critical_events")
            ->whereNotNull('ip_address')
            ->where('ip_address', '!=', '')
            ->groupBy('ip_address')
            ->orderByDesc('critical_events')
            ->orderByDesc('actionable_events')
            ->orderByDesc('total_events')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->total_events = (int) $row->total_events;
                $row->actionable_events = (int) $row->actionable_events;
                $row->critical_events = (int) $row->critical_events;

                return $row;
            });
    }
}
