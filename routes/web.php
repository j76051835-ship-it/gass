<?php

use App\Http\Controllers\ReviewController;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', ['reviews' => Review::where('approved', true)->oldest()->get()]);
})->name('home');

Route::view('/tentang-kami', 'about')->name('about');
Route::view('/layanan', 'services')->name('services');
Route::view('/proses', 'process')->name('process');
Route::get('/kontak', function () {
    return view('contact', ['reviews' => Review::where('approved', true)->oldest()->get()]);
})->name('contact');

Route::post('/ulasan', [ReviewController::class, 'store'])->name('reviews.store');
Route::delete('/ulasan/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
