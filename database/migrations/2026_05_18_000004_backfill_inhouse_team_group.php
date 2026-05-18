<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode=''");
        DB::table('users')
            ->where('usre_type_id', 2)
            ->whereNull('team_group')
            ->update(['team_group' => 'inhouse']);
    }

    public function down(): void
    {
        // Intentionally not reversing the backfill — would overwrite intentional assignments
    }
};
