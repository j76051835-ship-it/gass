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
    .home-page .promo-banner-slider { position: absolute; inset: 8px; z-index: 1; overflow: hidden; border: 1px solid rgba(255,255,255,.58); border-radius: 4px; box-shadow: inset 0 0 0 1px rgba(5,11,24,.42), inset 0 0 28px rgba(5,11,24,.3); touch-action: pan-y; }
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
    .home-video-controls { display: flex; gap: 6px; position: absolute; z-index: 5; left: 12px; bottom: 12px; }
    .home-video-control { width: 34px; height: 30px; border: 1px solid rgba(103,232,249,.85); border-radius: 3px; background: rgba(5,11,24,.86); color: #67e8f9; font: 14px var(--mono); cursor: pointer; }
    .home-video-control:hover, .home-video-control:focus-visible { background: #f7c934; color: #050b18; outline: 0; }
    .home-page .hero-art.has-promo-banner > :not(.promo-banner-slider):not(.promo-banner-dots):not(.promo-banner-arrows):not(.promo-banner-media):not(.promo-banner-overlay) { display: none; }
    .home-page .promo-banner-dots { display: flex; position: absolute; z-index: 5; right: 28px; bottom: 24px; gap: 9px; padding: 9px 11px; border: 1px solid rgba(103,232,249,.55); border-radius: 20px; background: rgba(5,11,24,.62); box-shadow: 0 0 18px rgba(34,211,238,.2); backdrop-filter: blur(8px); }
    .home-page .promo-banner-dot { width: 10px; height: 10px; padding: 0; border: 1px solid #fff; border-radius: 50%; background: transparent; cursor: pointer; transition: transform .2s ease, background .2s ease; }
    .home-page .promo-banner-dot:hover, .home-page .promo-banner-dot:focus-visible { transform: scale(1.35); background: #fff; outline: 2px solid #f7c934; outline-offset: 3px; }
    .home-page .promo-banner-dot.is-active { background: #fff; box-shadow: 0 0 0 3px rgba(255,255,255,.22); }
    .home-page .promo-banner-arrows { display: none; }
    .home-page .promo-banner-arrow { width: 30px; height: 42px; border: 1px solid rgba(103,232,249,.8); border-radius: 3px; background: rgba(5,11,24,.78); color: #67e8f9; font: 20px var(--mono); line-height: 1; cursor: pointer; }
    .home-page .promo-banner-arrow:focus-visible, .home-page .promo-banner-arrow:hover { background: #f7c934; color: #050b18; outline: 2px solid #fff; outline-offset: 2px; }
    @keyframes promo-scan { 0%, 100% { transform: translateY(0); opacity: .12; } 50% { transform: translateY(500px); opacity: .5; } }
    @media (max-width: 600px) { .home-page .promo-banner-dots { right: 18px; bottom: 18px; } .home-page .promo-banner-arrows { display: flex; position: absolute; z-index: 5; top: 50%; right: 12px; left: 12px; justify-content: space-between; transform: translateY(-50%); pointer-events: none; } .home-page .promo-banner-arrow { pointer-events: auto; } }
    .home-page .hero-art:before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: 1;
        background: linear-gradient(150deg, rgba(255,255,255,.18), transparent 35%, rgba(255,255,255,.08));
        pointer-events: none;
    }
    .home-page .hero-actions .button-yellow { color: #071a32; }
    .home-follow { display: flex; align-items: center; gap: 14px; margin-top: 28px; }
    .home-follow-label { margin: 0; color: #9bb4ca; font: 10px var(--mono); letter-spacing: .08em; text-transform: uppercase; }
    .home-follow-links { display: flex; gap: 8px; }
    .home-follow-link { display: grid; width: 34px; height: 34px; place-items: center; border: 1px solid rgba(103,232,249,.55); border-radius: 50%; background: #071a32; color: #67e8f9; transition: transform .2s ease, background .2s ease, color .2s ease; }
    .home-follow-link:hover, .home-follow-link:focus-visible { transform: translateY(-3px); background: #67e8f9; color: #071a32; outline: 0; }
    .home-follow-link svg { width: 17px; height: 17px; fill: currentColor; }
    .home-gallery { padding-top: 120px; padding-bottom: 120px; }
    .home-gallery-heading { display: flex; align-items: end; justify-content: space-between; gap: 30px; margin-bottom: 42px; }
    .home-gallery-heading h2 { max-width: 620px; margin: 0; font-size: clamp(42px, 6vw, 78px); line-height: .92; letter-spacing: -.06em; }
    .home-gallery-heading h2 em { color: var(--orange); font-style: normal; }
    .home-gallery-heading p:last-child { max-width: 270px; margin: 0; font-size: 15px; line-height: 1.5; }
    .home-gallery-grid { display: flex; justify-content: flex-start; gap: 20px; overflow-x: auto; padding: 4px 8px 20px 2px; scroll-snap-type: x mandatory; scrollbar-color: #67e8f9 #071a32; scrollbar-width: thin; }
    .home-gallery-grid::-webkit-scrollbar { height: 8px; }
    .home-gallery-grid::-webkit-scrollbar-track { background: #071a32; }
    .home-gallery-grid::-webkit-scrollbar-thumb { background: #67e8f9; border: 2px solid #071a32; }
    .home-gallery-card { position: relative; flex: 0 0 390px; scroll-snap-align: start; overflow: hidden; border: 1px solid rgba(103,232,249,.52); border-radius: 6px; background: #071a32; box-shadow: 8px 8px 0 rgba(5,11,24,.18), 0 14px 30px rgba(5,11,24,.16); transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease; }
    .home-gallery-card:hover { transform: translateY(-5px); border-color: #67e8f9; box-shadow: 12px 14px 0 rgba(5,11,24,.2), 0 18px 34px rgba(5,11,24,.2); }
    .home-gallery-media { position: relative; background: #050b18; }
    .home-gallery-media:before { content: "GASS / VISUAL LOG"; position: absolute; z-index: 2; top: 12px; left: 12px; padding: 5px 7px; border: 1px solid rgba(103,232,249,.55); background: rgba(5,11,24,.78); color: #67e8f9; font: 9px var(--mono); letter-spacing: .08em; pointer-events: none; }
    .home-gallery-slide { display: none; }
    .home-gallery-slide.is-active { display: block; }
    .home-gallery-slide img, .home-gallery-slide video { display: block; width: 100%; height: auto; max-height: 410px; object-fit: contain; background: #050b18; }
    .home-gallery-controls { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; border-top: 1px solid rgba(103,232,249,.24); background: #071a32; }
    .home-gallery-arrows { display: flex; gap: 6px; }
    .home-gallery-arrow { width: 32px; height: 30px; border: 1px solid rgba(103,232,249,.72); border-radius: 3px; background: transparent; color: #67e8f9; cursor: pointer; }
    .home-gallery-arrow:hover, .home-gallery-arrow:focus-visible { background: var(--yellow); color: var(--ink); outline: 0; }
    .home-gallery-dots { display: flex; gap: 6px; }
    .home-gallery-dot { width: 7px; height: 7px; padding: 0; border: 1px solid #f5f1e9; border-radius: 50%; background: transparent; cursor: pointer; }
    .home-gallery-dot.is-active { background: var(--yellow); }
    .home-gallery-copy { position: relative; padding: 18px 20px 20px; border-top: 1px solid rgba(103,232,249,.2); background: #071a32; }
    .home-gallery-copy:after { content: "↗"; position: absolute; top: 18px; right: 20px; color: #f7c934; font: 18px var(--mono); }
    .home-gallery-copy h3 { margin: 0 28px 8px 0; color: #f7f9fc; font-size: 23px; }
    .home-gallery-copy p { margin: 0; color: #9bb4ca; font-size: 13px; line-height: 1.5; }
    .home-gallery-card:not(:last-child)::after { display: none; }
    @media (max-width: 700px) {
        .home-gallery-card:not(:last-child)::after { content: "→\\A GESER"; display: block; position: absolute; z-index: 3; top: 50%; right: 7px; padding: 8px 5px; border: 1px solid rgba(103,232,249,.78); background: rgba(5,11,24,.84); color: #67e8f9; font: 8px/1.3 var(--mono); letter-spacing: .08em; text-align: center; white-space: pre; transform: translateY(-50%); pointer-events: none; animation: gallery-swipe-hint 1.8s ease-in-out infinite; }
    }
    @keyframes gallery-swipe-hint { 0%, 100% { opacity: .58; transform: translate(0, -50%); } 50% { opacity: 1; transform: translate(4px, -50%); } }
    @media (max-width: 700px) { .home-gallery { padding-top: 80px; padding-bottom: 85px; } .home-gallery-heading { display: block; } .home-gallery-heading p:last-child { margin-top: 22px; } .home-gallery-card { flex-basis: min(86vw, 350px); } }
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
<section class="hero section-shell"><div class="traffic-field" aria-hidden="true"><i class="traffic-lane lane-one"></i><i class="traffic-lane lane-two"></i><i class="traffic-lane lane-three"></i><i class="traffic-node node-one"></i><i class="traffic-node node-two"></i><i class="traffic-node node-three"></i><i class="traffic-node node-four"></i></div><div class="hero-copy"><p class="eyebrow"><span class="eyebrow-mark">✳</span> Your next move starts here</p><h1>Naik level,<br><em>tanpa ragu.</em></h1><p class="hero-intro">Kami membantu bisnis menemukan arah, tampil lebih berani, dan bergerak lebih cepat di dunia digital.</p><div class="hero-actions"><a class="button button-yellow" href="{{ route('order.form') }}">Pesan sekarang <span>↗</span></a><a class="text-link" href="{{ route('services') }}">Lihat layanan <span>↓</span></a></div></div><div class="hero-art {{ $banners->isNotEmpty() ? 'has-promo-banner' : '' }}" aria-label="Banner promo GASS">@if ($banners->isNotEmpty())<div class="promo-banner-slider" data-promo-slider>@foreach ($banners as $index => $banner)<div class="promo-banner-slide {{ $index === 0 ? 'is-active' : '' }}" data-promo-slide>@if ($banner->media_type === 'video')<video class="promo-banner-media" src="{{ asset('storage/'.$banner->media_path) }}" autoplay muted loop playsinline></video>@else<img class="promo-banner-media" src="{{ asset('storage/'.$banner->media_path) }}" alt="{{ $banner->title }}">@endif<div class="promo-banner-overlay"><h2>{{ $banner->title }}</h2></div></div>@endforeach</div>@endif<div class="art-note note-top">strategy<br><strong>→ growth</strong></div><div class="sun"></div><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div><div class="art-label">GROW<br>WITH<br>GASS</div><div class="art-sticker">digital<br>with<br>direction</div><div class="art-note note-bottom">ideas in<br><strong>motion</strong></div>@if ($banners->count() > 1)<div class="promo-banner-dots" aria-label="Navigasi banner promo">@foreach ($banners as $index => $banner)<button class="promo-banner-dot {{ $index === 0 ? 'is-active' : '' }}" type="button" data-promo-dot="{{ $index }}" aria-label="Banner {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"></button>@endforeach</div>@endif</div></section>
<section class="ticker" aria-label="Layanan GASS"><div><span class="ticker-group">WE BUILD <i>✦</i> WE GROW <i>✦</i> WE MOVE <i>✦</i> WE BUILD <i>✦</i> WE GROW <i>✦</i></span><span class="ticker-group" aria-hidden="true">WE BUILD <i>✦</i> WE GROW <i>✦</i> WE MOVE <i>✦</i> WE BUILD <i>✦</i> WE GROW <i>✦</i></span></div></section>
<section class="services section-shell"><div class="section-heading"><p class="eyebrow">What we do</p><h2>Satu partner,<br><em>empat cara tumbuh.</em></h2></div><div class="service-grid"><article class="service-card card-pink"><span class="card-number">01</span><span class="card-icon">↗</span><h3>Website<br>yang bekerja</h3><p>Website bukan cuma cantik. Kami rancang agar pengunjung paham, percaya, lalu bergerak.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-yellow"><span class="card-number">02</span><span class="card-icon">✳</span><h3>Konten<br>yang bicara</h3><p>Identitas dan konten yang membuat brand kamu terdengar jelas di tengah keramaian.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-orange"><span class="card-number">03</span><span class="card-icon">◌</span><h3>Sosmed<br>terawat</h3><p>Strategi, kalender, dan eksekusi agar akun sosialmu konsisten dan selalu relevan.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-lilac"><span class="card-number">04</span><span class="card-icon">⌁</span><h3>E-commerce<br>siap jual</h3><p>Dari etalase sampai checkout, kami buat pengalaman belanja yang terasa mudah.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article></div></section>
@if ($galleryItems->isNotEmpty())
<section class="home-gallery section-shell"><div class="home-gallery-heading"><div><p class="eyebrow">Selected work / 05</p><h2>Gallery<br><em>testimoni.</em></h2></div><p>Beberapa hasil kerja yang kami rancang untuk membuat brand terlihat, terdengar, dan tumbuh lebih jauh.</p></div><div class="home-gallery-grid">@foreach ($galleryItems as $item)<article class="home-gallery-card"><div class="home-gallery-media" data-gallery-slider>@foreach ($item->media as $index => $media)<div class="home-gallery-slide {{ $index === 0 ? 'is-active' : '' }}" data-gallery-slide>@if ($media['type'] === 'video')<video src="{{ asset('storage/'.$media['path']) }}" controls preload="metadata"></video>@else<img src="{{ asset('storage/'.$media['path']) }}" alt="{{ $item->title }} - media {{ $index + 1 }}">@endif</div>@endforeach @if (count($item->media) > 1)<div class="home-gallery-controls"><div class="home-gallery-arrows"><button class="home-gallery-arrow" type="button" data-gallery-prev aria-label="Media sebelumnya">←</button><button class="home-gallery-arrow" type="button" data-gallery-next aria-label="Media berikutnya">→</button></div><div class="home-gallery-dots" aria-label="Pilihan media">@foreach ($item->media as $index => $media)<button class="home-gallery-dot {{ $index === 0 ? 'is-active' : '' }}" type="button" data-gallery-dot="{{ $index }}" aria-label="Media {{ $index + 1 }}"></button>@endforeach</div></div>@endif</div><div class="home-gallery-copy"><h3>{{ $item->title }}</h3>@if ($item->description)<p>{{ $item->description }}</p>@endif</div></article>@endforeach</div></section>
@endif
<section class="manifesto section-shell"><div class="manifesto-stamp">GASS<br>YOUR<br>WAY</div><div class="manifesto-copy"><p class="eyebrow">Why GASS?</p><h2>Ambisi besar<br>butuh <em>arah.</em></h2></div><div class="manifesto-text"><p>Kami percaya pertumbuhan yang sehat dimulai dari strategi yang tepat. GASS hadir untuk mengubah ide menjadi aksi digital yang punya tujuan dan hasil.</p><a class="button button-outline" href="{{ route('about') }}">Kenalan dengan GASS <span>↗</span></a></div></section>
@include('partials.reviews')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const primaryCta = document.querySelector('.hero-actions .button-yellow');
    if (primaryCta) {
        primaryCta.innerHTML = 'Pesan sekarang <span>↗</span>';
    }

    const heroActions = document.querySelector('.hero-actions');
    if (heroActions && !document.querySelector('.home-follow')) {
        heroActions.insertAdjacentHTML('afterend', '<div class="home-follow"><p class="home-follow-label">Ikuti kami</p><div class="home-follow-links"><a class="home-follow-link" href="https://www.instagram.com/gass.generations/" target="_blank" rel="noreferrer" aria-label="Ikuti GASS di Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0-3 3Zm5.25-3.25a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5Z"/></svg></a><a class="home-follow-link" href="https://www.tiktok.com/@gass.generations" target="_blank" rel="noreferrer" aria-label="Ikuti GASS di TikTok"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.6 3c.3 2.2 1.5 3.5 3.4 3.6v2.8c-1.5.1-2.8-.4-3.9-1.2v6.5a5.3 5.3 0 1 1-5.3-5.3c.3 0 .7 0 1 .1v2.9a2.4 2.4 0 1 0 1.5 2.3V3h3.3Z"/></svg></a></div></div>');
    }

    const setupVideoControls = function (media) {
        media.controls = false;
        media.removeAttribute('controlslist');
        media.removeAttribute('disablepictureinpicture');
        const controls = document.createElement('div');
        controls.className = 'home-video-controls';
        const playButton = document.createElement('button');
        playButton.className = 'home-video-control';
        playButton.type = 'button';
        playButton.setAttribute('aria-label', 'Jedaikan video');
        const volumeButton = document.createElement('button');
        volumeButton.className = 'home-video-control';
        volumeButton.type = 'button';
        volumeButton.setAttribute('aria-label', 'Aktifkan suara video');
        const updatePlayButton = function () {
            playButton.textContent = media.paused ? '▶' : '❚❚';
            playButton.setAttribute('aria-label', media.paused ? 'Putar video' : 'Jedaikan video');
        };
        const updateVolumeButton = function () {
            volumeButton.textContent = media.muted ? '🔇' : '🔊';
            volumeButton.setAttribute('aria-label', media.muted ? 'Aktifkan suara video' : 'Matikan suara video');
        };
        playButton.addEventListener('click', function () {
            if (media.paused) {
                media.dataset.userPlay = 'true';
                media.play();
            } else {
                media.pause();
            }
        });
        volumeButton.addEventListener('click', function () { media.muted = !media.muted; updateVolumeButton(); });
        media.addEventListener('play', updatePlayButton);
        media.addEventListener('pause', updatePlayButton);
        controls.append(playButton, volumeButton);
        media.parentElement.append(controls);
        updatePlayButton();
        updateVolumeButton();
    };

    document.querySelectorAll('.promo-banner-media').forEach(function (media) {
        if (media.tagName.toLowerCase() === 'video') {
            media.removeAttribute('autoplay');
            media.pause();
            media.currentTime = 0;
            media.muted = true;
            setupVideoControls(media);
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
        const arrows = document.createElement('div');
        arrows.className = 'promo-banner-arrows';
        const previousButton = document.createElement('button');
        previousButton.className = 'promo-banner-arrow';
        previousButton.type = 'button';
        previousButton.textContent = '←';
        previousButton.setAttribute('aria-label', 'Banner sebelumnya');
        const nextButton = document.createElement('button');
        nextButton.className = 'promo-banner-arrow';
        nextButton.type = 'button';
        nextButton.textContent = '→';
        nextButton.setAttribute('aria-label', 'Banner berikutnya');
        arrows.append(previousButton, nextButton);
        heroArt.append(arrows);
        let current = 0;
        let timer;
        let isVideoPlaying = false;

        const showSlide = function (index) {
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === current;
                slide.classList.toggle('is-active', isActive);
                const video = slide.querySelector('video');
                if (!video) return;
                if (!isActive) {
                    video.pause();
                    video.currentTime = 0;
                    return;
                }
                video.play().catch(() => {});
            });
            dots.forEach((dot, dotIndex) => {
                const isActive = dotIndex === current;
                dot.classList.toggle('is-active', isActive);
                dot.setAttribute('aria-pressed', String(isActive));
            });
        };
        const start = function () {
            if (isVideoPlaying || timer) return;
            timer = window.setInterval(() => showSlide(current + 1), 5000);
        };
        const stop = function () { window.clearInterval(timer); timer = null; };
        const restart = function () { window.clearInterval(timer); start(); };

        slides.forEach((slide) => {
            const video = slide.querySelector('video');
            if (!video) return;
            video.addEventListener('play', () => {
                isVideoPlaying = true;
                stop();
            });
            video.addEventListener('pause', () => {
                if (video.ended) return;
                isVideoPlaying = false;
                start();
            });
        });

        dots.forEach((dot, index) => dot.addEventListener('click', () => { showSlide(index); restart(); }));
        previousButton.addEventListener('click', () => { showSlide(current - 1); restart(); });
        nextButton.addEventListener('click', () => { showSlide(current + 1); restart(); });
        let pointerStart = null;
        slider.addEventListener('pointerdown', (event) => {
            if (event.target.closest('button, video')) return;
            pointerStart = { x: event.clientX, y: event.clientY };
        });
        slider.addEventListener('pointerup', (event) => {
            if (!pointerStart) return;
            const deltaX = event.clientX - pointerStart.x;
            const deltaY = event.clientY - pointerStart.y;
            pointerStart = null;
            if (Math.abs(deltaX) < 45 || Math.abs(deltaX) <= Math.abs(deltaY)) return;
            showSlide(current + (deltaX < 0 ? 1 : -1));
            restart();
        });
        slider.addEventListener('pointercancel', () => { pointerStart = null; });
        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        showSlide(0);
        start();
    });

    document.querySelectorAll('[data-gallery-slider]').forEach(function (slider) {
        const slides = [...slider.querySelectorAll('[data-gallery-slide]')];
        const dots = [...slider.querySelectorAll('[data-gallery-dot]')];
        if (slides.length < 2) return;
        let current = 0;
        const showSlide = function (index) {
            current = (index + slides.length) % slides.length;
            slides.forEach((slide, slideIndex) => slide.classList.toggle('is-active', slideIndex === current));
            dots.forEach((dot, dotIndex) => dot.classList.toggle('is-active', dotIndex === current));
        };
        slider.querySelector('[data-gallery-prev]').addEventListener('click', () => showSlide(current - 1));
        slider.querySelector('[data-gallery-next]').addEventListener('click', () => showSlide(current + 1));
        dots.forEach((dot, index) => dot.addEventListener('click', () => showSlide(index)));
    });

    document.querySelectorAll('.home-gallery-media video').forEach(function (video) {
        setupVideoControls(video);
    });
});
</script>
@endsection
