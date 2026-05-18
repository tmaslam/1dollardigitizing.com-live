<?php

namespace App\Support;

use App\Models\AdminUser;
use App\Models\SupervisorTeamMember;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class TeamAccess
{
    public static function accessibleUserIds(AdminUser $teamUser): array
    {
        $ids = [$teamUser->user_id];

        if ((int) $teamUser->usre_type_id === AdminUser::TYPE_SUPERVISOR) {
            $ids = array_merge($ids, self::supervisorMemberIds($teamUser));
        }

        $teamPortalIds = AdminUser::query()
            ->teamPortalUsers()
            ->active()
            ->where('is_active', 1)
            ->whereIn('user_id', array_unique(array_map('intval', $ids)))
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return array_values(array_unique($teamPortalIds));
    }

    public static function teamMembers(AdminUser $teamUser): Collection
    {
        if ((int) $teamUser->usre_type_id !== AdminUser::TYPE_SUPERVISOR) {
            return collect();
        }

        $memberIds = self::supervisorMemberIds($teamUser);

        if ($memberIds === []) {
            return collect();
        }

        return AdminUser::query()
            ->teams()
            ->active()
            ->where('is_active', 1)
            ->whereIn('user_id', $memberIds)
            ->orderBy('user_name')
            ->get();
    }

    public static function assignableUsers(AdminUser $teamUser): Collection
    {
        if ((int) $teamUser->usre_type_id !== AdminUser::TYPE_SUPERVISOR) {
            return collect([$teamUser]);
        }

        $ids = self::accessibleUserIds($teamUser);

        return AdminUser::query()
            ->teamPortalUsers()
            ->active()
            ->where('is_active', 1)
            ->whereIn('user_id', $ids)
            ->orderByRaw('CASE WHEN usre_type_id = ? THEN 0 ELSE 1 END', [AdminUser::TYPE_SUPERVISOR])
            ->orderBy('user_name')
            ->get();
    }

    public static function canManageUser(AdminUser $teamUser, int $managedUserId): bool
    {
        return in_array($managedUserId, self::accessibleUserIds($teamUser), true);
    }

    public static function groupForUser(AdminUser $teamUser): ?string
    {
        $group = $teamUser->team_group ?? null;

        return ($group !== null && $group !== '') ? $group : null;
    }

    /**
     * Apply group-visibility filter to an Order query.
     *
     * Supervisors see all jobs (both groups + directly assigned).
     * In-house members see inhouse group-pool + own accepted jobs.
     * Freelance members see freelance group-pool + own accepted jobs.
     */
    public static function applyVisibilityScope(Builder $query, AdminUser $teamUser): Builder
    {
        $userId = (int) $teamUser->user_id;
        $isSupervisor = (int) $teamUser->usre_type_id === AdminUser::TYPE_SUPERVISOR;

        if ($isSupervisor) {
            $memberIds = self::accessibleUserIds($teamUser);

            return $query->where(function (Builder $q) use ($memberIds) {
                // Directly assigned to supervisor or any managed member
                $q->whereIn('assign_to', $memberIds)
                  // OR: unaccepted group-pool jobs (both groups)
                  ->orWhere(function (Builder $q2) {
                      $q2->whereNotNull('assigned_group')
                         ->where('assigned_group', '!=', '')
                         ->where(function (Builder $q3) {
                             $q3->whereNull('assign_to')
                                ->orWhere('assign_to', 0)
                                ->orWhere('assign_to', '');
                         });
                  });
            });
        }

        $group = self::groupForUser($teamUser);

        return $query->where(function (Builder $q) use ($userId, $group) {
            // Own directly-assigned jobs
            $q->where('assign_to', $userId);

            // Group-pool jobs not yet claimed by anyone
            if ($group !== null) {
                $q->orWhere(function (Builder $q2) use ($group) {
                    $q2->where('assigned_group', $group)
                       ->where(function (Builder $q3) {
                           $q3->whereNull('assign_to')
                              ->orWhere('assign_to', 0)
                              ->orWhere('assign_to', '');
                       });
                });
            }
        });
    }

    /**
     * Check whether a team user can see/act on a specific order.
     */
    public static function canAccessOrder(AdminUser $teamUser, \App\Models\Order $order): bool
    {
        $userId = (int) $teamUser->user_id;
        $isSupervisor = (int) $teamUser->usre_type_id === AdminUser::TYPE_SUPERVISOR;

        if ($isSupervisor) {
            $memberIds = self::accessibleUserIds($teamUser);
            $assignTo = (int) $order->assign_to;

            return $assignTo === 0
                || in_array($assignTo, $memberIds, true)
                || ($order->assigned_group !== null && $order->assigned_group !== '');
        }

        // Directly assigned to this user
        if ((int) $order->assign_to === $userId) {
            return true;
        }

        // Unaccepted group job matching user's group
        $group = self::groupForUser($teamUser);

        return $group !== null
            && $order->assigned_group === $group
            && ((int) $order->assign_to === 0 || $order->assign_to === null || $order->assign_to === '');
    }

    private static function supervisorMemberIds(AdminUser $teamUser): array
    {
        $memberIds = [];

        if (Schema::hasTable('supervisor_team_members')) {
            $memberIds = SupervisorTeamMember::query()
                ->active()
                ->where('supervisor_user_id', $teamUser->user_id)
                ->pluck('member_user_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $fallbackIds = AdminUser::query()
            ->teams()
            ->active()
            ->where('is_active', 1)
            ->where('register_by', $teamUser->user_name)
            ->pluck('user_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        return array_values(array_unique(array_merge($memberIds, $fallbackIds)));
    }
}
