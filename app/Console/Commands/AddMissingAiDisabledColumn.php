<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class AddMissingAiDisabledColumn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:add-ai-disabled-column {tenant?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add missing ai_disabled column to tenant contacts tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenantSubdomain = $this->argument('tenant');

        if ($tenantSubdomain) {
            // Fix specific tenant
            $this->fixTenant($tenantSubdomain);
        } else {
            // Fix all tenants
            $tenants = DB::table('tenants')->get();

            foreach ($tenants as $tenant) {
                $this->fixTenant($tenant->subdomain);
            }
        }

        $this->info('Done!');
    }

    private function fixTenant($subdomain)
    {
        $tableName = $subdomain . '_contacts';

        if (!Schema::hasTable($tableName)) {
            $this->warn("Table {$tableName} does not exist. Skipping.");
            return;
        }

        if (Schema::hasColumn($tableName, 'ai_disabled')) {
            $this->info("Column ai_disabled already exists in {$tableName}. Skipping.");
            return;
        }

        try {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('ai_disabled')->default(false)->after('is_enabled');
            });

            $this->info("✓ Added ai_disabled column to {$tableName}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to add column to {$tableName}: " . $e->getMessage());
        }
    }
}
