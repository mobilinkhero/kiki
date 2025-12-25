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
        Schema::create('ai_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tenant_id');
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('reserved', 10, 2)->default(0);
            $table->decimal('total_purchased', 10, 2)->default(0);
            $table->decimal('total_used', 10, 2)->default(0);
            $table->timestamps();

            // Unique constraint
            $table->unique(['user_id', 'tenant_id']);

            // Indexes
            $table->index('balance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_credits');
    }
};
