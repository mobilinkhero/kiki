<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apg_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_reference_number')->unique();
            $table->string('order_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('tenant_id')->nullable();

            // Transaction Details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PKR');
            $table->string('transaction_type')->default('subscription'); // subscription, invoice, etc.
            $table->unsignedBigInteger('related_id')->nullable(); // subscription_id, invoice_id, etc.

            // APG Response Data
            $table->string('auth_token')->nullable();
            $table->string('apg_transaction_id')->nullable();
            $table->string('account_number')->nullable();
            $table->string('mobile_number')->nullable();

            // Status Tracking
            $table->enum('status', ['pending', 'processing', 'paid', 'failed', 'cancelled'])->default('pending');
            $table->string('response_code')->nullable();
            $table->text('response_description')->nullable();

            // Timestamps
            $table->timestamp('order_datetime')->nullable();
            $table->timestamp('transaction_datetime')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Additional Data
            $table->json('request_data')->nullable();
            $table->json('response_data')->nullable();
            $table->text('error_message')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('tenant_id');
            $table->index('status');
            $table->index('apg_transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apg_transactions');
    }
};
