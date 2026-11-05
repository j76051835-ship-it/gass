<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function dashboard(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('admin.login');
        }

        $now = now('Asia/Jakarta');
        $today = $now->toDateString();
        $currentMonthPrefix = $now->format('Y-m');

        Attendance::query()
            ->where('user_id', $user->id)
            ->where('status', 'Terlambat')
            ->update(['status' => 'Hadir']);

        $attendances = Attendance::query()
            ->where('user_id', $user->id)
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        $todayAttendance = $attendances->firstWhere('date', $today);
        $monthlyAttendances = $attendances->filter(
            fn (Attendance $item): bool => str_starts_with($item->date, $currentMonthPrefix)
        );

        $presentCount = $monthlyAttendances->where('status', 'Hadir')->count();
        $alphaCount = $monthlyAttendances->where('status', 'Alpha')->count();
        $shiftPagiCount = $monthlyAttendances->filter(fn (Attendance $item): bool => $item->shift_label === 'Shift Pagi')->count();
        $shiftSoreCount = $monthlyAttendances->filter(fn (Attendance $item): bool => $item->shift_label === 'Shift Sore')->count();
        $totalMinutes = (int) $monthlyAttendances->sum(fn (Attendance $item): int => $item->work_minutes);
        $totalDuration = intdiv($totalMinutes, 60).'j '.($totalMinutes % 60).'m';
        $attendanceScore = 100;
        $grade = 'Grade A';

        return view('attendance.dashboard', [
            'user' => $user,
            'today' => $today,
            'now' => $now,
            'todayAttendance' => $todayAttendance,
            'attendances' => $attendances,
            'stats' => [
                'present' => $presentCount,
                'on_time' => $presentCount,
                'late' => 0,
                'alpha' => $alphaCount,
                'shift_pagi' => $shiftPagiCount,
                'shift_sore' => $shiftSoreCount,
                'total_duration' => $totalDuration,
                'score' => $attendanceScore,
                'grade' => $grade,
            ],
        ]);
    }

    public function action(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'action' => ['required', 'string', 'in:checkin,checkout'],
            'photo' => ['required', 'string'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $now = now('Asia/Jakarta');
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $attendance = Attendance::query()
            ->where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($validated['action'] === 'checkin') {
            if ($attendance && $attendance->check_in_time) {
                return $this->errorResponse($request, 'Anda sudah melakukan absensi masuk hari ini.');
            }

            $photoPath = $this->storeBase64Photo($validated['photo'], 'checkin', $user->id);
            if (! $photoPath) {
                return $this->errorResponse($request, 'Format foto selfie tidak valid.');
            }

            $status = 'Hadir';
            $shiftLabel = $currentTime < '13:30:00' ? 'Shift Pagi' : 'Shift Sore';

            $attendance = Attendance::updateOrCreate(
                ['user_id' => $user->id, 'date' => $today],
                [
                    'check_in_time' => $currentTime,
                    'status' => $status,
                    'check_in_lat' => $validated['latitude'],
                    'check_in_lng' => $validated['longitude'],
                    'check_in_photo' => $photoPath,
                    'notes' => $validated['notes'] ?? $shiftLabel,
                ]
            );

            return $this->successResponse($request, "Absensi Masuk berhasil dicatat pada {$currentTime} WIB ({$shiftLabel}).", $attendance);
        }

        if (! $attendance || ! $attendance->check_in_time) {
            return $this->errorResponse($request, 'Anda belum melakukan absensi masuk hari ini.');
        }

        if ($attendance->check_out_time) {
            return $this->errorResponse($request, 'Anda sudah melakukan absensi pulang hari ini.');
        }

        $photoPath = $this->storeBase64Photo($validated['photo'], 'checkout', $user->id);
        if (! $photoPath) {
            return $this->errorResponse($request, 'Format foto selfie tidak valid.');
        }

        $attendance->update([
            'check_out_time' => $currentTime,
            'check_out_lat' => $validated['latitude'],
            'check_out_lng' => $validated['longitude'],
            'check_out_photo' => $photoPath,
        ]);

        return $this->successResponse($request, "Absensi Pulang berhasil dicatat pada {$currentTime} WIB.", $attendance);
    }

    public function updateProfile(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:30'],
            'marital_status' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:1000'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'profile_photo_base64' => ['nullable', 'string'],
            'remove_profile_photo' => ['nullable', 'boolean'],
            'signature_base64' => ['nullable', 'string'],
            'remove_signature' => ['nullable', 'boolean'],
        ]);

        $updates = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'birth_date' => $validated['birth_date'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'marital_status' => $validated['marital_status'] ?? null,
            'address' => $validated['address'] ?? null,
            'nationality' => ($validated['nationality'] ?? null) ?: 'Indonesia',
        ];

        if (! empty($validated['remove_profile_photo'])) {
            $updates['profile_photo'] = null;
        } elseif (! empty($validated['profile_photo_base64'])) {
            $photoPath = $this->storeBase64Photo($validated['profile_photo_base64'], 'avatar', $user->id, 'profiles');
            if ($photoPath) {
                $updates['profile_photo'] = $photoPath;
            }
        }

        if (! empty($validated['remove_signature'])) {
            $updates['signature'] = null;
        } elseif (! empty($validated['signature_base64'])) {
            $sigPath = $this->storeBase64Photo($validated['signature_base64'], 'signature', $user->id, 'signatures');
            if ($sigPath) {
                $updates['signature'] = $sigPath;
            }
        }

        $user->update($updates);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Profil berhasil diperbarui.',
                'user' => $user->fresh(),
            ]);
        }

        return back()->with('status', 'Profil berhasil diperbarui.')->with('open_view', 'profile');
    }

    public function updatePassword(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        abort_unless($user !== null, 401);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Password berhasil diperbarui.',
            ]);
        }

        return back()->with('status', 'Password berhasil diperbarui.')->with('open_view', 'profile');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    private function storeBase64Photo(string $base64Data, string $prefix, int $userId, string $folder = 'attendances'): ?string
    {
        if (! preg_match('/^data:image\/(jpeg|jpg|png|webp|gif);base64,(.+)$/i', $base64Data, $matches)) {
            return null;
        }

        $extension = strtolower($matches[1]) === 'jpeg' ? 'jpg' : strtolower($matches[1]);
        $binary = base64_decode($matches[2], true);

        if ($binary === false || strlen($binary) < 32) {
            return null;
        }

        $filename = $folder.'/'.$prefix.'-'.$userId.'-'.now('Asia/Jakarta')->format('YmdHis').'-'.Str::lower(Str::random(6)).'.'.$extension;
        Storage::disk('public')->put($filename, $binary);

        return $filename;
    }

    private function errorResponse(Request $request, string $message): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 422);
        }

        return back()->withErrors(['attendance' => $message]);
    }

    private function successResponse(Request $request, string $message, Attendance $attendance): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
                'attendance' => $attendance,
            ]);
        }

        return back()->with('status', $message);
    }
}
