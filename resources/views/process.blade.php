@extends('layouts.app')

@section('title', 'Proses Kerja — GASS')

@section('body_class', 'process-page-body')

@push('styles')
<style>
	.process-page-body .process-page { margin-top: 10px; }
	.process-page-body .manifesto { margin-top: 0; }
</style>
@endpush

@section('content')
<section class="page-hero section-shell"><p class="eyebrow">03 / Proses kerja</p><h1>Rapi di kepala.<br><em>Berani di aksi.</em></h1><p class="hero-intro">Kolaborasi yang baik tidak dibuat terburu-buru. Kami menyusun langkah yang jelas, lalu mengeksekusinya dengan konsisten.</p></section>
<section class="process process-page section-shell"><div class="process-title"><p class="eyebrow">Our approach</p><h2>Tiga langkah<br>untuk <em>melaju.</em></h2></div><ol class="process-list"><li><span>01</span><div><h3>Temukan</h3><p>Kita bedah tantangan, audiens, dan peluang yang paling penting untuk bisnis.</p></div></li><li><span>02</span><div><h3>Rancang</h3><p>Strategi yang tajam, kreatif, dan relevan dengan target pertumbuhanmu.</p></div></li><li><span>03</span><div><h3>Gas</h3><p>Eksekusi konsisten, evaluasi rutin, dan optimasi agar hasil terus bergerak.</p></div></li></ol></section>
<section class="manifesto section-shell"><div class="manifesto-stamp">MOVE<br>WITH<br>MEANING</div><div class="manifesto-copy"><p class="eyebrow">The GASS way</p><h2>Setiap langkah<br>punya <em>arti.</em></h2></div><div class="manifesto-text"><p>Kami bekerja sebagai partner, bukan sekadar vendor. Artinya, kami ikut memahami konteks dan ikut menjaga momentum.</p></div></section>
@endsection
