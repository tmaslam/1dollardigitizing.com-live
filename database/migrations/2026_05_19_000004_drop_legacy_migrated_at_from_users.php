<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'legacy_migrated_at')) {
            DB::statement("SET SESSION sql_mode = ''");
            DB::statement('ALTER TABLE `users` DROP COLUMN `legacy_migrated_at`');
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'legacy_migrated_at')) {
            DB::statement("SET SESSION sql_mode = ''");
            DB::statement('ALTER TABLE `users` ADD COLUMN `legacy_migrated_at` TIMESTAMP NULL DEFAULT NULL AFTER `password_migrated_at`');
        }
    }
};
