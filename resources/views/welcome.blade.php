@extends('layouts.app')

@section('title', 'GASS — Growth Acceleration Strategic Services')

@section('body_class', 'home-page')

@push('styles')
<style>
	.home-page .hero { padding-top: 90px; }
</style>
@endpush

@section('content')
<section class="hero section-shell"><div class="hero-copy"><p class="eyebrow"><span class="eyebrow-mark">✳</span> Your next move starts here</p><h1>Naik level,<br><em>tanpa ragu.</em></h1><p class="hero-intro">Kami membantu bisnis menemukan arah, tampil lebih berani, dan bergerak lebih cepat di dunia digital.</p><div class="hero-actions"><a class="button button-yellow" href="{{ route('contact') }}">Ceritakan tantanganmu <span>↗</span></a><a class="text-link" href="{{ route('services') }}">Lihat layanan <span>↓</span></a></div></div><div class="hero-art" aria-label="Ilustrasi abstrak pertumbuhan digital"><div class="art-note note-top">strategy<br><strong>→ growth</strong></div><div class="sun"></div><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div><div class="art-label">GROW<br>WITH<br>GASS</div><div class="art-sticker">digital<br>with<br>direction</div><div class="art-note note-bottom">ideas in<br><strong>motion</strong></div></div></section>
<section class="ticker" aria-label="Layanan GASS"><div>WE BUILD <span>✦</span> WE GROW <span>✦</span> WE MOVE <span>✦</span> WE BUILD <span>✦</span> WE GROW <span>✦</span></div></section>
<section class="services section-shell"><div class="section-heading"><p class="eyebrow">What we do</p><h2>Satu partner,<br><em>empat cara tumbuh.</em></h2></div><div class="service-grid"><article class="service-card card-pink"><span class="card-number">01</span><span class="card-icon">↗</span><h3>Website<br>yang bekerja</h3><p>Website bukan cuma cantik. Kami rancang agar pengunjung paham, percaya, lalu bergerak.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-yellow"><span class="card-number">02</span><span class="card-icon">✳</span><h3>Konten<br>yang bicara</h3><p>Identitas dan konten yang membuat brand kamu terdengar jelas di tengah keramaian.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-orange"><span class="card-number">03</span><span class="card-icon">◌</span><h3>Sosmed<br>terawat</h3><p>Strategi, kalender, dan eksekusi agar akun sosialmu konsisten dan selalu relevan.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-lilac"><span class="card-number">04</span><span class="card-icon">⌁</span><h3>E-commerce<br>siap jual</h3><p>Dari etalase sampai checkout, kami buat pengalaman belanja yang terasa mudah.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article></div></section>
<section class="manifesto section-shell"><div class="manifesto-stamp">GASS<br>YOUR<br>WAY</div><div class="manifesto-copy"><p class="eyebrow">Why GASS?</p><h2>Ambisi besar<br>butuh <em>arah.</em></h2></div><div class="manifesto-text"><p>Kami percaya pertumbuhan yang sehat dimulai dari strategi yang tepat. GASS hadir untuk mengubah ide menjadi aksi digital yang punya tujuan dan hasil.</p><a class="button button-outline" href="{{ route('about') }}">Kenalan dengan GASS <span>↗</span></a></div></section>
@include('partials.reviews')
@endsection
