<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use App\Models\PromoBanner;
use App\Models\ServicePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        if ($this->hasInvalidUpload($request->file('media', []))) {
            return back()->withErrors(['media' => 'Media gagal diterima server. Periksa ukuran file dan konfigurasi upload PHP.']);
        }

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
        if ($this->hasInvalidUpload($request->file('media', []))) {
            return back()->withErrors(['media' => 'Media gagal diterima server. Periksa ukuran file dan konfigurasi upload PHP.']);
        }

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
            'media.*' => ['file'],
        ]);

        return $validated;
    }

    private function storeGalleryMedia(array $files): array
    {
        return array_map(function ($file): array {
            return [
                'path' => $file->store('gallery', 'public'),
                'type' => str_starts_with($file->getMimeType(), 'video/') || in_array(strtolower($file->getClientOriginalExtension()), ['mp4', 'mov', 'webm'], true) ? 'video' : 'image',
            ];
        }, $files);
    }

    private function deleteGalleryMedia(array $media): void
    {
        Storage::disk('public')->delete(array_column($media, 'path'));
    }

    private function hasInvalidUpload(array|UploadedFile $media): bool
    {
        $files = is_array($media) ? $media : [$media];

        return collect($files)->contains(fn (UploadedFile $file): bool => ! $file->isValid());
    }

    public function storeBanner(Request $request): RedirectResponse
    {
        if ($request->hasFile('media') && ! $request->file('media')->isValid()) {
            return back()->withErrors(['media' => 'Video gagal diterima server. Periksa ukuran file dan konfigurasi upload PHP.']);
        }

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'media' => ['required', 'file'],
        ]);

        $media = $request->file('media');
        $path = $media->store('promo-banners', 'public');
        $type = $this->bannerMediaType($media);

        $banner = PromoBanner::create([
            'title' => $validated['title'] ?? '',
            'media_path' => $path,
            'media_type' => $type,
            'is_active' => $request->boolean('is_active'),
        ]);

        return back()->with('status', 'Banner promo berhasil ditambahkan.');
    }

    public function updateBanner(Request $request, PromoBanner $banner): RedirectResponse
    {
        if ($request->hasFile('media') && ! $request->file('media')->isValid()) {
            return back()->withErrors(['media' => 'Video gagal diterima server. Periksa ukuran file dan konfigurasi upload PHP.']);
        }

        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:120'],
            'media' => ['nullable', 'file'],
        ]);

        $updates = ['title' => $validated['title'] ?? '', 'is_active' => $request->boolean('is_active')];
        if ($request->hasFile('media')) {
            Storage::disk('public')->delete($banner->media_path);
            $media = $request->file('media');
            $updates['media_path'] = $media->store('promo-banners', 'public');
            $updates['media_type'] = $this->bannerMediaType($media);
        }

        $banner->update($updates);

        return back()->with('status', 'Banner promo berhasil diperbarui.');
    }

    private function bannerMediaType(UploadedFile $media): string
    {
        return str_starts_with($media->getMimeType(), 'video/') || in_array(strtolower($media->getClientOriginalExtension()), ['mp4', 'mov', 'webm'], true)
            ? 'video'
            : 'image';
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
