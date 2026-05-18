<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode=''");
        Schema::table('users', function (Blueprint $table) {
            $table->string('team_group', 20)->nullable()->default(null)->after('usre_type_id');
        });
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode=''");
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('team_group');
        });
    }
};
