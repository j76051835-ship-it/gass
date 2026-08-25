<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GASS adalah growth partner untuk bisnis yang ingin bertumbuh lebih cepat melalui strategi digital yang tajam.">
    <title>@yield('title', 'GASS — Growth Acceleration Strategic Services')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="@yield('body_class', 'site-page')">
    <div class="shape-grid-background" aria-hidden="true">
        <i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
    </div>
    <div class="site-loader" data-site-loader aria-hidden="true">
        <div class="loader-orbit loader-orbit-one"></div>
        <div class="loader-orbit loader-orbit-two"></div>
        <div class="loader-impact">
            <div class="loader-core"><img src="{{ asset('LOGO GASS BULAT.png') }}" alt=""></div>
            <div class="loader-ground" aria-hidden="true">
                <i class="crack crack-one"></i><i class="crack crack-two"></i><i class="crack crack-three"></i>
                <i class="crack crack-four"></i><i class="crack crack-five"></i><i class="crack crack-six"></i>
                <i class="crack crack-seven"></i><i class="crack crack-eight"></i>
            </div>
        </div>
        <div class="loader-progress"><span></span></div>
        <p>GASS <span>/</span> Growth Acceleration Strategic Services</p>
    </div>
    <div class="announcement">GASS / Growth Acceleration Strategic Services <span>●</span> Jakarta · Indonesia</div>
    <header class="site-header">
        <a href="{{ auth()->check() && auth()->user()->is_admin ? route('admin.dashboard') : route('admin.login') }}" class="brand" aria-label="{{ auth()->check() && auth()->user()->is_admin ? 'Dashboard admin' : 'Login admin' }}"><img src="{{ asset('LOGO GASS LANDSCAPE.png') }}" alt="GASS"><span>Growth Acceleration<br>Strategic Services</span></a>
        <nav class="desktop-nav" aria-label="Navigasi utama">
            <a class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}"><small>00</small>Beranda</a>
            <a class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}"><small>01</small>Tentang</a>
            <a class="nav-link {{ request()->routeIs('services') ? 'is-active' : '' }}" href="{{ route('services') }}"><small>02</small>Layanan</a>
            <a class="nav-link {{ request()->routeIs('process') ? 'is-active' : '' }}" href="{{ route('process') }}"><small>03</small>Proses</a>
            <a class="nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}" href="{{ route('contact') }}"><small>04</small>Kontak</a>
        </nav>
        <a class="button button-dark header-cta" href="https://wa.me/6285890007359" target="_blank" rel="noreferrer"><span class="cta-label">Mulai ngobrol</span><span>↗</span></a>
        <nav class="mobile-nav section-shell" aria-label="Navigasi mobile">
            <button class="mobile-menu-toggle" type="button" data-mobile-menu-toggle aria-expanded="false" aria-controls="mobile-menu-links">
                <span class="hamburger-icon" aria-hidden="true"><i></i><i></i><i></i></span>
                <span>Menu</span>
            </button>
            <div class="mobile-menu-links" id="mobile-menu-links" data-mobile-menu-links>
                <a class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }}" href="{{ route('about') }}">Tentang</a>
                <a class="nav-link {{ request()->routeIs('services') ? 'is-active' : '' }}" href="{{ route('services') }}">Layanan</a>
                <a class="nav-link {{ request()->routeIs('process') ? 'is-active' : '' }}" href="{{ route('process') }}">Proses</a>
                <a class="nav-link {{ request()->routeIs('contact') ? 'is-active' : '' }}" href="{{ route('contact') }}">Kontak</a>
            </div>
        </nav>
    </header>

    <main id="top">@yield('content')</main>

    <footer class="site-footer section-shell"><div class="footer-brand"><img src="{{ asset('LOGO GASS LANDSCAPE.png') }}" alt="GASS"><div><strong>PT. GASS DIGITAL SOLUTIONS</strong><small>Growth Acceleration Strategic Services</small></div></div><div class="footer-copyright">© {{ date('Y') }} GASS. Built to move.</div><div class="social-links"><a href="https://www.instagram.com/gass.generations/" target="_blank" rel="noreferrer">Instagram ↗</a><a href="https://www.tiktok.com/@gass.generations" target="_blank" rel="noreferrer">TikTok ↗</a><a href="mailto:gassdigitalsoultions@gmail.com">Email ↗</a></div><a href="#top">Kembali ke atas ↑</a></footer>
</body>
</html>
