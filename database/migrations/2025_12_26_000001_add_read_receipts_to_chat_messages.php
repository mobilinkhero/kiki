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
        // This migration adds read receipt tracking fields to chat_messages table
        // Note: This will run on tenant-specific tables (e.g., tenant1_chat_messages)

        $tenants = \App\Models\Tenant::all();

        foreach ($tenants as $tenant) {
            $subdomain = $tenant->subdomain;
            $tableName = $subdomain . '_chat_messages';

            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // WhatsApp delivery and read status timestamps
                    $table->timestamp('delivered_at')->nullable()->after('is_read');
                    $table->timestamp('read_at')->nullable()->after('delivered_at');

                    // WhatsApp status from webhook (sent, delivered, read, failed)
                    $table->string('wa_status')->nullable()->after('read_at');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tenants = \App\Models\Tenant::all();

        foreach ($tenants as $tenant) {
            $subdomain = $tenant->subdomain;
            $tableName = $subdomain . '_chat_messages';

            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn(['delivered_at', 'read_at', 'wa_status']);
                });
            }
        }
    }
};
