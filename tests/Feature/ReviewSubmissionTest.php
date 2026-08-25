<?php

use App\Models\Review;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('a visitor can submit a review', function () {
    $response = $this->post(route('reviews.store'), [
        'name' => 'Rina Putri',
        'company' => 'Rina Creative',
        'rating' => 5,
        'comment' => 'Kolaborasi bersama GASS sangat jelas dan membantu bisnis kami berkembang.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('review_success');
    $this->assertDatabaseHas('reviews', ['company' => 'Rina Creative', 'rating' => 5]);
});

test('a review needs valid content', function () {
    $response = $this->from(route('contact'))->post(route('reviews.store'), [
        'name' => '',
        'company' => 'Rina Creative',
        'rating' => 8,
        'comment' => 'pendek',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHasErrors(['name', 'rating', 'comment']);
    expect(Review::count())->toBe(0);
});

test('a review stores uploaded media paths', function () {
    Storage::fake('public');

    $response = $this->post(route('reviews.store'), [
        'name' => 'Rina Putri',
        'company' => 'Rina Creative',
        'rating' => 5,
        'comment' => 'Kolaborasi bersama GASS sangat jelas dan membantu bisnis kami berkembang.',
        'media' => [UploadedFile::fake()->create('hasil.png', 100, 'image/png')],
    ]);

    $response->assertRedirect();
    $review = Review::query()->where('company', 'Rina Creative')->firstOrFail();
    expect($review->media)->toHaveCount(1)
        ->and($review->media[0])->toStartWith('reviews/');
    Storage::disk('public')->assertExists($review->media[0]);
});
