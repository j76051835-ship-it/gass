@extends('layouts.app')

@section('title', 'Layanan — GASS')

@section('body_class', 'services-page-body')

@push('styles')
<style>
	.services-page-body .services-page { padding-top: 10px; }
	.services-page-body .process-light { margin-top: 55px; }
</style>
@endpush

@section('content')
<section class="page-hero section-shell"><p class="eyebrow">02 / Layanan GASS</p><h1>Semua yang kamu butuhkan<br>untuk <em>naik level.</em></h1><p class="hero-intro">Dari fondasi digital sampai eksekusi harian, kami bantu brand tampil jelas, konsisten, dan siap bertumbuh.</p></section>
<section class="services section-shell services-page"><div class="service-grid"><article class="service-card card-pink"><span class="card-number">01</span><span class="card-icon">↗</span><h3>Website<br>yang bekerja</h3><p>Website bukan cuma cantik. Kami rancang agar pengunjung paham, percaya, lalu bergerak.</p><a href="{{ route('contact') }}">Diskusikan proyek <span>↗</span></a></article><article class="service-card card-yellow"><span class="card-number">02</span><span class="card-icon">✳</span><h3>Konten<br>yang bicara</h3><p>Identitas dan konten yang membuat brand kamu terdengar jelas di tengah keramaian.</p><a href="{{ route('contact') }}">Diskusikan proyek <span>↗</span></a></article><article class="service-card card-orange"><span class="card-number">03</span><span class="card-icon">◌</span><h3>Sosmed<br>terawat</h3><p>Strategi, kalender, dan eksekusi agar akun sosialmu konsisten dan selalu relevan.</p><a href="{{ route('contact') }}">Diskusikan proyek <span>↗</span></a></article><article class="service-card card-lilac"><span class="card-number">04</span><span class="card-icon">⌁</span><h3>E-commerce<br>siap jual</h3><p>Dari etalase sampai checkout, kami buat pengalaman belanja yang terasa mudah.</p><a href="{{ route('contact') }}">Diskusikan proyek <span>↗</span></a></article></div></section>
<section class="process section-shell process-light"><div class="process-title"><p class="eyebrow">What you get</p><h2>Strategi yang<br><em>nyambung.</em></h2></div><ol class="process-list"><li><span>01</span><div><h3>Terarah</h3><p>Setiap output punya alasan dan tujuan yang jelas.</p></div></li><li><span>02</span><div><h3>Terukur</h3><p>Kami menyusun indikator untuk melihat kemajuan.</p></div></li><li><span>03</span><div><h3>Terjaga</h3><p>Brand tetap konsisten setelah proyek selesai.</p></div></li></ol></section>
@endsection
