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
        Schema::create('user_addon_purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('addon_service_id');
            $table->unsignedBigInteger('invoice_id');

            // Purchase details
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('credits_received', 10, 2)->nullable();
            $table->decimal('bonus_received', 10, 2)->nullable();

            // Status
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Metadata
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('addon_service_id')->references('id')->on('addon_services')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

            // Indexes
            $table->index(['user_id', 'tenant_id']);
            $table->index('status');
            $table->index('activated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addon_purchases');
    }
};
