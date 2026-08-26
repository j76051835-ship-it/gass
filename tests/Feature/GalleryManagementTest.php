<?php

use App\Models\GalleryItem;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('allows an admin to create an active gallery carousel', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.gallery.store'), [
            'title' => 'Website Klinik Medistra',
            'description' => 'Website dengan arah visual yang lebih jelas.',
            'media' => [
                UploadedFile::fake()->create('project-one.jpg', 100, 'image/jpeg'),
                UploadedFile::fake()->create('project-two.png', 100, 'image/png'),
            ],
            'is_active' => 1,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $galleryItem = GalleryItem::firstOrFail();
    expect($galleryItem->is_active)->toBeTrue()->and($galleryItem->media)->toHaveCount(2);
    foreach ($galleryItem->media as $media) {
        Storage::disk('public')->assertExists($media['path']);
    }

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Website Klinik Medistra')
        ->assertSee('home-gallery-card', false)
        ->assertSee('home-gallery-dot', false);
});
