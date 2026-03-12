<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $metaTitle = trim($__env->yieldContent('title', 'SMALL ELEPHANTS V2'));
        $metaDescription = trim($__env->yieldContent(
            'meta_description',
            'Ethical elephant sanctuary experiences in Chiang Mai with meaningful, respectful programs.'
        ));
        $ogTitle = trim($__env->yieldContent('og_title', $metaTitle));
        $ogDescription = trim($__env->yieldContent('og_description', $metaDescription));
        $ogImage = trim($__env->yieldContent(
            'og_image',
            $siteSetting?->og_image_url ?: asset('samet/assets/cover-active.jpg')
        ));
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <link rel="icon" href="{{ asset('samet/assets/favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('samet/assets/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/lightgallery.min.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/slick.min.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/style.css') }}">

    <link rel="stylesheet" href="{{ asset('samet/assets/locomotive.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/pre-loader.css') }}">

    <link rel="stylesheet" href="{{ asset('samet/assets/style.video.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/theme-sametnangsheboutique.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/style.ibe.css') }}">
    <link rel="stylesheet" href="{{ asset('samet/assets/responsive.css') }}">

    <link rel="stylesheet" href="{{ asset('samet/assets/t-datepicker.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    @vite(['resources/css/frontend_v2.css', 'resources/js/frontend_v2.js'])

    <style>
        .hero-slider.hero-style::after {
            content: "";
            position: absolute;
            inset: 0;
            box-shadow: inset 0 60px 120px rgba(0, 0, 0, 0.85),
                        inset 0 -120px 180px rgba(0, 0, 0, 0.45);
            pointer-events: none;
            z-index: 2;
        }

        /* Shared HERO for v2 pages */
        .about-hero{
          position: relative;
          min-height: 520px;
          background-size: cover;
          background-position: center;
          display:flex;
          align-items:center;
        }
        .about-hero__overlay {
          position: absolute;
          inset: 0;
          background:
            radial-gradient(
              ellipse at center,
              rgba(0,0,0,0.00) 0%,
              rgba(0,0,0,0.10) 45%,
              rgba(0,0,0,0.30) 70%,
              rgba(0,0,0,0.55) 100%
            ),
            linear-gradient(
              to bottom,
              rgba(0,0,0,0.18),
              rgba(0,0,0,0.38)
            );
        }

        .about-hero__inner{
          position:relative;
          z-index:1;
          padding-top: 120px;
          padding-bottom: 90px;
          max-width: 880px;
          text-align:center;
        }
        .about-hero__kicker{
          font-size:12px;
          letter-spacing:.22em;
          opacity:.85;
          color:#fff;
          margin-bottom:10px;
        }
        .about-hero__title{
          color:#fff;
          font-size:54px;
          line-height:1.05;
          margin-bottom:14px;
        }
        .about-hero__lead{
          color:rgba(255,255,255,.88);
          font-size:18px;
          line-height:1.7;
          margin: 0 auto 26px;
          max-width: 720px;
        }
        .about-hero__actions{
          display:flex;
          justify-content:center;
          gap:12px;
          flex-wrap:wrap;
        }

        .btn-primary-soft{
          display:inline-block;
          padding:12px 22px;
          border-radius:999px;
          background:#ffffff;
          color:#111;
          text-decoration:none;
          font-weight:700;
          border:1px solid rgba(255,255,255,.25);
          box-shadow:0 18px 40px rgba(0,0,0,.25);
        }
        .btn-primary-soft:hover{ background:#f5f5f5; color:#111; }

        .btn-outline-soft{
          display:inline-block;
          padding:12px 22px;
          border-radius:999px;
          background:transparent;
          color:#fff;
          text-decoration:none;
          font-weight:700;
          border:1px solid rgba(255,255,255,.45);
        }
        .btn-outline-soft:hover{ background:rgba(255,255,255,.12); color:#fff; }

        @media (max-width: 991px){
          .about-hero__title{ font-size:40px; }
        }
        @media (max-width: 575px){
          .about-hero{ min-height: 480px; }
          .about-hero__title{ font-size:34px; }
        }
    </style>

    @stack('styles')
</head>
<body class="v2-body loaded">
    <section id="loader">
        <div class="item-logo-loader">
            <img src="./assets/logo.png"
                alt="Sametnangshe Boutique" class="opacity">
        </div>
    </section>
    @include('frontend_v2.partials.header')

    <section>
        @yield('content')
        @include('frontend_v2.partials.footer')
        @include('frontend_v2.partials.scripts')

        

        @stack('scripts')
    </section>
</body>
</html>
