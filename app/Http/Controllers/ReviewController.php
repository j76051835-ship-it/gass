<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'company' => ['required', 'string', 'max:150'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:15', 'max:600'],
        ]);

        Review::create($validated + ['approved' => true]);

        return back()->with('review_success', 'Terima kasih, ulasan kamu sudah tampil di halaman kami.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        $request->validate([
            'delete_code' => ['required', 'string', 'in:fefek'],
        ]);

        $review->delete();

        return back()->with('review_success', 'Ulasan berhasil dihapus.');
    }
}
