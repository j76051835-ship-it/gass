@extends('layouts.app')

@section('title', 'Kontak — GASS')

@section('body_class', 'contact-page-body')

@push('styles')
<style>
	.contact-page-body .contact-page { min-height: 650px; padding-top: 95px; }
	.contact-page-body .contact-side { padding: 34px; }
</style>
@endpush

@section('content')
<section class="contact contact-page section-shell"><div><p class="eyebrow">04 / Kontak GASS</p><h1>Siap untuk<br><em>mulai bergerak?</em></h1><p class="hero-intro">Ceritakan sedikit tentang bisnismu. Kami akan kembali dengan ide dan langkah pertama yang paling masuk akal.</p></div><div class="contact-side"><p>Hubungi kami melalui WhatsApp untuk konsultasi awal dan ceritakan tantangan digital yang sedang kamu hadapi.</p><a class="button button-dark" href="https://wa.me/6285890007359" target="_blank" rel="noreferrer">Ngobrol via WhatsApp <span>↗</span></a><p class="contact-email">atau email kami di <a href="mailto:gassdigitalsoultions@gmail.com">gassdigitalsoultions@gmail.com</a></p><div class="contact-detail"><span>WHATSAPP</span><strong>+62 858-9000-7359</strong></div><div class="contact-detail"><span>EMAIL</span><strong>gassdigitalsoultions@gmail.com</strong></div><div class="contact-detail"><span>AREA LAYANAN</span><strong>Indonesia · Remote friendly</strong></div></div></section>
<section class="contact-band"><div class="section-shell"><p class="eyebrow">A good conversation starts small</p><h2>GASS bareng,<br><em>tumbuh bareng.</em></h2></div></section>
@include('partials.reviews')
@endsection
