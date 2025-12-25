<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addon_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['credits', 'feature', 'one_time'])->default('credits');
            $table->string('category', 100)->nullable();
            
            // Pricing
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('PKR');
            
            // What user gets
            $table->decimal('credit_amount', 10, 2)->nullable();
            $table->decimal('bonus_amount', 10, 2)->nullable();
            $table->integer('duration_days')->nullable();
            
            // Display
            $table->string('icon')->nullable();
            $table->string('badge', 50)->nullable();
            $table->integer('sort_order')->default(0);
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            
            // Metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('type');
            $table->index('category');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_services');
    }
};
