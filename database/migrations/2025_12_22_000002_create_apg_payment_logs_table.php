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
        Schema::create('apg_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('apg_transaction_id')->nullable();
            $table->string('transaction_reference_number')->nullable();

            // Log Details
            $table->string('action'); // handshake, payment, ipn, inquiry
            $table->string('method')->default('POST'); // GET, POST
            $table->string('url');

            // Request/Response
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->integer('response_code')->nullable();

            // Status
            $table->boolean('is_successful')->default(false);
            $table->text('error_message')->nullable();

            // Metadata
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('apg_transaction_id');
            $table->index('transaction_reference_number');
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apg_payment_logs');
    }
};
