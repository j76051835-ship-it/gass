<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'company' => ['required', 'string', 'max:150'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['required', 'string', 'min:15', 'max:600'],
            'media' => ['nullable', 'array', 'max:3'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm', 'max:20480'],
        ]);

        $media = collect($request->file('media', []))
            ->map(fn ($file): string => $file->store('reviews', 'public'))
            ->values()
            ->all();
        unset($validated['media']);

        Review::create($validated + ['media' => $media ?: null, 'approved' => true]);

        return back()->with('review_success', 'Terima kasih, ulasan kamu sudah tampil di halaman kami.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        $request->validate([
            'delete_code' => ['required', 'string', 'in:fefek'],
        ]);

        foreach (collect($review->media ?? [])->flatten()->filter(fn ($media): bool => is_string($media)) as $media) {
            Storage::disk('public')->delete($media);
        }

        $review->delete();

        return back()->with('review_success', 'Ulasan berhasil dihapus.');
    }
}
