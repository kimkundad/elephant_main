@php
    $menuActiveImage = \App\Models\PageMedia::url('v2.header.menu.active_image', Vite::asset('resources/frontend/images/NEW-home-page-image.webp'));
    $menuHoverImage = \App\Models\PageMedia::url('v2.header.menu.hover_image', Vite::asset('resources/frontend/images/bg-chang.webp'));
    $menuActiveAlt = \App\Models\PageMedia::alt('v2.header.menu.active_image', 'Small Elephants');
    $menuHoverAlt = \App\Models\PageMedia::alt('v2.header.menu.hover_image', 'Small Elephants');

    // Each menu item can show its own picture on hover, falling back to the
    // shared hover image when it has none of its own.
    $menuLinkImages = [
        'about' => \App\Models\PageMedia::url('v2.header.menu.about_image', $menuHoverImage),
        'programs' => \App\Models\PageMedia::url('v2.header.menu.programs_image', $menuHoverImage),
        'contact' => \App\Models\PageMedia::url('v2.header.menu.contact_image', $menuHoverImage),
    ];
@endphp
<style>
/* Pill header: a transparent bar over the hero that turns into a white sticky
   bar once the page scrolls, with the links in a floating white pill. */
.pillhdr{
  position:absolute;
  top:0; left:0; right:0;
  z-index:999;
}
.pillhdr__bar{
  padding-block:22px;
  transition:background-color .25s ease, box-shadow .25s ease, padding .25s ease;
}
.pillhdr.is-stuck{ position:fixed; }
.pillhdr.is-stuck .pillhdr__bar{
  padding-block:10px;
  background:#fff;
  box-shadow:0 8px 24px rgba(0,0,0,.08);
}
.pillhdr__inner{
  display:flex;
  align-items:center;
  gap:20px;
  min-height:56px;
}
.pillhdr__brand{ flex:0 0 auto; display:inline-flex; align-items:center; }
.pillhdr__brand img{ display:block; width:auto; height:46px; }

.pillhdr__nav{
  display:flex;
  align-items:center;
  gap:2px;
  margin-inline:auto;
  padding:5px;
  background:#fff;
  border-radius:999px;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
}
.pillhdr__nav a{
  display:inline-flex;
  align-items:center;
  min-height:44px;
  padding:0 24px;
  border-radius:999px;
  font-size:15px;
  font-weight:700;
  color:#2b2621;
  text-decoration:none;
  white-space:nowrap;
  transition:background-color .2s ease, color .2s ease;
}
.pillhdr__nav a:hover,
.pillhdr__nav a[aria-current="page"]{ background:#b5db2a; color:#fff; }

.pillhdr__actions{ display:flex; align-items:center; gap:10px; }
.pillhdr__icon{
  display:inline-grid;
  place-items:center;
  width:46px; height:46px;
  border-radius:50%;
  background:#fff;
  color:#2b2621;
  box-shadow:0 10px 30px rgba(0,0,0,.08);
  transition:color .2s ease;
}
.pillhdr__icon svg{ width:20px; height:20px; }
.pillhdr__icon:hover{ color:#7f9c13; }

.pillhdr__lang{
  display:flex; align-items:center; gap:6px;
  font-size:14px; font-weight:700; letter-spacing:.04em;
  color:#fff;
}
.pillhdr__lang a{ color:inherit; text-decoration:none; opacity:.7; }
.pillhdr__lang a.is-active{ opacity:1; text-decoration:underline; text-underline-offset:4px; }
.pillhdr.is-stuck .pillhdr__lang{ color:#2b2621; }

.pillhdr__cta{
  display:inline-flex; align-items:center;
  min-height:46px; padding:0 22px;
  border-radius:999px;
  background:#b5db2a; color:#fff;
  font-size:14px; font-weight:700; letter-spacing:.06em; text-transform:uppercase;
  text-decoration:none;
  transition:background-color .2s ease;
}
.pillhdr__cta:hover{ background:#a6cb24; color:#fff; }

/* The theme's own hamburger styles still drive the full screen menu, so this
   only decides when it shows and keeps the bars visible on a light bar. */
.pillhdr__burger{ display:none; }
.pillhdr.is-stuck .pillhdr__burger span{ background:#2b2621; }

@media (max-width:1199px){
  .pillhdr__nav a{ padding-inline:16px; font-size:14px; }
}
@media (max-width:991px){
  .pillhdr__nav, .pillhdr__cta, .pillhdr__icon{ display:none; }
  .pillhdr__burger{ display:block; }
  .pillhdr__actions{ margin-left:auto; }
  .pillhdr__bar{ padding-block:14px; }
  .pillhdr__brand img{ height:40px; }
}
</style>

<header class="v2-header pillhdr" id="siteHeader">
    @php
        $logoUrl = $siteSetting?->logo_header_url ?: asset('img/logo.webp');
        $locale = app()->getLocale();
    @endphp

    <div class="pillhdr__bar">
        <div class="container pillhdr__inner">
            <a class="pillhdr__brand" href="{{ route('frontend.home') }}" aria-label="Small Elephants">
                <img src="{{ $logoUrl }}" alt="Small Elephants" width="150" height="57">
            </a>

            <nav class="pillhdr__nav" aria-label="{{ __('common.menu') }}">
                <a href="{{ route('frontend.home') }}" @if(request()->routeIs('frontend.home')) aria-current="page" @endif>{{ __('common.nav_home') }}</a>
                <a href="{{ route('frontend.program') }}" @if(request()->routeIs('frontend.program*') || request()->routeIs('frontend.tours.*')) aria-current="page" @endif>{{ __('common.nav_programs') }}</a>
                <a href="{{ route('frontend.about') }}" @if(request()->routeIs('frontend.about')) aria-current="page" @endif>{{ __('common.nav_about') }}</a>
                <a href="{{ route('frontend.contact') }}" @if(request()->routeIs('frontend.contact')) aria-current="page" @endif>{{ __('common.nav_contact') }}</a>
            </nav>

            <div class="pillhdr__actions">
                <a class="pillhdr__icon" href="{{ route('frontend.program') }}" aria-label="{{ __('common.search') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                    </svg>
                </a>

                <div class="pillhdr__lang" role="group" aria-label="Language">
                    <a href="{{ route('frontend.locale.switch', 'en') }}" class="{{ $locale === 'en' ? 'is-active' : '' }}">EN</a>
                    <span aria-hidden="true">/</span>
                    <a href="{{ route('frontend.locale.switch', 'th') }}" class="{{ $locale === 'th' ? 'is-active' : '' }}">TH</a>
                </div>

                <a class="pillhdr__cta" href="{{ route('frontend.program') }}">{{ __('common.book_now') }}</a>

                {{-- Keeps the class the theme script binds the full screen menu to. --}}
                <div class="hamburger pillhdr__burger" id="v2-menu-toggle" role="button" tabindex="0" aria-label="{{ __('common.menu') }}">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

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
                            <li class="nav-link " data-src="{{ $menuLinkImages['about'] }}">
                                <a href="{{ route('frontend.about') }}">
                                    <div class="real">About</div>
                                    <div class="hover">
                                        <span>About</span>
                                        <div class="cover-hover"></div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-link " data-src="{{ $menuLinkImages['programs'] }}">
                                <a href="{{ route('frontend.program') }}">
                                    <div class="real">Programs</div>
                                    <div class="hover">
                                        <span>Programs</span>
                                        <div class="cover-hover"></div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-link " data-src="{{ $menuLinkImages['contact'] }}">
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
<script>
// The bar floats over the hero until the page scrolls, then sticks as a white
// bar. Runs before jQuery loads so a refresh part-way down paints correctly.
(function () {
    var header = document.getElementById('siteHeader');
    if (!header) return;

    function sync() {
        var y = window.pageYOffset || document.documentElement.scrollTop || 0;
        header.classList.toggle('is-stuck', y > 80);
    }

    sync();
    window.addEventListener('scroll', sync, { passive: true });
})();
</script>
