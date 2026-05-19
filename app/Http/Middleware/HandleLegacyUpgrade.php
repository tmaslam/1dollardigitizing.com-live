<?php

namespace App\Http\Middleware;

use App\Models\AdminUser;
use App\Support\LegacyCustomerMigration;
use App\Support\SiteContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class HandleLegacyUpgrade
{
    // Token is valid for 1 hour.
    private const TOKEN_TTL_SECONDS = 3600;

    public function handle(Request $request, Closure $next): Response
    {
        $token = trim((string) $request->query('legacy_customer_id', ''));

        if ($token === '') {
            return $next($request);
        }

        $userId = $this->decrypt($token);

        if ($userId === null) {
            Log::warning('LegacyUpgrade: invalid or expired token', [
                'ip'    => $request->ip(),
                'token' => substr($token, 0, 20) . '...',
            ]);

            return redirect(url('/login.php'))->withErrors([
                'auth' => 'Your upgrade link has expired or is invalid. Please contact support.',
            ]);
        }

        // Find the legacy user to get their email.
        $legacyUser = DB::connection('legacy')
            ->table('users')
            ->where('user_id', $userId)
            ->first();

        if (! $legacyUser) {
            Log::warning('LegacyUpgrade: legacy user not found', ['legacy_user_id' => $userId]);

            return redirect(url('/login.php'))->withErrors([
                'auth' => 'Account not found. Please contact support.',
            ]);
        }

        // Find the matching v2 customer.
        $customer = AdminUser::query()
            ->customers()
            ->where('user_email', $legacyUser->user_email)
            ->first();

        if (! $customer) {
            Log::warning('LegacyUpgrade: no v2 customer for email', ['email' => $legacyUser->user_email]);

            return redirect(url('/login.php'))->withErrors([
                'auth' => 'Account not found in the new system. Please contact support.',
            ]);
        }

        /** @var SiteContext $site */
        $site = $request->attributes->get('siteContext');

        // Activate the account if not already active.
        if ((int) $customer->is_active !== 1) {
            $customer->update([
                'is_active'      => 1,
                'user_term'      => 'active',
                'exist_customer' => '1',
            ]);
            $customer->refresh();
        }

        // Copy orders, billing, and files from the legacy database.
        LegacyCustomerMigration::migrate($customer->fresh());

        // Establish the customer session (same variables as normal login).
        $request->session()->forget([
            'admin_user_id',
            'admin_user_name',
            'team_user_id',
            'team_user_name',
        ]);
        $request->session()->regenerate();
        $request->session()->put([
            'customer_user_id'   => (int) $customer->user_id,
            'customer_user_name' => (string) $customer->display_name,
            'customer_site_key'  => $site->legacyKey,
        ]);

        Log::info('LegacyUpgrade: auto-login successful', [
            'user_id' => $customer->user_id,
            'email'   => $customer->user_email,
        ]);

        // Redirect to the dashboard without the token parameter so the
        // normal customer.auth middleware takes over from here.
        return redirect(url('/dashboard.php'));
    }

    private function decrypt(string $token): ?int
    {
        $padding = str_repeat('=', (4 - strlen($token) % 4) % 4);
        $raw = base64_decode(strtr($token, '-_', '+/') . $padding);

        if ($raw === false || strlen($raw) < 17) {
            return null;
        }

        $iv         = substr($raw, 0, 16);
        $ciphertext = substr($raw, 16);

        $keys = [];
        $explicitSecret = (string) config('app.legacy_migration_secret');
        if ($explicitSecret !== '') {
            $keys[] = hash('sha256', $explicitSecret, true);
        }
        $appKey = (string) config('app.key');
        if (str_starts_with($appKey, 'base64:')) {
            $appKey = base64_decode(substr($appKey, 7));
        }
        $keys[] = hash('sha256', $appKey . ':legacy_upgrade', true);

        foreach ($keys as $key) {
            $plain = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
            if ($plain === false) {
                continue;
            }
            $parts = explode(':', $plain, 2);
            if (count($parts) !== 2 || ! ctype_digit($parts[0]) || ! ctype_digit($parts[1])) {
                continue;
            }
            if (time() - (int) $parts[1] > self::TOKEN_TTL_SECONDS) {
                return null;
            }
            return (int) $parts[0];
        }

        return null;
    }
}
