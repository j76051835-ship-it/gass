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
.admin-edit-form-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
.admin-employee-list { display: grid; gap: 10px; margin-top: 18px; }
.admin-employee-item { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; padding: 14px 16px; border: 1px solid #cbd5e1; background: #f8fafc; }

/* View Switcher & Folder UI */
.admin-view-tabs { display: inline-flex; gap: 6px; background: #e8eef6; padding: 4px; border: 1px solid #cbd5e1; }
.admin-view-tab { border: 0; background: transparent; color: #334155; padding: 8px 13px; font: 700 11px var(--mono); text-transform: uppercase; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all .15s ease; }
.admin-view-tab.is-active { background: #071a32; color: #fff; box-shadow: 0 2px 6px rgba(7, 26, 50, .18); }

.admin-folder-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(265px, 1fr)); gap: 16px; margin-top: 8px; }
.admin-folder-card { position: relative; border: 1px solid #cbd5e1; background: #f8fafc; padding: 18px; cursor: pointer; transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease; display: flex; flex-direction: column; justify-content: space-between; gap: 14px; text-align: left; }
.admin-folder-card::before { content: ""; position: absolute; top: -7px; left: 14px; width: 74px; height: 7px; background: #f59e0b; border: 1px solid #d97706; border-bottom: 0; border-radius: 4px 4px 0 0; transition: background .16s ease; }
.admin-folder-card:hover { transform: translateY(-3px); border-color: #087bdc; background: #fff; box-shadow: 6px 6px 0 #f7c934; }
.admin-folder-card:hover::before { background: #087bdc; border-color: #087bdc; }

.admin-folder-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; }
.admin-folder-icon-wrap { display: inline-flex; align-items: center; gap: 10px; }
.admin-folder-svg { width: 40px; height: 40px; color: #f59e0b; flex-shrink: 0; filter: drop-shadow(0 2px 4px rgba(245, 158, 11, .22)); }
.admin-folder-nik { display: inline-block; padding: 3px 7px; background: #e8f3ff; color: #087bdc; font: 700 10px var(--mono); border-radius: 4px; }
.admin-folder-status-dot { display: inline-flex; align-items: center; gap: 5px; padding: 3px 8px; border-radius: 999px; font: 700 9.5px var(--mono); text-transform: uppercase; }
.admin-folder-status-dot.is-present { background: #d1fae5; color: #065f46; }
.admin-folder-status-dot.is-idle { background: #e2e8f0; color: #475569; }

.admin-folder-name { margin: 4px 0 2px; font-size: 17px; font-weight: 700; color: #071a32; line-height: 1.25; }
.admin-folder-role { margin: 0; font-size: 12px; color: #52647a; }

.admin-folder-recap-mini { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; padding: 10px 12px; background: #eef3f9; border: 1px solid #dde4ee; }
.admin-folder-recap-mini div small { display: block; font: 700 9px var(--mono); color: #64748b; text-transform: uppercase; margin-bottom: 2px; }
.admin-folder-recap-mini div strong { font: 700 13px var(--mono); color: #071a32; }

.admin-folder-foot { display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px dashed #cbd5e1; font: 700 11px var(--mono); color: #087bdc; }
.admin-folder-foot small { font-weight: 500; color: #64748b; font-size: 10px; }

/* Inside Employee Folder Detail */
.admin-folder-breadcrumb { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; padding: 12px 16px; background: #eef3f9; border: 1px solid #cbd5e1; margin-bottom: 18px; }
.admin-folder-path { display: flex; align-items: center; gap: 8px; font: 700 12px var(--mono); color: #071a32; flex-wrap: wrap; }
.admin-folder-back { padding: 8px 14px; border: 1px solid #071a32; background: #fff; color: #071a32; font: 700 11px var(--mono); text-transform: uppercase; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all .15s ease; }
.admin-folder-back:hover { background: #071a32; color: #fff; }

.admin-emp-recap-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
.admin-emp-recap-card { padding: 14px 16px; border: 1px solid #cbd5e1; background: #f8fafc; }
.admin-emp-recap-card small { display: block; font: 700 9.5px var(--mono); color: #52647a; text-transform: uppercase; margin-bottom: 6px; }
.admin-emp-recap-card strong { display: block; font-size: 22px; line-height: 1.1; color: #071a32; font-family: var(--mono); }
.admin-emp-recap-card span { display: inline-block; margin-top: 4px; font-size: 11px; color: #64748b; }

.admin-month-folders { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
.admin-month-pill { border: 1px solid #cbd5e1; background: #f8fafc; color: #334155; padding: 7px 12px; font: 700 11px var(--mono); cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all .15s ease; }
.admin-month-pill:hover { border-color: #087bdc; color: #087bdc; }
.admin-month-pill.is-active { background: #087bdc; border-color: #087bdc; color: #fff; }

.admin-pagination-bar { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-top: 14px; padding: 11px 14px; background: #eef3f9; border: 1px solid #cbd5e1; font: 700 11px var(--mono); color: #334155; }
.admin-pagination-info { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
.admin-pagination-select { padding: 5px 9px; border: 1px solid #94a3b8; background: #fff; color: #071a32; font: 700 11px var(--mono); cursor: pointer; }
.admin-pagination-controls { display: inline-flex; align-items: center; gap: 6px; }
.admin-page-btn { padding: 6px 12px; border: 1px solid #071a32; background: #fff; color: #071a32; font: 700 10.5px var(--mono); text-transform: uppercase; cursor: pointer; transition: all .15s ease; }
.admin-page-btn:hover:not(:disabled) { background: #071a32; color: #fff; }
.admin-page-btn:disabled { opacity: .4; cursor: not-allowed; border-color: #94a3b8; color: #64748b; }
.admin-page-indicator { padding: 6px 10px; background: #071a32; color: #fff; font: 700 10.5px var(--mono); }

.admin-page main { z-index: auto !important; }
.admin-modal { position: fixed; inset: 0; z-index: 99999; background: rgba(5, 11, 24, .82); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 20px 16px; overflow-y: auto; }
.admin-modal[hidden] { display: none !important; }
.admin-modal-card { width: min(100%, 560px); max-height: calc(100dvh - 40px); display: flex; flex-direction: column; background: #fffdf8; color: #071a32; border: 1px solid #b8c4d3; padding: 18px 20px; box-shadow: 10px 10px 0 #f7c934; position: relative; overflow: hidden; }
.admin-modal-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid #dde4ee; flex-shrink: 0; }
.admin-modal-head h3 { margin: 0; font-size: 16px; line-height: 1.3; }
.admin-modal-close { border: 1px solid #071a32; background: #071a32; color: #fff; width: 34px; height: 34px; border-radius: 6px; cursor: pointer; font-weight: 700; font-size: 18px; line-height: 1; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background .15s ease, border-color .15s ease; }
.admin-modal-close:hover { background: #dc2626; border-color: #dc2626; }

@media (max-width: 860px) {
    .admin-workspace { padding: 32px 18px 65px; }
    .admin-stats-grid, .admin-form-grid, .admin-emp-recap-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 560px) {
    .admin-stats-grid, .admin-form-grid, .admin-emp-recap-grid { grid-template-columns: 1fr; }
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
            <span>{{ $todayStats['shift_pagi_today'] ?? 0 }} Shift Pagi • {{ $todayStats['shift_sore_today'] ?? 0 }} Shift Sore</span>
        </div>
        <div class="admin-stat-box">
            <small>Skema Shift Aktif</small>
            <strong style="color:#087bdc;">2 Shift</strong>
            <span>Pagi (09:00) &amp; Sore (16:00) • Per Jam</span>
        </div>
        <div class="admin-stat-box">
            <small>Belum Absen Masuk</small>
            <strong style="color:#b91c1c;">{{ $todayStats['not_checked_in'] }}</strong>
            <span>Menunggu verifikasi hari ini</span>
        </div>
    </div>

    <!-- Panel 1: Log Kehadiran Karyawan (Tampilan Folder & Rekap) -->
    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <h2>Rekap Log Absensi Karyawan</h2>
                <p>Klik folder karyawan untuk membuka rekap kehadiran, total jam kerja, shift, titik lokasi GPS, dan bukti foto selfie masing-masing karyawan.</p>
            </div>
            <div class="admin-view-tabs" role="tablist" aria-label="Mode Tampilan Rekap">
                <button type="button" class="admin-view-tab is-active" data-admin-view-tab="folders">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span>Folder Karyawan ({{ $employeeFolders->count() }})</span>
                </button>
                <button type="button" class="admin-view-tab" data-admin-view-tab="table">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span>Semua Log ({{ $attendances->count() }})</span>
                </button>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.attendance') }}" class="admin-filter-bar">
            <label>
                Cari Folder Karyawan
                <input type="search" id="admin-folder-search" placeholder="Ketik nama / NIK / divisi...">
            </label>
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

        <!-- MODE 1A: GRID DAFTAR FOLDER KARYAWAN -->
        <div id="admin-folder-grid-view">
            @if ($employeeFolders->isEmpty())
                <div style="text-align:center;padding:36px 20px;border:1px dashed #cbd5e1;background:#f8fafc;color:#52647a;">
                    Belum ada akun karyawan yang terdaftar. Tambahkan akun karyawan pada panel di bawah.
                </div>
            @else
                <div class="admin-folder-grid" id="admin-folder-grid">
                    @foreach ($employeeFolders as $folder)
                        @php
                            $emp = $folder['employee'];
                        @endphp
                        <button
                            type="button"
                            class="admin-folder-card"
                            data-open-emp-folder="{{ $emp->id }}"
                            data-folder-search="{{ strtolower($emp->name . ' ' . ($emp->employee_id ?? '') . ' ' . ($emp->division ?? '') . ' ' . ($emp->position ?? '')) }}"
                        >
                            <div>
                                <div class="admin-folder-top">
                                    <div class="admin-folder-icon-wrap">
                                        <svg class="admin-folder-svg" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                        </svg>
                                        <span class="admin-folder-nik">{{ $emp->employee_id ?? 'EMP' }}</span>
                                    </div>
                                    @if ($folder['present_today'])
                                        <span class="admin-folder-status-dot is-present">● Hadir Hari Ini</span>
                                    @else
                                        <span class="admin-folder-status-dot is-idle">Belum Absen</span>
                                    @endif
                                </div>

                                <h3 class="admin-folder-name">{{ $emp->name }}</h3>
                                <p class="admin-folder-role">{{ $emp->division ?? 'Staf' }} • {{ $emp->position ?? 'Karyawan' }}</p>
                            </div>

                            <div class="admin-folder-recap-mini">
                                <div>
                                    <small>Kehadiran</small>
                                    <strong>{{ $folder['total_present'] }} Hari</strong>
                                </div>
                                <div>
                                    <small>Total Jam</small>
                                    <strong>{{ $folder['total_duration'] }}</strong>
                                </div>
                                <div>
                                    <small>Shift</small>
                                    <strong>{{ $folder['shift_pagi'] }}P / {{ $folder['shift_sore'] }}S</strong>
                                </div>
                            </div>

                            <div class="admin-folder-foot">
                                <small>
                                    @if ($folder['last_date'])
                                        Terakhir: {{ $folder['last_date'] }}
                                    @else
                                        Belum ada riwayat
                                    @endif
                                </small>
                                <span>Buka Rekap →</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- MODE 1B: ISI DALAM FOLDER REKAP PER KARYAWAN -->
        <div id="admin-folder-detail-view" hidden>
            @foreach ($employeeFolders as $folder)
                @php
                    $emp = $folder['employee'];
                    $records = $folder['records'];
                    $months = $folder['months'];
                @endphp
                <div class="admin-emp-folder-pane" data-emp-folder-pane="{{ $emp->id }}" hidden>
                    <div class="admin-folder-breadcrumb">
                        <div class="admin-folder-path">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="#f59e0b" style="flex-shrink:0;"><path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                            <span style="color:#64748b;">Folder Rekap Karyawan</span>
                            <span>/</span>
                            <strong>{{ $emp->name }}</strong>
                            <span class="admin-folder-nik">{{ $emp->employee_id ?? 'EMP' }}</span>
                            <span style="color:#52647a;font-weight:400;">({{ $emp->division ?? 'Staf' }} • {{ $emp->position ?? 'Karyawan' }})</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                            <button
                                type="button"
                                class="admin-folder-back"
                                style="border-color:#087bdc;color:#087bdc;"
                                data-edit-employee
                                data-action="{{ route('admin.employees.update', $emp) }}"
                                data-name="{{ $emp->name }}"
                                data-email="{{ $emp->email }}"
                                data-employee-id="{{ $emp->employee_id }}"
                                data-division="{{ $emp->division }}"
                                data-position="{{ $emp->position }}"
                            >
                                ✎ Edit Akun
                            </button>
                            <button type="button" class="admin-folder-back" data-back-to-folders>
                                ← Kembali ke Semua Folder
                            </button>
                        </div>
                    </div>

                    <!-- 4 Kartu Rekapitulasi di Dalam Folder Karyawan -->
                    <div class="admin-emp-recap-grid">
                        <div class="admin-emp-recap-card">
                            <small>Total Kehadiran</small>
                            <strong data-emp-stat-present>{{ $folder['total_present'] }} Hari</strong>
                            <span>Rekap kehadiran tercatat</span>
                        </div>
                        <div class="admin-emp-recap-card">
                            <small>Akumulasi Jam Kerja</small>
                            <strong style="color:#087bdc;" data-emp-stat-duration>{{ $folder['total_duration'] }}</strong>
                            <span>Dihitung otomatis per jam</span>
                        </div>
                        <div class="admin-emp-recap-card">
                            <small>Shift Pagi (09:00)</small>
                            <strong style="color:#065f46;" data-emp-stat-pagi>{{ $folder['shift_pagi'] }} Kali</strong>
                            <span>Presensi jadwal pagi</span>
                        </div>
                        <div class="admin-emp-recap-card">
                            <small>Shift Sore (16:00)</small>
                            <strong style="color:#d97706;" data-emp-stat-sore>{{ $folder['shift_sore'] }} Kali</strong>
                            <span>Presensi jadwal sore</span>
                        </div>
                    </div>

                    <!-- Filter Sub-Folder Bulan di Dalam Folder Karyawan -->
                    @if (count($months) > 0)
                        <div class="admin-month-folders" aria-label="Sub-folder Bulan">
                            <button type="button" class="admin-month-pill is-active" data-emp-month-filter="all">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <span>Semua Periode ({{ $folder['total_present'] }})</span>
                            </button>
                            @foreach ($months as $ym => $count)
                                <button type="button" class="admin-month-pill" data-emp-month-filter="{{ $ym }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                    <span>Bulan {{ $ym }} ({{ $count }})</span>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <!-- Tabel Rekap Karyawan di Dalam Folder -->
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Durasi Kerja</th>
                                    <th>Status &amp; Shift</th>
                                    <th>Lokasi GPS</th>
                                    <th>Foto Selfie</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($records as $item)
                                    <tr
                                        data-emp-record-row
                                        data-month="{{ substr((string) $item->date, 0, 7) }}"
                                        data-shift="{{ $item->shift_label }}"
                                        data-minutes="{{ $item->work_minutes }}"
                                    >
                                        <td style="font-family:var(--mono);font-size:12px;font-weight:700;">{{ $item->date }}</td>
                                        <td style="font-family:var(--mono);font-weight:700;color:#065f46;">{{ $item->check_in_time ?? '--:--' }}</td>
                                        <td style="font-family:var(--mono);font-weight:700;color:#087bdc;">{{ $item->check_out_time ?? '--:--' }}</td>
                                        <td style="font-family:var(--mono);font-size:12px;">{{ $item->work_duration }}</td>
                                        <td>
                                            <span class="admin-badge hadir">
                                                {{ $item->status }} • {{ $item->shift_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($item->check_in_lat && $item->check_in_lng)
                                                <button
                                                    type="button"
                                                    class="admin-gps-btn"
                                                    data-admin-map
                                                    data-name="{{ $emp->name }}"
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
                                                        data-caption="Foto Masuk — {{ $emp->name }} ({{ $item->date }} {{ $item->check_in_time }} WIB)"
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
                                                        data-caption="Foto Pulang — {{ $emp->name }} ({{ $item->date }} {{ $item->check_out_time }} WIB)"
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
                                        <td colspan="8" style="text-align:center;padding:32px;color:#52647a;">
                                            Folder rekap absensi untuk <strong>{{ $emp->name }}</strong> masih kosong (belum ada riwayat kehadiran).
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($records->isNotEmpty())
                        <div class="admin-pagination-bar" data-emp-pagination>
                            <div class="admin-pagination-info">
                                <span data-emp-page-summary>Menampilkan 1–{{ min(8, $records->count()) }} dari {{ $records->count() }} log</span>
                            </div>
                            <div class="admin-pagination-controls">
                                <button type="button" class="admin-page-btn" data-emp-page-prev>← Sebelumnya</button>
                                <span class="admin-page-indicator" data-emp-page-indicator>Hal 1 / {{ max(1, (int) ceil($records->count() / 8)) }}</span>
                                <button type="button" class="admin-page-btn" data-emp-page-next>Berikutnya →</button>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- MODE 2: TABEL GABUNGAN SEMUA LOG (DIBATASI PER HALAMAN AGAR RINGKAS) -->
        <div id="admin-all-table-view" hidden>
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
                    <tbody id="admin-all-log-tbody">
                        @forelse ($attendances as $item)
                            <tr data-all-log-row>
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
                                    <span class="admin-badge hadir">
                                        {{ $item->status }} • {{ $item->shift_label }}
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

            @if ($attendances->isNotEmpty())
                <div class="admin-pagination-bar" id="admin-all-pagination">
                    <div class="admin-pagination-info">
                        <span id="admin-all-page-summary">Menampilkan 1–{{ min(8, $attendances->count()) }} dari {{ $attendances->count() }} log</span>
                        <label style="display:inline-flex;align-items:center;gap:6px;">
                            <span>Baris:</span>
                            <select id="admin-all-page-size" class="admin-pagination-select">
                                <option value="8" selected>8 / hal</option>
                                <option value="15">15 / hal</option>
                                <option value="25">25 / hal</option>
                            </select>
                        </label>
                    </div>
                    <div class="admin-pagination-controls">
                        <button type="button" class="admin-page-btn" id="admin-all-page-prev">← Sebelumnya</button>
                        <span class="admin-page-indicator" id="admin-all-page-indicator">Hal 1 / {{ max(1, (int) ceil($attendances->count() / 8)) }}</span>
                        <button type="button" class="admin-page-btn" id="admin-all-page-next">Berikutnya →</button>
                    </div>
                </div>
            @endif
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
                    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                        <button
                            type="button"
                            class="admin-btn"
                            style="background:#087bdc;padding:7px 12px;font-size:10px;"
                            data-edit-employee
                            data-action="{{ route('admin.employees.update', $employee) }}"
                            data-name="{{ $employee->name }}"
                            data-email="{{ $employee->email }}"
                            data-employee-id="{{ $employee->employee_id }}"
                            data-division="{{ $employee->division }}"
                            data-position="{{ $employee->position }}"
                        >
                            ✎ Edit Akun
                        </button>
                        <form method="POST" action="{{ route('admin.employees.destroy', $employee) }}" onsubmit="return confirm('Hapus akun karyawan {{ $employee->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn admin-btn-danger">Hapus Akun</button>
                        </form>
                    </div>
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
            <button type="button" class="admin-modal-close" data-admin-modal-close aria-label="Tutup">×</button>
        </div>
        <img id="admin-photo-img" src="" alt="Bukti Foto" style="width:100%;max-height:calc(100dvh - 135px);object-fit:contain;background:#071a32;border-radius:4px;display:block;">
    </div>
</div>

<!-- Modal Edit Akun Karyawan -->
<div class="admin-modal" id="admin-edit-emp-modal" hidden>
    <div class="admin-modal-card" style="width:min(100%, 620px);overflow-y:auto;">
        <div class="admin-modal-head">
            <h3 id="admin-edit-emp-title">Edit Akun Karyawan</h3>
            <button type="button" class="admin-modal-close" data-admin-modal-close aria-label="Tutup">×</button>
        </div>
        <form method="POST" id="admin-edit-emp-form" action="">
            @csrf
            @method('PATCH')
            <div class="admin-form-grid admin-edit-form-grid">
                <label>
                    Username / Nama Karyawan *
                    <input type="text" name="name" id="admin-edit-name" required>
                </label>
                <label>
                    Email Login
                    <input type="text" name="email" id="admin-edit-email" placeholder="Kosongkan untuk otomatis">
                </label>
                <label>
                    NIK / ID Karyawan
                    <input type="text" name="employee_id" id="admin-edit-employee-id" placeholder="Contoh: EMP-046">
                </label>
                <label>
                    Password Baru (Opsional)
                    <input type="password" name="password" id="admin-edit-password" placeholder="Kosongkan jika tidak diubah" minlength="6">
                </label>
                <label>
                    Divisi
                    <input type="text" name="division" id="admin-edit-division" placeholder="Contoh: IT / Creative">
                </label>
                <label>
                    Posisi / Jabatan
                    <input type="text" name="position" id="admin-edit-position" placeholder="Contoh: Intern IT / Staff">
                </label>
            </div>
            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:8px;">
                <button type="button" class="admin-btn admin-btn-secondary" data-admin-modal-close>Batal</button>
                <button type="submit" class="admin-btn">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapModal = document.getElementById('admin-map-modal');
    const photoModal = document.getElementById('admin-photo-modal');
    const editEmpModal = document.getElementById('admin-edit-emp-modal');
    const editEmpForm = document.getElementById('admin-edit-emp-form');

    [mapModal, photoModal, editEmpModal].forEach(function (modal) {
        if (!modal) return;
        document.body.appendChild(modal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.hidden = true;
            }
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (mapModal) mapModal.hidden = true;
            if (photoModal) photoModal.hidden = true;
            if (editEmpModal) editEmpModal.hidden = true;
        }
    });

    document.querySelectorAll('[data-edit-employee]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!editEmpModal || !editEmpForm) return;
            editEmpForm.action = btn.dataset.action || '';
            document.getElementById('admin-edit-emp-title').textContent = 'Edit Akun — ' + (btn.dataset.name || 'Karyawan');
            document.getElementById('admin-edit-name').value = btn.dataset.name || '';
            document.getElementById('admin-edit-email').value = btn.dataset.email || '';
            document.getElementById('admin-edit-employee-id').value = btn.dataset.employeeId || '';
            document.getElementById('admin-edit-division').value = btn.dataset.division || '';
            document.getElementById('admin-edit-position').value = btn.dataset.position || '';
            document.getElementById('admin-edit-password').value = '';
            editEmpModal.hidden = false;
        });
    });
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
            if (mapModal) mapModal.hidden = true;
            if (photoModal) photoModal.hidden = true;
            if (editEmpModal) editEmpModal.hidden = true;
        });
    });

    // Folder Recap Navigation & Filtering Logic
    const folderGridView = document.getElementById('admin-folder-grid-view');
    const folderDetailView = document.getElementById('admin-folder-detail-view');
    const allTableView = document.getElementById('admin-all-table-view');
    const viewTabs = document.querySelectorAll('[data-admin-view-tab]');
    const empPanes = document.querySelectorAll('[data-emp-folder-pane]');
    const folderSearch = document.getElementById('admin-folder-search');
    const folderCards = document.querySelectorAll('[data-open-emp-folder]');

    const showMainViewTab = function (tabName) {
        viewTabs.forEach(function (tab) {
            tab.classList.toggle('is-active', tab.dataset.adminViewTab === tabName);
        });
        if (tabName === 'table') {
            if (folderGridView) folderGridView.hidden = true;
            if (folderDetailView) folderDetailView.hidden = true;
            if (allTableView) allTableView.hidden = false;
        } else {
            if (allTableView) allTableView.hidden = true;
            if (folderDetailView) folderDetailView.hidden = true;
            if (folderGridView) folderGridView.hidden = false;
        }
    };

    viewTabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            showMainViewTab(tab.dataset.adminViewTab);
        });
    });

    const openEmployeeFolder = function (empId) {
        if (!folderDetailView || !folderGridView) return;
        let found = false;
        empPanes.forEach(function (pane) {
            const isMatch = String(pane.dataset.empFolderPane) === String(empId);
            pane.hidden = !isMatch;
            if (isMatch) found = true;
        });
        if (!found) return;
        viewTabs.forEach(function (tab) {
            tab.classList.toggle('is-active', tab.dataset.adminViewTab === 'folders');
        });
        if (allTableView) allTableView.hidden = true;
        folderGridView.hidden = true;
        folderDetailView.hidden = false;
    };

    folderCards.forEach(function (card) {
        card.addEventListener('click', function () {
            openEmployeeFolder(card.dataset.openEmpFolder);
        });
    });

    document.querySelectorAll('[data-back-to-folders]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            showMainViewTab('folders');
        });
    });

    // Live search for employee folders
    if (folderSearch) {
        folderSearch.addEventListener('input', function () {
            const q = folderSearch.value.trim().toLowerCase();
            if (folderDetailView && !folderDetailView.hidden) {
                showMainViewTab('folders');
            }
            folderCards.forEach(function (card) {
                const hay = (card.dataset.folderSearch || '').toLowerCase();
                card.hidden = q !== '' && !hay.includes(q);
            });
        });
    }

    // Sub-folder Month Filter + Pagination inside each Employee Folder
    empPanes.forEach(function (pane) {
        const monthBtns = pane.querySelectorAll('[data-emp-month-filter]');
        const rows = Array.from(pane.querySelectorAll('[data-emp-record-row]'));
        const statPresent = pane.querySelector('[data-emp-stat-present]');
        const statDuration = pane.querySelector('[data-emp-stat-duration]');
        const statPagi = pane.querySelector('[data-emp-stat-pagi]');
        const statSore = pane.querySelector('[data-emp-stat-sore]');
        const pageSummary = pane.querySelector('[data-emp-page-summary]');
        const pageIndicator = pane.querySelector('[data-emp-page-indicator]');
        const prevBtn = pane.querySelector('[data-emp-page-prev]');
        const nextBtn = pane.querySelector('[data-emp-page-next]');
        const paginationBar = pane.querySelector('[data-emp-pagination]');

        let currentMonth = 'all';
        let currentPage = 1;
        const pageSize = 8;

        const renderPaneRows = function () {
            const matchedRows = [];
            let totalMin = 0;
            let pagiCount = 0;
            let soreCount = 0;

            rows.forEach(function (row) {
                const match = currentMonth === 'all' || row.dataset.month === currentMonth;
                if (match) {
                    matchedRows.push(row);
                    totalMin += Number(row.dataset.minutes || 0);
                    if (row.dataset.shift === 'Shift Pagi') pagiCount++;
                    if (row.dataset.shift === 'Shift Sore') soreCount++;
                } else {
                    row.hidden = true;
                }
            });

            const totalItems = matchedRows.length;
            const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));
            if (currentPage > totalPages) currentPage = totalPages;

            const startIdx = (currentPage - 1) * pageSize;
            const endIdx = Math.min(startIdx + pageSize, totalItems);

            matchedRows.forEach(function (row, idx) {
                row.hidden = !(idx >= startIdx && idx < endIdx);
            });

            if (statPresent) statPresent.textContent = totalItems + ' Hari';
            if (statDuration) statDuration.textContent = Math.floor(totalMin / 60) + 'j ' + (totalMin % 60) + 'm';
            if (statPagi) statPagi.textContent = pagiCount + ' Kali';
            if (statSore) statSore.textContent = soreCount + ' Kali';

            if (paginationBar) {
                paginationBar.hidden = totalItems === 0;
                if (pageSummary) {
                    pageSummary.textContent = totalItems > 0
                        ? 'Menampilkan ' + (startIdx + 1) + '–' + endIdx + ' dari ' + totalItems + ' log'
                        : '0 log';
                }
                if (pageIndicator) {
                    pageIndicator.textContent = 'Hal ' + currentPage + ' / ' + totalPages;
                }
                if (prevBtn) prevBtn.disabled = currentPage <= 1;
                if (nextBtn) nextBtn.disabled = currentPage >= totalPages;
            }
        };

        monthBtns.forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentMonth = btn.dataset.empMonthFilter;
                currentPage = 1;
                monthBtns.forEach(function (b) {
                    b.classList.toggle('is-active', b === btn);
                });
                renderPaneRows();
            });
        });

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                if (currentPage > 1) {
                    currentPage--;
                    renderPaneRows();
                }
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                currentPage++;
                renderPaneRows();
            });
        }

        renderPaneRows();
    });

    // Pagination for Combined "Semua Log" Table
    const allLogRows = Array.from(document.querySelectorAll('[data-all-log-row]'));
    const allPageSummary = document.getElementById('admin-all-page-summary');
    const allPageIndicator = document.getElementById('admin-all-page-indicator');
    const allPrevBtn = document.getElementById('admin-all-page-prev');
    const allNextBtn = document.getElementById('admin-all-page-next');
    const allPageSizeSelect = document.getElementById('admin-all-page-size');
    let allCurrentPage = 1;

    const renderAllLogPage = function () {
        if (!allLogRows.length) return;
        const pageSize = Number(allPageSizeSelect ? allPageSizeSelect.value : 8) || 8;
        const totalItems = allLogRows.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / pageSize));
        if (allCurrentPage > totalPages) allCurrentPage = totalPages;

        const startIdx = (allCurrentPage - 1) * pageSize;
        const endIdx = Math.min(startIdx + pageSize, totalItems);

        allLogRows.forEach(function (row, idx) {
            row.hidden = !(idx >= startIdx && idx < endIdx);
        });

        if (allPageSummary) {
            allPageSummary.textContent = 'Menampilkan ' + (startIdx + 1) + '–' + endIdx + ' dari ' + totalItems + ' log';
        }
        if (allPageIndicator) {
            allPageIndicator.textContent = 'Hal ' + allCurrentPage + ' / ' + totalPages;
        }
        if (allPrevBtn) allPrevBtn.disabled = allCurrentPage <= 1;
        if (allNextBtn) allNextBtn.disabled = allCurrentPage >= totalPages;
    };

    if (allPrevBtn) {
        allPrevBtn.addEventListener('click', function () {
            if (allCurrentPage > 1) {
                allCurrentPage--;
                renderAllLogPage();
            }
        });
    }
    if (allNextBtn) {
        allNextBtn.addEventListener('click', function () {
            allCurrentPage++;
            renderAllLogPage();
        });
    }
    if (allPageSizeSelect) {
        allPageSizeSelect.addEventListener('change', function () {
            allCurrentPage = 1;
            renderAllLogPage();
        });
    }
    renderAllLogPage();

    // Auto-open employee folder if user_id filter is selected in URL
    const selectedUserId = @json(request('user_id'));
    if (selectedUserId) {
        openEmployeeFolder(selectedUserId);
    }
});
</script>
@endpush
