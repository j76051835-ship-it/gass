@extends('layouts.app')

@section('title', 'Kontrol Banner — GASS')
@section('body_class', 'admin-page')

@push('styles')
<style>
.admin-page { background: #071a32; color: #101828; }
.admin-workspace { max-width: 1120px; margin: 0 auto; padding: 42px 32px 90px; }
.admin-topbar { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 34px; }
.admin-topbar h1 { margin: 8px 0 0; color: #fff; font-size: clamp(36px, 6vw, 64px); line-height: .9; letter-spacing: -.06em; }
.admin-topbar .eyebrow { color: #b9c9dc; }
.admin-actions { display: flex; align-items: center; gap: 10px; }
.admin-link, .admin-logout { border: 1px solid #fff; padding: 11px 15px; background: #fff; color: #071a32; font: 11px var(--mono); cursor: pointer; }
.admin-link:hover, .admin-logout:hover { background: #f7c934; }
.admin-intro { margin: 0 0 22px; color: #b9c9dc; font-size: 14px; }
.admin-status { margin-bottom: 20px; padding: 13px 16px; background: #d9f8e2; color: #166534; font-size: 13px; }
.admin-banner-create, .admin-banner-item { display: grid; grid-template-columns: 180px 1fr; gap: 18px; padding: 18px; border: 1px solid #b8c4d3; background: #fffdf8; }
.admin-banner-create { margin-bottom: 10px; }
.admin-banner-preview { display: grid; width: 180px; height: 100px; place-items: center; overflow: hidden; border: 1px solid #8292a8; background: #071a32; }
.admin-banner-preview img, .admin-banner-preview video { width: 100%; height: 100%; object-fit: contain; }
.admin-banner-form { display: grid; grid-template-columns: 1fr 1fr auto; align-items: end; gap: 12px; }
.admin-banner-form .admin-title-field { grid-column: 1 / -1; }
.admin-banner-form label { display: grid; gap: 7px; color: #263b55; font: 10px var(--mono); text-transform: uppercase; }
.admin-banner-form input[type="text"], .admin-banner-form input[type="file"] { width: 100%; padding: 11px; border: 1px solid #8292a8; background: #f4f7fb; color: #071a32; font: 13px var(--display); }
.admin-banner-form input:focus { outline: 3px solid #f7c934; outline-offset: 1px; }
.admin-banner-check { display: flex !important; align-items: center; gap: 8px !important; min-height: 40px; }
.admin-banner-check input { accent-color: #071a32; }
.admin-banner-form button { min-height: 40px; padding: 11px 15px; border: 0; background: #071a32; color: #fff; font: 11px var(--mono); cursor: pointer; }
.admin-banner-form .admin-delete { background: #a51d2d; }
.admin-banner-list { display: grid; gap: 10px; }
.admin-banner-item h2 { margin: 0 0 5px; color: #071a32; font-size: 16px; }
.admin-banner-item small { color: #52647a; font: 10px var(--mono); }
.admin-banner-item .admin-banner-form { margin-top: 12px; }
.admin-empty { padding: 20px; border: 1px dashed #8292a8; color: #dbe4ef; font-size: 13px; }
@media (max-width: 760px) { .admin-workspace { padding: 35px 20px 65px; } .admin-topbar { align-items: start; flex-direction: column; } .admin-actions { flex-wrap: wrap; } .admin-banner-create, .admin-banner-item { grid-template-columns: 1fr; } .admin-banner-preview { width: 100%; height: 170px; } .admin-banner-form { grid-template-columns: 1fr; } .admin-banner-form .admin-title-field { grid-column: auto; } }
</style>
@endpush

@section('content')
<section class="admin-workspace">
    <div class="admin-topbar"><div><p class="eyebrow">GASS / Promo</p><h1>Kontrol banner.</h1></div><div class="admin-actions"><a class="admin-link" href="{{ route('admin.dashboard') }}">Workspace</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="admin-logout" type="submit">Keluar ↗</button></form></div></div>
    <p class="admin-intro">Kelola foto dan video promo yang tampil bergantian di hero halaman beranda.</p>
    @if (session('status'))<div class="admin-status">{{ session('status') }}</div>@endif
    <form class="admin-banner-create" method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-banner-preview"><span class="admin-empty">Preview media baru</span></div>
        <div class="admin-banner-form"><label class="admin-title-field">Judul banner<input type="text" name="title" placeholder="Promo Agustus GASS" required></label><label>Media foto / video<input type="file" name="media" accept="image/jpeg,image/png,image/webp,video/mp4,video/quicktime,video/webm" required></label><label class="admin-banner-check"><input type="checkbox" name="is_active" value="1"> Tampilkan di beranda</label><button type="submit">Tambah banner</button></div>
    </form>
    <div class="admin-banner-list">
        @forelse ($banners as $banner)
            <div class="admin-banner-item"><div class="admin-banner-preview">@if ($banner->media_type === 'video')<video src="{{ asset('storage/'.$banner->media_path) }}" muted controls></video>@else<img src="{{ asset('storage/'.$banner->media_path) }}" alt="{{ $banner->title }}">@endif</div><div><h2>{{ $banner->title }}</h2><small>{{ strtoupper($banner->media_type) }} · {{ $banner->is_active ? 'AKTIF DI BERANDA' : 'TIDAK AKTIF' }}</small><form class="admin-banner-form" method="POST" action="{{ route('admin.banners.update', $banner) }}" enctype="multipart/form-data">@csrf @method('PATCH')<label class="admin-title-field">Judul banner<input type="text" name="title" value="{{ $banner->title }}" required></label><label>Ganti media<input type="file" name="media" accept="image/jpeg,image/png,image/webp,video/mp4,video/quicktime,video/webm"></label><label class="admin-banner-check"><input type="checkbox" name="is_active" value="1" @checked($banner->is_active)> Tampilkan</label><button type="submit">Simpan</button></form><form class="admin-banner-form" method="POST" action="{{ route('admin.banners.destroy', $banner) }}" onsubmit="return confirm('Hapus banner ini?')">@csrf @method('DELETE')<button class="admin-delete" type="submit">Hapus banner</button></form></div></div>
        @empty
            <p class="admin-empty">Belum ada banner promo. Tambahkan foto atau video di atas.</p>
        @endforelse
    </div>
</section>
@endsection
