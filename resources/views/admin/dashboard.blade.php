@extends('layouts.app')

@section('title', 'Dashboard Admin — GASS')
@section('body_class', 'admin-page')

@push('styles')
<style>
.admin-page { background: #071a32; color: #101828; }
.admin-dashboard { max-width: 1120px; margin: 0 auto; padding: 50px 32px 90px; }
.admin-topbar { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 42px; }
.admin-topbar h1 { margin: 8px 0 0; color: #fff; font-size: clamp(38px, 6vw, 70px); line-height: .88; letter-spacing: -.06em; }
.admin-topbar .eyebrow { color: #b9c9dc; }
.admin-logout { border: 1px solid #fff; padding: 11px 15px; background: #fff; color: #071a32; font: 11px var(--mono); cursor: pointer; }
.admin-logout:hover { background: #f7c934; }
.admin-welcome { max-width: 580px; margin: 0 0 28px; color: #dbe4ef; font-size: 16px; line-height: 1.5; }
.admin-nav { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
.admin-nav-card { display: flex; min-height: 210px; flex-direction: column; justify-content: space-between; padding: 25px; border: 1px solid #b8c4d3; background: #fffdf8; color: #071a32; transition: transform .2s ease, box-shadow .2s ease; }
.admin-nav-card:hover { transform: translateY(-5px); box-shadow: 8px 8px 0 #f7c934; }
.admin-nav-card small { color: #52647a; font: 11px var(--mono); }
.admin-nav-card h2 { margin: 25px 0 8px; color: #071a32; font-size: 28px; }
.admin-nav-card p { max-width: 330px; margin: 0; color: #52647a; line-height: 1.45; }
.admin-nav-card strong { align-self: end; color: #087bdc; font: 13px var(--mono); }
@media (max-width: 650px) { .admin-dashboard { padding: 35px 20px 65px; } .admin-topbar { align-items: start; flex-direction: column; } .admin-nav { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<section class="admin-dashboard">
    <div class="admin-topbar"><div><p class="eyebrow">GASS / Admin</p><h1>Workspace.</h1></div><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="admin-logout" type="submit">Keluar ↗</button></form></div>
    <p class="admin-welcome">Kelola tampilan digital GASS dari dua area kerja yang terpisah dan lebih terarah.</p>
    <nav class="admin-nav" aria-label="Menu admin">
        <a class="admin-nav-card" href="{{ route('admin.prices') }}"><div><small>01 / PRODUK</small><h2>Kontrol harga</h2><p>Atur harga dasar dan persentase diskon seluruh paket layanan.</p></div><strong>Kelola harga →</strong></a>
        <a class="admin-nav-card" href="{{ route('admin.banners') }}"><div><small>02 / PROMO</small><h2>Kontrol banner</h2><p>Tambah, ubah, aktifkan, atau hapus banner foto dan video di beranda.</p></div><strong>Kelola banner →</strong></a>
    </nav>
</section>
@endsection
