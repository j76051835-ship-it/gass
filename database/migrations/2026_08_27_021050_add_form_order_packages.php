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
            ['slug' => 'pembuatan-website', 'name' => 'Pembuatan Website', 'base_price' => 3500000],
            ['slug' => 'maintenance-website', 'name' => 'Maintenance Website', 'base_price' => 500000],
            ['slug' => 'pembuatan-social-media', 'name' => 'Pembuatan Social Media', 'base_price' => 100000],
            ['slug' => 'maintenance-social-media', 'name' => 'Maintenance Social Media', 'base_price' => 1000000],
            ['slug' => 'video-ai-standard-satuan', 'name' => 'Video AI Standard', 'base_price' => 100000],
            ['slug' => 'video-ai-premium-satuan', 'name' => 'Video AI Premium', 'base_price' => 175000],
            ['slug' => 'video-ai-professional', 'name' => 'Video AI Professional', 'base_price' => 300000],
            ['slug' => 'carousel-standard', 'name' => 'Carousel Standard', 'base_price' => 20000],
            ['slug' => 'carousel-premium', 'name' => 'Carousel Premium', 'base_price' => 35000],
            ['slug' => 'carousel-pro', 'name' => 'Carousel Pro', 'base_price' => 55000],
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
            'pembuatan-website',
            'maintenance-website',
            'pembuatan-social-media',
            'maintenance-social-media',
            'video-ai-standard-satuan',
            'video-ai-premium-satuan',
            'video-ai-professional',
            'carousel-standard',
            'carousel-premium',
            'carousel-pro',
        ])->delete();
    }
};
