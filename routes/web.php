<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use App\Models\GalleryItem;
use App\Models\PromoBanner;
use App\Models\Review;
use App\Models\ServicePackage;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'reviews' => Review::where('approved', true)->oldest()->get(),
        'banners' => PromoBanner::where('is_active', true)->latest()->get(),
        'galleryItems' => GalleryItem::where('is_active', true)->latest()->get(),
    ]);
})->name('home');

Route::view('/tentang-kami', 'about')->name('about');
Route::get('/layanan', function () {
    return view('services', ['packages' => ServicePackage::where('is_active', true)->get()]);
})->name('services');
Route::view('/proses', 'process')->name('process');
Route::get('/kontak', function () {
    return view('contact', ['reviews' => Review::where('approved', true)->oldest()->get()]);
})->name('contact');

Route::post('/ulasan', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/ulasan/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/harga', [AdminController::class, 'prices'])->name('prices');
    Route::get('/banner', [AdminController::class, 'banners'])->name('banners');
    Route::get('/galeri', [AdminController::class, 'gallery'])->name('gallery');
    Route::patch('/paket/{package}', [AdminController::class, 'updatePackage'])->name('packages.update');
    Route::post('/banner', [AdminController::class, 'storeBanner'])->name('banners.store');
    Route::patch('/banner/{banner}', [AdminController::class, 'updateBanner'])->name('banners.update');
    Route::delete('/banner/{banner}', [AdminController::class, 'destroyBanner'])->name('banners.destroy');
    Route::post('/galeri', [AdminController::class, 'storeGalleryItem'])->name('gallery.store');
    Route::patch('/galeri/{galleryItem}', [AdminController::class, 'updateGalleryItem'])->name('gallery.update');
    Route::delete('/galeri/{galleryItem}', [AdminController::class, 'destroyGalleryItem'])->name('gallery.destroy');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
