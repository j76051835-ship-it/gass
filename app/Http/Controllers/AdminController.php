<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\PromoBanner;
use App\Models\ServicePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function login(): View
    {
        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Email atau password tidak sesuai.'])->onlyInput('email');
        }

        if (! $request->user()->is_admin) {
            Auth::logout();

            return back()->withErrors(['email' => 'Akun ini tidak memiliki akses admin.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'packages' => ServicePackage::query()->orderBy('id')->get(),
            'banners' => PromoBanner::query()->latest()->get(),
            'galleryItems' => GalleryItem::query()->latest()->get(),
        ]);
    }

    public function prices(): View
    {
        return view('admin.prices', ['packages' => ServicePackage::query()->orderBy('id')->get()]);
    }

    public function banners(): View
    {
        return view('admin.banners', ['banners' => PromoBanner::query()->latest()->get()]);
    }

    public function gallery(): View
    {
        return view('admin.gallery', ['galleryItems' => GalleryItem::query()->latest()->get()]);
    }

    public function storeGalleryItem(Request $request): RedirectResponse
    {
        $validated = $this->validateGalleryRequest($request, true);

        GalleryItem::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'media' => $this->storeGalleryMedia($request->file('media')),
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Karya galeri berhasil ditambahkan.');
    }

    public function updateGalleryItem(Request $request, GalleryItem $galleryItem): RedirectResponse
    {
        $validated = $this->validateGalleryRequest($request, false);
        $updates = [
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('media')) {
            $this->deleteGalleryMedia($galleryItem->media);
            $updates['media'] = $this->storeGalleryMedia($request->file('media'));
        }

        $galleryItem->update($updates);

        return back()->with('status', 'Karya galeri berhasil diperbarui.');
    }

    public function destroyGalleryItem(GalleryItem $galleryItem): RedirectResponse
    {
        $this->deleteGalleryMedia($galleryItem->media);
        $galleryItem->delete();

        return back()->with('status', 'Karya galeri berhasil dihapus.');
    }

    private function validateGalleryRequest(Request $request, bool $mediaRequired): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'media' => [$mediaRequired ? 'required' : 'nullable', 'array', 'min:1'],
            'media.*' => ['file', 'mimes:jpg,jpeg,png,webp,gif,mp4,mov,webm'],
        ]);

        foreach ($request->file('media', []) as $media) {
            if (str_starts_with($media->getMimeType(), 'video/') && $media->getSize() > 500 * 1024 * 1024) {
                throw ValidationException::withMessages(['media' => 'Ukuran setiap video maksimal 500 MB.']);
            }
        }

        return $validated;
    }

    private function storeGalleryMedia(array $files): array
    {
        return array_map(function ($file): array {
            return [
                'path' => $file->store('gallery', 'public'),
                'type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image',
            ];
        }, $files);
    }

    private function deleteGalleryMedia(array $media): void
    {
        Storage::disk('public')->delete(array_column($media, 'path'));
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'media' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm', 'max:51200'],
        ]);

        $media = $request->file('media');
        $path = $media->store('promo-banners', 'public');
        $type = str_starts_with($media->getMimeType(), 'video/') ? 'video' : 'image';

        $banner = PromoBanner::create([
            'title' => $validated['title'],
            'media_path' => $path,
            'media_type' => $type,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Banner promo berhasil ditambahkan.');
    }

    public function updateBanner(Request $request, PromoBanner $banner): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'media' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,mov,webm', 'max:51200'],
        ]);

        $updates = ['title' => $validated['title'], 'is_active' => $request->boolean('is_active')];
        if ($request->hasFile('media')) {
            Storage::disk('public')->delete($banner->media_path);
            $media = $request->file('media');
            $updates['media_path'] = $media->store('promo-banners', 'public');
            $updates['media_type'] = str_starts_with($media->getMimeType(), 'video/') ? 'video' : 'image';
        }

        $banner->update($updates);

        return back()->with('status', 'Banner promo berhasil diperbarui.');
    }

    public function destroyBanner(PromoBanner $banner): RedirectResponse
    {
        Storage::disk('public')->delete($banner->media_path);
        $banner->delete();

        return back()->with('status', 'Banner promo berhasil dihapus.');
    }

    public function updatePackage(Request $request, ServicePackage $package): RedirectResponse
    {
        $validated = $request->validate([
            'base_price' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'discount_percent' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $package->update($validated);

        return back()->with('status', "Harga {$package->name} berhasil diperbarui.");
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
