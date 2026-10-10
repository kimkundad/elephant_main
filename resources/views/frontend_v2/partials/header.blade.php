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
  /* A fixed row, not a minimum: the logo is taller than the bar on purpose
     and must not push the rest of the header down with it. */
  height:56px;
}
.pillhdr__brand{ flex:0 0 auto; display:inline-flex; align-items:center; }
.pillhdr__brand img{ display:block; width:auto; height:75px; }

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
.pillhdr__lang{ position:relative; }
.pillhdr__flag{
  display:inline-grid; place-items:center;
  width:46px; height:46px; padding:0;
  border:0; background:none; cursor:pointer;
}
.pillhdr__flag .flag-icon{
  width:40px; height:40px;
  border-radius:50%;
  box-shadow:0 0 0 2px rgba(255,255,255,.75);
  transition:box-shadow .2s ease;
}
.pillhdr.is-stuck .pillhdr__flag .flag-icon{ box-shadow:0 0 0 1px rgba(0,0,0,.12); }
.pillhdr__flag:hover .flag-icon,
.pillhdr__flag[aria-expanded="true"] .flag-icon{ box-shadow:0 0 0 2px #b5db2a; }

.pillhdr__langmenu{
  position:absolute; right:0; top:calc(100% + 10px); z-index:20;
  min-width:190px; padding:8px;
  background:#fff; border:1px solid rgba(0,0,0,.08); border-radius:18px;
  box-shadow:0 18px 40px rgba(0,0,0,.14);
}
.pillhdr__langmenu[hidden]{ display:none; }
.pillhdr__langitem{
  display:flex; align-items:center; gap:10px;
  min-height:44px; padding:0 12px; border-radius:12px;
  font-size:15px; font-weight:600; color:#2b2621; text-decoration:none;
}
.pillhdr__langitem:hover{ background:#f6f4f0; color:#2b2621; }
.pillhdr__langitem .flag-icon{ width:22px; height:22px; border-radius:50%; flex:0 0 auto; }
.pillhdr__langcheck{ width:16px; height:16px; margin-left:auto; color:#7f9c13; }

.nav-menu__close{
  position:absolute;
  top:24px; right:24px;
  z-index:5;
  display:grid; place-items:center;
  width:48px; height:48px;
  padding:0;
  border:1px solid rgba(255,255,255,.35);
  border-radius:50%;
  background:transparent;
  color:#fff;
  cursor:pointer;
  transition:background-color .2s ease, border-color .2s ease;
}
.nav-menu__close svg{ width:22px; height:22px; }
.nav-menu__close:hover{ background:rgba(255,255,255,.12); border-color:#fff; }

/* The theme's own hamburger styles still drive the full screen menu, so this
   only decides when it shows and keeps the bars visible on a light bar. */
.pillhdr__burger{ display:none; }
.pillhdr.is-stuck .pillhdr__burger span{ background:#2b2621; }

@media (max-width:1199px){
  .pillhdr__nav a{ padding-inline:16px; font-size:14px; }
}
@media (max-width:991px){
  .pillhdr__nav{ display:none; }
  .pillhdr__burger{ display:block; }
  .pillhdr__actions{ margin-left:auto; }
  .pillhdr__bar{ padding-block:14px; }
  /* Taller than the 56px row on purpose, as on a desktop, just less so. */
  .pillhdr__brand img{ height:62px; }
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
                {{-- The flag is the button; the menu lists the languages by name. --}}
                <div class="pillhdr__lang" id="localeMenu">
                    <button type="button" class="pillhdr__flag js-locale-toggle" aria-haspopup="true" aria-expanded="false"
                            aria-label="{{ __('common.language_switch') }}">
                        @include('frontend_v2.partials.locale-flag', ['locale' => $locale, 'size' => 40])
                    </button>

                    <div class="pillhdr__langmenu" role="menu" hidden>
                        @foreach(['en' => 'English', 'th' => 'ไทย'] as $code => $name)
                            <a class="pillhdr__langitem" role="menuitemradio" aria-checked="{{ $locale === $code ? 'true' : 'false' }}"
                               href="{{ route('frontend.locale.switch', $code) }}">
                                @include('frontend_v2.partials.locale-flag', ['locale' => $code, 'size' => 22])
                                <span>{{ $name }}</span>
                                @if($locale === $code)
                                    <svg class="pillhdr__langcheck" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m20 6-11 11-5-5"/></svg>
                                @endif
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Keeps the class the theme script binds the full screen menu to. --}}
                <div class="hamburger pillhdr__burger" id="v2-menu-toggle" role="button" tabindex="0" aria-label="{{ __('common.menu') }}">
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

    <article class="nav-menu">
            {{-- The hamburger is hidden behind the open menu, so closing needs its
                 own button. --}}
            <button type="button" class="nav-menu__close" id="v2-menu-close" aria-label="{{ __('common.close') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>

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

// Closing the full screen menu runs the theme's own hamburger handler, so the
// body scroll lock and the image fades unwind exactly as they were set.
(function () {
    var close = document.getElementById('v2-menu-close');
    var burger = document.getElementById('v2-menu-toggle');
    if (!close || !burger) return;

    close.addEventListener('click', function () {
        burger.click();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.querySelector('.nav-menu.active')) burger.click();
    });
})();

// Language menu: the flag opens it, a click anywhere else or Escape closes it.
(function () {
    var anchor = document.getElementById('localeMenu');
    if (!anchor) return;

    var toggle = anchor.querySelector('.js-locale-toggle');
    var menu = anchor.querySelector('.pillhdr__langmenu');

    function setOpen(open) {
        menu.hidden = !open;
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    toggle.addEventListener('click', function (e) {
        e.stopPropagation();
        setOpen(menu.hidden);
    });

    document.addEventListener('click', function (e) {
        if (!anchor.contains(e.target)) setOpen(false);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') setOpen(false);
    });
})();
</script>
