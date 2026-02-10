<div class="menu-mask"></div>
   <!-- MOBILE MENU HOLDER -->
   <div class="mobile-menu-holder">
      <div class="modal-menu-container">
         <div class="exit-mobile">
            <span class="icon-bar1"></span>
            <span class="icon-bar2"></span>
         </div>
         <!-- MOBILE MENU -->
         <ul class="menu-mobile">
            <li class="menu-item {{ request()->routeIs('frontend.home') ? 'current-menu-item' : '' }}">
               <a href="{{ route('frontend.home') }}">Home</a>
            </li>
            <li class="menu-item {{ request()->routeIs('frontend.tours.index') ? 'current-menu-item' : '' }}">
               <a href="{{ route('frontend.tours.index') }}">Programs</a>
            </li>
            <li class="menu-item {{ request()->routeIs('frontend.about') ? 'current-menu-item' : '' }}">
               <a href="{{ route('frontend.about') }}">About</a>
            </li>
            <li class="menu-item {{ request()->routeIs('frontend.contact') ? 'current-menu-item' : '' }}">
               <a href="{{ route('frontend.contact') }}">Contact</a>
            </li>
         </ul>
         <!-- /MOBILE MENU -->
      </div>
      <!-- modal-menu-container -->
      <div class="menu-contact">
         <div class="mobile-btn">
            <a href="{{ route('frontend.tours.index') }}" class="view-more">Book Now</a>
         </div>
         <ul class="mobile-contact">
            <li class="mobile-address">{!! nl2br(e($siteSetting->address ?? "58 Ralph Ave\nNew York, New York 1111")) !!}</li>
            <li class="mobile-phone">{{ $siteSetting->phone ?? '+1 800 000 111' }}</li>
            <li class="mobile-email">{{ $siteSetting->email ?? 'infosmallelephants@gmail.com' }}</li>
         </ul>
         <ul class="social-media">
            @if(!empty($siteSetting?->facebook_url))
            <li><a class="social-facebook" href="{{ $siteSetting->facebook_url }}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
            @endif
            @if(!empty($siteSetting?->instagram_url))
            <li><a class="social-instagram" href="{{ $siteSetting->instagram_url }}" target="_blank"><i class="fab fa-instagram"></i></a></li>
            @endif
         </ul>
      </div>
      <!-- /menu-contact-->
   </div>
   <!-- /MOBILE MENU HOLDER -->
   <!-- HEADER -->
   @php
  // หน้าไหนให้ header ดำทันที
  $forceDark = request()->routeIs('frontend.tours.*')
            || request()->routeIs('frontend.booking.*') || request()->routeIs('frontend.about') || request()->routeIs('frontend.contact') || request()->routeIs('frontend.tours.index');
@endphp

<header id="header-1"
        class="headerHolder header-1  {{ $forceDark ? 'nav-fixed-top header-dark' : '' }}">
      <div class="nav-button-holder">
         <button type="button" class="nav-button">
            <span class="icon-bar"></span>
         </button>
      </div>
      <!-- /nav-button-holder-->
      <!-- LOGO -->
      <div class="logo logo-1"><a href="{{ url('/') }}"><img class="img-fluid"
               src="https://www.phuketelephantsanctuary.org/wp-content/uploads/sites/7659/2025/01/45c7bf722bb167f407ce49150b85be7b.png?h=120&zoom=2"
               alt="Small Elephants"></a></div>
      <!-- MENU -->
      <nav class="nav-holder nav-holder-1">
         <ul class="menu-nav menu-nav-1">
            <li class="menu-item menu-item-has-children {{ request()->routeIs('frontend.home') ? 'current-menu-item' : '' }}">
  <a href="{{ route('frontend.home') }}">Home</a>
</li>

<li class="menu-item menu-item-has-children {{ request()->routeIs('frontend.tours.index') ? 'current-menu-item' : '' }}">
  <a href="{{ route('frontend.tours.index') }}">Programs</a>
</li>

<li class="menu-item menu-item-has-children {{ request()->routeIs('frontend.about') ? 'current-menu-item' : '' }}">
  <a href="{{ route('frontend.about') }}">About</a>
</li>


<li class="menu-item menu-item-has-children {{ request()->routeIs('frontend.contact') ? 'current-menu-item' : '' }}">
  <a href="{{ route('frontend.contact') }}">Contact</a>
</li>




         </ul>
      </nav>
      <!-- /MENU -->
      <div class="social-btn-top1">
         <ul class="social-media social-media1">

         </ul>
         <div class="btn-header btn-header1">
         </div>
      </div>
      <!-- /social-btn-top1 -->
   </header>
