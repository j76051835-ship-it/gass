<?php

namespace Database\Seeders;

use App\Models\Review;
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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
