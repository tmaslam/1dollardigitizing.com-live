<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'payment_terms')) {
            DB::statement("SET sql_mode = ''");
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `payment_terms` INT(5) NOT NULL DEFAULT 5");
            DB::statement("SET sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'payment_terms')) {
            DB::statement("SET sql_mode = ''");
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `payment_terms` INT(5) NOT NULL DEFAULT 7");
            DB::statement("SET sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
        }
    }
};
