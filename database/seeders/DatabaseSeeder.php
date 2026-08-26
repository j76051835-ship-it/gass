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
