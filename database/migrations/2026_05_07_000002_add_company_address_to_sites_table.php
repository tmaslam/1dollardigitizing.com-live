<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sites') && ! Schema::hasColumn('sites', 'company_address')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->string('company_address', 500)->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sites') && Schema::hasColumn('sites', 'company_address')) {
            Schema::table('sites', function (Blueprint $table) {
                $table->dropColumn('company_address');
            });
        }
    }
};
