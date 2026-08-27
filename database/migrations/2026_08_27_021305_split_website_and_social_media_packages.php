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
            ['slug' => 'pembuatan-website-basic', 'name' => 'Pembuatan Website Basic', 'base_price' => 3500000],
            ['slug' => 'pembuatan-website-standar', 'name' => 'Pembuatan Website Standar', 'base_price' => 5000000],
            ['slug' => 'pembuatan-website-pro', 'name' => 'Pembuatan Website Pro', 'base_price' => 8000000],
            ['slug' => 'pembuatan-social-media-basic', 'name' => 'Pembuatan Social Media Basic', 'base_price' => 100000],
            ['slug' => 'pembuatan-social-media-standar', 'name' => 'Pembuatan Social Media Standar', 'base_price' => 200000],
            ['slug' => 'pembuatan-social-media-pro', 'name' => 'Pembuatan Social Media Pro', 'base_price' => 300000],
        ];

        foreach ($packages as $package) {
            DB::table('service_packages')->updateOrInsert(
                ['slug' => $package['slug']],
                [...$package, 'discount_percent' => 0, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        DB::table('service_packages')->whereIn('slug', ['pembuatan-website', 'pembuatan-social-media'])->update(['is_active' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('service_packages')->whereIn('slug', [
            'pembuatan-website-basic',
            'pembuatan-website-standar',
            'pembuatan-website-pro',
            'pembuatan-social-media-basic',
            'pembuatan-social-media-standar',
            'pembuatan-social-media-pro',
        ])->delete();
    }
};
