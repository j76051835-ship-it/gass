<?php

namespace App\Http\Controllers;

use App\Models\PromoBanner;
use App\Models\ServicePackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
