<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_fines', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->integer('order_id')->index();
            $table->integer('team_user_id')->index();
            $table->integer('imposed_by');
            $table->decimal('amount', 10, 2);
            $table->text('reason');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_fines');
    }
};
