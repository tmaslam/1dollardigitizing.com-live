<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::createIfNotExists('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->nullable()->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedBigInteger('billing_id')->nullable()->index();
            $table->string('legacy_website', 100)->nullable()->index();
            $table->string('provider', 100)->index();
            $table->string('provider_transaction_id', 255)->nullable()->index();
            $table->string('merchant_reference', 255)->unique();
            $table->string('payment_scope', 100)->index();
            $table->string('status', 50)->default('initiated')->index();
            $table->char('currency', 3)->default('USD');
            $table->decimal('requested_amount', 12, 2)->default(0);
            $table->decimal('confirmed_amount', 12, 2)->nullable();
            $table->text('redirect_url')->nullable();
            $table->text('return_url')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('provider_payload')->nullable();
            $table->timestamp('reconciled_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });

        Schema::createIfNotExists('payment_transaction_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_transaction_id')->index();
            $table->unsignedBigInteger('billing_id')->nullable()->index();
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->string('legacy_website', 100)->nullable();
            $table->decimal('requested_amount', 12, 2)->default(0);
            $table->decimal('confirmed_amount', 12, 2)->nullable();
            $table->string('status', 50)->default('initiated');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('payment_transaction_id')
                ->references('id')
                ->on('payment_transactions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transaction_items');
        Schema::dropIfExists('payment_transactions');
    }
};
