<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SMALL ELEPHANTS')</title>

    {{-- ถ้าใช้ Vite --}}
    @vite(['resources/frontend/css/app.css', 'resources/frontend/js/app.js'])


    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->


    <!-- Font Awesome -->


    <!-- Owl Carousel -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

    <!-- Main Theme CSS -->


    <!-- Favicons -->
    <link rel="icon" href="{{ Vite::asset('resources/frontend/images/icons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ Vite::asset('resources/frontend/images/icons/favicon-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ Vite::asset('resources/frontend/images/icons/favicon-180x180.png') }}">

    {{-- Custom inline style (ถ้าจำเป็น) --}}
    <style id="fit-vids-style">
      .fluid-width-video-wrapper {
         width: 100%;
         position: relative;
         padding: 0;
      }

      .fluid-width-video-wrapper iframe,
      .fluid-width-video-wrapper object,
      .fluid-width-video-wrapper embed {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
      }
      .logo img {
    width: 160px;
}
.testimonial-box{
    background:#222;
    padding:25px;
    border-radius:12px;
    color:white;
    height:100%;
}

.testimonial-author{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:15px;
}

.testimonial-author img{
    width:50px;
    height:50px;
    border-radius:50%;
}

.testimonial-rating{
    color:#9acd32;
    font-size:18px;
    margin-bottom:10px;
}

.testimonial-text{
    font-size:14px;
    line-height:1.6;
}
.testimonial-slider {
    position: relative;
    z-index: 5;
}
.testimonial-slider {
    position: relative;
    z-index: 50;
}
   </style>

    {{-- เผื่อหน้าอื่น push css เพิ่ม --}}

    @stack('styles')
</head>
<body class="@yield('body-class')">

    @include('frontend.partials.header')

    @yield('content')


    @include('frontend.partials.footer')
    @include('frontend.partials.scripts')

    @stack('scripts')
</body>
</html>

