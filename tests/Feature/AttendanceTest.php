<?php

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

it('redirects employees to the attendance dashboard upon login and admins to the admin dashboard', function () {
    $admin = User::factory()->create([
        'email' => 'admin@gass.test',
        'password' => 'password',
        'is_admin' => true,
        'role' => 'admin',
    ]);

    $employee = User::factory()->create([
        'name' => 'Farid',
        'email' => 'farid@gass.local',
        'password' => 'user123',
        'is_admin' => false,
        'role' => 'karyawan',
        'employee_id' => 'EMP-046',
        'division' => 'IT',
        'position' => 'Intern IT',
    ]);

    $this->post(route('admin.authenticate'), [
        'email' => 'Farid',
        'password' => 'user123',
    ])->assertRedirect(route('attendance.dashboard'));

    $this->get(route('attendance.dashboard'))
        ->assertOk()
        ->assertSee('Attendance & HCM')
        ->assertSee('Farid')
        ->assertSee('EMP-046')
        ->assertSee('Check In Sekarang');

    $this->post(route('attendance.logout'))
        ->assertRedirect(route('admin.login'));

    $this->post(route('admin.authenticate'), [
        'email' => $admin->email,
        'password' => 'password',
    ])->assertRedirect(route('admin.dashboard'));

    $this->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('04 / ABSENSI')
        ->assertSee('Absensi karyawan');
});

it('records check-in and check-out with selfie photo, gps coordinates, and time', function () {
    Storage::fake('public');

    $employee = User::factory()->create([
        'name' => 'Karyawan GASS',
        'is_admin' => false,
        'role' => 'karyawan',
        'employee_id' => 'EMP-010',
    ]);

    $fakeBase64Jpeg = 'data:image/png;base64,'.base64_encode(str_repeat('a', 64));

    $this->actingAs($employee)
        ->postJson(route('attendance.action'), [
            'action' => 'checkin',
            'photo' => $fakeBase64Jpeg,
            'latitude' => -6.658412,
            'longitude' => 106.848612,
        ])
        ->assertOk()
        ->assertJsonStructure(['message', 'attendance']);

    $attendance = Attendance::firstOrFail();
    expect($attendance->user_id)->toBe($employee->id)
        ->and($attendance->check_in_time)->not->toBeNull()
        ->and($attendance->check_in_lat)->toBe(-6.658412)
        ->and($attendance->check_in_lng)->toBe(106.848612)
        ->and($attendance->check_in_photo)->toStartWith('attendances/checkin-');

    Storage::disk('public')->assertExists($attendance->check_in_photo);

    $this->actingAs($employee)
        ->postJson(route('attendance.action'), [
            'action' => 'checkout',
            'photo' => $fakeBase64Jpeg,
            'latitude' => -6.658500,
            'longitude' => 106.848700,
        ])
        ->assertOk();

    $attendance->refresh();
    expect($attendance->check_out_time)->not->toBeNull()
        ->and($attendance->check_out_lat)->toBe(-6.6585)
        ->and($attendance->check_out_photo)->toStartWith('attendances/checkout-');

    Storage::disk('public')->assertExists($attendance->check_out_photo);
});

it('allows an admin to manage employee accounts and view attendance logs', function () {
    $admin = User::factory()->create(['is_admin' => true, 'role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.employees.store'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@gass.test',
            'password' => 'secret123',
            'employee_id' => 'EMP-099',
            'division' => 'Creative',
            'position' => 'Video Editor',
        ])
        ->assertRedirect()
        ->assertSessionHas('status');

    $employee = User::query()->where('email', 'budi@gass.test')->firstOrFail();
    expect($employee->is_admin)->toBeFalse()
        ->and($employee->role)->toBe('karyawan')
        ->and($employee->employee_id)->toBe('EMP-099');

    Attendance::create([
        'user_id' => $employee->id,
        'date' => now('Asia/Jakarta')->toDateString(),
        'check_in_time' => '08:15:20',
        'check_out_time' => '17:35:00',
        'status' => 'Hadir',
        'check_in_lat' => -6.6584,
        'check_in_lng' => 106.8486,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.attendance'))
        ->assertOk()
        ->assertSee('Budi Santoso')
        ->assertSee('EMP-099')
        ->assertSee('08:15:20')
        ->assertSee('17:35:00')
        ->assertSee('9j 20m');
});

it('allows an employee to update their profile, photo, digital signature, and password', function () {
    Storage::fake('public');

    $employee = User::factory()->create([
        'name' => 'Farid',
        'email' => 'farid@gass.local',
        'password' => 'user123',
        'is_admin' => false,
        'role' => 'karyawan',
        'employee_id' => 'EMP-046',
    ]);

    $fakeImg = 'data:image/png;base64,'.base64_encode(str_repeat('b', 64));

    $this->actingAs($employee)
        ->post(route('attendance.profile.update'), [
            'name' => 'Muhammad Farid Ramadhan',
            'email' => 'farid@gmail.com',
            'phone' => '081234567890',
            'birth_date' => '2003-05-10',
            'gender' => 'Laki-laki',
            'marital_status' => 'Belum Menikah',
            'address' => 'Bogor, Jawa Barat',
            'nationality' => 'Indonesia',
            'profile_photo_base64' => $fakeImg,
            'signature_base64' => $fakeImg,
        ])
        ->assertRedirect()
        ->assertSessionHas('open_view', 'profile');

    $employee->refresh();
    expect($employee->name)->toBe('Muhammad Farid Ramadhan')
        ->and($employee->email)->toBe('farid@gmail.com')
        ->and($employee->phone)->toBe('081234567890')
        ->and($employee->gender)->toBe('Laki-laki')
        ->and($employee->profile_photo)->toStartWith('profiles/avatar-')
        ->and($employee->signature)->toStartWith('signatures/signature-');

    $this->actingAs($employee)
        ->post(route('attendance.password.update'), [
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ])
        ->assertRedirect()
        ->assertSessionHas('open_view', 'profile');
});
