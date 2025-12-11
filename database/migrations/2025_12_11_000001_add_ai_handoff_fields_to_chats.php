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
        // Get all tenant subdomains
        $tenants = \App\Models\Tenant::all();

        foreach ($tenants as $tenant) {
            $tableName = $tenant->subdomain . '_chats';

            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // AI Handoff tracking
                    $table->boolean('ai_handed_off')->default(false)->after('is_bots_stoped');
                    $table->timestamp('ai_handoff_at')->nullable()->after('ai_handed_off');
                    $table->string('ai_handoff_reason')->nullable()->after('ai_handoff_at');
                    $table->integer('assigned_agent_id')->nullable()->after('ai_handoff_reason');
                    $table->boolean('agent_notified')->default(false)->after('assigned_agent_id');
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
            $tableName = $tenant->subdomain . '_chats';

            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn([
                        'ai_handed_off',
                        'ai_handoff_at',
                        'ai_handoff_reason',
                        'assigned_agent_id',
                        'agent_notified',
                    ]);
                });
            }
        }
    }
};
