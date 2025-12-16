<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert Alfa Payment Gateway settings
        DB::table('settings')->insert([
            [
                'group' => 'payment',
                'name' => 'alfa_enabled',
                'locked' => 0,
                'payload' => json_encode(['value' => false]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'payment',
                'name' => 'alfa_mode',
                'locked' => 0,
                'payload' => json_encode(['value' => 'sandbox']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'payment',
                'name' => 'alfa_merchant_id',
                'locked' => 0,
                'payload' => json_encode(['value' => '']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'payment',
                'name' => 'alfa_store_id',
                'locked' => 0,
                'payload' => json_encode(['value' => '']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'payment',
                'name' => 'alfa_merchant_hash',
                'locked' => 0,
                'payload' => json_encode(['value' => '']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'payment',
                'name' => 'alfa_merchant_username',
                'locked' => 0,
                'payload' => json_encode(['value' => '']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'payment',
                'name' => 'alfa_merchant_password',
                'locked' => 0,
                'payload' => json_encode(['value' => '']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')
            ->where('group', 'payment')
            ->whereIn('name', [
                    'alfa_enabled',
                    'alfa_mode',
                    'alfa_merchant_id',
                    'alfa_store_id',
                    'alfa_merchant_hash',
                    'alfa_merchant_username',
                    'alfa_merchant_password',
                ])
            ->delete();
    }
};
