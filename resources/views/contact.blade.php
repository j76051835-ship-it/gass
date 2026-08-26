@extends('layouts.app')

@section('title', 'Kontak — GASS')

@section('body_class', 'contact-page-body')

@push('styles')
<style>
	.contact-page-body .contact-page { min-height: 650px; padding-top: 95px; }
	.contact-page-body .contact-page { gap: 8%; }
	.contact-page-body .contact-page > div:first-child { padding: 34px 0; }
	.contact-page-body .contact-side { padding: 34px; border: 1px solid rgba(103,232,249,.42); border-radius: 12px; background: rgba(5,11,24,.88); box-shadow: 12px 12px 0 rgba(34,211,238,.16), 0 18px 40px rgba(2,11,31,.28); color: #f7f9fc; }
	.contact-page-body .contact-side > p { color: #f7f9fc; }
	.contact-page-body .contact-side .button { display: inline-flex; background: #087bdc; color: #fff; box-shadow: 5px 5px 0 #67e8f9; }
	.contact-page-body .contact-side .button:hover { background: #22d3ee; color: #050b18; }
	.contact-page-body .contact-email { color: #dbe4ef; }
	.contact-page-body .contact-email a { color: #67e8f9; }
	.contact-page-body .contact-detail { border-top-color: rgba(103,232,249,.26); }
	.contact-page-body .contact-detail span { color: #67e8f9; }
	.contact-page-body .contact-detail strong { color: #f7f9fc; }
	.contact-page-body .contact-side { position: relative; overflow: hidden; }
	.contact-page-body .contact-side:before { content: ""; position: absolute; top: -90px; right: -70px; width: 220px; height: 220px; border: 1px solid rgba(103,232,249,.24); border-radius: 50%; box-shadow: 0 0 0 18px rgba(103,232,249,.04), 0 0 0 38px rgba(103,232,249,.025); pointer-events: none; }
	.contact-social-heading { position: relative; margin: 34px 0 12px; color: #67e8f9; font: 10px var(--mono); letter-spacing: .08em; text-transform: uppercase; }
	.contact-socials { position: relative; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
	.contact-social { display: flex; min-height: 86px; flex-direction: column; justify-content: space-between; padding: 12px; border: 1px solid rgba(255,255,255,.18); color: #fff; text-decoration: none; transition: transform .2s ease, border-color .2s ease, background .2s ease; }
	.contact-social:hover, .contact-social:focus-visible { transform: translateY(-5px); border-color: #fff; outline: none; }
	.contact-social.instagram { background: linear-gradient(145deg, rgba(225,48,108,.92), rgba(131,58,180,.88)); }
	.contact-social.tiktok { background: linear-gradient(145deg, rgba(37,244,238,.82), rgba(5,11,24,.92) 48%, rgba(254,44,85,.82)); }
	.contact-social.whatsapp { background: #139447; }
	.contact-social svg { width: 23px; height: 23px; fill: currentColor; }
	.contact-social span { display: flex; align-items: center; justify-content: space-between; gap: 5px; font-size: 10px; font-weight: 700; }
	.contact-social span small { color: rgba(255,255,255,.78); font: 9px var(--mono); }
	.contact-page-body .contact-email { position: relative; }
	@media (max-width: 800px) { .contact-page-body .contact-page { gap: 28px; } .contact-page-body .contact-side { padding: 24px; } }
	@media (max-width: 430px) { .contact-socials { grid-template-columns: 1fr; } .contact-social { min-height: 64px; flex-direction: row; align-items: center; } }
</style>
@endpush

@section('content')
<section class="contact contact-page section-shell"><div><p class="eyebrow">04 / Kontak GASS</p><h1>Siap untuk<br><em>mulai bergerak?</em></h1><p class="hero-intro">Ceritakan sedikit tentang bisnismu. Kami akan kembali dengan ide dan langkah pertama yang paling masuk akal.</p></div><div class="contact-side"><p>Hubungi kami melalui WhatsApp untuk konsultasi awal dan ceritakan tantangan digital yang sedang kamu hadapi.</p><a class="button button-dark" href="https://wa.me/6285890007359" target="_blank" rel="noreferrer">Ngobrol via WhatsApp <span>↗</span></a><p class="contact-email">atau email kami di <a href="mailto:gassdigitalsoultions@gmail.com">gassdigitalsoultions@gmail.com</a></p><p class="contact-social-heading">Temukan GASS di kanal digital</p><div class="contact-socials"><a class="contact-social instagram" href="https://www.instagram.com/gass.generations/" target="_blank" rel="noreferrer" aria-label="GASS di Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.25-3.25a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5Z"/></svg><span>Instagram <small>↗</small></span></a><a class="contact-social tiktok" href="https://www.tiktok.com/@gass.generations" target="_blank" rel="noreferrer" aria-label="GASS di TikTok"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M16.6 3c.3 2.2 1.5 3.5 3.4 3.6v2.8c-1.5.1-2.8-.4-3.9-1.2v6.5a5.3 5.3 0 1 1-5.3-5.3c.3 0 .7 0 1 .1v2.9a2.4 2.4 0 1 0 1.5 2.3V3h3.3Z"/></svg><span>TikTok <small>↗</small></span></a><a class="contact-social whatsapp" href="https://wa.me/6285890007359" target="_blank" rel="noreferrer" aria-label="GASS di WhatsApp"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a9.9 9.9 0 0 0-8.5 15L2 22l5.2-1.4A10 10 0 1 0 12 2Zm0 2a8 8 0 0 1 6.9 12l-.3.5.3 2.1-2-.5-.5.3A8 8 0 1 1 12 4Zm-3 3.7c-.2 0-.5.1-.7.4-.3.3-1 1-1 2.4 0 1.4 1 2.8 1.2 3 .2.2 2 3.2 5 4.3 2.4.9 2.9.7 3.4.6.5-.1 1.6-.7 1.8-1.3.2-.6.2-1.1.1-1.2-.1-.1-.3-.2-.7-.4-.4-.2-1.6-.8-1.9-.9-.3-.1-.5-.2-.7.2l-.8 1c-.2.2-.4.3-.7.1-1.2-.6-2-1.1-2.8-2.5-.2-.4.2-.4.5-1.1.1-.2 0-.4-.1-.6l-.8-2c-.2-.5-.4-.5-.6-.5Z"/></svg><span>WhatsApp <small>↗</small></span></a></div><div class="contact-detail"><span>WHATSAPP</span><strong>+62 858-9000-7359</strong></div><div class="contact-detail"><span>EMAIL</span><strong>gassdigitalsoultions@gmail.com</strong></div><div class="contact-detail"><span>AREA LAYANAN</span><strong>Indonesia · Remote friendly</strong></div></div></section>
<section class="contact-band"><div class="section-shell"><p class="eyebrow">A good conversation starts small</p><h2>GASS bareng,<br><em>tumbuh bareng.</em></h2></div></section>
@include('partials.reviews')
@endsection
