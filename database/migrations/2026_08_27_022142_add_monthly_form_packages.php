<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $packages = [
            ['slug' => 'video-ai-standard-bulanan', 'name' => 'Video AI Standard Bulanan', 'base_price' => 3000000],
            ['slug' => 'video-ai-premium-bulanan', 'name' => 'Video AI Premium Bulanan', 'base_price' => 5250000],
            ['slug' => 'video-ai-professional-bulanan', 'name' => 'Video AI Professional Bulanan', 'base_price' => 9000000],
            ['slug' => 'carousel-standard-bulanan', 'name' => 'Carousel Standard Bulanan', 'base_price' => 750000],
            ['slug' => 'carousel-premium-bulanan', 'name' => 'Carousel Premium Bulanan', 'base_price' => 1050000],
            ['slug' => 'carousel-pro-bulanan', 'name' => 'Carousel Pro Bulanan', 'base_price' => 1650000],
        ];

        foreach ($packages as $package) {
            DB::table('service_packages')->updateOrInsert(
                ['slug' => $package['slug']],
                [...$package, 'discount_percent' => 0, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('service_packages')->whereIn('slug', [
            'video-ai-standard-bulanan',
            'video-ai-premium-bulanan',
            'video-ai-professional-bulanan',
            'carousel-standard-bulanan',
            'carousel-premium-bulanan',
            'carousel-pro-bulanan',
        ])->delete();
    }
};
