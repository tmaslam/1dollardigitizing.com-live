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
            $table->enum('supervisor_status', ['pending', 'approved', 'disapproved'])->nullable()->default(null)->after('assigned_group');
            $table->unsignedInteger('freelance_payment_request_id')->nullable()->default(null)->after('supervisor_status');
        });
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode=''");
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['supervisor_status', 'freelance_payment_request_id']);
        });
    }
};
