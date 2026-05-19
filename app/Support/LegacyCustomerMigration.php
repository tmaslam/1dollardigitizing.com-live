<?php

namespace App\Support;

use App\Models\AdminUser;
use Illuminate\Support\Facades\DB;
use Throwable;

class LegacyCustomerMigration
{
    public static function migrate(AdminUser $v2Customer): void
    {
        if ($v2Customer->legacy_migrated_at !== null) {
            return;
        }

        try {
            self::run($v2Customer);
        } catch (Throwable $e) {
            \Illuminate\Support\Facades\Log::error('LegacyCustomerMigration failed', [
                'user_id' => $v2Customer->user_id,
                'email'   => $v2Customer->user_email,
                'error'   => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }
    }

    // Same as migrate() but re-throws so the Artisan command can surface errors.
    public static function migrateOrFail(AdminUser $v2Customer): void
    {
        if ($v2Customer->legacy_migrated_at !== null) {
            return;
        }

        self::run($v2Customer);
    }

    private static function run(AdminUser $v2Customer): void
    {
        $legacyUser = DB::connection('legacy')
            ->table('users')
            ->where('user_email', $v2Customer->user_email)
            ->first();

        if (! $legacyUser) {
            $v2Customer->update(['legacy_migrated_at' => now()]);
            return;
        }

        $legacyUserId = $legacyUser->user_id;
        $v2UserId     = $v2Customer->user_id;

        // Build allowed-column sets from the v2 schema to avoid "Unknown column" errors
        // when the legacy table has extra columns v2 doesn't.
        $v2OrderCols      = self::tableColumns('orders');
        $v2BillingCols    = self::tableColumns('billing');
        $v2AttachCols     = self::tableColumns('attach_files');

        // --- Copy orders ---
        $legacyOrders = DB::connection('legacy')
            ->table('orders')
            ->where('user_id', $legacyUserId)
            ->whereRaw("(end_date IS NULL OR end_date = '' OR end_date = '0000-00-00')")
            ->get();

        $orderIdMap = [];

        foreach ($legacyOrders as $order) {
            $data = self::pick((array) $order, $v2OrderCols, ['order_id']);
            $data['user_id'] = $v2UserId;

            $newId = DB::table('orders')->insertGetId($data);
            $orderIdMap[$order->order_id] = $newId;
        }

        if (empty($orderIdMap)) {
            $v2Customer->update(['legacy_migrated_at' => now()]);
            return;
        }

        // --- Copy billing records ---
        $legacyBillings = DB::connection('legacy')
            ->table('billing')
            ->whereIn('order_id', array_keys($orderIdMap))
            ->get();

        foreach ($legacyBillings as $billing) {
            $data = self::pick((array) $billing, $v2BillingCols, ['bill_id']);
            $data['user_id']  = $v2UserId;
            $data['order_id'] = $orderIdMap[$billing->order_id];
            DB::table('billing')->insert($data);
        }

        // --- Copy attachments and files ---
        $legacyAttachments = DB::connection('legacy')
            ->table('attach_files')
            ->whereIn('order_id', array_keys($orderIdMap))
            ->get();

        $legacyUploadsPath = rtrim((string) env('LEGACY_UPLOADS_PATH', '/home/digixjhl/legacy.1dollardigitizing.com/uploads'), '/');
        $v2UploadsPath     = rtrim((string) env('SHARED_UPLOADS_PATH', ''), '/');

        // Safety guard: refuse to copy if the paths are the same — that would
        // overwrite or pollute the legacy directory with v2 data.
        if ($v2UploadsPath !== '' && $v2UploadsPath === $legacyUploadsPath) {
            throw new \RuntimeException(
                'SHARED_UPLOADS_PATH and LEGACY_UPLOADS_PATH must not point to the same directory.'
            );
        }

        foreach ($legacyAttachments as $attachment) {
            if ($v2UploadsPath !== '' && $attachment->file_source !== null) {
                $relPath = ltrim((string) $attachment->file_source, '/');
                $src     = $legacyUploadsPath . '/' . $relPath;
                $dest    = $v2UploadsPath     . '/' . $relPath;

                if (file_exists($src) && ! file_exists($dest)) {
                    @mkdir(dirname($dest), 0755, true);
                    @copy($src, $dest);
                }
            }

            $data = self::pick((array) $attachment, $v2AttachCols, ['id']);
            $data['order_id'] = $orderIdMap[$attachment->order_id];
            DB::table('attach_files')->insert($data);
        }

        $v2Customer->update(['legacy_migrated_at' => now()]);
    }

    /** Return column names for a v2 table (cached per request). */
    private static array $columnCache = [];

    private static function tableColumns(string $table): array
    {
        if (! isset(self::$columnCache[$table])) {
            self::$columnCache[$table] = array_flip(
                array_column(DB::select("DESCRIBE `{$table}`"), 'Field')
            );
        }
        return self::$columnCache[$table];
    }

    /**
     * Keep only keys present in $allowedMap (a flip'd column list),
     * then drop any keys in $exclude.
     */
    private static function pick(array $row, array $allowedMap, array $exclude = []): array
    {
        $filtered = array_intersect_key($row, $allowedMap);
        foreach ($exclude as $col) {
            unset($filtered[$col]);
        }
        return $filtered;
    }
}
