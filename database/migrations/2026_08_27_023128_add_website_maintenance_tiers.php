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
            ['slug' => 'maintenance-website-basic', 'name' => 'Maintenance Website Basic', 'base_price' => 500000],
            ['slug' => 'maintenance-website-standard', 'name' => 'Maintenance Website Standard', 'base_price' => 750000],
            ['slug' => 'maintenance-website-professional', 'name' => 'Maintenance Website Professional', 'base_price' => 1000000],
            ['slug' => 'maintenance-website-advanced', 'name' => 'Maintenance Website Advanced', 'base_price' => 1500000],
            ['slug' => 'maintenance-website-premium', 'name' => 'Maintenance Website Premium', 'base_price' => 2000000],
        ];

        foreach ($packages as $package) {
            DB::table('service_packages')->updateOrInsert(
                ['slug' => $package['slug']],
                [...$package, 'discount_percent' => 0, 'is_active' => true, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        DB::table('service_packages')->where('slug', 'maintenance-website')->update(['is_active' => false]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('service_packages')->whereIn('slug', [
            'maintenance-website-basic',
            'maintenance-website-standard',
            'maintenance-website-professional',
            'maintenance-website-advanced',
            'maintenance-website-premium',
        ])->delete();
    }
};
