<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('freelance_payment_requests', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->integer('freelancer_id')->index();
            $table->enum('status', ['pending', 'paid'])->default('pending')->index();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('paid_at')->nullable();
            $table->integer('paid_by')->nullable();
            $table->decimal('amount_pkr', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('freelance_payment_requests');
    }
};
