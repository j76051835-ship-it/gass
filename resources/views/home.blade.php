@extends('layouts.app')

@section('title', 'GASS — Growth Acceleration Strategic Services')

@section('body_class', 'home-page home-landing')

@push('styles')
<style>
    .home-page .hero { padding-top: 90px; }
    .home-page .hero-art {
        min-height: 515px;
        background: linear-gradient(135deg, #74d9e7 0%, #f2cf39 34%, #ff9a00 62%, #9c7ae3 100%);
        box-shadow: 18px 18px 0 rgba(2, 11, 31, .28);
    }
    .home-page .hero-art.has-promo-banner { background: #050b18; }
    .home-page .hero-art.has-promo-banner { border: 1px solid rgba(103,232,249,.9); border-radius: 8px; box-shadow: 18px 18px 0 rgba(2, 11, 31, .38), 0 0 0 6px rgba(5, 11, 24, .16), 0 0 34px rgba(34,211,238,.26); }
    .home-page .promo-banner-slider { position: absolute; inset: 8px; z-index: 1; overflow: hidden; border: 1px solid rgba(255,255,255,.58); border-radius: 4px; box-shadow: inset 0 0 0 1px rgba(5,11,24,.42), inset 0 0 28px rgba(5,11,24,.3); }
    .home-page .promo-banner-slider:before, .home-page .promo-banner-slider:after { content: ""; position: absolute; z-index: 3; pointer-events: none; }
    .home-page .promo-banner-slider:before { inset: 10px; border: 1px solid rgba(103,232,249,.36); border-radius: 2px; }
    .home-page .promo-banner-slider:after { top: 0; right: 12%; left: 12%; height: 1px; background: #67e8f9; box-shadow: 0 0 12px 2px rgba(103,232,249,.72); opacity: .32; animation: promo-scan 4.5s ease-in-out infinite; }
    .home-page .promo-banner-slide { position: absolute; inset: 0; opacity: 0; visibility: hidden; transition: opacity .7s ease, visibility .7s ease; }
    .home-page .promo-banner-slide.is-active { opacity: 1; visibility: visible; }
    .home-page .promo-banner-media { position: absolute; inset: 0; z-index: 1; width: 100%; height: 100%; object-fit: contain; background: #050b18; }
    .home-page .promo-banner-overlay { position: absolute; inset: auto 0 0; z-index: 2; padding: 28px; background: linear-gradient(transparent, rgba(5, 11, 24, .78)); color: #fff; pointer-events: none; }
    .home-page .promo-banner-overlay h2 { max-width: 600px; margin: 0; color: #fff; font-size: clamp(24px, 4vw, 48px); line-height: 1; }
    .home-page .promo-banner-volume { display: block; margin-top: 10px; padding: 7px 10px; border: 1px solid rgba(255,255,255,.72); border-radius: 3px; background: rgba(5,11,24,.72); color: #fff; font: 10px var(--mono); cursor: pointer; pointer-events: auto; }
    .home-page .promo-banner-volume:hover, .home-page .promo-banner-volume:focus-visible { background: #fff; color: #050b18; outline: 2px solid #f7c934; outline-offset: 2px; }
    .home-page .hero-art.has-promo-banner > :not(.promo-banner-slider):not(.promo-banner-dots):not(.promo-banner-media):not(.promo-banner-overlay) { display: none; }
    .home-page .promo-banner-dots { display: flex; position: absolute; z-index: 5; right: 28px; bottom: 24px; gap: 9px; padding: 9px 11px; border: 1px solid rgba(103,232,249,.55); border-radius: 20px; background: rgba(5,11,24,.62); box-shadow: 0 0 18px rgba(34,211,238,.2); backdrop-filter: blur(8px); }
    .home-page .promo-banner-dot { width: 10px; height: 10px; padding: 0; border: 1px solid #fff; border-radius: 50%; background: transparent; cursor: pointer; transition: transform .2s ease, background .2s ease; }
    .home-page .promo-banner-dot:hover, .home-page .promo-banner-dot:focus-visible { transform: scale(1.35); background: #fff; outline: 2px solid #f7c934; outline-offset: 3px; }
    .home-page .promo-banner-dot.is-active { background: #fff; box-shadow: 0 0 0 3px rgba(255,255,255,.22); }
    @keyframes promo-scan { 0%, 100% { transform: translateY(0); opacity: .12; } 50% { transform: translateY(500px); opacity: .5; } }
    @media (max-width: 600px) { .home-page .promo-banner-dots { right: 18px; bottom: 18px; } }
    .home-page .hero-art:before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(150deg, rgba(255,255,255,.18), transparent 35%, rgba(255,255,255,.08));
        pointer-events: none;
    }
    .home-page .sun {
        z-index: 1;
        width: 260px;
        height: 260px;
        background: #050b18;
        box-shadow: 0 0 0 16px rgba(255, 157, 0, .38), 0 20px 35px rgba(2, 11, 31, .2);
    }
    .home-page .orbit { z-index: 2; border-color: rgba(5, 11, 24, .78); }
    .home-page .art-label { z-index: 3; color: #f7f9fc; text-shadow: 0 2px 0 rgba(5, 11, 24, .12); }
    .home-page .art-note { color: #f7f9fc; text-shadow: 0 1px 2px rgba(5, 11, 24, .25); }
    .home-page .art-note strong { color: #ffffff; }
    .home-page .art-sticker { background: #050b18; color: #67e8f9; box-shadow: 8px 8px 0 rgba(255,255,255,.16); }
    @media (max-width: 800px) {
        .home-page .hero-art { min-height: 390px; box-shadow: 10px 10px 0 rgba(2, 11, 31, .38), 0 0 0 4px rgba(5, 11, 24, .16); }
    }
    @media (max-width: 430px) {
        .home-page .hero-art { min-height: 330px; }
        .home-page .sun { width: 210px; height: 210px; }
    }
</style>
@endpush

@section('content')
<section class="hero section-shell"><div class="traffic-field" aria-hidden="true"><i class="traffic-lane lane-one"></i><i class="traffic-lane lane-two"></i><i class="traffic-lane lane-three"></i><i class="traffic-node node-one"></i><i class="traffic-node node-two"></i><i class="traffic-node node-three"></i><i class="traffic-node node-four"></i></div><div class="hero-copy"><p class="eyebrow"><span class="eyebrow-mark">✳</span> Your next move starts here</p><h1>Naik level,<br><em>tanpa ragu.</em></h1><p class="hero-intro">Kami membantu bisnis menemukan arah, tampil lebih berani, dan bergerak lebih cepat di dunia digital.</p><div class="hero-actions"><a class="button button-yellow" href="{{ route('contact') }}">Ceritakan tantanganmu <span>↗</span></a><a class="text-link" href="{{ route('services') }}">Lihat layanan <span>↓</span></a></div></div><div class="hero-art {{ $banners->isNotEmpty() ? 'has-promo-banner' : '' }}" aria-label="Banner promo GASS">@if ($banners->isNotEmpty())<div class="promo-banner-slider" data-promo-slider>@foreach ($banners as $index => $banner)<div class="promo-banner-slide {{ $index === 0 ? 'is-active' : '' }}" data-promo-slide>@if ($banner->media_type === 'video')<video class="promo-banner-media" src="{{ asset('storage/'.$banner->media_path) }}" autoplay muted loop playsinline></video>@else<img class="promo-banner-media" src="{{ asset('storage/'.$banner->media_path) }}" alt="{{ $banner->title }}">@endif<div class="promo-banner-overlay"><h2>{{ $banner->title }}</h2></div></div>@endforeach</div>@endif<div class="art-note note-top">strategy<br><strong>→ growth</strong></div><div class="sun"></div><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div><div class="art-label">GROW<br>WITH<br>GASS</div><div class="art-sticker">digital<br>with<br>direction</div><div class="art-note note-bottom">ideas in<br><strong>motion</strong></div>@if ($banners->count() > 1)<div class="promo-banner-dots" aria-label="Navigasi banner promo">@foreach ($banners as $index => $banner)<button class="promo-banner-dot {{ $index === 0 ? 'is-active' : '' }}" type="button" data-promo-dot="{{ $index }}" aria-label="Banner {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"></button>@endforeach</div>@endif</div></section>
<section class="ticker" aria-label="Layanan GASS"><div><span class="ticker-group">WE BUILD <i>✦</i> WE GROW <i>✦</i> WE MOVE <i>✦</i> WE BUILD <i>✦</i> WE GROW <i>✦</i></span><span class="ticker-group" aria-hidden="true">WE BUILD <i>✦</i> WE GROW <i>✦</i> WE MOVE <i>✦</i> WE BUILD <i>✦</i> WE GROW <i>✦</i></span></div></section>
<section class="services section-shell"><div class="section-heading"><p class="eyebrow">What we do</p><h2>Satu partner,<br><em>empat cara tumbuh.</em></h2></div><div class="service-grid"><article class="service-card card-pink"><span class="card-number">01</span><span class="card-icon">↗</span><h3>Website<br>yang bekerja</h3><p>Website bukan cuma cantik. Kami rancang agar pengunjung paham, percaya, lalu bergerak.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-yellow"><span class="card-number">02</span><span class="card-icon">✳</span><h3>Konten<br>yang bicara</h3><p>Identitas dan konten yang membuat brand kamu terdengar jelas di tengah keramaian.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-orange"><span class="card-number">03</span><span class="card-icon">◌</span><h3>Sosmed<br>terawat</h3><p>Strategi, kalender, dan eksekusi agar akun sosialmu konsisten dan selalu relevan.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-lilac"><span class="card-number">04</span><span class="card-icon">⌁</span><h3>E-commerce<br>siap jual</h3><p>Dari etalase sampai checkout, kami buat pengalaman belanja yang terasa mudah.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article></div></section>
<section class="manifesto section-shell"><div class="manifesto-stamp">GASS<br>YOUR<br>WAY</div><div class="manifesto-copy"><p class="eyebrow">Why GASS?</p><h2>Ambisi besar<br>butuh <em>arah.</em></h2></div><div class="manifesto-text"><p>Kami percaya pertumbuhan yang sehat dimulai dari strategi yang tepat. GASS hadir untuk mengubah ide menjadi aksi digital yang punya tujuan dan hasil.</p><a class="button button-outline" href="{{ route('about') }}">Kenalan dengan GASS <span>↗</span></a></div></section>
@include('partials.reviews')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.promo-banner-media').forEach(function (media) {
        if (media.tagName.toLowerCase() === 'video') {
            media.controls = false;
            media.muted = true;
            const volumeButton = document.createElement('button');
            volumeButton.className = 'promo-banner-volume';
            volumeButton.type = 'button';
            volumeButton.textContent = 'Volume off';
            volumeButton.setAttribute('aria-label', 'Aktifkan suara video');
            volumeButton.addEventListener('click', function () {
                media.muted = !media.muted;
                volumeButton.textContent = media.muted ? 'Volume off' : 'Volume on';
                volumeButton.setAttribute('aria-label', media.muted ? 'Aktifkan suara video' : 'Matikan suara video');
            });
            media.closest('.promo-banner-slide')?.querySelector('.promo-banner-overlay')?.append(volumeButton);
        }
    });

    document.querySelectorAll('[data-promo-slider]').forEach(function (slider) {
        const slides = [...slider.querySelectorAll('[data-promo-slide]')];
        const heroArt = slider.closest('.hero-art');
        let dots = [...heroArt.querySelectorAll('[data-promo-dot]')];
        if (dots.length === 0) {
            const dotsContainer = document.createElement('div');
            dotsContainer.className = 'promo-banner-dots';
            dotsContainer.setAttribute('aria-label', 'Navigasi banner promo');
            const dot = document.createElement('button');
            dot.className = 'promo-banner-dot is-active';
            dot.type = 'button';
            dot.setAttribute('data-promo-dot', '0');
            dot.setAttribute('aria-label', 'Banner 1');
            dot.setAttribute('aria-pressed', 'true');
            dotsContainer.append(dot);
            heroArt.append(dotsContainer);
            dots = [dot];
        }
        if (slides.length < 2) return;
        let current = 0;
        let timer;

        const showSlide = function (index) {
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => slide.classList.toggle('is-active', slideIndex === current));
            dots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === current;
                dot.classList.toggle('is-active', isActive);
                dot.setAttribute('aria-pressed', String(isActive));
            });
        };
        const start = function () { timer = window.setInterval(() => showSlide(current + 1), 5000); };
        const restart = function () { window.clearInterval(timer); start(); };

        dots.forEach((dot, index) => dot.addEventListener('click', () => { showSlide(index); restart(); }));
        slider.addEventListener('mouseenter', () => window.clearInterval(timer));
        slider.addEventListener('mouseleave', start);
        start();
    });
});
</script>
@endsection
