@extends('layouts.app')

@section('title', 'Tentang Kami — GASS')

@section('body_class', 'about-page-body')

@push('styles')
<style>
	.about-page-body .about-copy { padding-left: 12px; }
	.about-page-body .manifesto { background: rgba(8, 123, 220, 0.06); }
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
	.about-services-intro p { max-width: 520px; margin: 0; color: #61708d; font-size: 13px; line-height: 1.6; }
	.about-service-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
	.about-service-card { padding: 22px 18px; border-top: 4px solid var(--service-color); background: #fff; box-shadow: 0 8px 24px rgba(6, 27, 69, .07); }
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
<section class="page-hero section-shell"><p class="eyebrow">01 / Tentang GASS</p><h1>Digital innovation<br><em>for future growth.</em></h1><p class="hero-intro">PT. Gass Digital Solutions adalah perusahaan layanan jasa digital kreatif dan teknologi yang berfokus pada pengembangan website profesional, konten visual modern, serta produksi video berbasis Artificial Intelligence (AI).</p></section>
<section class="about section-shell about-page"><div class="about-visual" aria-label="Ilustrasi tim GASS mengembangkan solusi digital"><div class="about-glow"></div><div class="about-screen screen-back"><div class="screen-bar"></div><div class="screen-avatar"></div><div class="screen-line line-one"></div><div class="screen-line line-two"></div><div class="screen-line line-three"></div></div><div class="about-chart-card"><span>REACH</span><strong>+84%</strong><div class="mini-chart"><i></i><i></i><i></i><i></i><i></i></div></div><div class="about-laptop"><div class="laptop-screen"><div class="screen-bar"></div><div class="chart"><i></i><i></i><i></i><i></i><i></i><i></i></div><div class="screen-line line-one"></div><div class="screen-line line-two"></div></div><div class="laptop-base"></div></div><div class="about-phone"><div class="phone-notch"></div><div class="phone-line"></div><div class="phone-line"></div><div class="phone-line short"></div></div><div class="about-card card-check">✦ <span>strategy<br>in motion</span></div><div class="about-person person-one"><i></i><b></b></div><div class="about-person person-two"><i></i><b></b></div><div class="about-gear">⚙</div><div class="about-orbit"></div></div><div class="about-copy"><p class="eyebrow">Tentang Kami</p><h2>Solusi digital<br><em>yang berdampak.</em></h2><p>Kami membantu perusahaan, brand, startup, dan berbagai organisasi membangun kehadiran digital yang kuat melalui solusi teknologi dan konten kreatif yang inovatif. Dengan menggabungkan kreativitas, teknologi terkini, dan strategi digital yang efektif, kami menghadirkan layanan untuk meningkatkan citra merek, memperluas jangkauan pasar, dan mendukung pertumbuhan bisnis di era digital.</p><a class="button button-dark" href="{{ route('contact') }}">Mulai kolaborasi <span>↗</span></a></div></section>
<section class="about-profile section-shell"><article class="about-panel about-panel--vision"><p class="eyebrow">Visi</p><h2>Menjadi partner<br><em>masa depan.</em></h2><p>Menjadi perusahaan solusi digital terdepan yang menghadirkan inovasi teknologi, kreativitas, dan kecerdasan buatan untuk mendukung transformasi digital bisnis di tingkat nasional maupun global.</p></article><article class="about-panel"><p class="eyebrow">Misi</p><ol class="about-mission"><li>Mengembangkan website profesional yang modern, responsif, aman, dan berorientasi pada pengalaman pengguna.</li><li>Menghasilkan konten foto dan desain carousel yang kreatif untuk memperkuat identitas dan komunikasi brand.</li><li>Menyediakan layanan produksi video berbasis Artificial Intelligence (AI) yang inovatif, efisien, dan berkualitas tinggi.</li><li>Memberikan solusi digital yang mengikuti perkembangan teknologi dan kebutuhan pasar.</li><li>Membangun hubungan kerja sama jangka panjang melalui pelayanan yang profesional, transparan, dan terpercaya.</li><li>Mendorong transformasi digital perusahaan melalui layanan yang berfokus pada kualitas dan hasil.</li></ol></article></section>
<section class="about-services section-shell"><div class="about-services-intro"><div><p class="eyebrow">Layanan Kami</p><h2>Dari ide menjadi<br><em>aksi digital.</em></h2></div><p>Kami menggabungkan kreativitas, teknologi terkini, dan strategi digital yang efektif untuk mendukung pertumbuhan bisnis.</p></div><div class="about-service-grid"><article class="about-service-card"><span>01 / WEBSITE</span><h3>Pengembangan Website</h3><ul><li>Website company profile</li><li>Website corporate</li><li>Landing page profesional</li><li>Website e-commerce</li><li>Sistem dan website kustom</li></ul></article><article class="about-service-card"><span>02 / VISUAL</span><h3>Konten Foto & Carousel</h3><ul><li>Konten branding</li><li>Konten promosi produk dan jasa</li><li>Desain media sosial profesional</li><li>Konten edukasi dan informasi</li><li>Carousel marketing</li></ul></article><article class="about-service-card"><span>03 / AI VIDEO</span><h3>Video AI</h3><ul><li>Video promosi AI</li><li>Video company profile AI</li><li>Video branding AI</li><li>Video produk dan layanan AI</li><li>Video motion graphic AI</li></ul></article></div></section>
<section class="about-values section-shell"><p class="eyebrow">Nilai Perusahaan</p><h2>Prinsip yang menjaga<br><em>setiap langkah.</em></h2><div class="about-value-grid"><div><strong>Inovasi</strong><p>Mengadopsi teknologi terbaru untuk menciptakan solusi digital yang relevan dan bernilai.</p></div><div><strong>Profesionalisme</strong><p>Menjalankan setiap proyek dengan standar kualitas tinggi dan komitmen penuh terhadap kepuasan klien.</p></div><div><strong>Kreativitas</strong><p>Menghasilkan karya digital yang unik, menarik, dan mampu memberikan dampak positif bagi brand.</p></div><div><strong>Integritas</strong><p>Menjunjung tinggi kepercayaan, transparansi, dan tanggung jawab dalam setiap kerja sama.</p></div><div><strong>Berorientasi Hasil</strong><p>Fokus pada pencapaian tujuan bisnis klien melalui strategi dan solusi digital yang efektif.</p></div></div></section>
<section class="manifesto section-shell"><div class="manifesto-stamp"><img src="{{ asset('LOGO GASS BULAT.png') }}" alt="GASS Your Way"></div><div class="manifesto-copy"><p class="eyebrow">Our belief</p><h2>Digital innovation<br>for <em>future growth.</em></h2></div><div class="manifesto-text"><p>PT. Gass Digital Solutions<br><em>Empowering Brands Through Digital Innovation & Artificial Intelligence.</em></p></div></section>
@endsection
