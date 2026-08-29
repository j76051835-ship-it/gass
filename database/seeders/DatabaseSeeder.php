<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $reviews = [
            ['name' => 'Tim Sumberindo', 'company' => 'PBF Sumberindo Farma Tama', 'rating' => 5, 'comment' => 'GASS membantu kami tampil lebih profesional dan konsisten di ruang digital.', 'approved' => true],
            ['name' => 'Tim Medistra', 'company' => 'Apotek Medistra Farma', 'rating' => 5, 'comment' => 'Dari strategi sampai eksekusi, semuanya terasa terarah dan relevan.', 'approved' => true],
            ['name' => 'Tim Alfa Group', 'company' => 'Apotek Alfa Group', 'rating' => 5, 'comment' => 'Konten kami menjadi lebih hidup dan mudah dipahami oleh audiens.', 'approved' => true],
            ['name' => 'Tim Medikpedia', 'company' => 'Apotek Medikpedia', 'rating' => 5, 'comment' => 'GASS memberi perspektif baru untuk mengembangkan bisnis kami secara digital.', 'approved' => true],
        ];

        foreach ($reviews as $review) {
            Review::updateOrCreate(['company' => $review['company']], $review);
        }

        User::updateOrCreate(['email' => 'admin@gass.local'], [
            'name' => 'Admin GASS',
            'password' => 'password',
            'is_admin' => true,
        ]);

        $packages = [
            ['slug' => 'pembuatan-website-basic', 'name' => 'Pembuatan Website Basic', 'base_price' => 3500000],
            ['slug' => 'pembuatan-website-standar', 'name' => 'Pembuatan Website Standar', 'base_price' => 5000000],
            ['slug' => 'pembuatan-website-pro', 'name' => 'Pembuatan Website Pro', 'base_price' => 8000000],
            ['slug' => 'maintenance-website-basic', 'name' => 'Maintenance Website Basic', 'base_price' => 500000],
            ['slug' => 'maintenance-website-standard', 'name' => 'Maintenance Website Standard', 'base_price' => 750000],
            ['slug' => 'maintenance-website-professional', 'name' => 'Maintenance Website Professional', 'base_price' => 1000000],
            ['slug' => 'maintenance-website-advanced', 'name' => 'Maintenance Website Advanced', 'base_price' => 1500000],
            ['slug' => 'maintenance-website-premium', 'name' => 'Maintenance Website Premium', 'base_price' => 2000000],
            ['slug' => 'pembuatan-social-media-basic', 'name' => 'Pembuatan Social Media Basic', 'base_price' => 100000],
            ['slug' => 'pembuatan-social-media-standar', 'name' => 'Pembuatan Social Media Standar', 'base_price' => 200000],
            ['slug' => 'pembuatan-social-media-pro', 'name' => 'Pembuatan Social Media Pro', 'base_price' => 300000],
            ['slug' => 'maintenance-social-media', 'name' => 'Maintenance Social Media', 'base_price' => 1000000],
            ['slug' => 'video-ai-standard-satuan', 'name' => 'Video AI Standard', 'base_price' => 100000],
            ['slug' => 'video-ai-premium-satuan', 'name' => 'Video AI Premium', 'base_price' => 175000],
            ['slug' => 'video-ai-professional', 'name' => 'Video AI Professional', 'base_price' => 300000],
            ['slug' => 'carousel-standard', 'name' => 'Carousel Standard', 'base_price' => 25000],
            ['slug' => 'carousel-premium', 'name' => 'Carousel Premium', 'base_price' => 35000],
            ['slug' => 'carousel-pro', 'name' => 'Carousel Pro', 'base_price' => 55000],
            ['slug' => 'video-ai-standard-bulanan', 'name' => 'Video AI Standard Bulanan', 'base_price' => 3000000],
            ['slug' => 'video-ai-premium-bulanan', 'name' => 'Video AI Premium Bulanan', 'base_price' => 5250000],
            ['slug' => 'video-ai-professional-bulanan', 'name' => 'Video AI Professional Bulanan', 'base_price' => 9000000],
            ['slug' => 'carousel-standard-bulanan', 'name' => 'Carousel Standard Bulanan', 'base_price' => 750000],
            ['slug' => 'carousel-premium-bulanan', 'name' => 'Carousel Premium Bulanan', 'base_price' => 1050000],
            ['slug' => 'carousel-pro-bulanan', 'name' => 'Carousel Pro Bulanan', 'base_price' => 1650000],
            ['slug' => 'ai-video-basic', 'name' => 'AI Video Basic', 'base_price' => 350000],
            ['slug' => 'ai-video-standard', 'name' => 'AI Video Standard', 'base_price' => 750000],
            ['slug' => 'ai-video-premium', 'name' => 'AI Video Premium', 'base_price' => 1500000],
            ['slug' => 'ai-video-pro', 'name' => 'AI Video Pro', 'base_price' => 2500000],
            ['slug' => 'foto-ai-basic', 'name' => 'Foto AI Basic', 'base_price' => 60000],
            ['slug' => 'foto-ai-standard', 'name' => 'Foto AI Standard', 'base_price' => 120000],
            ['slug' => 'foto-ai-premium', 'name' => 'Foto AI Premium', 'base_price' => 200000],
            ['slug' => 'ai-video-basic-bulanan', 'name' => 'AI Video Basic Bulanan', 'base_price' => 1200000],
            ['slug' => 'ai-video-standard-bulanan', 'name' => 'AI Video Standard Bulanan', 'base_price' => 2500000],
            ['slug' => 'ai-video-premium-bulanan', 'name' => 'AI Video Premium Bulanan', 'base_price' => 4000000],
            ['slug' => 'ai-video-pro-bulanan', 'name' => 'AI Video Pro Bulanan', 'base_price' => 6500000],
        ];

        foreach ($packages as $package) {
            ServicePackage::updateOrCreate(['slug' => $package['slug']], $package);
        }
    }
}
