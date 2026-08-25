@extends('layouts.app')

@section('title', 'Kontrol Harga — GASS')
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
.admin-package-list { display: grid; gap: 10px; }
.admin-package { display: grid; grid-template-columns: 1.4fr 1fr 1fr auto; align-items: end; gap: 16px; padding: 18px; border: 1px solid #b8c4d3; background: #fffdf8; }
.admin-package h2 { margin: 0 0 5px; color: #071a32; font-size: 17px; }
.admin-package small { color: #52647a; font: 10px var(--mono); }
.admin-package label { display: grid; gap: 7px; color: #263b55; font: 10px var(--mono); text-transform: uppercase; }
.admin-package input { width: 100%; min-width: 0; padding: 11px; border: 1px solid #8292a8; background: #f4f7fb; color: #071a32; font: 14px var(--display); }
.admin-package input:focus { outline: 3px solid #f7c934; outline-offset: 1px; }
.admin-package button { padding: 12px 15px; border: 0; background: #071a32; color: #fff; font: 11px var(--mono); cursor: pointer; white-space: nowrap; }
.admin-status { margin-bottom: 20px; padding: 13px 16px; background: #d9f8e2; color: #166534; font-size: 13px; }
@media (max-width: 760px) { .admin-workspace { padding: 35px 20px 65px; } .admin-topbar { align-items: start; flex-direction: column; } .admin-actions { flex-wrap: wrap; } .admin-package { grid-template-columns: 1fr 1fr; } .admin-package > div { grid-column: 1 / -1; } .admin-package button { grid-column: 1 / -1; } }
</style>
@endpush

@section('content')
<section class="admin-workspace">
    <div class="admin-topbar"><div><p class="eyebrow">GASS / Produk</p><h1>Kontrol harga.</h1></div><div class="admin-actions"><a class="admin-link" href="{{ route('admin.dashboard') }}">Workspace</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="admin-logout" type="submit">Keluar ↗</button></form></div></div>
    <p class="admin-intro">Perubahan di sini langsung diterapkan ke pilihan layanan dan harga pemesanan di halaman layanan.</p>
    @if (session('status'))<div class="admin-status">{{ session('status') }}</div>@endif
    <div class="admin-package-list">
        @foreach ($packages as $package)
            <form class="admin-package" method="POST" action="{{ route('admin.packages.update', $package) }}">@csrf @method('PATCH')<div><h2>{{ $package->name }}</h2><small>{{ $package->slug }}</small></div><label>Harga dasar<input type="number" name="base_price" min="0" value="{{ old('base_price', $package->base_price) }}" required></label><label>Diskon (%)<input type="number" name="discount_percent" min="0" max="100" value="{{ old('discount_percent', $package->discount_percent) }}" required></label><button type="submit">Simpan</button></form>
        @endforeach
    </div>
</section>
@endsection
