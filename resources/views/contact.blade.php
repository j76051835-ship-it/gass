@extends('layouts.app')

@section('title', 'Kontak — GASS')

@section('body_class', 'contact-page-body')

@push('styles')
<style>

    .contact-page-body .contact-page {
        min-height: 650px;
        padding-top: 95px;
    }

    .contact-page-body .contact-page {
        gap: 8%;
    }

    .contact-page-body .contact-page > div:first-child {
        padding: 34px 0;
    }

    .contact-page-body .contact-side {
        padding: 34px;
        border: 1px solid rgba(103,232,249,.42);
        border-radius: 12px;
        background: rgba(5,11,24,.88);
        box-shadow:
            12px 12px 0 rgba(34,211,238,.16),
            0 18px 40px rgba(2,11,31,.28);
        color: #f7f9fc;
    }

    .contact-page-body .contact-side > p {
        color: #f7f9fc;
    }

    .contact-page-body .contact-side .button {
        display: inline-flex;
        background: #087bdc;
        color: #fff;
        box-shadow: 5px 5px 0 #67e8f9;
    }

    .contact-page-body .contact-side .button:hover {
        background: #22d3ee;
        color: #050b18;
    }

    .contact-page-body .contact-email {
        color: #dbe4ef;
    }

    .contact-page-body .contact-email a {
        color: #67e8f9;
    }

    .contact-page-body .contact-detail {
        border-top-color: rgba(103,232,249,.26);
    }

    .contact-page-body .contact-detail span {
        color: #67e8f9;
    }

    .contact-page-body .contact-detail strong {
        color: #f7f9fc;
    }

    .contact-page-body .contact-side {
        position: relative;
        overflow: hidden;
    }

    .contact-page-body .contact-side:before {
        content: "";
        position: absolute;
        top: -90px;
        right: -70px;
        width: 220px;
        height: 220px;
        border: 1px solid rgba(103,232,249,.24);
        border-radius: 50%;
        box-shadow:
            0 0 0 18px rgba(103,232,249,.04),
            0 0 0 38px rgba(103,232,249,.025);
        pointer-events: none;
    }

    .contact-social-heading {
        position: relative;
        margin: 34px 0 12px;
        color: #67e8f9;
        font: 10px var(--mono);
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .contact-socials {
        position: relative;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .contact-social {
        display: flex;
        min-height: 86px;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
        padding: 12px;
        border: 1px solid rgba(255,255,255,.18);
        color: #fff;
        text-decoration: none;
        transition:
            transform .2s ease,
            border-color .2s ease,
            background .2s ease;
    }

    .contact-social:hover,
    .contact-social:focus-visible {
        transform: translateY(-5px);
        border-color: #fff;
        outline: none;
    }

    /* INSTAGRAM */
    .contact-social.instagram {
        background: linear-gradient(
            145deg,
            rgba(225,48,108,.92),
            rgba(131,58,180,.88)
        );
    }

    /* TIKTOK */
    .contact-social.tiktok {
        background: linear-gradient(
            145deg,
            rgba(37,244,238,.82),
            rgba(5,11,24,.92) 48%,
            rgba(254,44,85,.82)
        );
    }

    /* WHATSAPP */
    .contact-social.whatsapp {
        background: #139447;
    }

    /* ICON */
    .contact-social svg {
        display: block;
        flex: 0 0 26px;
        width: 26px;
        height: 26px;
        fill: currentColor;
    }

    .contact-social span {
        display: flex;
        min-width: 0;
        align-items: center;
        justify-content: space-between;
        gap: 5px;
        font-size: 10px;
        font-weight: 700;
    }

    .contact-social span small {
        color: rgba(255,255,255,.78);
        font: 9px var(--mono);
    }

    .contact-page-body .contact-email {
        position: relative;
    }

    /* MOBILE */
    @media (max-width: 800px) {

        .contact-page-body .contact-page {
            gap: 28px;
        }

        .contact-page-body .contact-side {
            padding: 24px;
        }

    }

    @media (max-width: 430px) {

        .contact-socials {
            grid-template-columns: 1fr;
        }

        .contact-social {
            min-height: 64px;
            flex-direction: row;
            align-items: center;
            gap: 14px;
        }

        .contact-social span {
            flex: 1;
        }

    }

</style>
@endpush


@section('content')

<section class="contact contact-page section-shell">

    <div>

        <p class="eyebrow">04 / Kontak GASS</p>

        <h1>
            Siap untuk<br>
            <em>mulai bergerak?</em>
        </h1>

        <p class="hero-intro">
            Ceritakan sedikit tentang bisnismu.
            Kami akan kembali dengan ide dan langkah pertama
            yang paling masuk akal.
        </p>

    </div>


    <div class="contact-side">

        <p>
            Hubungi kami melalui WhatsApp untuk konsultasi awal
            dan ceritakan tantangan digital yang sedang kamu hadapi.
        </p>


        <a
            class="button button-dark"
            href="https://wa.me/6285890007359"
            target="_blank"
            rel="noreferrer"
        >
            Ngobrol via WhatsApp
            <span>↗</span>
        </a>


        <p class="contact-email">
            atau email kami di
            <a href="mailto:gassdigitalsoultions@gmail.com">
                gassdigitalsoultions@gmail.com
            </a>
        </p>


        <p class="contact-social-heading">
            Temukan GASS di kanal digital
        </p>


        <div class="contact-socials">

            <!-- INSTAGRAM -->
            <a
                class="contact-social instagram"
                href="https://www.instagram.com/gass.generations/"
                target="_blank"
                rel="noreferrer"
                aria-label="GASS di Instagram"
            >

                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path d="M7.5 2h9A5.5 5.5 0 0 1 22 7.5v9a5.5 5.5 0 0 1-5.5 5.5h-9A5.5 5.5 0 0 1 2 16.5v-9A5.5 5.5 0 0 1 7.5 2Zm0 2A3.5 3.5 0 0 0 4 7.5v9A3.5 3.5 0 0 0 7.5 20h9a3.5 3.5 0 0 0 3.5-3.5v-9A3.5 3.5 0 0 0 16.5 4h-9ZM12 7a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm5.25-3.25a1.25 1.25 0 1 1 0 2.5 1.25 1.25 0 0 1 0-2.5Z"/>
                </svg>

                <span>
                    Instagram
                    <small>↗</small>
                </span>

            </a>


            <!-- TIKTOK -->
            <a
                class="contact-social tiktok"
                href="https://www.tiktok.com/@gass.generations"
                target="_blank"
                rel="noreferrer"
                aria-label="GASS di TikTok"
            >

                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path d="M16.6 3c.3 2.2 1.5 3.5 3.4 3.6v2.8c-1.5.1-2.8-.4-3.9-1.2v6.5a5.3 5.3 0 1 1-5.3-5.3c.3 0 .7 0 1 .1v2.9a2.4 2.4 0 1 0 1.5 2.3V3h3.3Z"/>
                </svg>

                <span>
                    TikTok
                    <small>↗</small>
                </span>

            </a>


            <!-- WHATSAPP -->
            <a
                class="contact-social whatsapp"
                href="https://wa.me/6285890007359"
                target="_blank"
                rel="noreferrer"
                aria-label="GASS di WhatsApp"
            >

                <svg
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <path d="M12.04 2C6.49 2 2 6.49 2 12.04c0 1.77.46 3.43 1.27 4.88L2 22l5.22-1.25a9.96 9.96 0 0 0 4.82 1.24h.01C17.59 21.99 22 17.5 22 12.04 22 6.49 17.51 2 12.04 2Zm0 18.02h-.01a8.32 8.32 0 0 1-4.24-1.16l-.3-.18-3.1.74.76-3.02-.2-.31a8.34 8.34 0 1 1 7.09 3.93Zm4.58-6.25c-.25-.13-1.47-.73-1.7-.81-.23-.08-.39-.13-.56.13-.16.25-.64.81-.78.98-.14.17-.29.19-.54.06-.25-.13-1.04-.38-1.98-1.21-.73-.65-1.22-1.45-1.36-1.69-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.12-.15.16-.25.25-.41.08-.16.04-.31-.02-.44-.06-.13-.56-1.35-.77-1.85-.2-.49-.41-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.23.25-.87.85-.87 2.07s.89 2.4 1.01 2.57c.12.17 1.75 2.67 4.24 3.75.59.26 1.05.41 1.41.53.59.19 1.13.16 1.55.1.47-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.15-1.18-.06-.1-.23-.16-.48-.29Z"/>
                </svg>

                <span>
                    WhatsApp
                    <small>↗</small>
                </span>

            </a>

        </div>


        <div class="contact-detail">
            <span>WHATSAPP</span>
            <strong>+62 858-9000-7359</strong>
        </div>


        <div class="contact-detail">
            <span>EMAIL</span>
            <strong>gassdigitalsoultions@gmail.com</strong>
        </div>


        <div class="contact-detail">
            <span>AREA LAYANAN</span>
            <strong>Indonesia · Remote friendly</strong>
        </div>

    </div>

</section>


<section class="contact-band">

    <div class="section-shell">

        <p class="eyebrow">
            A good conversation starts small
        </p>

        <h2>
            GASS bareng,<br>
            <em>tumbuh bareng.</em>
        </h2>

    </div>

</section>


@include('partials.reviews')

@endsection
