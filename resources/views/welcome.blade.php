@extends('layouts.app')

@section('title', 'GASS — Growth Acceleration Strategic Services')

@section('body_class', 'home-page')

@push('styles')
<style>
	.home-page .hero { padding-top: 90px; }

	@media (max-width: 480px) {
		.home-page {
			background: #041b2d;
		}

		.home-page .announcement,
		.home-page .site-header .desktop-nav,
		.home-page .site-header .header-cta,
		.home-page .hero-art,
		.home-page .ticker,
		.home-page .services,
		.home-page .manifesto,
		.home-page .site-footer {
			display: none !important;
		}

		.home-page .site-header {
			padding: 18px 18px 10px;
			background: #041b2d;
			border-bottom: 1px solid rgba(255, 255, 255, 0.12);
		}

		.home-page .site-header .brand {
			gap: 8px;
		}

		.home-page .site-header .brand img {
			width: 110px;
		}

		.home-page .site-header .brand span {
			display: none;
		}

		.home-page .site-header .mobile-nav {
			display: flex !important;
			width: 100%;
			margin: 0;
			padding: 0;
			background: #0a5ab2;
			border-radius: 12px;
			border: 1px solid rgba(255, 255, 255, 0.2);
			overflow: hidden;
		}

		.home-page .site-header .mobile-menu-toggle {
			padding: 12px 14px;
			border-radius: 0;
			border: 0;
			background: transparent;
			color: #f5f7fb;
			font-size: 14px;
			letter-spacing: 0.08em;
			text-transform: uppercase;
		}

		.home-page .site-header .mobile-menu-toggle .hamburger-icon i {
			background: #ffffff;
		}

		.home-page .site-header .mobile-menu-links {
			background: #062d52;
			border-top: 1px solid rgba(255, 255, 255, 0.16);
		}

		.home-page .site-header .mobile-menu-links a {
			padding: 14px 18px;
			font-size: 14px;
			color: #f5f7fb;
		}

		.home-page .hero {
			display: block;
			min-height: auto;
			padding: 18px 18px 30px;
			background: rgba(0, 0, 0, 0);
		}

		.home-page .hero-copy {
			padding: 0;
		}

		.home-page .eyebrow {
			margin-bottom: 18px;
			font-size: 10px;
			letter-spacing: 0.2em;
			color: #f3d862;
		}

		.home-page .hero h1 {
			max-width: none;
			margin-bottom: 18px;
			font-size: clamp(2.8rem, 15vw, 4.3rem);
			line-height: 0.86;
			letter-spacing: -0.07em;
			color: #f5f7fb;
		}

		.home-page .hero h1 em {
			color: #ff7e49;
		}

		.home-page .hero-intro {
			max-width: none;
			font-size: 1.05rem;
			line-height: 1.5;
			color: rgba(245, 247, 251, 0.78);
		}

		.home-page .hero-actions {
			margin-top: 18px;
			gap: 12px;
		}

		.home-page .hero .button {
			gap: 10px;
			padding: 12px 14px;
			font-size: 11px;
			border-radius: 999px;
			box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.08);
		}

		.home-page .hero .button span {
			font-size: 16px;
		}

		.home-page .hero .text-link {
			display: none;
		}
	}
</style>
@endpush

@section('content')
<section class="mobile-hero-device" aria-label="Versi mobile GASS">
    <div class="mobile-brand-header">
        <div class="mobile-brand-wordmark">GASS</div>
        <div class="mobile-brand-sub">GROWTH | ACCELERATION | DIGITAL SOLUTIONS FOR BIGGER GROWTH</div>
    </div>
    <div class="mobile-menu-bar">
        <span class="mobile-menu-icon" aria-hidden="true"><i></i><i></i><i></i></span>
        <span>MENU</span>
    </div>
    <div class="mobile-badge">Medsos</div>
    <div class="mobile-copy">
        <p class="eyebrow"><span class="eyebrow-mark">✳</span> Your next move starts here</p>
        <h1>Naik level,<br><em>tanpa ragu.</em></h1>
        <p class="hero-intro">Kami membantu bisnis menemukan arah, tampil lebih berani, dan bergerak lebih cepat di dunia digital.</p>
        <div class="hero-actions">
            <a class="button button-yellow" href="{{ route('contact') }}">Ceritakan tantanganmu <span>↗</span></a>
            <a class="text-link" href="{{ route('services') }}">Lihat layanan <span>↓</span></a>
        </div>
    </div>
</section>

<section class="hero section-shell"><div class="hero-copy"><p class="eyebrow"><span class="eyebrow-mark">✳</span> Your next move starts here</p><h1>Naik level,<br><em>tanpa ragu.</em></h1><p class="hero-intro">Kami membantu bisnis menemukan arah, tampil lebih berani, dan bergerak lebih cepat di dunia digital.</p><div class="hero-actions"><a class="button button-yellow" href="{{ route('contact') }}">Ceritakan tantanganmu <span>↗</span></a><a class="text-link" href="{{ route('services') }}">Lihat layanan <span>↓</span></a></div></div><div class="hero-art" aria-label="Ilustrasi abstrak pertumbuhan digital"><div class="art-note note-top">strategy<br><strong>→ growth</strong></div><div class="sun"></div><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div><div class="art-label">GROW<br>WITH<br>GASS</div><div class="art-sticker">digital<br>with<br>direction</div><div class="art-note note-bottom">ideas in<br><strong>motion</strong></div></div></section>
<section class="ticker" aria-label="Layanan GASS"><div>WE BUILD <span>✦</span> WE GROW <span>✦</span> WE MOVE <span>✦</span> WE BUILD <span>✦</span> WE GROW <span>✦</span></div></section>
<section class="services section-shell"><div class="section-heading"><p class="eyebrow">What we do</p><h2>Satu partner,<br><em>empat cara tumbuh.</em></h2></div><div class="service-grid"><article class="service-card card-pink"><span class="card-number">01</span><span class="card-icon">↗</span><h3>Website<br>yang bekerja</h3><p>Website bukan cuma cantik. Kami rancang agar pengunjung paham, percaya, lalu bergerak.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-yellow"><span class="card-number">02</span><span class="card-icon">✳</span><h3>Konten<br>yang bicara</h3><p>Identitas dan konten yang membuat brand kamu terdengar jelas di tengah keramaian.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-orange"><span class="card-number">03</span><span class="card-icon">◌</span><h3>Sosmed<br>terawat</h3><p>Strategi, kalender, dan eksekusi agar akun sosialmu konsisten dan selalu relevan.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-lilac"><span class="card-number">04</span>
<span class="card-icon">⌁</span><h3>E-commerce<br>siap jual</h3><p>Dari etalase sampai checkout, kami buat pengalaman belanja yang terasa mudah.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article></div></section>
<section class="manifesto section-shell"><div class="manifesto-stamp">GASS<br>YOUR<br>WAY</div><div class="manifesto-copy"><p class="eyebrow">Why GASS?</p><h2>Ambisi besar<br>butuh <em>arah.</em></h2></div><div class="manifesto-text"><p>Kami percaya pertumbuhan yang sehat dimulai dari strategi yang tepat. GASS hadir untuk mengubah ide menjadi aksi digital yang punya tujuan dan hasil.</p><a class="button button-outline" href="{{ route('about') }}">Kenalan dengan GASS <span>↗</span></a></div></section>
@include('partials.reviews')
@endsection