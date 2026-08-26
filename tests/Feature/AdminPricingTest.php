<?php

use App\Models\PromoBanner;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('redirects guests to the admin login page', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('admin.login'));
});

it('authenticates an admin and renders database pricing on services', function () {
    $admin = User::factory()->create([
        'email' => 'admin@gass.test',
        'password' => 'password',
        'is_admin' => true,
    ]);
    ServicePackage::create([
        'slug' => 'ai-video-basic',
        'name' => 'AI Video Basic',
        'base_price' => 350000,
        'discount_percent' => 10,
    ]);

    $this->post(route('admin.authenticate'), [
        'email' => 'admin@gass.test',
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->get(route('services'))
        ->assertOk()
        ->assertSee('AI Video Basic')
        ->assertSee('"basePrice":350000', false)
        ->assertSee('"price":315000', false);
});

it('allows an admin to update a package price and discount', function () {
    $admin = User::factory()->create(['is_admin' => true]);
    $package = ServicePackage::create([
        'slug' => 'test-package',
        'name' => 'Test Package',
        'base_price' => 100000,
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.packages.update', $package), [
            'base_price' => 200000,
            'discount_percent' => 15,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    expect($package->fresh()->base_price)->toBe(200000)
        ->and($package->fresh()->discount_percent)->toBe(15)
        ->and($package->fresh()->final_price)->toBe(170000);
});

it('does not allow regular users into the admin dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

it('allows an admin to upload and display an active promo banner', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.banners.store'), [
            'title' => 'Promo Video Agustus',
            'media' => UploadedFile::fake()->create('promo.mp4', 100, 'video/mp4'),
            'is_active' => 1,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $banner = PromoBanner::firstOrFail();
    expect($banner->media_type)->toBe('video')->and($banner->is_active)->toBeTrue();
    Storage::disk('public')->assertExists($banner->media_path);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Promo Video Agustus')
        ->assertSee('promo-banner-media', false)
        ->assertSee('muted', false)
        ->assertSee('data-promo-slider', false);

    PromoBanner::create([
        'title' => 'Promo Foto September',
        'media_path' => 'promo-banners/promo.jpg',
        'media_type' => 'image',
        'is_active' => true,
    ]);

    $this->get(route('home'))
        ->assertSee('Promo Video Agustus')
        ->assertSee('Promo Foto September')
        ->assertSee('promo-banner-dot', false);
});

it('allows an admin to upload an untitled video promo banner', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->post(route('admin.banners.store'), [
            'media' => UploadedFile::fake()->create('promo.webm', 100, 'video/webm'),
            'is_active' => 1,
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $banner = PromoBanner::firstOrFail();
    expect($banner->title)->toBe('')
        ->and($banner->media_type)->toBe('video');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<video class="promo-banner-media"', false);
});
