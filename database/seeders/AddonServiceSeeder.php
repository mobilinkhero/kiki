<?php

namespace Database\Seeders;

use App\Models\AddonService;
use Illuminate\Database\Seeder;

class AddonServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addons = [
            [
                'name' => 'AI Credits - Starter Pack',
                'slug' => 'ai-credits-starter',
                'description' => 'Perfect for getting started with AI-powered conversations. Includes 100 credits plus 10 bonus credits.',
                'type' => 'credits',
                'category' => 'AI',
                'price' => 2000.00,
                'credit_amount' => 100,
                'bonus_amount' => 10,
                'icon' => 'fas fa-coins',
                'badge' => 'Popular',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'AI Credits - Pro Pack',
                'slug' => 'ai-credits-pro',
                'description' => 'For power users who need more AI capabilities. Get 500 credits with 50 bonus credits included.',
                'type' => 'credits',
                'category' => 'AI',
                'price' => 9000.00,
                'credit_amount' => 500,
                'bonus_amount' => 50,
                'icon' => 'fas fa-star',
                'badge' => 'Best Value',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'name' => 'AI Credits - Enterprise Pack',
                'slug' => 'ai-credits-enterprise',
                'description' => 'Maximum value for businesses. 1,000 credits with 150 bonus credits for extended usage.',
                'type' => 'credits',
                'category' => 'AI',
                'price' => 16000.00,
                'credit_amount' => 1000,
                'bonus_amount' => 150,
                'icon' => 'fas fa-crown',
                'badge' => null,
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'name' => 'AI Credits - Ultimate Pack',
                'slug' => 'ai-credits-ultimate',
                'description' => 'Ultimate package for heavy users. 5,000 credits with 1,000 bonus credits.',
                'type' => 'credits',
                'category' => 'AI',
                'price' => 75000.00,
                'credit_amount' => 5000,
                'bonus_amount' => 1000,
                'icon' => 'fas fa-gem',
                'badge' => null,
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($addons as $addon) {
            AddonService::updateOrCreate(
                ['slug' => $addon['slug']],
                $addon
            );
        }

        $this->command->info('Addon services seeded successfully!');
    }
}
