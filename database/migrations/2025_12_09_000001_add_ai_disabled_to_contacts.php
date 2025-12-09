<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all tenant tables
        $tenants = DB::table('tenants')->get();
        
        foreach ($tenants as $tenant) {
            $tableName = $tenant->subdomain . '_contacts';
            
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    // Add AI disabled flag - defaults to false (AI enabled)
                    $table->boolean('ai_disabled')->default(false)->after('is_enabled');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tenants = DB::table('tenants')->get();
        
        foreach ($tenants as $tenant) {
            $tableName = $tenant->subdomain . '_contacts';
            
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'ai_disabled')) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn('ai_disabled');
                });
            }
        }
    }
};
