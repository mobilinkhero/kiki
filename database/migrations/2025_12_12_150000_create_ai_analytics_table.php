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
        Schema::create('ai_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('contact_id')->nullable()->constrained('contacts')->onDelete('set null');
            $table->foreignId('personal_assistant_id')->nullable()->constrained()->onDelete('set null');

            // Event tracking
            $table->string('event_type'); // 'ai_response', 'handoff', 'resolution', 'error'
            $table->string('conversation_id')->nullable();
            $table->text('user_message')->nullable();
            $table->text('ai_response')->nullable();

            // Performance metrics
            $table->integer('response_time_ms')->nullable(); // Response time in milliseconds
            $table->boolean('was_successful')->default(true);
            $table->string('handoff_reason')->nullable(); // 'user_request', 'image_verification', 'uncertain', etc.
            $table->boolean('ai_disabled_after')->default(false); // Track if handoff disabled AI

            // Sentiment & Quality
            $table->string('detected_language')->nullable(); // 'en', 'ur', 'roman_urdu'
            $table->string('user_sentiment')->nullable(); // 'positive', 'neutral', 'negative'
            $table->integer('message_length')->nullable();

            // Business context
            $table->string('business_category')->nullable(); // From assistant's use case
            $table->boolean('was_in_business_scope')->default(true);

            // Timestamps
            $table->timestamp('event_time');
            $table->timestamps();

            // Indexes for fast queries
            $table->index(['tenant_id', 'event_type', 'created_at']);
            $table->index(['tenant_id', 'event_time']);
            $table->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analytics');
    }
};
