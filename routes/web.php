<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AI\GassAIController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MidtransPaymentController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\ReviewController;
use App\Models\GalleryItem;
use App\Models\PromoBanner;
use App\Models\Review;
use App\Models\ServicePackage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/media/{path}', function (string $path) {
    abort_if(str_contains($path, '..') || ! Storage::disk('public')->exists($path), 404);

    $file = Storage::disk('public')->path($path);

    return response()->file($file, [
        'Accept-Ranges' => 'bytes',
        'Cache-Control' => 'public, max-age=31536000',
        'Content-Type' => mime_content_type($file) ?: 'application/octet-stream',
    ]);
})->where('path', '.*')->name('media');

Route::get('/', function () {
    return view('home', [
        'reviews' => Review::where('approved', true)->oldest()->get(),
        'banners' => PromoBanner::where('is_active', true)->latest()->get(),
        'galleryItems' => GalleryItem::where('is_active', true)->latest()->get(),
    ]);
})->name('home');

Route::view('/formulir-pemesanan', 'order-form')->name('order.form');
Route::view('/tentang-kami', 'about')->name('about');
Route::get('/layanan', function () {
    return view('services', ['packages' => ServicePackage::where('is_active', true)->get()]);
})->name('services');
Route::view('/proses', 'process')->name('process');
Route::get('/kontak', function () {
    return view('contact', ['reviews' => Review::where('approved', true)->oldest()->get()]);
})->name('contact');

Route::post('/ulasan', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/ai/chat', [GassAIController::class, 'chat'])->middleware('throttle:ai-chat')->name('ai.chat');
Route::post('/payment/midtrans/token', [MidtransPaymentController::class, 'token'])->name('payment.midtrans.token');
Route::post('/payment/midtrans/webhook', [MidtransWebhookController::class, 'handle'])->name('payment.midtrans.webhook');
Route::delete('/ulasan/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

Route::get('/admin/login', [AdminController::class, 'login'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');

Route::middleware('auth')->prefix('absensi')->name('attendance.')->group(function () {
    Route::get('/', [AttendanceController::class, 'dashboard'])->name('dashboard');
    Route::post('/action', [AttendanceController::class, 'action'])->name('action');
    Route::post('/profile', [AttendanceController::class, 'updateProfile'])->name('profile.update');
    Route::post('/password', [AttendanceController::class, 'updatePassword'])->name('password.update');
    Route::post('/logout', [AttendanceController::class, 'logout'])->name('logout');
});

Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/harga', [AdminController::class, 'prices'])->name('prices');
    Route::patch('/harga', [AdminController::class, 'updatePackages'])->name('packages.bulk-update');
    Route::get('/banner', [AdminController::class, 'banners'])->name('banners');
    Route::get('/galeri', [AdminController::class, 'gallery'])->name('gallery');
    Route::get('/absensi', [AdminController::class, 'attendance'])->name('attendance');
    Route::post('/karyawan', [AdminController::class, 'storeEmployee'])->name('employees.store');
    Route::patch('/karyawan/{employee}', [AdminController::class, 'updateEmployee'])->name('employees.update');
    Route::delete('/karyawan/{employee}', [AdminController::class, 'destroyEmployee'])->name('employees.destroy');
    Route::delete('/absensi/{attendance}', [AdminController::class, 'destroyAttendance'])->name('attendance.destroy');
    Route::patch('/paket/{package}', [AdminController::class, 'updatePackage'])->name('packages.update');
    Route::post('/banner', [AdminController::class, 'storeBanner'])->name('banners.store');
    Route::patch('/banner/{banner}', [AdminController::class, 'updateBanner'])->name('banners.update');
    Route::delete('/banner/{banner}', [AdminController::class, 'destroyBanner'])->name('banners.destroy');
    Route::post('/galeri', [AdminController::class, 'storeGalleryItem'])->name('gallery.store');
    Route::patch('/galeri/{galleryItem}', [AdminController::class, 'updateGalleryItem'])->name('gallery.update');
    Route::delete('/galeri/{galleryItem}', [AdminController::class, 'destroyGalleryItem'])->name('gallery.destroy');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
});
