<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\GalleryItem;
use App\Models\PromoBanner;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = trim($credentials['email']);
        $user = User::query()
            ->whereRaw('LOWER(email) = ?', [mb_strtolower($loginInput)])
            ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($loginInput)])
            ->first();

        if (! $user || ! Auth::attempt(['email' => $user->email, 'password' => $credentials['password']])) {
            return back()->withErrors(['email' => 'Username/email atau password tidak sesuai.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $request->user()->is_admin) {
            return redirect()->route('attendance.dashboard');
        }

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
        $packageSlugs = [
            'pembuatan-website-basic',
            'pembuatan-website-standar',
            'pembuatan-website-pro',
            'pembuatan-social-media-basic',
            'pembuatan-social-media-standar',
            'pembuatan-social-media-pro',
            'video-ai-standard-satuan',
            'video-ai-premium-satuan',
            'video-ai-professional',
            'carousel-standard',
            'carousel-premium',
            'carousel-pro',
            'maintenance-website-basic',
            'maintenance-website-standard',
            'maintenance-website-professional',
            'maintenance-website-advanced',
            'maintenance-website-premium',
            'maintenance-social-media',
            'video-ai-standard-bulanan',
            'video-ai-premium-bulanan',
            'video-ai-professional-bulanan',
            'carousel-standard-bulanan',
            'carousel-premium-bulanan',
            'carousel-pro-bulanan',
        ];
        $packages = ServicePackage::query()->whereIn('slug', $packageSlugs)->orderBy('id')->get();
        $monthlySlugs = collect($packageSlugs)->filter(fn (string $slug): bool => str_contains($slug, 'bulanan') || str_starts_with($slug, 'maintenance-'))->values();

        return view('admin.prices', [
            'singlePackages' => $packages->whereNotIn('slug', $monthlySlugs),
            'monthlyPackages' => $packages->whereIn('slug', $monthlySlugs),
        ]);
    }

    public function banners(): View
    {
        return view('admin.banners', ['banners' => PromoBanner::query()->latest()->get()]);
    }

    public function gallery(): View
    {
        return view('admin.gallery', ['galleryItems' => GalleryItem::query()->latest()->get()]);
    }

    public function attendance(Request $request): View
    {
        $today = now('Asia/Jakarta')->toDateString();
        $employees = User::query()
            ->where('is_admin', false)
            ->orderBy('name')
            ->get();

        $query = Attendance::query()->with('user')->orderByDesc('date')->orderByDesc('check_in_time');

        if ($request->filled('date')) {
            $query->where('date', $request->string('date')->toString());
        } elseif ($request->filled('month')) {
            $query->where('date', 'like', $request->string('month')->toString().'%');
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        Attendance::query()
            ->where('status', 'Terlambat')
            ->update(['status' => 'Hadir']);

        $attendances = $query->limit(300)->get();
        $todayAttendances = Attendance::query()->where('date', $today)->get();
        $shiftPagiToday = $todayAttendances->filter(fn (Attendance $item): bool => $item->shift_label === 'Shift Pagi')->count();
        $shiftSoreToday = $todayAttendances->filter(fn (Attendance $item): bool => $item->shift_label === 'Shift Sore')->count();

        $employeeFolders = $employees->map(function (User $employee) use ($attendances, $todayAttendances): array {
            $records = $attendances->where('user_id', $employee->id)->values();
            $totalMinutes = $records->sum(fn (Attendance $item): int => $item->work_minutes);
            $months = $records
                ->groupBy(fn (Attendance $item): string => substr((string) $item->date, 0, 7))
                ->map(fn ($items): int => $items->count())
                ->all();

            return [
                'employee' => $employee,
                'records' => $records,
                'total_present' => $records->count(),
                'shift_pagi' => $records->filter(fn (Attendance $item): bool => $item->shift_label === 'Shift Pagi')->count(),
                'shift_sore' => $records->filter(fn (Attendance $item): bool => $item->shift_label === 'Shift Sore')->count(),
                'total_minutes' => $totalMinutes,
                'total_duration' => sprintf('%dj %dm', intdiv($totalMinutes, 60), $totalMinutes % 60),
                'present_today' => $todayAttendances->contains('user_id', $employee->id),
                'last_date' => $records->first()?->date,
                'months' => $months,
            ];
        });

        return view('admin.attendance', [
            'employees' => $employees,
            'employeeFolders' => $employeeFolders,
            'attendances' => $attendances,
            'today' => $today,
            'todayStats' => [
                'total_employees' => $employees->count(),
                'present_today' => $todayAttendances->where('status', 'Hadir')->count(),
                'on_time_today' => $todayAttendances->where('status', 'Hadir')->count(),
                'late_today' => 0,
                'shift_pagi_today' => $shiftPagiToday,
                'shift_sore_today' => $shiftSoreToday,
                'not_checked_in' => max(0, $employees->count() - $todayAttendances->count()),
            ],
        ]);
    }

    public function storeEmployee(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'string', 'max:150'],
            'password' => ['required', 'string', 'min:6', 'max:100'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'division' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:100'],
            'supervisor' => ['nullable', 'string', 'max:120'],
        ]);

        $rawLogin = trim(($validated['email'] ?? '') ?: $validated['name']);
        $email = str_contains($rawLogin, '@')
            ? mb_strtolower($rawLogin)
            : preg_replace('/[^a-z0-9._-]/', '', mb_strtolower($rawLogin)).'@gass.local';

        if (User::query()
            ->where(function ($query) use ($email, $validated): void {
                $query->whereRaw('LOWER(email) = ?', [$email])
                    ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($validated['name'])]);
            })
            ->exists()) {
            return back()->withErrors(['email' => 'Username atau email karyawan tersebut sudah terdaftar.'])->withInput();
        }

        $nextNumber = User::query()->where('is_admin', false)->count() + 1;
        while (User::query()->where('employee_id', sprintf('EMP-%03d', $nextNumber))->exists()) {
            $nextNumber++;
        }

        User::create([
            'name' => $validated['name'],
            'email' => $email,
            'password' => $validated['password'],
            'is_admin' => false,
            'role' => 'karyawan',
            'employee_id' => ($validated['employee_id'] ?? null) ?: sprintf('EMP-%03d', $nextNumber),
            'division' => ($validated['division'] ?? null) ?: 'Digital & Creative',
            'position' => ($validated['position'] ?? null) ?: 'Staff',
            'supervisor' => ($validated['supervisor'] ?? null) ?: 'Head Departemen',
        ]);

        return back()->with('status', "Akun karyawan {$validated['name']} berhasil ditambahkan.");
    }

    public function updateEmployee(Request $request, User $employee): RedirectResponse
    {
        abort_if($employee->is_admin, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'string', 'max:150'],
            'password' => ['nullable', 'string', 'min:6', 'max:100'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'division' => ['nullable', 'string', 'max:100'],
            'position' => ['nullable', 'string', 'max:100'],
            'supervisor' => ['nullable', 'string', 'max:120'],
        ]);

        $rawLogin = trim(($validated['email'] ?? '') ?: $validated['name']);
        $email = str_contains($rawLogin, '@')
            ? mb_strtolower($rawLogin)
            : preg_replace('/[^a-z0-9._-]/', '', mb_strtolower($rawLogin)).'@gass.local';

        if (User::query()
            ->where('id', '!=', $employee->id)
            ->where(function ($query) use ($email, $validated): void {
                $query->whereRaw('LOWER(email) = ?', [$email])
                    ->orWhereRaw('LOWER(name) = ?', [mb_strtolower($validated['name'])]);
            })
            ->exists()) {
            return back()->withErrors(['email' => 'Username atau email karyawan tersebut sudah digunakan akun lain.'])->withInput();
        }

        $updates = [
            'name' => $validated['name'],
            'email' => $email,
            'employee_id' => ($validated['employee_id'] ?? null) ?: $employee->employee_id,
            'division' => ($validated['division'] ?? null) ?: $employee->division,
            'position' => ($validated['position'] ?? null) ?: $employee->position,
            'supervisor' => ($validated['supervisor'] ?? null) ?: $employee->supervisor,
        ];

        if (! empty($validated['password'])) {
            $updates['password'] = $validated['password'];
        }

        $employee->update($updates);

        return back()->with('status', "Data karyawan {$employee->name} berhasil diperbarui.");
    }

    public function destroyEmployee(User $employee): RedirectResponse
    {
        abort_if($employee->is_admin, 403);

        foreach ($employee->attendances as $attendance) {
            Storage::disk('public')->delete(array_filter([
                $attendance->check_in_photo,
                $attendance->check_out_photo,
            ]));
        }

        $name = $employee->name;
        $employee->delete();

        return back()->with('status', "Akun karyawan {$name} berhasil dihapus.");
    }

    public function destroyAttendance(Attendance $attendance): RedirectResponse
    {
        Storage::disk('public')->delete(array_filter([
            $attendance->check_in_photo,
            $attendance->check_out_photo,
        ]));

        $attendance->delete();

        return back()->with('status', 'Data log absensi berhasil dihapus.');
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

    public function updatePackages(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'packages' => ['required', 'array'],
            'packages.*.id' => ['required', 'integer', 'exists:service_packages,id'],
            'packages.*.base_price' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'packages.*.discount_percent' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        DB::transaction(function () use ($validated): void {
            foreach ($validated['packages'] as $packageData) {
                ServicePackage::whereKey($packageData['id'])->update([
                    'base_price' => $packageData['base_price'],
                    'discount_percent' => $packageData['discount_percent'],
                ]);
            }
        });

        return back()->with('status', 'Semua harga dan diskon berhasil diperbarui.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
