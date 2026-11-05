@extends('layouts.app')

@section('title', 'Absensi Karyawan — GASS Admin')
@section('body_class', 'admin-page')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
.admin-page { background: #071a32; color: #101828; }
.admin-workspace { max-width: 1180px; margin: 0 auto; padding: 42px 32px 90px; }
.admin-topbar { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 28px; }
.admin-topbar h1 { margin: 8px 0 0; color: #fff; font-size: clamp(36px, 6vw, 64px); line-height: .9; letter-spacing: -.06em; }
.admin-topbar .eyebrow { color: #b9c9dc; }
.admin-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.admin-link, .admin-logout { border: 1px solid #fff; padding: 11px 15px; background: #fff; color: #071a32; font: 11px var(--mono); cursor: pointer; text-decoration: none; }
.admin-link:hover, .admin-logout:hover { background: #f7c934; }
.admin-intro { margin: 0 0 24px; color: #b9c9dc; font-size: 14px; }
.admin-status { margin-bottom: 20px; padding: 13px 16px; background: #d9f8e2; color: #166534; font-size: 13px; }
.admin-error { margin-bottom: 20px; padding: 13px 16px; background: #ffe1e1; color: #8b1010; font-size: 13px; }

.admin-stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 26px; }
.admin-stat-box { padding: 20px; border: 1px solid #b8c4d3; background: #fffdf8; color: #071a32; }
.admin-stat-box small { display: block; color: #52647a; font: 10px var(--mono); text-transform: uppercase; margin-bottom: 8px; }
.admin-stat-box strong { display: block; font-size: 32px; line-height: 1; color: #071a32; }
.admin-stat-box span { display: inline-block; margin-top: 8px; font-size: 11px; color: #52647a; }

.admin-panel { margin-bottom: 28px; padding: 24px; border: 1px solid #b8c4d3; background: #fffdf8; color: #071a32; }
.admin-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 18px; padding-bottom: 14px; border-bottom: 1px solid #dde4ee; }
.admin-panel-head h2 { margin: 0; font-size: 22px; color: #071a32; }
.admin-panel-head p { margin: 4px 0 0; font-size: 12.5px; color: #52647a; }

.admin-filter-bar { display: flex; flex-wrap: wrap; gap: 12px; align-items: end; margin-bottom: 18px; }
.admin-filter-bar label, .admin-form-grid label { display: grid; gap: 6px; color: #263b55; font: 10px var(--mono); text-transform: uppercase; }
.admin-filter-bar input, .admin-filter-bar select, .admin-form-grid input { padding: 10px 12px; border: 1px solid #8292a8; background: #f4f7fb; color: #071a32; font: 13px var(--display); }
.admin-btn { padding: 11px 16px; border: 0; background: #071a32; color: #fff; font: 700 11px var(--mono); text-transform: uppercase; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
.admin-btn:hover { background: #087bdc; }
.admin-btn-secondary { background: #e2e8f0; color: #071a32; }
.admin-btn-danger { background: #dc2626; color: #fff; padding: 7px 11px; font-size: 10px; }
.admin-btn-danger:hover { background: #b91c1c; }

.admin-table-wrap { width: 100%; overflow-x: auto; }
.admin-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; }
.admin-table th { padding: 12px 14px; background: #eef3f9; color: #263b55; font: 700 10px var(--mono); text-transform: uppercase; border-bottom: 2px solid #cbd5e1; }
.admin-table td { padding: 13px 14px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; color: #071a32; }
.admin-table tr:hover td { background: #f8fafc; }
.admin-badge { display: inline-block; padding: 4px 9px; border-radius: 4px; font: 700 10px var(--mono); text-transform: uppercase; }
.admin-badge.hadir { background: #d1fae5; color: #065f46; }
.admin-badge.terlambat { background: #fef3c7; color: #92400e; }

.admin-thumb-group { display: flex; gap: 6px; align-items: center; }
.admin-thumb { width: 38px; height: 38px; object-fit: cover; border-radius: 6px; border: 1px solid #94a3b8; cursor: pointer; transition: transform .15s ease; }
.admin-thumb:hover { transform: scale(1.08); border-color: #087bdc; }
.admin-gps-btn { padding: 6px 10px; border: 1px solid #087bdc; background: #e8f3ff; color: #087bdc; font: 700 10px var(--mono); cursor: pointer; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px; }
.admin-gps-btn:hover { background: #087bdc; color: #fff; }

.admin-form-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 16px; }
.admin-employee-list { display: grid; gap: 10px; margin-top: 18px; }
.admin-employee-item { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; padding: 14px 16px; border: 1px solid #cbd5e1; background: #f8fafc; }

.admin-modal { position: fixed; inset: 0; z-index: 3000; background: rgba(5, 11, 24, .76); display: grid; place-items: center; padding: 20px; }
.admin-modal[hidden] { display: none; }
.admin-modal-card { width: min(100%, 620px); background: #fffdf8; color: #071a32; border: 1px solid #b8c4d3; padding: 22px; box-shadow: 10px 10px 0 #f7c934; }
.admin-modal-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px solid #dde4ee; }
.admin-modal-head h3 { margin: 0; font-size: 18px; }
.admin-modal-close { border: 1px solid #071a32; background: #071a32; color: #fff; width: 32px; height: 32px; cursor: pointer; font-weight: 700; }

@media (max-width: 860px) {
    .admin-workspace { padding: 32px 18px 65px; }
    .admin-stats-grid, .admin-form-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
    .admin-stats-grid, .admin-form-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<section class="admin-workspace">
    <div class="admin-topbar">
        <div>
            <p class="eyebrow">GASS / 04 Absensi &amp; SDM</p>
            <h1>Absensi karyawan.</h1>
        </div>
        <div class="admin-actions">
            <a class="admin-link" href="{{ route('attendance.dashboard') }}">Lihat Portal Absensi ↗</a>
            <a class="admin-link" href="{{ route('admin.dashboard') }}">← Workspace</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="admin-logout" type="submit">Keluar ↗</button>
            </form>
        </div>
    </div>

    <p class="admin-intro">Pantau jam masuk, jam pulang, durasi kerja, titik lokasi GPS, dan bukti foto selfie karyawan secara real-time.</p>

    @if (session('status'))
        <div class="admin-status">{{ session('status') }}</div>
    @endif
    @if ($errors->any())
        <div class="admin-error">{{ $errors->first() }}</div>
    @endif

    <!-- Statistik Hari Ini -->
    <div class="admin-stats-grid">
        <div class="admin-stat-box">
            <small>Total Karyawan</small>
            <strong>{{ $todayStats['total_employees'] }}</strong>
            <span>Akun karyawan terdaftar</span>
        </div>
        <div class="admin-stat-box">
            <small>Hadir Hari Ini ({{ $today }})</small>
            <strong style="color:#065f46;">{{ $todayStats['present_today'] }}</strong>
            <span>{{ $todayStats['on_time_today'] }} tepat waktu</span>
        </div>
        <div class="admin-stat-box">
            <small>Terlambat Hari Ini</small>
            <strong style="color:#b45309;">{{ $todayStats['late_today'] }}</strong>
            <span>Check-in lewat 08:30 WIB</span>
        </div>
        <div class="admin-stat-box">
            <small>Belum Absen Masuk</small>
            <strong style="color:#b91c1c;">{{ $todayStats['not_checked_in'] }}</strong>
            <span>Menunggu verifikasi hari ini</span>
        </div>
    </div>

    <!-- Panel 1: Log Kehadiran Karyawan -->
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Rekap Log Absensi Karyawan</h2>
                <p>Klik tombol Peta atau thumbnail foto untuk memeriksa bukti kehadiran kamera &amp; koordinat GPS.</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.attendance') }}" class="admin-filter-bar">
            <label>
                Tanggal Spesifik
                <input type="date" name="date" value="{{ request('date') }}">
            </label>
            <label>
                Atau Pilih Bulan
                <input type="month" name="month" value="{{ request('month') }}">
            </label>
            <label>
                Karyawan
                <select name="user_id">
                    <option value="">Semua Karyawan</option>
                    @foreach ($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(request('user_id') == $emp->id)>{{ $emp->name }} ({{ $emp->employee_id ?? 'Karyawan' }})</option>
                    @endforeach
                </select>
            </label>
            <button type="submit" class="admin-btn">Filter Data</button>
            @if (request()->hasAny(['date', 'month', 'user_id']))
                <a href="{{ route('admin.attendance') }}" class="admin-btn admin-btn-secondary">Reset</a>
            @endif
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Lokasi GPS</th>
                        <th>Foto Selfie</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $item)
                        <tr>
                            <td>
                                <strong>{{ $item->user?->name ?? 'Karyawan' }}</strong><br>
                                <small style="color:#52647a;font-family:var(--mono);font-size:11px;">
                                    {{ $item->user?->employee_id ?? '-' }} • {{ $item->user?->division ?? 'Staf' }}
                                </small>
                            </td>
                            <td style="font-family:var(--mono);font-size:12px;">{{ $item->date }}</td>
                            <td style="font-family:var(--mono);font-weight:700;color:#065f46;">{{ $item->check_in_time ?? '--:--' }}</td>
                            <td style="font-family:var(--mono);font-weight:700;color:#087bdc;">{{ $item->check_out_time ?? '--:--' }}</td>
                            <td style="font-family:var(--mono);font-size:12px;">{{ $item->work_duration }}</td>
                            <td>
                                <span class="admin-badge {{ strtolower($item->status) === 'terlambat' ? 'terlambat' : 'hadir' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td>
                                @if ($item->check_in_lat && $item->check_in_lng)
                                    <button
                                        type="button"
                                        class="admin-gps-btn"
                                        data-admin-map
                                        data-name="{{ $item->user?->name }}"
                                        data-date="{{ $item->date }}"
                                        data-in-lat="{{ $item->check_in_lat }}"
                                        data-in-lng="{{ $item->check_in_lng }}"
                                        data-out-lat="{{ $item->check_out_lat }}"
                                        data-out-lng="{{ $item->check_out_lng }}"
                                    >
                                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Lihat Peta</span>
                                    </button>
                                @else
                                    <span>-</span>
                                @endif
                            </td>
                            <td>
                                <div class="admin-thumb-group">
                                    @if ($item->check_in_photo)
                                        <img
                                            src="{{ route('media', ['path' => $item->check_in_photo]) }}"
                                            alt="Foto Masuk"
                                            title="Foto Masuk: {{ $item->check_in_time }}"
                                            class="admin-thumb"
                                            data-admin-photo
                                            data-url="{{ route('media', ['path' => $item->check_in_photo]) }}"
                                            data-caption="Foto Masuk — {{ $item->user?->name }} ({{ $item->date }} {{ $item->check_in_time }} WIB)"
                                        >
                                    @endif
                                    @if ($item->check_out_photo)
                                        <img
                                            src="{{ route('media', ['path' => $item->check_out_photo]) }}"
                                            alt="Foto Pulang"
                                            title="Foto Pulang: {{ $item->check_out_time }}"
                                            class="admin-thumb"
                                            data-admin-photo
                                            data-url="{{ route('media', ['path' => $item->check_out_photo]) }}"
                                            data-caption="Foto Pulang — {{ $item->user?->name }} ({{ $item->date }} {{ $item->check_out_time }} WIB)"
                                        >
                                    @endif
                                    @if (! $item->check_in_photo && ! $item->check_out_photo)
                                        <span>-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.attendance.destroy', $item) }}" onsubmit="return confirm('Hapus data absensi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align:center;padding:30px;color:#52647a;">
                                Belum ada rekaman absensi karyawan yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Panel 2: Kelola Akun Karyawan -->
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Kelola Akun Karyawan</h2>
                <p>Karyawan login melalui halaman <code>/admin/login</code> dan otomatis diarahkan ke halaman Absensi Karyawan.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.employees.store') }}">
            @csrf
            <div class="admin-form-grid">
                <label>
                    Username / Nama Karyawan *
                    <input type="text" name="name" placeholder="Masukkan nama / username" required>
                </label>
                <label>
                    Email (Opsional)
                    <input type="text" name="email" placeholder="Kosongkan untuk otomatis">
                </label>
                <label>
                    Password *
                    <input type="password" name="password" value="user123" placeholder="Minimal 6 karakter" required minlength="6">
                </label>
                <label>
                    NIK / ID Karyawan
                    <input type="text" name="employee_id" placeholder="Contoh: EMP-046">
                </label>
                <label>
                    Divisi
                    <input type="text" name="division" placeholder="Contoh: IT / Creative">
                </label>
                <label>
                    Posisi / Jabatan
                    <input type="text" name="position" placeholder="Contoh: Intern IT / Staff">
                </label>
            </div>
            <button type="submit" class="admin-btn">+ Tambah Akun Karyawan</button>
        </form>

        <div class="admin-employee-list">
            @forelse ($employees as $employee)
                <div class="admin-employee-item">
                    <div>
                        <strong style="font-size:15px;">{{ $employee->name }}</strong>
                        <span style="margin-left:8px;padding:2px 7px;background:#e8f3ff;color:#087bdc;font:700 10px var(--mono);">{{ $employee->employee_id ?? 'EMP' }}</span>
                        <div style="font-size:12px;color:#52647a;margin-top:4px;">
                            Email: <strong>{{ $employee->email }}</strong> • Divisi: {{ $employee->division ?? '-' }} • Posisi: {{ $employee->position ?? '-' }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Hapus akun karyawan {{ $employee->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="admin-btn admin-btn-danger">Hapus Akun</button>
                    </form>
                </div>
            @empty
                <p style="color:#52647a;font-size:13px;">Belum ada akun karyawan. Tambahkan akun karyawan di atas.</p>
            @endforelse
        </div>
    </section>
</section>

<!-- Modal Peta Admin -->
<div class="admin-modal" id="admin-map-modal" hidden>
    <div class="admin-modal-card">
        <div class="admin-modal-head">
            <h3 id="admin-map-title">Titik Lokasi GPS</h3>
            <button type="button" class="admin-modal-close" data-admin-modal-close>×</button>
        </div>
        <div id="admin-leaflet-map" style="width:100%;height:340px;border:1px solid #cbd5e1;"></div>
        <p id="admin-map-meta" style="margin:10px 0 0;font:11px var(--mono);color:#52647a;"></p>
    </div>
</div>

<!-- Modal Foto Admin -->
<div class="admin-modal" id="admin-photo-modal" hidden>
    <div class="admin-modal-card">
        <div class="admin-modal-head">
            <h3 id="admin-photo-title">Bukti Foto Selfie</h3>
            <button type="button" class="admin-modal-close" data-admin-modal-close>×</button>
        </div>
        <img id="admin-photo-img" src="" alt="Bukti Foto" style="width:100%;max-height:420px;object-fit:contain;background:#071a32;">
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapModal = document.getElementById('admin-map-modal');
    const photoModal = document.getElementById('admin-photo-modal');
    let adminMap = null;
    let adminMarkers = [];

    document.querySelectorAll('[data-admin-map]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const inLat = parseFloat(btn.dataset.inLat);
            const inLng = parseFloat(btn.dataset.inLng);
            const outLat = parseFloat(btn.dataset.outLat);
            const outLng = parseFloat(btn.dataset.outLng);

            document.getElementById('admin-map-title').textContent = 'Lokasi GPS — ' + btn.dataset.name + ' (' + btn.dataset.date + ')';
            document.getElementById('admin-map-meta').textContent = 'Check-In: ' + inLat + ', ' + inLng + (!isNaN(outLat) ? ' | Check-Out: ' + outLat + ', ' + outLng : '');
            mapModal.hidden = false;

            setTimeout(function () {
                if (!window.L) return;
                if (!adminMap) {
                    adminMap = L.map('admin-leaflet-map').setView([inLat, inLng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap',
                    }).addTo(adminMap);
                } else {
                    adminMap.setView([inLat, inLng], 15);
                    adminMarkers.forEach(function (m) { m.remove(); });
                    adminMarkers = [];
                }
                adminMap.invalidateSize();
                adminMarkers.push(L.marker([inLat, inLng]).addTo(adminMap).bindPopup('<b>Check-In Masuk</b><br>' + btn.dataset.name).openPopup());
                if (!isNaN(outLat) && !isNaN(outLng)) {
                    adminMarkers.push(L.marker([outLat, outLng]).addTo(adminMap).bindPopup('<b>Check-Out Pulang</b><br>' + btn.dataset.name));
                }
            }, 120);
        });
    });

    const isAdminThumbLegacyBlank = function (img) {
        try {
            const c = document.createElement('canvas');
            c.width = img.naturalWidth || 640;
            c.height = img.naturalHeight || 480;
            const ctx = c.getContext('2d');
            ctx.drawImage(img, 0, 0);
            const sample = ctx.getImageData(0, 0, c.width, Math.floor(c.height * 0.35)).data;
            let diff = 0;
            const r0 = sample[0], g0 = sample[1], b0 = sample[2];
            for (let i = 0; i < sample.length; i += 64) {
                diff += Math.abs(sample[i] - r0) + Math.abs(sample[i + 1] - g0) + Math.abs(sample[i + 2] - b0);
            }
            return (diff / (sample.length / 64)) < 6;
        } catch {
            return false;
        }
    };

    const generateAdminDigitalCard = function (caption) {
        const c = document.createElement('canvas');
        c.width = 640;
        c.height = 480;
        const ctx = c.getContext('2d');
        const bgGrad = ctx.createLinearGradient(0, 0, 640, 480);
        bgGrad.addColorStop(0, '#091224');
        bgGrad.addColorStop(0.5, '#112240');
        bgGrad.addColorStop(1, '#0b172d');
        ctx.fillStyle = bgGrad;
        ctx.fillRect(0, 0, 640, 480);

        ctx.fillStyle = 'rgba(19, 34, 63, 0.94)';
        ctx.strokeStyle = 'rgba(45, 212, 191, 0.4)';
        ctx.lineWidth = 2;
        ctx.fillRect(32, 32, 576, 416);
        ctx.strokeRect(32, 32, 576, 416);

        ctx.fillStyle = 'rgba(9, 18, 36, 0.78)';
        ctx.fillRect(54, 54, 532, 44);
        ctx.fillStyle = '#2dd4bf';
        ctx.font = 'bold 13px Inter, sans-serif';
        ctx.fillText('GASS HRS • KARTU BUKTI PRESENSI DIGITAL', 72, 81);

        ctx.fillStyle = '#60a5fa';
        ctx.font = 'bold 12px monospace';
        ctx.fillText('✓ GPS & WAKTU TERVERIFIKASI (MODE TANPA KAMERA)', 72, 145);

        ctx.fillStyle = '#f8fafc';
        ctx.font = 'bold 21px Inter, sans-serif';
        ctx.fillText(caption || 'Bukti Presensi Karyawan', 72, 185);

        ctx.fillStyle = 'rgba(15, 23, 42, 0.85)';
        ctx.fillRect(54, 225, 532, 95);
        ctx.fillStyle = '#94a3b8';
        ctx.font = 'bold 11px Inter, sans-serif';
        ctx.fillText('KETERANGAN VERIFIKASI PERANGKAT', 74, 254);
        ctx.fillStyle = '#34d399';
        ctx.font = 'bold 16px Inter, sans-serif';
        ctx.fillText('● Presensi Sah — Terverifikasi via Koordinat GPS & Jam Server WIB', 74, 282);
        ctx.fillStyle = '#cbd5e1';
        ctx.font = '12px Inter, sans-serif';
        ctx.fillText('Kamera perangkat karyawan tidak tersedia saat pengambilan presensi.', 74, 304);

        return c.toDataURL('image/jpeg', 0.9);
    };

    document.querySelectorAll('[data-admin-photo]').forEach(function (img) {
        const checkUpgrade = function () {
            if (img.naturalWidth && isAdminThumbLegacyBlank(img)) {
                const upgraded = generateAdminDigitalCard(img.dataset.caption);
                img.src = upgraded;
                img.dataset.url = upgraded;
            }
        };
        if (img.complete) checkUpgrade();
        else img.addEventListener('load', checkUpgrade, { once: true });

        img.addEventListener('click', function () {
            document.getElementById('admin-photo-title').textContent = img.dataset.caption;
            document.getElementById('admin-photo-img').src = img.dataset.url;
            photoModal.hidden = false;
        });
    });

    document.querySelectorAll('[data-admin-modal-close]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            mapModal.hidden = true;
            photoModal.hidden = true;
        });
    });
});
</script>
@endpush
