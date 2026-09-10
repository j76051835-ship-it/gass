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
    .home-trust { position: relative; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 0; margin-top: 22px; padding: 24px 0; border-top: 1px solid rgba(103, 232, 249, .28); border-bottom: 1px solid rgba(103, 232, 249, .28); background: linear-gradient(90deg, rgba(7, 26, 50, .92), rgba(7, 26, 50, .68), rgba(7, 26, 50, .92)); overflow: hidden; }
    .home-trust::before { content: ""; position: absolute; top: 0; left: -20%; width: 18%; height: 1px; background: #67e8f9; box-shadow: 0 0 14px #67e8f9; animation: trust-scan 5s ease-in-out infinite; }
    .home-trust-item { position: relative; padding: 0 26px; border-right: 1px solid rgba(155, 180, 202, .24); opacity: 0; animation: trust-rise .7s cubic-bezier(.16, 1, .3, 1) forwards; }
    .home-trust-item:last-child { border-right: 0; }
    .home-trust-item:nth-child(2) { animation-delay: .1s; } .home-trust-item:nth-child(3) { animation-delay: .2s; } .home-trust-item:nth-child(4) { animation-delay: .3s; }
    .home-trust-number { display: block; color: #67e8f9; font: 700 clamp(27px, 3vw, 42px)/1 var(--mono); letter-spacing: .01em; text-shadow: 0 0 18px rgba(103, 232, 249, .25); }
    .home-trust-label { display: block; margin-top: 9px; color: #dbeafe; font-size: 12px; line-height: 1.35; }
    .home-trust-item::after { content: ""; display: block; width: 28px; height: 2px; margin-top: 14px; background: #f7c934; box-shadow: 0 0 8px rgba(247, 201, 52, .45); }
    @keyframes trust-rise { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes trust-scan { 0%, 20% { transform: translateX(0); opacity: 0; } 35%, 70% { opacity: 1; } 100% { transform: translateX(680%); opacity: 0; } }
    @media (max-width: 700px) { .home-trust { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 22px 0; padding: 22px 0; } .home-trust-item:nth-child(2) { border-right: 0; } .home-trust-item:nth-child(-n+2) { padding-bottom: 20px; border-bottom: 1px solid rgba(155, 180, 202, .24); } .home-trust-item { padding: 0 18px; } .home-trust-number { font-size: 28px; } }
    @media (max-width: 430px) { .home-trust-item { padding: 0 12px; } .home-trust-label { font-size: 11px; } }
    @media (prefers-reduced-motion: reduce) { .home-trust::before, .home-trust-item { animation: none; } .home-trust-item { opacity: 1; } }
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
        .home-gallery-card:not(:last-child)::after { content: "→\A GESER"; display: block; position: absolute; z-index: 3; top: 50%; right: 7px; padding: 8px 5px; border: 1px solid rgba(103,232,249,.78); background: rgba(5,11,24,.84); color: #67e8f9; font: 8px/1.3 var(--mono); letter-spacing: .08em; text-align: center; white-space: pre; transform: translateY(-50%); pointer-events: none; animation: gallery-swipe-hint 1.8s ease-in-out infinite; }
    }
    @keyframes gallery-swipe-hint { 0%, 100% { opacity: .58; transform: translate(0, -50%); } 50% { opacity: 1; transform: translate(4px, -50%); } }
    @media (max-width: 700px) { .home-gallery { padding-top: 80px; padding-bottom: 85px; } .home-gallery-heading { display: block; } .home-gallery-heading p:last-child { margin-top: 22px; } .home-gallery-card { flex-basis: min(86vw, 350px); } }
    .home-page .mobile-social-lockup {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        margin: 0 0 22px;
    }
    .home-page .mobile-social-label {
        margin: 0;
        color: #f3d862;
        font: 700 9px var(--mono);
        letter-spacing: .18em;
        text-transform: uppercase;
    }
    .home-page .mobile-social-row {
        display: flex;
        align-items: center;
        gap: 12px;
        perspective: 1000px;
    }
    .home-page .mobile-social-row a {
        position: relative;
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 10px 22px rgba(7, 26, 50, .2), inset 0 1px 0 rgba(255,255,255,.3), 0 0 0 1px rgba(255,255,255,.12);
        transition: transform .25s ease, filter .25s ease, box-shadow .25s ease;
        transform-style: preserve-3d;
        transform: perspective(600px) rotateX(0deg) rotateY(0deg);
        isolation: isolate;
    }
    .home-page .mobile-social-row a::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: linear-gradient(135deg, rgba(255,255,255,.46), rgba(255,255,255,0) 42%, rgba(0,0,0,.18));
        opacity: .92;
        pointer-events: none;
    }
    .home-page .mobile-social-row a::after {
        content: "";
        position: absolute;
        inset: 3px;
        border-radius: 50%;
        border: 1px solid rgba(255,255,255,.18);
        pointer-events: none;
        transform: translateZ(12px);
    }
    .home-page .mobile-social-row a:hover,
    .home-page .mobile-social-row a:focus-visible {
        transform: perspective(600px) translateY(-5px) rotateX(18deg) rotateY(-18deg) scale(1.06);
        filter: brightness(1.08);
        box-shadow: 0 18px 30px rgba(7, 26, 50, .28), 0 0 28px rgba(103, 232, 249, .18), inset 0 1px 0 rgba(255,255,255,.38);
        outline: 0;
        animation: social-bounce-3d .45s ease;
    }
    @keyframes social-bounce-3d {
        0% { transform: perspective(600px) translateY(0) rotateX(0deg) rotateY(0deg) scale(1); }
        35% { transform: perspective(600px) translateY(-8px) rotateX(20deg) rotateY(-20deg) scale(1.08); }
        100% { transform: perspective(600px) translateY(-5px) rotateX(18deg) rotateY(-18deg) scale(1.06); }
    }
    .home-page .mobile-social-row svg {
        position: relative;
        z-index: 1;
        display: block;
        width: 18px;
        height: 18px;
        fill: currentColor;
        margin: 0 auto;
    }
    .home-page .mobile-social-row .instagram-link {
        background: linear-gradient(135deg, #feda75 0%, #fa7e1e 25%, #d62976 52%, #962fbf 75%, #4f5bd5 100%);
        color: #fff;
    }
    .home-page .mobile-social-row .tiktok-link {
        background: #000;
        color: #fff;
    }
    .home-page .mobile-social-row .youtube-link {
        background: #ff0033;
        color: #fff;
    }
    .home-page .mobile-social-row .pinterest-link {
        background: #e60023;
        color: #fff;
    }
    .home-page .mobile-social-row .whatsapp-link {
        background: linear-gradient(135deg, #2fe57e, #1fcb68 52%, #15b95c);
        color: #fff;
        box-shadow: 0 12px 24px rgba(31, 188, 92, .28), inset 0 1px 0 rgba(255,255,255,.32), 0 0 0 1px rgba(255,255,255,.16);
    }
    .home-page .mobile-social-row .maps-link {
        background: linear-gradient(135deg, #38bdf8 0%, #2563eb 46%, #1d4ed8 100%);
        color: #fff;
        box-shadow: 0 12px 24px rgba(37, 99, 235, .28), inset 0 1px 0 rgba(255,255,255,.32), 0 0 0 1px rgba(255,255,255,.16);
    }
    .home-page .mobile-social-row .whatsapp-link svg {
        width: 20px;
        height: 20px;
        margin: 0 auto;
        transform: translateX(.3px) translateY(.7px) scale(1.02);
        filter: drop-shadow(0 1px 0 rgba(0,0,0,.12));
    }
    .home-page .mobile-social-row .maps-link svg {
        width: 18px;
        height: 18px;
        margin: 0 auto;
        transform: translateY(1px);
        filter: drop-shadow(0 1px 0 rgba(0,0,0,.12));
    }
    @media (max-width: 480px) {
        .home-page .hero {
            display: block;
            padding-top: 18px;
            padding-bottom: 30px;
        }
        .home-page .hero-copy {
            width: 100%;
            padding-bottom: 0;
        }
        .home-page .mobile-social-lockup {
            margin: 0 0 14px;
        }
        .home-page .mobile-social-row {
            gap: 10px;
        }
        .home-page .mobile-social-row a {
            width: 36px;
            height: 36px;
        }
        .home-page .mobile-social-row svg {
            width: 16px;
            height: 16px;
        }
        .home-page .hero-copy .eyebrow {
            margin-top: 6px;
            margin-bottom: 14px;
        }
        .home-page .hero-copy h1 {
            font-size: clamp(52px, 14vw, 70px);
        }
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
<section class="hero section-shell"><div class="traffic-field" aria-hidden="true"><i class="traffic-lane lane-one"></i><i class="traffic-lane lane-two"></i><i class="traffic-lane lane-three"></i><i class="traffic-node node-one"></i><i class="traffic-node node-two"></i><i class="traffic-node node-three"></i><i class="traffic-node node-four"></i></div><div class="hero-copy"><div class="mobile-social-lockup" aria-label="Ikuti kami di media sosial GASS"><p class="mobile-social-label">Ikuti kami</p><div class="mobile-social-row"><a class="instagram-link" href="https://www.instagram.com/gass.generations/" target="_blank" rel="noreferrer" aria-label="Instagram GASS"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0-3 3Zm5.25-3.25a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5Z"/></svg></a><a class="tiktok-link" href="https://www.tiktok.com/@gass.generations" target="_blank" rel="noreferrer" aria-label="TikTok GASS"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.6 3c.3 2.2 1.5 3.5 3.4 3.6v2.8c-1.5.1-2.8-.4-3.9-1.2v6.5a5.3 5.3 0 1 1-5.3-5.3c.3 0 .7 0 1 .1v2.9a2.4 2.4 0 1 0 1.5 2.3V3h3.3Z"/></svg></a><a class="pinterest-link" href="https://pin.it/34Yxkq9r6" target="_blank" rel="noreferrer" aria-label="Pinterest GASS"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-3.64 19.31c-.09-1.64-.02-3.61.41-5.48l1.05-4.45s-.27-.54-.27-1.33c0-1.25.73-2.18 1.64-2.18.77 0 1.14.58 1.14 1.27 0 .77-.49 1.92-.74 2.99-.21.89.45 1.61 1.33 1.61 1.6 0 2.68-1.68 2.68-4.1 0-2.14-1.54-3.64-3.75-3.64-2.56 0-4.06 1.92-4.06 3.9 0 .77.3 1.6.67 2.05.07.08.08.15.06.24l-.25 1.02c-.04.16-.13.2-.3.12-1.12-.52-1.82-2.13-1.82-3.43 0-2.79 2.03-5.35 5.85-5.35 3.07 0 5.45 2.19 5.45 5.12 0 3.05-1.92 5.51-4.59 5.51-.9 0-1.75-.47-2.04-1.03l-.55 2.1c-.2.77-.74 1.74-1.1 2.33.83.25 1.71.39 2.62.39A10 10 0 0 0 12 2Z"/></svg></a><a class="youtube-link" href="https://youtube.com/@gassdigitalsolutions?si=-HZ9h297EeObAse6" target="_blank" rel="noreferrer" aria-label="YouTube GASS"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M23.5 7.5c-.3-1.6-1.5-2.7-3.1-3.1C18.7 4 12 4 12 4s-6.7 0-8.4.4A3.8 3.8 0 0 0 .5 7.5C.1 9.2 0 10.7 0 12s.1 2.8.5 4.5c.3 1.6 1.5 2.7 3.1 3.1 1.7.4 8.4.4 8.4.4s6.7 0 8.4-.4c1.6-.4 2.8-1.5 3.1-3.1.4-1.7.5-3.2.5-4.5s-.1-2.8-.5-4.5ZM9.8 15.6V8.4L16 12l-6.2 3.6Z"/></svg></a><a class="maps-link" href="https://maps.app.goo.gl/hH3uke8PjupxzHUk8" target="_blank" rel="noreferrer" aria-label="Google Maps GASS"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.5a7.5 7.5 0 0 1 7.5 7.5c0 5.25-7.5 11.5-7.5 11.5S4.5 15.25 4.5 10A7.5 7.5 0 0 1 12 2.5Zm0 3a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9Z"/></svg></a><a class="whatsapp-link" href="https://wa.me/6285890007359" target="_blank" rel="noreferrer" aria-label="WhatsApp GASS"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2C6.48 2 2 6.48 2 12c0 1.72.45 3.37 1.22 4.82L2 22l5.24-1.2A9.98 9.98 0 1 0 12 2Zm0 18.3a8.28 8.28 0 0 1-4.19-1.15l-.29-.18-3.11.72.76-2.97-.19-.29A8.3 8.3 0 1 1 12 20.3Zm4.6-5.96c-.25-.12-1.46-.72-1.7-.8-.24-.08-.42-.12-.59.13-.17.24-.65.8-.8.96-.15.17-.3.19-.55.07-.24-.12-1.02-.38-1.96-1.22-.74-.66-1.23-1.45-1.37-1.69-.14-.24-.02-.37.11-.49.12-.12.25-.3.38-.46.13-.15.17-.27.26-.43.09-.17.04-.32-.02-.45-.06-.13-.56-1.35-.77-1.84-.2-.48-.41-.41-.58-.42l-.49-.01c-.17 0-.43.06-.66.3-.22.25-.85.84-.85 2.05 0 1.2.87 2.33 1 2.48.13.15 1.7 2.57 4.12 3.57.58.25 1.02.4 1.37.51.58.18 1.11.16 1.53.1.49-.07 1.43-.59 1.64-1.17.2-.57.2-1.05.14-1.15-.06-.1-.22-.16-.46-.29Z"/></svg></a></div></div><p class="eyebrow"><span class="eyebrow-mark">✦</span> Your next move starts here</p><h1>Naik level,<br><em>tanpa ragu.</em></h1><p class="hero-intro">Kami membantu bisnis menemukan arah, tampil lebih berani, dan bergerak lebih cepat di dunia digital.</p><div class="hero-actions"><a class="button button-yellow" href="{{ route('order.form') }}">Pesan sekarang <span>↗</span></a><a class="text-link" href="{{ route('services') }}">Lihat layanan <span>→</span></a></div></div><div class="hero-art {{ $banners->isNotEmpty() ? 'has-promo-banner' : '' }}" aria-label="Banner promo GASS">@if ($banners->isNotEmpty())<div class="promo-banner-slider" data-promo-slider>@foreach ($banners as $index => $banner)<div class="promo-banner-slide {{ $index === 0 ? 'is-active' : '' }}" data-promo-slide>@if ($banner->media_type === 'video')<video class="promo-banner-media" src="{{ asset('storage/'.$banner->media_path) }}" autoplay muted loop playsinline></video>@else<img class="promo-banner-media" src="{{ asset('storage/'.$banner->media_path) }}" alt="{{ $banner->title }}">@endif<div class="promo-banner-overlay"><h2>{{ $banner->title }}</h2></div></div>@endforeach</div>@endif<div class="art-note note-top">strategy<br><strong>→ growth</strong></div><div class="sun"></div><div class="orbit orbit-one"></div><div class="orbit orbit-two"></div><div class="orbit orbit-three"></div><div class="art-label">GROW<br>WITH<br>GASS</div><div class="art-sticker">digital<br>with<br>direction</div><div class="art-note note-bottom">ideas in<br><strong>motion</strong></div>@if ($banners->count() > 1)<div class="promo-banner-dots" aria-label="Navigasi banner promo">@foreach ($banners as $index => $banner)<button class="promo-banner-dot {{ $index === 0 ? 'is-active' : '' }}" type="button" data-promo-dot="{{ $index }}" aria-label="Banner {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"></button>@endforeach</div>@endif</div></section>
<section class="ticker" aria-label="Layanan GASS"><div><span class="ticker-group">WE BUILD <i>✦</i> WE GROW <i>✦</i> WE MOVE <i>✦</i> WE BUILD <i>✦</i> WE GROW <i>✦</i></span><span class="ticker-group" aria-hidden="true">WE BUILD <i>✦</i> WE GROW <i>✦</i> WE MOVE <i>✦</i> WE BUILD <i>✦</i> WE GROW <i>✦</i></span></div></section>
<section class="home-trust section-shell" aria-label="Kepercayaan klien GASS"><div class="home-trust-item"><span class="home-trust-number">50+</span><span class="home-trust-label">Project Selesai</span></div><div class="home-trust-item"><span class="home-trust-number">20+</span><span class="home-trust-label">Klien Aktif</span></div><div class="home-trust-item"><span class="home-trust-number">95%</span><span class="home-trust-label">Klien Repeat Order</span></div><div class="home-trust-item"><span class="home-trust-number">3 Hari</span><span class="home-trust-label">Rata-rata Pengerjaan Konten</span></div></section>
<section class="services section-shell"><div class="section-heading"><p class="eyebrow">What we do</p><h2>Satu partner,<br><em>empat cara tumbuh.</em></h2></div><div class="service-grid"><article class="service-card card-pink"><span class="card-number">01</span><span class="card-icon">↗</span><h3>Website<br>yang bekerja</h3><p>Website bukan cuma cantik. Kami rancang agar pengunjung paham, percaya, lalu bergerak.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-yellow"><span class="card-number">02</span><span class="card-icon">✦</span><h3>Konten<br>yang bicara</h3><p>Identitas dan konten yang membuat brand kamu terdengar jelas di tengah keramaian.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-orange"><span class="card-number">03</span><span class="card-icon">◎</span><h3>Sosmed<br>terawat</h3><p>Strategi, kalender, dan eksekusi agar akun sosialmu konsisten dan selalu relevan.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article><article class="service-card card-lilac"><span class="card-number">04</span><span class="card-icon">◫</span><h3>E-commerce<br>siap jual</h3><p>Dari etalase sampai checkout, kami buat pengalaman belanja yang terasa mudah.</p><a href="{{ route('services') }}">Pelajari layanan <span>↗</span></a></article></div></section>
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
            volumeButton.textContent = media.muted ? '🔊' : '🔉';
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


