@extends('layouts.app')

@section('title', 'Galeri Karya — GASS')
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
.admin-gallery-create, .admin-gallery-item { display: grid; grid-template-columns: 240px 1fr; gap: 18px; padding: 18px; border: 1px solid #b8c4d3; background: #fffdf8; }
.admin-gallery-create { margin-bottom: 10px; }
.admin-gallery-preview { display: flex; align-items: center; justify-content: center; gap: 10px; min-height: 230px; max-height: 290px; padding: 12px; overflow-x: auto; overflow-y: hidden; border: 1px solid #8292a8; background: #071a32; scrollbar-color: #67e8f9 #071a32; scrollbar-width: thin; }
.admin-gallery-preview::-webkit-scrollbar { height: 7px; }
.admin-gallery-preview::-webkit-scrollbar-track { background: #071a32; }
.admin-gallery-preview::-webkit-scrollbar-thumb { background: #67e8f9; }
.admin-gallery-preview img, .admin-gallery-preview video { display: block; flex: 0 0 auto; width: auto; height: 210px; max-width: 100%; object-fit: contain; border: 1px solid rgba(103,232,249,.35); background: #050b18; }
.admin-gallery-preview .admin-empty { flex: 0 0 112px; margin: 0; padding: 21px 10px; text-align: center; }
.admin-gallery-form { display: grid; grid-template-columns: 1fr 1fr auto; align-items: end; gap: 12px; }
.admin-gallery-form .admin-title-field, .admin-gallery-form .admin-description-field { grid-column: 1 / -1; }
.admin-gallery-form label { display: grid; gap: 7px; color: #263b55; font: 10px var(--mono); text-transform: uppercase; }
.admin-gallery-form input[type="text"], .admin-gallery-form input[type="file"], .admin-gallery-form textarea { width: 100%; padding: 11px; border: 1px solid #8292a8; background: #f4f7fb; color: #071a32; font: 13px var(--display); }
.admin-gallery-form textarea { min-height: 70px; resize: vertical; }
.admin-gallery-form input:focus, .admin-gallery-form textarea:focus { outline: 3px solid #f7c934; outline-offset: 1px; }
.admin-gallery-check { display: flex !important; align-items: center; gap: 8px !important; min-height: 40px; }
.admin-gallery-check input { accent-color: #071a32; }
.admin-gallery-form button { min-height: 40px; padding: 11px 15px; border: 0; background: #071a32; color: #fff; font: 11px var(--mono); cursor: pointer; }
.admin-gallery-form .admin-delete { background: #a51d2d; }
.admin-gallery-list { display: grid; gap: 10px; }
.admin-gallery-item h2 { margin: 0 0 5px; color: #071a32; font-size: 16px; }
.admin-gallery-item small { color: #52647a; font: 10px var(--mono); }
.admin-gallery-item .admin-gallery-form { margin-top: 12px; }
.admin-empty { padding: 20px; border: 1px dashed #8292a8; color: #dbe4ef; font-size: 13px; }
@media (max-width: 760px) { .admin-workspace { padding: 35px 20px 65px; } .admin-topbar { align-items: start; flex-direction: column; } .admin-actions { flex-wrap: wrap; } .admin-gallery-create, .admin-gallery-item { grid-template-columns: 1fr; } .admin-gallery-preview { min-height: 170px; } .admin-gallery-form { grid-template-columns: 1fr; } .admin-gallery-form .admin-title-field, .admin-gallery-form .admin-description-field { grid-column: auto; } }
</style>
@endpush

@section('content')
<section class="admin-workspace">
    <div class="admin-topbar"><div><p class="eyebrow">GASS / Portfolio</p><h1>Galeri karya.</h1></div><div class="admin-actions"><a class="admin-link" href="{{ route('admin.dashboard') }}">Workspace</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="admin-logout" type="submit">Keluar ↗</button></form></div></div>
    <p class="admin-intro">Tampilkan hasil kerja terbaik GASS sebagai foto tunggal, carousel beberapa foto, atau video. Ukuran dan rasio media dipertahankan seperti file asli.</p>
    @if (session('status'))<div class="admin-status">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="admin-status" style="background:#ffe1e1;color:#9b1c1c;">{{ $errors->first() }}</div>@endif
    <form class="admin-gallery-create" method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="admin-gallery-preview" data-gallery-preview><span class="admin-empty">Preview karya baru</span></div>
        <div class="admin-gallery-form"><label class="admin-title-field">Judul karya<input type="text" name="title" placeholder="Website Klinik Medistra" required></label><label class="admin-description-field">Deskripsi singkat<textarea name="description" placeholder="Ceritakan hasil atau pendekatan proyek ini."></textarea></label><label>Foto / video (bisa pilih beberapa foto)<input type="file" name="media[]" accept="image/*,video/mp4,video/quicktime,video/webm" multiple required data-gallery-input></label><label class="admin-gallery-check"><input type="checkbox" name="is_active" value="1" checked> Tampilkan di beranda</label><button type="submit">Tambah karya</button></div>
    </form>
    <div class="admin-gallery-list">
        @forelse ($galleryItems as $item)
            <div class="admin-gallery-item"><div class="admin-gallery-preview" data-gallery-preview>@foreach ($item->media as $media)@if ($media['type'] === 'video')<video src="{{ asset('storage/'.$media['path']) }}" muted controls></video>@else<img src="{{ asset('storage/'.$media['path']) }}" alt="{{ $item->title }}">@endif @endforeach</div><div><h2>{{ $item->title }}</h2><small>{{ count($item->media) }} MEDIA · {{ $item->is_active ? 'AKTIF DI BERANDA' : 'TIDAK AKTIF' }}</small><form class="admin-gallery-form" method="POST" action="{{ route('admin.gallery.update', $item) }}" enctype="multipart/form-data">@csrf @method('PATCH')<label class="admin-title-field">Judul karya<input type="text" name="title" value="{{ $item->title }}" required></label><label class="admin-description-field">Deskripsi<textarea name="description">{{ $item->description }}</textarea></label><label>Ganti semua media<input type="file" name="media[]" accept="image/*,video/mp4,video/quicktime,video/webm" multiple data-gallery-input></label><label class="admin-gallery-check"><input type="checkbox" name="is_active" value="1" @checked($item->is_active)> Tampilkan</label><button type="submit">Simpan</button></form><form class="admin-gallery-form" method="POST" action="{{ route('admin.gallery.destroy', $item) }}" onsubmit="return confirm('Hapus karya ini?')">@csrf @method('DELETE')<button class="admin-delete" type="submit">Hapus karya</button></form></div></div>
        @empty
            <p class="admin-empty">Belum ada karya. Tambahkan foto atau video pertama di atas.</p>
        @endforelse
    </div>
</section>
<script>
document.querySelectorAll('[data-gallery-input]').forEach(function (input) {
    input.addEventListener('change', function () {
        const preview = input.closest('form').querySelector('[data-gallery-preview]');
        preview.replaceChildren();
        [...input.files].forEach(function (file) {
            const media = document.createElement(file.type.startsWith('video/') ? 'video' : 'img');
            media.src = URL.createObjectURL(file);
            media.alt = file.name;
            if (media.tagName === 'VIDEO') { media.muted = true; media.controls = true; }
            preview.append(media);
        });
    });
});
</script>
@endsection
