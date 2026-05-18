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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('assigned_group', 20)->nullable()->default(null)->after('assign_to');
        });
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode=''");
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('assigned_group');
        });
    }
};
