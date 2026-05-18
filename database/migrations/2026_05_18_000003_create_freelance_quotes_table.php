<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freelance_quotes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->integer('order_id')->index();
            $table->integer('team_user_id')->index();
            $table->decimal('quoted_price', 10, 2);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->integer('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->unique(['order_id', 'team_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelance_quotes');
    }
};
