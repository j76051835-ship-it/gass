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
	@media (max-width: 800px) { .contact-page-body .contact-page { gap: 28px; } .contact-page-body .contact-side { padding: 24px; } }
</style>
@endpush

@section('content')
<section class="contact contact-page section-shell"><div><p class="eyebrow">04 / Kontak GASS</p><h1>Siap untuk<br><em>mulai bergerak?</em></h1><p class="hero-intro">Ceritakan sedikit tentang bisnismu. Kami akan kembali dengan ide dan langkah pertama yang paling masuk akal.</p></div><div class="contact-side"><p>Hubungi kami melalui WhatsApp untuk konsultasi awal dan ceritakan tantangan digital yang sedang kamu hadapi.</p><a class="button button-dark" href="https://wa.me/6285890007359" target="_blank" rel="noreferrer">Ngobrol via WhatsApp <span>↗</span></a><p class="contact-email">atau email kami di <a href="mailto:gassdigitalsoultions@gmail.com">gassdigitalsoultions@gmail.com</a></p><div class="contact-detail"><span>WHATSAPP</span><strong>+62 858-9000-7359</strong></div><div class="contact-detail"><span>EMAIL</span><strong>gassdigitalsoultions@gmail.com</strong></div><div class="contact-detail"><span>AREA LAYANAN</span><strong>Indonesia · Remote friendly</strong></div></div></section>
<section class="contact-band"><div class="section-shell"><p class="eyebrow">A good conversation starts small</p><h2>GASS bareng,<br><em>tumbuh bareng.</em></h2></div></section>
@include('partials.reviews')
@endsection
