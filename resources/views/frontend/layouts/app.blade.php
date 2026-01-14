<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Elephant Sanctuary')</title>

    {{-- ถ้าใช้ Vite --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">

    <!-- Owl Carousel -->
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">

    <!-- Main Theme CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('frontend/images/icons/favicon-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('frontend/images/icons/favicon-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('frontend/images/icons/favicon-180x180.png') }}">

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
