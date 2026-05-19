<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to avoid Laravel's Schema builder triggering a full
        // ALTER TABLE rebuild, which fails on this table because the existing
        // date_added column has a 0000-00-00 default (invalid in strict mode).
        DB::statement('ALTER TABLE `users` ADD COLUMN `legacy_migrated_at` TIMESTAMP NULL DEFAULT NULL AFTER `password_migrated_at`');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `users` DROP COLUMN `legacy_migrated_at`');
    }
};
