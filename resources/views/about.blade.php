@extends('layouts.app')

@section('title', 'Tentang Kami — GASS')

@section('body_class', 'about-page-body')

@push('styles')
<style>
	.about-page-body .about-copy { padding-left: 12px; }
	.about-page-body .manifesto { background: rgba(8, 123, 220, 0.06); }
</style>
@endpush

@section('content')
<section class="page-hero section-shell"><p class="eyebrow">01 / Tentang GASS</p><h1>Partner digital<br><em>untuk langkah besar.</em></h1><p class="hero-intro">GASS adalah growth partner yang menggabungkan strategi, kreativitas, dan teknologi untuk membantu bisnis bergerak lebih percaya diri.</p></section>
<section class="about section-shell about-page"><div class="about-visual" aria-label="Ilustrasi tim GASS mengembangkan solusi digital"><div class="about-glow"></div><div class="about-screen screen-back"><div class="screen-bar"></div><div class="screen-avatar"></div><div class="screen-line line-one"></div><div class="screen-line line-two"></div><div class="screen-line line-three"></div></div><div class="about-chart-card"><span>REACH</span><strong>+84%</strong><div class="mini-chart"><i></i><i></i><i></i><i></i><i></i></div></div><div class="about-laptop"><div class="laptop-screen"><div class="screen-bar"></div><div class="chart"><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="screen-line line-one"></div><div class="screen-line line-two"></div></div><div class="laptop-base"></div></div><div class="about-phone"><div class="phone-notch"></div><div class="phone-line"></div><div class="phone-line"></div><div class="phone-line short"></div></div><div class="about-card card-check">✦ <span>strategy<br>in motion</span></div><div class="about-person person-one"><i></i><b></b></div><div class="about-person person-two"><i></i><b></b></div><div class="about-gear">⚙</div><div class="about-orbit"></div></div><div class="about-copy"><p class="eyebrow">Cara kami bekerja</p><h2>Ide yang jelas.<br><em>Aksi yang nyata.</em></h2><p>Kami percaya pertumbuhan yang sehat dimulai dari strategi yang tepat. Setiap kolaborasi kami bangun dari pemahaman, komunikasi terbuka, dan eksekusi yang konsisten.</p><a class="button button-dark" href="{{ route('contact') }}">Mulai kolaborasi <span>↗</span></a></div></section>
<section class="manifesto section-shell"><div class="manifesto-stamp"><img src="{{ asset('LOGO GASS BULAT.png') }}" alt="GASS Your Way"></div><div class="manifesto-copy"><p class="eyebrow">Our belief</p><h2>Ambisi besar<br>butuh <em>arah.</em></h2></div><div class="manifesto-text"><p>Kami hadir untuk menjadikan ide yang baik lebih berani, lebih relevan, dan lebih dekat dengan hasil.</p></div></section>
@endsection
