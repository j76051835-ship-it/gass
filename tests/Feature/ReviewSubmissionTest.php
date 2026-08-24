<?php

use App\Models\Review;

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
