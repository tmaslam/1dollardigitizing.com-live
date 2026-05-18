<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sites') && ! Schema::hasColumn('sites', 'phone_number')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->string('phone_number', 100)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sites') && Schema::hasColumn('sites', 'phone_number')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->dropColumn('phone_number');
            });
        }
    }
};
