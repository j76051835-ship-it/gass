@extends('layouts.app')

@section('title', 'Login Admin — GASS')
@section('body_class', 'admin-page')

@push('styles')
<style>
.admin-page { background: #071a32; color: #101828; }
.admin-page main { min-height: 68vh; display: grid; place-items: center; padding: 50px 20px; }
.admin-auth { width: min(100%, 430px); padding: 38px; border: 1px solid #b8c4d3; background: #fffdf8; color: #101828; box-shadow: 10px 10px 0 #f7c934; }
.admin-auth h1 { margin: 8px 0 10px; color: #071a32; font-size: 42px; line-height: .95; letter-spacing: -.05em; }
.admin-auth p { color: #40516a; line-height: 1.5; }
.admin-auth label { display: grid; gap: 7px; margin-top: 18px; color: #263b55; font: 11px var(--mono); text-transform: uppercase; }
.admin-auth input { width: 100%; padding: 13px; border: 1px solid #8292a8; background: #f4f7fb; color: #071a32; font: 14px var(--display); }
.admin-auth input:focus { outline: 3px solid #f7c934; outline-offset: 1px; }
.admin-auth button { width: 100%; margin-top: 24px; padding: 14px; border: 0; background: #071a32; color: #ffffff; font: 700 12px var(--mono); text-transform: uppercase; cursor: pointer; }
.admin-auth button:hover { background: #15558c; }
.admin-error { padding: 10px; background: #ffe1e1; color: #8b1010; font-size: 13px; }
</style>
@endpush

@section('content')
<section class="admin-auth">
    <p class="eyebrow">GASS / Area terbatas</p>
    <h1>Masuk ke dashboard.</h1>
    <p>Kelola harga layanan dan diskon dari satu tempat.</p>
    @if ($errors->any())<div class="admin-error">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('admin.authenticate') }}">
        @csrf
        <label>Email<input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required></label>
        <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
        <button type="submit">Masuk ke dashboard →</button>
    </form>
</section>
@endsection
