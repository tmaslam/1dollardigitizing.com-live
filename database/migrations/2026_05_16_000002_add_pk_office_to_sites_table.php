<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('sites')) {
            Schema::table('sites', function (Blueprint $table) {
                if (! Schema::hasColumn('sites', 'pk_phone_number')) {
                    $table->string('pk_phone_number', 100)->nullable();
                }
                if (! Schema::hasColumn('sites', 'pk_address')) {
                    $table->string('pk_address', 500)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sites')) {
            Schema::table('sites', function (Blueprint $table) {
                if (Schema::hasColumn('sites', 'pk_phone_number')) {
                    $table->dropColumn('pk_phone_number');
                }
                if (Schema::hasColumn('sites', 'pk_address')) {
                    $table->dropColumn('pk_address');
                }
            });
        }
    }
};
