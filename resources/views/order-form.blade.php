@extends('layouts.app')

@section('title', 'Formulir Pemesanan — GASS')
@section('body_class', 'order-form-page')

@push('styles')
<style>
    .order-form-page { background: #e4edf6; color: #061b45; }
    .order-form-page .shape-grid-background, .order-form-page .global-robot, .order-form-page .site-loader { display: none; }
    .order-form-page main { padding: 54px 24px 80px; }
    .order-form-wrap { width: min(1180px, 100%); margin: 0 auto; }
    .order-form-header { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 28px; }
    .order-form-header h1 { max-width: 650px; margin: 0; color: #061b45; font-size: clamp(36px, 6vw, 68px); line-height: .95; letter-spacing: -.05em; }
    .order-form-header h1 em { background: transparent !important; background-image: none !important; background-clip: border-box !important; -webkit-background-clip: border-box !important; color: #061b45 !important; -webkit-text-fill-color: #061b45 !important; font-style: normal; font-weight: 900; text-shadow: none; }
    .order-form-header .eyebrow { color: #415674; }
    .order-form-header p { max-width: 360px; margin: 0; color: #304663; font-size: 14px; line-height: 1.5; }
    .order-form-grid { display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(280px, .7fr); gap: 22px; align-items: start; }
    .order-form-panel { min-width: 0; padding: 22px; border: 1px solid #c5d0de; background: #fff; box-shadow: 10px 10px 0 rgba(6, 27, 69, .08); }
    .order-form-panel h2 { margin: 0 0 7px; font-size: 22px; }
    .order-form-panel > p { margin: 0 0 18px; color: #61708d; font-size: 12px; line-height: 1.45; }
    .order-pdf-frame { width: 100%; height: 680px; border: 1px solid #b9c5d3; background: #52617b; }
    .order-whatsapp { display: grid; gap: 18px; align-content: start; }
    .order-whatsapp-icon { display: grid; width: 58px; height: 58px; place-items: center; border-radius: 50%; background: #16a34a; color: #fff; font-size: 28px; }
    .order-whatsapp h2 { margin: 0; font-size: 30px; line-height: 1; }
    .order-whatsapp p { margin: 0; color: #304663; font-size: 13px; line-height: 1.55; }
    .order-whatsapp-link { display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: #16a34a; color: #fff; font: 700 13px var(--display); text-decoration: none; }
    .order-whatsapp-link:hover { background: #12813d; }
    .order-field { display: grid; gap: 6px; color: #061b45; font-size: 11px; font-weight: 700; }
    .order-field input, .order-field textarea, .order-field select { width: 100%; padding: 11px; border: 1px solid #b9c5d3; border-radius: 2px; background: #fff; color: #061b45; font: 13px var(--display); }
    .order-field textarea { min-height: 90px; resize: vertical; }
    .order-field input:focus, .order-field textarea:focus, .order-field select:focus { outline: 2px solid #087bdc; outline-offset: 1px; }
    .order-preview { display: grid; gap: 14px; margin-top: 22px; padding: 24px; border: 1px solid #b9c5d3; background: #fff; color: #061b45; }
    .order-preview-head { display: flex; justify-content: space-between; gap: 15px; padding-bottom: 13px; border-bottom: 3px solid #087bdc; }
    .order-preview-head strong { color: #087bdc; font-size: 22px; }
    .order-preview-head span { font: 10px var(--mono); text-align: right; }
    .order-preview h3 { margin: 0; font-size: 18px; }
    .order-preview dl { display: grid; gap: 9px; margin: 0; }
    .order-preview dl div { display: flex; justify-content: space-between; gap: 15px; padding-bottom: 8px; border-bottom: 1px solid #e1e6ed; font-size: 12px; }
    .order-preview dt { color: #61708d; }
    .order-preview dd { max-width: 65%; margin: 0; font-weight: 700; text-align: right; overflow-wrap: anywhere; }
    .order-preview-brief { min-height: 70px; margin: 0; padding: 12px; background: #f1f6fb; color: #52617b; font-size: 12px; line-height: 1.45; white-space: pre-wrap; }
    .order-preview-section { display: grid; gap: 8px; margin-top: 4px; }
    .order-preview-section h4 { margin: 0; color: #087bdc; font-size: 10px; letter-spacing: .08em; text-transform: uppercase; }
    .order-preview-signature { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 15px; padding-top: 32px; border-top: 1px solid #b9c5d3; color: #61708d; font-size: 10px; text-align: center; }
    .order-form-actions { display: flex; flex-wrap: wrap; gap: 9px; margin-top: 18px; }
    .order-form-actions button, .order-form-actions a { flex: 1; min-width: 150px; padding: 13px 15px; border: 1px solid #087bdc; background: #087bdc; color: #fff; font: 600 12px var(--display); text-align: center; text-decoration: none; cursor: pointer; }
    .order-form-actions a { background: transparent; color: #087bdc; }
    @media (max-width: 850px) { .order-form-page main { padding: 34px 16px 55px; } .order-form-header { display: grid; gap: 15px; } .order-form-grid { grid-template-columns: 1fr; } .order-pdf-frame { height: 520px; } }
    @media print { @page { size: A4; margin: 14mm; } .order-form-page main { padding: 0; } .order-form-page .site-header, .order-form-page .site-footer, .order-form-header, .order-pdf-panel, .order-editor, .order-form-actions { display: none !important; } .order-form-grid { display: block; } .order-form-panel { border: 0; box-shadow: none; padding: 0; } .order-preview { width: 100%; margin: 0; border: 0; padding: 0; } .order-preview-head strong { color: #087bdc !important; } .order-preview-brief { background: #f1f6fb !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; } }
</style>
@endpush

@section('content')
<div class="order-form-wrap">
    <header class="order-form-header">
        <div><p class="eyebrow">Formulir pemesanan / GASS</p><h1>Pesan dengan<br><em>lebih terarah.</em></h1></div>
        <p>Lihat formulir pemesanan di kiri, lalu hubungi kami langsung melalui WhatsApp untuk mengirim data pesanan.</p>
    </header>
    <div class="order-form-grid">
        <section class="order-form-panel order-pdf-panel">
            <h2>Preview formulir asli</h2>
            <p>Dokumen dapat dibuka, diperbesar, dan diunduh dari toolbar PDF.</p>
            <iframe class="order-pdf-frame" src="{{ asset('Formulir_Pemesanan_Layanan_PT_GASS_Digital_Solutions.pdf') }}#toolbar=1" title="Preview formulir pemesanan PDF"></iframe>
        </section>
        <section class="order-form-panel order-whatsapp">
            <span class="order-whatsapp-icon" aria-hidden="true">◔</span>
            <h2>Chat WhatsApp langsung</h2>
            <p>Unduh atau lihat formulir di panel kiri, kemudian kirimkan formulir dan detail pesanan Anda kepada tim GASS melalui WhatsApp.</p>
            <a class="order-whatsapp-link" href="https://wa.me/6285890007359?text=Halo%20GASS%2C%20saya%20ingin%20memesan%20layanan.%20Saya%20akan%20mengirimkan%20formulir%20pemesanan." target="_blank" rel="noreferrer">Buka WhatsApp <span>↗</span></a>
            <a class="order-form-actions" href="{{ route('home') }}">Kembali ke beranda</a>
        </section>
    </div>
</div>
@endsection

