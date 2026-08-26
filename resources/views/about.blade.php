@extends('layouts.app')

@section('title', 'Tentang Kami — GASS')

@section('body_class', 'about-page-body')

@push('styles')
<style>
	.about-page-body .about-copy { padding-left: 12px; }
	.about-page-body .about-book { overflow: hidden; }
	.about-page-body .manifesto { background: rgba(8, 123, 220, 0.06); }
	.about-page-body .about-visual { isolation: isolate; }
	.about-page-body .about-visual.lottie-visual > :not(.lottie-animation-mount) { display: none; }
	.about-page-body .about-visual.lottie-visual::before, .about-page-body .about-visual.lottie-visual::after { display: none; }
	.about-page-body .lottie-animation-mount { position: absolute; z-index: 20; inset: 0; display: grid; place-items: center; overflow: hidden; background: #073384; }
	.about-page-body .lottie-animation-mount > div { width: 100%; height: 100%; }
	.about-page-body .lottie-animation-mount canvas { display: block; width: 100% !important; height: 100% !important; object-fit: contain; }
	.about-page-body .about-visual::before { content: "LIVE CAMPAIGN / ACTIVE"; position: absolute; z-index: 7; top: 18px; left: 20px; padding: 6px 9px; border: 1px solid rgba(57, 223, 255, .5); background: rgba(5, 11, 24, .58); color: #9ff6ff; font: 9px var(--mono); letter-spacing: .08em; box-shadow: 0 0 15px rgba(57, 223, 255, .2); animation: marketing-status 2.8s ease-in-out infinite; }
	.about-page-body .about-glow::before { content: ""; position: absolute; z-index: 8; left: -18%; top: 67%; width: 155%; height: 15%; border: 4px solid #05244f; background: linear-gradient(180deg, #eef3f7, #c9d3df); transform: skewX(-8deg); box-shadow: 0 8px 0 rgba(6, 27, 69, .24), 0 0 16px rgba(57, 223, 255, .18); }
	.about-page-body .about-glow::after { content: ""; position: absolute; z-index: 4; left: 28%; top: 82%; width: 8px; height: 70%; background: #05244f; box-shadow: 210px 0 #05244f; }
	.about-page-body .about-screen, .about-page-body .about-laptop { will-change: transform; }
	.about-page-body .about-screen::after, .about-page-body .laptop-screen::after { content: ""; position: absolute; z-index: 4; top: -20%; left: -35%; width: 18%; height: 150%; background: rgba(57, 223, 255, .38); filter: blur(8px); transform: skewX(-18deg); animation: marketing-scan 4.8s linear infinite; pointer-events: none; }
	.about-page-body .chart i { animation-name: marketing-chart-rise; animation-duration: 3.2s; animation-play-state: paused; }
	.about-page-body .mini-chart i { animation: marketing-chart-rise 2.8s ease-in-out infinite; animation-play-state: paused; transform-origin: bottom; }
	.about-page-body .about-chart-card strong { color: #087bdc; }
	.about-page-body .about-chart-card strong::after { content: "+120%"; animation: marketing-reach 7s steps(1, end) infinite; }
	.about-page-body .about-chart-card strong { font-size: 0; }
	.about-page-body .about-chart-card strong::after { font-size: 20px; }
	.about-page-body .about-person i { animation: marketing-head 4s ease-in-out infinite; transform-origin: bottom center; }
	.about-page-body .about-person { height: 118px; }
	.about-page-body .about-person b { position: relative; height: 48px; clip-path: polygon(14% 0, 86% 0, 100% 100%, 0 100%); }
	.about-page-body .about-person b::after { content: ""; position: absolute; right: -13px; top: 12px; width: 18px; height: 4px; border-radius: 3px; background: #05244f; transform-origin: left center; animation: marketing-typing 1.2s ease-in-out infinite; }
	.about-page-body .about-person b::before { content: ""; position: absolute; left: 8px; bottom: -25px; width: 22px; height: 27px; border-left: 7px solid #05244f; border-bottom: 5px solid #05244f; border-radius: 0 0 0 12px; transform: skewX(-12deg); }
	.about-page-body .about-person.person-one::after { content: ""; position: absolute; left: 0; top: 38px; width: 24px; height: 4px; border-radius: 3px; background: #05244f; transform: rotate(17deg); transform-origin: right center; animation: marketing-typing 1.2s .35s ease-in-out infinite reverse; }
	.about-page-body .about-person.person-one { left: 43%; bottom: 7%; z-index: 7; }
	.about-page-body .about-person.person-one::before { content: ""; position: absolute; z-index: -1; left: -13px; top: 30px; width: 42px; height: 67px; border: 7px solid rgba(5, 36, 79, .7); border-bottom: 0; border-radius: 18px 18px 0 0; }
	.about-page-body .marketing-desk { position: absolute; z-index: 6; left: 28%; right: 14%; bottom: 14%; height: 7%; border: 3px solid #05244f; background: #dce3e9; transform: skewX(-8deg); box-shadow: 0 7px 0 rgba(6, 27, 69, .22); }
	.about-page-body .marketing-desk::before { content: ""; position: absolute; left: 43%; top: 12%; width: 22%; height: 22%; border-radius: 2px; background: #b2bdc9; box-shadow: 0 0 0 2px rgba(5, 36, 79, .18); }
	.about-page-body .marketing-desk::after { content: ""; position: absolute; left: 9%; top: 100%; width: 5px; height: 43px; background: #05244f; box-shadow: 240px 0 #05244f; }
	.about-page-body .marketing-chair { position: absolute; z-index: 5; left: 37%; bottom: 8%; width: 11%; height: 23%; border-left: 9px solid rgba(5, 36, 79, .72); border-bottom: 9px solid rgba(5, 36, 79, .72); border-radius: 18px 0 0 12px; transform: rotate(-8deg); }
	.about-page-body .about-person.person-one { width: 92px; height: 182px; left: 40%; bottom: 0; transform: scale(.9); transform-origin: bottom center; }
	.about-page-body .about-person.person-one i { left: 31px; width: 31px; height: 31px; background: #f1b58d; border: 3px solid #05244f; box-shadow: inset 8px 0 rgba(110, 53, 35, .16); }
	.about-page-body .about-person.person-one b { left: 20px; top: 30px; width: 56px; height: 68px; border: 3px solid #05244f; background: #e9edf2; clip-path: polygon(18% 0, 82% 0, 100% 100%, 0 100%); box-shadow: inset 0 -22px #c9d3df; }
	.about-page-body .about-person.person-one b::before { left: 5px; bottom: -54px; width: 30px; height: 56px; border-left: 10px solid #152c4d; border-bottom: 7px solid #152c4d; border-radius: 0 0 0 18px; transform: skewX(-20deg) rotate(-8deg); }
	.about-page-body .about-person.person-one b::after { right: -31px; top: 18px; width: 42px; height: 7px; background: #f1b58d; border: 2px solid #05244f; border-radius: 7px; transform: rotate(21deg); }
	.about-page-body .about-person.person-one::after { left: -3px; top: 48px; width: 42px; height: 7px; background: #f1b58d; border: 2px solid #05244f; border-radius: 7px; transform: rotate(18deg); }
	.about-page-body .about-person.person-one::before { left: 4px; top: 30px; width: 84px; height: 92px; border-width: 8px; border-bottom: 0; border-radius: 28px 28px 0 0; }
	.about-page-body .marketing-desk { left: 24%; right: 8%; bottom: 28%; height: 7%; z-index: 8; }
	.about-page-body .marketing-desk::after { height: 65px; }
	.about-page-body .marketing-chair { left: 34%; bottom: 4%; width: 18%; height: 32%; z-index: 4; }
	.about-page-body .marketing-dashboard { opacity: .72; transition: opacity .5s ease; }
	.about-page-body .about-visual.dashboard-live .marketing-dashboard { opacity: 1; }
	.about-page-body .marketing-dashboard .marketing-data-row, .about-page-body .marketing-dashboard .marketing-progress { opacity: 0; transition: opacity .45s ease; }
	.about-page-body .about-visual.dashboard-data-live .marketing-dashboard .marketing-data-row, .about-page-body .about-visual.dashboard-data-live .marketing-dashboard .marketing-progress { opacity: 1; }
	.about-page-body .about-visual.campaign-success .marketing-notification:last-child { border-color: #139447; color: #b9ffcf; }
	.about-page-body .about-visual.dashboard-live .marketing-dashboard .marketing-data-row i, .about-page-body .about-visual.dashboard-live .marketing-progress span, .about-page-body .about-visual.dashboard-live .chart i, .about-page-body .about-visual.dashboard-live .mini-chart i { animation-play-state: running; }
	.about-page-body .about-visual.dashboard-live .marketing-dashboard { animation: marketing-dashboard-boot .8s cubic-bezier(.16, 1, .3, 1) both; }
	.about-page-body .about-visual.dashboard-live .about-person { animation: marketing-person-arrive .9s cubic-bezier(.16, 1, .3, 1) both, person-bob 3.5s .9s ease-in-out infinite; }
	.about-page-body .about-gear { text-shadow: 0 0 12px #f7c934; }
	.about-page-body .about-orbit { box-shadow: 0 0 16px rgba(57, 223, 255, .45); animation: marketing-orbit 7s linear infinite; }
	.marketing-dashboard { position: absolute; z-index: 5; inset: 23% 8% 8%; padding: 5%; background: rgba(5, 36, 79, .08); font-family: var(--mono); }
	.marketing-dashboard-head { display: flex; justify-content: space-between; color: #05244f; font-size: 7px; font-weight: 700; }
	.marketing-live { color: #139447; }
	.marketing-metrics { display: grid; grid-template-columns: repeat(3, 1fr); gap: 3%; margin-top: 8%; }
	.marketing-metric { padding: 5% 4%; border: 1px solid rgba(8, 123, 220, .24); background: rgba(255,255,255,.72); color: #52617b; font-size: 6px; }
	.marketing-metric strong { display: block; margin-top: 3px; color: #087bdc; font-size: 8px; }
	.marketing-metric:nth-child(2) strong { color: #e15b4f; }
	.marketing-metric:nth-child(3) strong { color: #139447; }
	.marketing-data-row { position: relative; display: flex; align-items: end; justify-content: space-between; gap: 5%; height: 32%; margin-top: 7%; border-bottom: 1px solid #05244f; background: linear-gradient(170deg, transparent 48%, rgba(57, 223, 255, .28) 49%, transparent 50%); }
	.marketing-data-row::before { content: "LEADS +126     CAMPAIGN 92%"; position: absolute; top: -13px; left: 0; color: #52617b; font: 6px var(--mono); white-space: nowrap; }
	.marketing-data-row i { flex: 1; height: 28%; background: #39dfff; transform-origin: bottom; animation: marketing-chart-rise 3s ease-in-out infinite; }
	.marketing-data-row i:nth-child(2) { height: 46%; animation-delay: .18s; background: #8b5cf6; }
	.marketing-data-row i:nth-child(3) { height: 39%; animation-delay: .36s; }
	.marketing-data-row i:nth-child(4) { height: 68%; animation-delay: .54s; background: #8b5cf6; }
	.marketing-data-row i:nth-child(5) { height: 88%; animation-delay: .72s; }
	.marketing-channels { display: grid; gap: 3px; margin-top: 7%; color: #52617b; font-size: 6px; }
	.marketing-channel { display: flex; align-items: center; justify-content: space-between; padding: 3px; background: rgba(255,255,255,.6); }
	.marketing-channel b { display: inline-grid; width: 12px; height: 12px; margin-right: 3px; place-items: center; border-radius: 3px; background: #05244f; color: #fff; font-size: 7px; }
	.marketing-channel:nth-child(1) b { background: #111; color: #39dfff; }
	.marketing-channel:nth-child(2) b { background: #dd2a7b; }
	.marketing-channel:nth-child(3) b { background: #1877f2; }
	.marketing-channel:nth-child(4) b { background: #ff0033; }
	.marketing-summary { display: grid; grid-template-columns: 1fr 1fr; gap: 4%; margin-top: 5%; color: #52617b; font-size: 6px; }
	.marketing-summary strong { color: #087bdc; font-size: 8px; }
	.marketing-progress { height: 4px; margin-top: 6%; background: rgba(5,36,79,.14); }
	.marketing-progress span { display: block; width: 0; height: 100%; background: linear-gradient(90deg, #39dfff, #8b5cf6); animation: marketing-progress 6s ease-out infinite; }
	.marketing-notification { position: absolute; z-index: 9; right: 7%; top: 35%; padding: 6px 8px; border-left: 2px solid #39dfff; background: rgba(5, 11, 24, .84); color: #dffcff; font: 8px var(--mono); box-shadow: 0 0 15px rgba(57, 223, 255, .28); animation: marketing-notify 5s ease-in-out infinite; }
	.marketing-notification:nth-of-type(2) { top: 42%; animation-delay: 2.4s; }
	@keyframes marketing-status { 50% { opacity: .55; box-shadow: 0 0 24px rgba(57, 223, 255, .5); } }
	@keyframes marketing-scan { 0% { transform: translateX(0) skewX(-18deg); opacity: 0; } 15%, 75% { opacity: 1; } 100% { transform: translateX(720%) skewX(-18deg); opacity: 0; } }
	@keyframes marketing-chart-rise { 0%, 100% { transform: scaleY(.72); filter: brightness(1); } 50% { transform: scaleY(1); filter: brightness(1.5) drop-shadow(0 0 5px #39dfff); } }
	@keyframes marketing-dashboard-boot { 0% { opacity: .2; filter: brightness(.45) blur(2px); } 45% { opacity: 1; filter: brightness(1.65) blur(0); } 100% { opacity: 1; filter: brightness(1); } }
	@keyframes marketing-person-arrive { 0% { opacity: 0; translate: 0 18px; } 100% { opacity: 1; translate: 0 0; } }
	@keyframes marketing-reach { 0%, 45% { content: "+84%"; } 46%, 75% { content: "+102%"; } 76%, 100% { content: "+120%"; } }
	@keyframes marketing-head { 0%, 42%, 100% { rotate: 0deg; } 48%, 58% { rotate: -4deg; } 63%, 72% { rotate: 3deg; } }
	@keyframes marketing-typing { 0%, 100% { rotate: -8deg; translate: 0 0; } 50% { rotate: 12deg; translate: 3px 2px; } }
	@keyframes marketing-orbit { to { rotate: 360deg; } }
	@keyframes marketing-progress { 0%, 20% { width: 0; } 80%, 100% { width: 100%; } }
	@keyframes marketing-notify { 0%, 12%, 100% { opacity: 0; translate: 12px 0; } 20%, 48% { opacity: 1; translate: 0 0; } 58% { opacity: 0; translate: -5px 0; } }
	@media (max-width: 600px) { .marketing-notification { right: 4%; font-size: 7px; } .about-page-body .about-visual::before { left: 12px; font-size: 8px; } .about-page-body .marketing-desk::after { box-shadow: 145px 0 #05244f; } .about-page-body .about-person.person-one { left: 37%; transform: scale(.72); } .about-page-body .marketing-desk { left: 17%; right: 4%; } }
	@media (prefers-reduced-motion: reduce) { .about-page-body .about-visual::before, .about-page-body .about-screen::after, .about-page-body .laptop-screen::after, .marketing-notification, .about-page-body .about-person i, .about-page-body .about-person b::after { animation: none; } }
	.about-book { position: relative; perspective: 1800px; transform-style: preserve-3d; }
	.about-book::before { content: ""; position: absolute; z-index: 10; inset: 0; background: linear-gradient(105deg, #fff 0%, #eef4f8 48%, rgba(8, 123, 220, .1) 100%); box-shadow: 14px 0 30px rgba(6, 27, 69, .2); transform-origin: left center; pointer-events: none; animation: about-page-turn 1.35s cubic-bezier(.22, .75, .22, 1) both; }
	.about-book::after { content: ""; position: absolute; z-index: 9; top: 0; bottom: 0; left: 0; width: 2px; background: linear-gradient(180deg, transparent, #f7c934 15%, #087bdc 50%, transparent 85%); opacity: .7; pointer-events: none; }
	@keyframes about-page-turn { 0% { opacity: 1; transform: rotateY(0deg); } 65% { opacity: 1; } 100% { opacity: 0; transform: rotateY(-105deg); visibility: hidden; } }
	@media (prefers-reduced-motion: reduce) { .about-book::before { animation: none; opacity: 0; visibility: hidden; } }
	.about-profile { display: grid; grid-template-columns: 1.15fr .85fr; gap: 22px; margin-top: 28px; }
	.about-panel { padding: 28px; border: 1px solid rgba(6, 27, 69, .28); background: #fff; box-shadow: 0 10px 26px rgba(6, 27, 69, .16); }
	.about-panel h2 { margin: 0 0 14px; color: #061b45; font-size: clamp(28px, 4vw, 44px); line-height: .95; }
	.about-panel--vision h2 em { color: #087bdc; }
	.about-panel p { margin: 0; color: #263d61; font-size: 13px; line-height: 1.65; }
	.about-panel--vision { border-top: 4px solid #f7c934; }
	.about-mission { display: grid; gap: 10px; margin: 0; padding: 0; list-style: none; counter-reset: mission; }
	.about-mission li { display: grid; grid-template-columns: 30px 1fr; gap: 10px; color: #263d61; font-size: 12px; line-height: 1.45; counter-increment: mission; }
	.about-mission li::before { content: counter(mission, decimal-leading-zero); color: #087bdc; font: 11px var(--mono); }
	.about-services { margin-top: 70px; }
	.about-services-intro { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; align-items: end; margin-bottom: 26px; }
	.about-services-intro h2 { margin: 0; color: #061b45; font-size: clamp(30px, 4vw, 48px); line-height: .95; }
	.about-services-intro h2 { color: #f7f9fc; }
	.about-services-intro h2 em { color: #67e8f9; }
	.about-services-intro p { max-width: 520px; margin: 0; color: #61708d; font-size: 13px; line-height: 1.6; }
	.about-service-grid { position: relative; width: min(100%, 820px); min-height: 430px; margin: 0 auto; perspective: 1400px; touch-action: pan-y; }
	.about-service-grid::before { content: "GASS / DIGITAL CATALOG"; position: absolute; z-index: 0; inset: 12px 20px 0; display: grid; place-items: center; border: 1px solid rgba(57, 223, 255, .28); background: radial-gradient(circle at 50% 45%, rgba(139, 92, 246, .2), transparent 45%), #071329; box-shadow: inset 0 0 35px rgba(57, 223, 255, .08), 0 20px 35px rgba(0, 0, 0, .25); color: rgba(57, 223, 255, .62); font: 11px var(--mono); letter-spacing: .12em; }
	.about-service-grid::after { content: ""; position: absolute; z-index: 5; top: 12px; bottom: 0; left: 20px; width: 5px; background: linear-gradient(180deg, #39dfff, #8b5cf6, transparent); box-shadow: 0 0 18px #39dfff; pointer-events: none; }
	.about-service-card { position: absolute; z-index: 1; inset: 12px 20px 0; padding: 34px 32px; border-top: 4px solid var(--service-color); background: linear-gradient(105deg, #fff 0%, #fff 72%, #f0f6fa 100%); box-shadow: 12px 14px 28px rgba(6, 27, 69, .22); transform-origin: left center; transform-style: preserve-3d; opacity: 0; visibility: hidden; transform: rotateY(92deg) translateX(-10px); backface-visibility: hidden; will-change: transform, opacity, filter; transition: transform .9s cubic-bezier(.16, .84, .24, 1), opacity .45s ease, box-shadow .45s ease; }
	.about-service-card::before { content: ""; position: absolute; z-index: 2; inset: 0; background: linear-gradient(105deg, transparent 28%, rgba(255, 255, 255, .72) 45%, transparent 62%); opacity: 0; pointer-events: none; }
	.about-service-grid:not(.is-ready) .about-service-card:first-child { opacity: 1; visibility: visible; transform: none; }
	.about-service-card::after { content: ""; position: absolute; z-index: -1; inset: 0 -8px 0 auto; width: 8px; background: linear-gradient(90deg, rgba(6, 27, 69, .14), rgba(57, 223, 255, .62)); transform: translateZ(-8px); }
	.about-service-card.is-active { z-index: 3; opacity: 1; visibility: visible; transform: rotateY(0deg) translateX(0); }
	.about-service-card.is-turning { z-index: 4; opacity: 0; visibility: visible; box-shadow: -15px 15px 35px rgba(6, 27, 69, .3); }
	.about-service-grid.is-flipping-next .about-service-card.is-turning { animation: about-page-fold-next .9s cubic-bezier(.16, .84, .24, 1) both; }
	.about-service-grid.is-flipping-prev .about-service-card.is-turning { animation: about-page-fold-prev .9s cubic-bezier(.16, .84, .24, 1) both; }
	.about-service-grid.is-flipping-next .about-service-card.is-active { animation: about-page-enter-next .9s cubic-bezier(.16, .84, .24, 1) both; }
	.about-service-grid.is-flipping-prev .about-service-card.is-active { animation: about-page-enter-prev .9s cubic-bezier(.16, .84, .24, 1) both; }
	@keyframes about-page-fold-next { 0% { opacity: 1; transform: rotateY(0) rotateX(0) translateZ(0) scaleX(1); box-shadow: 12px 14px 28px rgba(6, 27, 69, .22); } 20% { transform: rotateY(-16deg) rotateX(3deg) translateZ(13px) scaleX(.99); box-shadow: 4px 18px 30px rgba(6, 27, 69, .3); } 100% { opacity: 0; transform: rotateY(-108deg) rotateX(0) translateZ(0) translateX(-12px) scaleX(.96); box-shadow: -15px 15px 35px rgba(6, 27, 69, .08); } }
	@keyframes about-page-fold-prev { 0% { opacity: 1; transform: rotateY(0) rotateX(0) translateZ(0) scaleX(1); box-shadow: 12px 14px 28px rgba(6, 27, 69, .22); } 20% { transform: rotateY(16deg) rotateX(3deg) translateZ(13px) scaleX(.99); box-shadow: 4px 18px 30px rgba(6, 27, 69, .3); } 100% { opacity: 0; transform: rotateY(108deg) rotateX(0) translateZ(0) translateX(12px) scaleX(.96); box-shadow: 15px 15px 35px rgba(6, 27, 69, .08); } }
	@keyframes about-page-enter-next { 0% { opacity: 0; transform: rotateY(92deg) rotateX(-2deg) translateX(-10px) scaleX(.96); } 24% { opacity: 1; transform: rotateY(15deg) rotateX(2deg) translateZ(8px) scaleX(.99); } 100% { opacity: 1; transform: rotateY(0) rotateX(0) translateZ(0) scaleX(1); } }
	@keyframes about-page-enter-prev { 0% { opacity: 0; transform: rotateY(-92deg) rotateX(-2deg) translateX(10px) scaleX(.96); } 24% { opacity: 1; transform: rotateY(-15deg) rotateX(2deg) translateZ(8px) scaleX(.99); } 100% { opacity: 1; transform: rotateY(0) rotateX(0) translateZ(0) scaleX(1); } }
	.about-service-card:hover { box-shadow: 16px 18px 34px rgba(6, 27, 69, .28); }
	.about-service-book.is-opening .about-service-card.is-active { animation: about-book-page-open .95s cubic-bezier(.22, .75, .22, 1) both; }
	@keyframes about-book-page-open { 0% { transform: rotateY(-70deg) translateX(-22px); opacity: 0; } 72% { transform: rotateY(6deg); opacity: 1; } 100% { transform: rotateY(0); opacity: 1; } }
	.about-service-hint { margin: 14px 0 0; color: #8fa6c4; font: 10px var(--mono); letter-spacing: .04em; text-align: center; }
	.about-service-hint span { color: #39dfff; }
	.about-service-controls { display: flex; justify-content: center; gap: 8px; margin-top: 12px; }
	.about-service-control { display: grid; width: 36px; height: 30px; place-items: center; border: 1px solid rgba(57, 223, 255, .45); background: rgba(7, 19, 41, .72); color: #39dfff; font-size: 20px; line-height: 1; cursor: pointer; }
	.about-service-control:hover, .about-service-control:focus-visible { border-color: #fff; background: #39dfff; color: #071329; outline: none; }
	@media (max-width: 600px) { .about-service-grid { min-height: 390px; } .about-service-card { inset: 12px 10px 0; padding: 26px 22px; } .about-service-grid::before { inset: 12px 10px 0; } .about-service-grid::after { left: 10px; } }
	@media (prefers-reduced-motion: reduce) { .about-service-card, .about-service-card.is-turning, .about-service-book.is-opening .about-service-card.is-active { animation: none; transition: none; transform: none; } }
	.about-service-card:nth-child(1) { --service-color: #087bdc; }
	.about-service-card:nth-child(2) { --service-color: #e15b4f; }
	.about-service-card:nth-child(3) { --service-color: #139447; }
	.about-service-card span { color: var(--service-color); font: 11px var(--mono); }
	.about-service-card h3 { margin: 20px 0 12px; color: #061b45; font-size: 16px; }
	.about-service-card ul { display: grid; gap: 8px; margin: 0; padding: 0; list-style: none; }
	.about-service-card li { color: #52617b; font-size: 11px; line-height: 1.35; }
	.about-service-card li::before { content: "✓"; margin-right: 6px; color: var(--service-color); font-weight: 700; }
	.about-values { margin-top: 70px; padding: 28px; background: #061b45; color: #fff; }
	.about-values h2 { margin: 0 0 22px; font-size: clamp(28px, 4vw, 44px); line-height: .95; }
	.about-value-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 18px; }
	.about-value-grid strong { display: block; margin-bottom: 7px; color: #67e8f9; font-size: 13px; }
	.about-value-grid p { margin: 0; color: #b9c9dc; font-size: 11px; line-height: 1.45; }
	@media (max-width: 800px) { .about-profile, .about-services-intro { grid-template-columns: 1fr; } .about-service-grid { grid-template-columns: 1fr; } .about-value-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
	@media (max-width: 430px) { .about-panel, .about-values { padding: 20px; } .about-value-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="about-book">
<section class="page-hero section-shell"><p class="eyebrow">01 / Tentang GASS</p><h1>Digital innovation<br><em>for future growth.</em></h1><p class="hero-intro">PT. Gass Digital Solutions adalah perusahaan layanan jasa digital kreatif dan teknologi yang berfokus pada pengembangan website profesional, konten visual modern, serta produksi video berbasis Artificial Intelligence (AI).</p></section>
<section class="about section-shell about-page"><div class="about-visual" aria-label="Ilustrasi profesional mengelola dashboard digital marketing interaktif"><div class="about-glow"></div><div class="about-screen screen-back"><div class="screen-bar"></div><div class="screen-avatar"></div><div class="screen-line line-one"></div><div class="screen-line line-two"></div><div class="screen-line line-three"></div></div><div class="about-chart-card"><span>REACH</span><strong>+84%</strong><div class="mini-chart"><i></i><i></i><i></i><i></i><i></i></div></div><div class="about-laptop"><div class="laptop-screen"><div class="screen-bar"></div><div class="marketing-dashboard"><div class="marketing-dashboard-head"><span>CAMPAIGN OVERVIEW</span><span class="marketing-live">LIVE</span></div><div class="marketing-metrics"><div class="marketing-metric">TRAFFIC<strong>↑ 84%</strong></div><div class="marketing-metric">ENGAGE<strong>↑ 72%</strong></div><div class="marketing-metric">CONVERT<strong>↑ 48%</strong></div></div><div class="marketing-data-row"><i></i><i></i><i></i><i></i><i></i></div><div class="marketing-channels"><div class="marketing-channel"><span><b>♪</b>TikTok</span><strong>+32%</strong></div><div class="marketing-channel"><span><b>◎</b>Instagram</span><strong>+28%</strong></div><div class="marketing-channel"><span><b>f</b>Facebook</span><strong>+19%</strong></div><div class="marketing-channel"><span><b>▶</b>YouTube</span><strong>+41%</strong></div></div><div class="marketing-progress"><span></span></div></div><div class="chart"><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="screen-line line-one"></div><div class="screen-line line-two"></div></div><div class="laptop-base"></div></div><div class="about-phone"><div class="phone-notch"></div><div class="phone-line"></div><div class="phone-line"></div><div class="phone-line short"></div></div><div class="about-card card-check">✦ <span>strategy<br>in motion</span></div><div class="about-person person-one"><i></i><b></b></div><div class="about-gear">⚙</div><div class="about-orbit"></div><div class="marketing-notification">NEW LEAD +1</div><div class="marketing-notification">TRAFFIC INCREASED</div></div><div class="about-copy"><p class="eyebrow">Tentang Kami</p><h2>Solusi digital<br><em>yang berdampak.</em></h2><p>Kami membantu perusahaan, brand, startup, dan berbagai organisasi membangun kehadiran digital yang kuat melalui solusi teknologi dan konten kreatif yang inovatif. Dengan menggabungkan kreativitas, teknologi terkini, dan strategi digital yang efektif, kami menghadirkan layanan untuk meningkatkan citra merek, memperluas jangkauan pasar, dan mendukung pertumbuhan bisnis di era digital.</p><a class="button button-dark" href="{{ route('contact') }}">Mulai kolaborasi <span>↗</span></a></div></section>
<section class="about-profile section-shell"><article class="about-panel about-panel--vision"><p class="eyebrow">Visi</p><h2>Menjadi partner<br><em>masa depan.</em></h2><p>Menjadi perusahaan solusi digital terdepan yang menghadirkan inovasi teknologi, kreativitas, dan kecerdasan buatan untuk mendukung transformasi digital bisnis di tingkat nasional maupun global.</p></article><article class="about-panel"><p class="eyebrow">Misi</p><ol class="about-mission"><li>Mengembangkan website profesional yang modern, responsif, aman, dan berorientasi pada pengalaman pengguna.</li><li>Menghasilkan konten foto dan desain carousel yang kreatif untuk memperkuat identitas dan komunikasi brand.</li><li>Menyediakan layanan produksi video berbasis Artificial Intelligence (AI) yang inovatif, efisien, dan berkualitas tinggi.</li><li>Memberikan solusi digital yang mengikuti perkembangan teknologi dan kebutuhan pasar.</li><li>Membangun hubungan kerja sama jangka panjang melalui pelayanan yang profesional, transparan, dan terpercaya.</li><li>Mendorong transformasi digital perusahaan melalui layanan yang berfokus pada kualitas dan hasil.</li></ol></article></section>
<section class="about-services about-service-book section-shell" data-about-book><div class="about-services-intro"><div><p class="eyebrow">Layanan Kami</p><h2>Dari ide menjadi<br><em>aksi digital.</em></h2></div><p>Kami menggabungkan kreativitas, teknologi terkini, dan strategi digital yang efektif untuk mendukung pertumbuhan bisnis.</p></div><div class="about-service-grid" data-about-book-pages><article class="about-service-card"><span>01 / WEBSITE</span><h3>Pengembangan Website</h3><ul><li>Website company profile</li><li>Website corporate</li><li>Landing page profesional</li><li>Website e-commerce</li><li>Sistem dan website kustom</li></ul></article><article class="about-service-card"><span>02 / VISUAL</span><h3>Konten Foto & Carousel</h3><ul><li>Konten branding</li><li>Konten promosi produk dan jasa</li><li>Desain media sosial profesional</li><li>Konten edukasi dan informasi</li><li>Carousel marketing</li></ul></article><article class="about-service-card"><span>03 / AI VIDEO</span><h3>Video AI</h3><ul><li>Video promosi AI</li><li>Video company profile AI</li><li>Video branding AI</li><li>Video produk dan layanan AI</li><li>Video motion graphic AI</li></ul></article></div><div class="about-service-controls"><button class="about-service-control" type="button" data-about-book-prev aria-label="Halaman sebelumnya">‹</button><button class="about-service-control" type="button" data-about-book-next aria-label="Halaman berikutnya">›</button></div><p class="about-service-hint"><span>SCROLL</span> atau geser untuk membalik halaman <span>01 / 03</span></p></section>
<section class="about-values section-shell"><p class="eyebrow">Nilai Perusahaan</p><h2>Prinsip yang menjaga<br><em>setiap langkah.</em></h2><div class="about-value-grid"><div><strong>Inovasi</strong><p>Mengadopsi teknologi terbaru untuk menciptakan solusi digital yang relevan dan bernilai.</p></div><div><strong>Profesionalisme</strong><p>Menjalankan setiap proyek dengan standar kualitas tinggi dan komitmen penuh terhadap kepuasan klien.</p></div><div><strong>Kreativitas</strong><p>Menghasilkan karya digital yang unik, menarik, dan mampu memberikan dampak positif bagi brand.</p></div><div><strong>Integritas</strong><p>Menjunjung tinggi kepercayaan, transparansi, dan tanggung jawab dalam setiap kerja sama.</p></div><div><strong>Berorientasi Hasil</strong><p>Fokus pada pencapaian tujuan bisnis klien melalui strategi dan solusi digital yang efektif.</p></div></div></section>
<section class="manifesto section-shell"><div class="manifesto-stamp"><img src="{{ asset('LOGO GASS BULAT.png') }}" alt="GASS Your Way"></div><div class="manifesto-copy"><p class="eyebrow">Our belief</p><h2>Digital innovation<br>for <em>future growth.</em></h2></div><div class="manifesto-text"><p>PT. Gass Digital Solutions<br><em>Empowering Brands Through Digital Innovation & Artificial Intelligence.</em></p></div></section>
</div>
@endsection
