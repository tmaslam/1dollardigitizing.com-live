<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Support\LegacyCustomerMigration;
use App\Support\SiteContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LegacyMigrationController extends Controller
{
    // Token is valid for 15 minutes after the legacy system generates it.
    private const TOKEN_TTL_SECONDS = 900;

    public function handle(Request $request)
    {
        $token = trim((string) $request->query('legacy_customer_id', ''));

        if ($token === '') {
            return redirect(url('/login.php'));
        }

        $legacyUserId = $this->decrypt($token);

        if ($legacyUserId === null) {
            Log::warning('LegacyMigration: invalid or expired token', [
                'ip'    => $request->ip(),
                'token' => substr($token, 0, 20) . '...',
            ]);

            return redirect(url('/login.php'))->withErrors([
                'auth' => 'Your upgrade link has expired or is invalid. Please contact support.',
            ]);
        }

        // Look up the legacy user by their legacy user_id to get the email.
        $legacyUser = DB::connection('legacy')
            ->table('users')
            ->where('user_id', $legacyUserId)
            ->first();

        if (! $legacyUser) {
            Log::warning('LegacyMigration: legacy user not found', ['legacy_user_id' => $legacyUserId]);

            return redirect(url('/login.php'))->withErrors([
                'auth' => 'Account not found. Please contact support.',
            ]);
        }

        // Find the matching v2 account by email.
        $customer = AdminUser::query()
            ->customers()
            ->where('user_email', $legacyUser->user_email)
            ->first();

        if (! $customer) {
            Log::warning('LegacyMigration: no v2 customer for email', [
                'email'          => $legacyUser->user_email,
                'legacy_user_id' => $legacyUserId,
            ]);

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

        // Copy orders, billing records, and files from the legacy database.
        LegacyCustomerMigration::migrate($customer->fresh());

        // Establish the customer session (same as normal login).
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

        Log::info('LegacyMigration: auto-login successful', [
            'user_id'        => $customer->user_id,
            'email'          => $customer->user_email,
            'legacy_user_id' => $legacyUserId,
        ]);

        return redirect(url('/dashboard.php'));
    }

    private function decrypt(string $token): ?int
    {
        $secret = (string) config('app.legacy_migration_secret');

        if ($secret === '') {
            Log::error('LegacyMigration: LEGACY_MIGRATION_SECRET is not configured on this server');
            return null;
        }

        // Restore standard base64 padding from URL-safe encoding.
        $padding = str_repeat('=', (4 - strlen($token) % 4) % 4);
        $raw = base64_decode(strtr($token, '-_', '+/') . $padding);

        if ($raw === false || strlen($raw) < 17) {
            return null;
        }

        $iv         = substr($raw, 0, 16);
        $ciphertext = substr($raw, 16);
        $key        = hash('sha256', $secret, true); // 32-byte AES key

        $plain = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

        if ($plain === false) {
            return null;
        }

        // Payload format: "user_id:unix_timestamp"
        $parts = explode(':', $plain, 2);
        if (count($parts) !== 2 || ! ctype_digit($parts[0]) || ! ctype_digit($parts[1])) {
            return null;
        }

        if (time() - (int) $parts[1] > self::TOKEN_TTL_SECONDS) {
            return null;
        }

        return (int) $parts[0];
    }
}
