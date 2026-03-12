@php
    $hideBookMobile = request()->routeIs('frontend.booking.create')
        || request()->routeIs('frontend.booking.create.v2')
        || request()->is('booking')
        || request()->is('v2/booking');
    $menuActiveImage = \App\Models\PageMedia::url('v2.header.menu.active_image', asset('samet/assets/cover-active.jpg'));
    $menuHoverImage = \App\Models\PageMedia::url('v2.header.menu.hover_image', asset('samet/assets/gallery.jpg'));
    $menuActiveAlt = \App\Models\PageMedia::alt('v2.header.menu.active_image', 'Small Elephants');
    $menuHoverAlt = \App\Models\PageMedia::alt('v2.header.menu.hover_image', 'Small Elephants');
@endphp
@unless($hideBookMobile)
<div class="book-mobile">
        <a href="{{ url('/programs') }}" aria-label="Book Now" >
            Book Now
        </a>
    </div>
@endunless
<header class="scroll_nonefix  v2-header">
    <article class="navbar-header">
        <div class="pull-left">
            
        </div>
        <div class="navbar-brand">
            <a href="{{ route('frontend.home') }}" aria-label="Small Elephants">
                @if(!empty($siteSetting?->logo_header_url))
                    <img src="{{ $siteSetting->logo_header_url }}" alt="Small Elephants" width="150" height="57">
                @else
                    <img src="{{ asset('samet/assets/logo.png') }}" alt="Small Elephants" width="150" height="57">
                @endif
            </a>
        </div>
        <div class="pull-right">
            <div class="nav-lang">
                @php $locale = app()->getLocale(); @endphp
                <a href="{{ route('frontend.locale.switch', 'en') }}" class="{{ $locale === 'en' ? 'is-active' : '' }}"><span>En</span></a>
                <a href="{{ route('frontend.locale.switch', 'th') }}" class="{{ $locale === 'th' ? 'is-active' : '' }}"><span>Th</span></a>
            </div>
            <div class="hamburger" id="v2-menu-toggle">
                <span></span>
                <span></span>
            </div>
        </div>
    </article>
    <article class="nav-menu">
            <div class="box-menu">
                <div class="col-xs-6">
                    <div>
                        <div class="image-hover-active">
                            <figure>
                                <div class="reveal-block"></div>
                                <img src="{{ $menuActiveImage }}"
                                    alt="{{ $menuActiveAlt }}">
                            </figure>
                        </div>
                        <div class="image-hover">
                            <figure>
                                <img src="{{ $menuHoverImage }}"
                                    alt="{{ $menuHoverAlt }}">
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <nav>
                        <ul>
                            <li class="nav-link " data-src="/assets/images/cover-menu/room.jpg">
                                <a href="{{ route('frontend.about') }}">
                                    <div class="real">About</div>
                                    <div class="hover">
                                        <span>About</span>
                                        <div class="cover-hover"></div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-link " data-src="/assets/images/cover-menu/experience.jpg">
                                <a href="{{ route('frontend.program') }}">
                                    <div class="real">Programs</div>
                                    <div class="hover">
                                        <span>Programs</span>
                                        <div class="cover-hover"></div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-link " data-src="/assets/images/cover-menu/contact.jpg">
                                <a href="{{ route('frontend.contact') }}">
                                    <div class="real">Contact</div>
                                    <div class="hover">
                                        <span>Contact</span>
                                        <div class="cover-hover"></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </article>
</header>
