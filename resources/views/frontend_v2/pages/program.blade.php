@extends('frontend_v2.layouts.app')

@section('title', 'Program V2')
@section('meta_description', app()->getLocale() === 'th'
  ? 'เลือกโปรแกรมที่เหมาะกับคุณ ทั้งครึ่งวันและเต็มวัน พร้อมกิจกรรมเรียนรู้พฤติกรรมช้างอย่างใกล้ชิดโดยไม่ทำร้ายสัตว์'
  : 'Explore half-day and full-day elephant experiences designed around welfare, learning, and respectful interaction.')
@push('styles')
<style>
/* =========================
   ABOUT (Elegant + Elephant mood)
   ========================= */
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

/* responsive */
@media (max-width: 991px){
  .about-hero__title{ font-size:40px; }
}
@media (max-width: 575px){
  .about-hero{ min-height: 480px; }
  .about-hero__title{ font-size:34px; }
  .program-title{ font-size:24px; }
}

/* Filter panel */
.program-filter{
  position:relative;
  margin-bottom:18px;
}
.program-filter__bar{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:14px;
  flex-wrap:wrap;
}
.program-filter__toggle{
  display:inline-flex;
  align-items:center;
  gap:10px;
  min-height:46px;
  padding:0 20px;
  border:1px solid rgba(0,0,0,.12);
  border-radius:999px;
  background:#fff;
  color:#2b2621;
  font-size:15px;
  font-weight:700;
  cursor:pointer;
  transition:border-color .2s ease, box-shadow .2s ease;
}
.program-filter__toggle svg{ width:18px; height:18px; }
.program-filter__toggle:hover,
.program-filter__toggle[aria-expanded="true"]{
  border-color:#b5db2a;
  box-shadow:0 8px 20px rgba(0,0,0,.06);
}
.program-filter__count{
  display:inline-grid;
  place-items:center;
  min-width:22px;
  height:22px;
  padding:0 6px;
  border-radius:999px;
  background:#b5db2a;
  color:#fff;
  font-size:12px;
}
.program-filter__count[hidden]{ display:none; }

.program-filter__panel{
  position:absolute;
  top:calc(100% + 10px);
  left:0;
  z-index:20;
  width:min(720px, 100%);
  padding:20px;
  background:#fff;
  border:1px solid rgba(0,0,0,.08);
  border-radius:18px;
  box-shadow:0 24px 60px rgba(0,0,0,.16);
}
.program-filter__panel[hidden]{ display:none; }
.program-filter__head{
  display:none;
  align-items:center;
  justify-content:space-between;
  margin-bottom:12px;
  font-size:17px;
}
.program-filter__close{
  border:0;
  background:none;
  font-size:26px;
  line-height:1;
  color:#2b2621;
  cursor:pointer;
}
.program-filter__groups{
  display:grid;
  grid-template-columns:repeat(2, minmax(0, 1fr));
  gap:18px 24px;
}
.program-filter__group{
  margin:0;
  padding:0;
  border:0;
}
.program-filter__group--wide{ grid-column:1 / -1; }
.program-filter__group legend{
  padding:0;
  margin-bottom:8px;
  font-size:13px;
  font-weight:700;
  letter-spacing:.06em;
  text-transform:uppercase;
  color:#8a7f73;
}
.program-filter__group--wide{
  display:block;
}
.program-filter__group--wide legend{ margin-bottom:10px; }
.program-filter__option{
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin:0 8px 8px 0;
  padding:8px 14px;
  border:1px solid rgba(0,0,0,.12);
  border-radius:999px;
  font-size:14px;
  color:#2b2621;
  cursor:pointer;
  transition:border-color .2s ease, background-color .2s ease;
}
.program-filter__option input{
  width:16px;
  height:16px;
  accent-color:#7f9c13;
  margin:0;
}
.program-filter__option:has(input:checked){
  border-color:#b5db2a;
  background:#f4fae1;
}
.program-filter__actions{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:12px;
  margin-top:18px;
  padding-top:16px;
  border-top:1px solid rgba(0,0,0,.08);
}
.program-filter__clear{
  color:#6b6156;
  font-size:14px;
  text-decoration:underline;
}
.program-filter__apply{
  min-height:46px;
  padding:0 24px;
  border:0;
  border-radius:999px;
  background:#b5db2a;
  color:#fff;
  font-size:14px;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:.06em;
  cursor:pointer;
}
.program-filter__apply:hover{ background:#a6cb24; }

/* On a phone the panel takes the screen, which is the only way the four
   groups fit without pinching. */
@media (max-width:767px){
  .program-filter__panel{
    position:fixed;
    inset:auto 0 0 0;
    top:auto;
    width:100%;
    max-height:85vh;
    overflow-y:auto;
    border-radius:20px 20px 0 0;
    z-index:1000;
  }
  .program-filter__head{ display:flex; }
  .program-filter__groups{ grid-template-columns:1fr; }
  .program-filter__actions{
    position:sticky;
    bottom:0;
    background:#fff;
    margin-top:14px;
  }
}

/* Program list v2 */
.program-list{
  padding: 80px 0 40px;
  background:#f7f5f1;
}
.program-list.is-loading .program-grid,
.program-list.is-loading .program-empty{
  opacity:.22;
  pointer-events:none;
}
.program-list.is-loading .program-chip,
.program-list.is-loading .program-filter__prev,
.program-list.is-loading .program-filter__next{
  pointer-events:none;
}
.program-filter{
  margin-bottom: 26px;
}
.program-filter__rail{
  position: relative;
}
.program-filter__row{
  display:flex;
  align-items:center;
  gap:10px;
  flex-wrap: nowrap;
  overflow-x:auto;
  white-space:nowrap;
  padding: 0 56px 8px 56px;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
}
.program-filter__row::-webkit-scrollbar{ display:none; }
.program-chip{
  display:inline-flex;
  flex: 0 0 auto;
  align-items:center;
  gap:7px;
  padding:10px 16px;
  border-radius:999px;
  border:1px solid #e0d9d0;
  background:#ece8e2;
  color:#38322c;
  font-size:15px;
  line-height:1;
  text-decoration:none;
  font-family:inherit;
  cursor:pointer;
}
.program-chip--lead{
  background:#e5e0d8;
  font-weight:700;
}
.program-chip__count{
  min-width:22px;
  height:22px;
  border-radius:999px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:12px;
  background:#bba383;
  color:#fff;
  padding:0 6px;
}
.program-chip--selectable.is-active{
  background:#bba383;
  border-color:#bba383;
  color:#fff;
}
.program-chip__close{
  display:none;
  font-size:14px;
  line-height:1;
}
.program-chip--selectable.is-active .program-chip__close{
  display:inline-block;
}
.program-chip--arrow{
  min-width:40px;
  justify-content:center;
  padding:10px 0;
  cursor:pointer;
}
.program-filter__next{
  position:absolute;
  top:0;
  right:0;
  width:42px;
  height:42px;
  border-radius:999px;
  border:1px solid #e0d9d0;
  background:#ece8e2;
  color:#38322c;
  font-size:24px;
  line-height:1;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
}
.program-filter__prev{
  position:absolute;
  top:0;
  left:0;
  width:42px;
  height:42px;
  border-radius:999px;
  border:1px solid #e0d9d0;
  background:#ece8e2;
  color:#38322c;
  font-size:24px;
  line-height:1;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  cursor:pointer;
}
.program-filter__next[hidden]{
  display:none !important;
}
.program-filter__prev[hidden]{
  display:none !important;
}
.program-filter__prev.is-disabled{
  opacity:.45;
  cursor:default;
}
.program-filter__next.is-disabled{
  opacity:.45;
  cursor:default;
}
.program-filter__result{
  display:flex;
  align-items:center;
  gap:8px;
  color:#5f5850;
  font-size:20px;
}
.program-filter__result strong{
  color:#2f2924;
  font-weight:700;
}
.program-filter__info{
  width:20px;
  height:20px;
  border-radius:999px;
  border:1px solid #b6aea5;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:12px;
  color:#8d8479;
}
.program-grid{
  position: relative;
}
.program-grid .owl-stage{
  display:flex;
}
.program-grid .owl-item{
  display:flex;
  height:auto;
}
.program-item{
  display:flex;
  flex-direction:column;
  background:#2f2f2f;
  color:#fff;
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 8px 18px rgba(0,0,0,.18);
  min-height:100%;
  width:100%;
}
.program-item[hidden]{
  display:none !important;
}
.program-empty{
  color:#5f5850;
  font-size:16px;
  margin: 10px 0 0;
}
.program-loading{
  display:none;
  min-height:138px;
  align-items:center;
  justify-content:center;
  padding: 18px 0 28px;
}
.program-list.is-loading .program-loading{
  display:flex;
}
.program-loading__panel{
  width:min(720px, 100%);
  padding: 22px 28px;
  border-radius:12px;
  background:rgba(255,255,255,.48);
  border:1px solid rgba(224,217,208,.85);
}
.program-loading__line{
  height:15px;
  border-radius:999px;
  background:linear-gradient(90deg, #e8e2d9 0%, #f8f5f0 42%, #d8ccbd 74%, #e8e2d9 100%);
  background-size:220% 100%;
  animation: program-loading-shimmer 1.05s ease-in-out infinite;
}
.program-loading__line + .program-loading__line{
  margin-top:14px;
}
.program-loading__line:nth-child(2){
  width:76%;
}
.program-loading__line:nth-child(3){
  width:54%;
}
@keyframes program-loading-shimmer{
  0%{ background-position: 120% 0; }
  100%{ background-position: -120% 0; }
}
.program-media{
  display:block;
  width:100%;
  aspect-ratio: 4 / 3;
  overflow:hidden;
  background:#eae6e1;
}
.program-title a{
  color:inherit;
  text-decoration:none;
}
.program-title a:hover,
.program-title a:focus-visible{
  text-decoration:underline;
}
.program-media img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}
.program-content{
  padding:16px 18px 18px;
  display:flex;
  flex-direction:column;
  gap:8px;
  min-height:220px;
  /* Fill the card so the description takes the slack and every Book Now
     button lines up, however long the text above it is. */
  flex:1 1 auto;
}
.program-title{
  text-align:center;
  font-size:16px;
  line-height:1.35;
  font-weight:700;
  color:#fff;
  margin-bottom:0;
  /* Clamp to two whole lines: a fixed height cut the second line in half
     once the font grew on smaller screens. min-height keeps cards aligned. */
  display:-webkit-box;
  -webkit-line-clamp:2;
  -webkit-box-orient:vertical;
  overflow:hidden;
  min-height:2.7em;
}
.program-meta{
  text-align:center;
  font-size:12px;
  letter-spacing:.06em;
  text-transform:uppercase;
  color:#d7d7d7;
}
.program-price{
  display:flex;
  flex-direction:column;
  align-items:center;
  gap:2px;
  line-height:1.4;
  letter-spacing:.04em;
}
.program-price b{
  font-size:15px;
  font-weight:700;
  color:#fff;
}
.program-price i{
  font-style:normal;
  font-size:12px;
  color:#d7d7d7;
}
.program-provinces{ display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px; }
.program-provinces a{ text-decoration:none; }
.program-province{ opacity:.8; }
.program-desc{
  font-size:13px;
  line-height:1.6;
  color:#f1f1f1;
  margin-bottom:18px;
  display:-webkit-box;
  -webkit-line-clamp: 6;
  -webkit-box-orient: vertical;
  overflow:hidden;
  flex:1 1 auto;
}
.owl-theme .owl-nav [class*=owl-] {
 
    border-radius: 50px;

}
.program-content .btn-primary{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  width: calc(100% - 36px);
  min-height:46px;
  margin-top:auto;
  align-self:center;
  border-radius:999px;
  background:#b5db2a;
  color:#fff;
  font-size:13px;
  font-weight:700;
  letter-spacing:.08em;
  text-transform:uppercase;
  text-decoration:none;
  border:0;
}
.program-content .btn-primary:hover{
  background:#a6cb24;
  color:#fff;
}
.program-grid .owl-nav{
  position:absolute;
  top:40%;
  left:8px;
  right:8px;
  display:flex;
  justify-content:space-between;
  pointer-events:none;
}
.program-grid .owl-nav button{
  pointer-events:auto;
  width:52px;
  height:52px;
  border-radius:50%;
  border:0;
  background:#fff !important;
  color:#333 !important;
  font-size:32px;
  font-weight:900;
  box-shadow:0 4px 12px rgba(0,0,0,.15);
}
.program-grid .owl-nav button .gr-nav{
  font-size:32px;
  line-height:1;
}
.program-grid .owl-dots{
  margin-top:14px;
  text-align:center;
}
.program-grid .owl-dot span{
  width:8px;
  height:8px;
}
@media (max-width: 992px){
  /* Same size as desktop: 22px pushed long titles past two lines here. */
  .program-title{ font-size:16px; }
  .program-filter__result{ font-size:16px; }
}
@media (max-width: 767px){
  .program-content{
    min-height:0;
  }
  .program-title{
    font-size:15px;
  }
  /* At one item per view the card is tall enough that the arrows' 40% offset
     lands on the program title. Anchor them to the media block instead: this
     box tracks it (card starts at the carousel top, media is 4/3), so the
     arrows stay centred on the image whatever the title length. */
  .program-grid .owl-nav{
    top: 0;
    aspect-ratio: 4 / 3;
    align-items: center;
  }
}
</style>
@endpush

@section('content')
@php
  $programHeroBackground = \App\Models\PageMedia::url('v2.program.hero.background', Vite::asset('resources/frontend/images/bg-chang.webp'));
@endphp

{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ $programHeroBackground }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">SMALL ELEPHANTS</div>
    <h1 class="about-hero__title">{{ app()->getLocale() === 'en' ? 'Our Elephant Tours' : 'โปรแกรมท่องเที่ยว' }}</h1>
  </div>
</section>


<section id="program-list" class="program-list">
  <div class="container">
    {{-- One button, and everything to choose from behind it. It is a plain GET
         form, so the filters work without JavaScript; the script only opens the
         panel and keeps the count on the button fresh. --}}
    <form class="program-filter" method="GET" action="{{ route('frontend.program') }}" id="programFilters">
      @if($searchTerm !== '')
        <input type="hidden" name="q" value="{{ $searchTerm }}">
      @endif

      <div class="program-filter__bar">
        <button type="button" class="program-filter__toggle js-filter-toggle" aria-expanded="false" aria-controls="programFilterPanel">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" aria-hidden="true">
            <path d="M4 6h16M7 12h10M10 18h4"/>
          </svg>
          <span>{{ __('tour_filter.title') }}</span>
          <span class="program-filter__count js-selected-count" @if(!$selectedCount) hidden @endif>{{ $selectedCount }}</span>
        </button>

        <div class="program-filter__result">
          <strong class="js-result-count">{{ $tours->count() }}</strong>
          {{ app()->getLocale() === 'en' ? 'results' : 'ผลลัพธ์' }}
        </div>
      </div>

      <div class="program-filter__panel" id="programFilterPanel" hidden>
        <div class="program-filter__head">
          <strong>{{ __('tour_filter.title') }}</strong>
          <button type="button" class="program-filter__close js-filter-close" aria-label="{{ __('tour_filter.close') }}">&times;</button>
        </div>

        <div class="program-filter__groups">
          @if($provinces->count() > 1)
            <fieldset class="program-filter__group">
              <legend>{{ __('tour_filter.groups.location') }}</legend>
              @foreach($provinces as $province)
                <label class="program-filter__option">
                  <input type="checkbox" name="province[]" value="{{ $province->slug }}" @checked(in_array($province->slug, $selectedProvinces, true))>
                  <span>{{ $province->name() }}</span>
                </label>
              @endforeach
            </fieldset>
          @endif

          <fieldset class="program-filter__group">
            <legend>{{ __('tour_filter.groups.duration') }}</legend>
            @foreach(\App\Models\Tour::DURATIONS as $duration)
              <label class="program-filter__option">
                <input type="checkbox" name="duration[]" value="{{ $duration }}" @checked(in_array($duration, $selectedDurations, true))>
                <span>{{ __('tour_filter.durations.' . $duration) }}</span>
              </label>
            @endforeach
          </fieldset>

          <fieldset class="program-filter__group">
            <legend>{{ __('tour_filter.groups.experience_type') }}</legend>
            @foreach(\App\Models\Tour::EXPERIENCE_TYPES as $type)
              <label class="program-filter__option">
                <input type="checkbox" name="experience[]" value="{{ $type }}" @checked(in_array($type, $selectedExperiences, true))>
                <span>{{ __('tour_filter.experience_types.' . $type) }}</span>
              </label>
            @endforeach
          </fieldset>

          <fieldset class="program-filter__group program-filter__group--wide">
            <legend>{{ __('tour_filter.groups.activities') }}</legend>
            @foreach(($availableTags ?? collect()) as $tag)
              <label class="program-filter__option">
                <input type="checkbox" name="tags[]" value="{{ $tag->slug }}" @checked(in_array($tag->slug, $selectedTags, true))>
                <span>{{ $tag->label }}</span>
              </label>
            @endforeach
          </fieldset>
        </div>

        <div class="program-filter__actions">
          <a class="program-filter__clear" href="{{ route('frontend.program') }}#program-list">{{ __('tour_filter.clear') }}</a>
          <button type="submit" class="program-filter__apply">{{ __('tour_filter.apply', ['count' => $tours->count()]) }}</button>
        </div>
      </div>
    </form>

    <div class="program-loading js-program-loading" hidden aria-live="polite" aria-label="{{ app()->getLocale() === 'en' ? 'Loading programs' : 'กำลังโหลดโปรแกรม' }}">
      <div class="program-loading__panel">
        <div class="program-loading__line"></div>
        <div class="program-loading__line"></div>
        <div class="program-loading__line"></div>
      </div>
    </div>

    <div class="program-grid owl-carousel owl-theme js-program-slider">
      @forelse($tours as $tour)
        @php($tr = $tour->translation())
        <div class="program-item js-program-item" data-tour-tags="{{ json_encode($tour->tags->pluck('slug')->values()->all()) }}">
          <a class="program-media" href="{{ route('frontend.tours.show.v2', $tour->slug) }}" tabindex="-1" aria-hidden="true">
            <img src="{{ $tour->thumbnail }}" alt="{{ $tr?->name ?? $tour->name }}">
          </a>
          <div class="program-content">
            <div class="program-title"><a href="{{ route('frontend.tours.show.v2', $tour->slug) }}">{{ $tr?->name ?? $tour->name }}</a></div>
            <div class="program-meta">{{ strtoupper(__('common.program')) }}@if($tour->province) &middot; {{ $tour->province->name() }}@endif</div>
            <span class="program-price">
              <b>{{ __('common.price_adult') }} THB {{ number_format($tour->price_adult ?? 0) }}</b>
              <i>{{ __('common.price_child') }} THB {{ number_format($tour->price_child ?? 0) }}</i>
            </span>
            <div class="program-desc">
              {{ \Illuminate\Support\Str::limit(strip_tags($tr?->short_description ?? $tr?->description ?? $tour->short_description ?? $tour->description ?? ''), 220) }}
            </div>
            <a class="btn-primary" href="{{ route('frontend.tours.show.v2', $tour->slug) }}">{{ __('common.book_now') }}</a>
          </div>
        </div>
      @empty
        <p>{{ __('common.no_tours_list') }}</p>
      @endforelse
    </div>
    <p class="program-empty js-program-empty" hidden>{{ __('common.no_tours_filter') }}</p>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('programFilters');
  var slider = window.jQuery ? window.jQuery('.js-program-slider') : null;

  function initProgramSlider() {
    if (!slider || !slider.length || !window.jQuery || !window.jQuery.fn || !window.jQuery.fn.owlCarousel) return;
    if (slider.hasClass('owl-loaded')) return;

    slider.owlCarousel({
      loop: false,
      margin: 24,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplayHoverPause: true,
      nav: true,
      dots: true,
      navText: [
        '<span class="gr-nav gr-prev">&lsaquo;</span>',
        '<span class="gr-nav gr-next">&rsaquo;</span>'
      ],
      responsive: {
        0:    { items: 1 },
        768:  { items: 2 },
        1024: { items: 3 },
        1280: { items: 4 }
      }
    });
  }

  initProgramSlider();

  if (!form) return;

  // The form submits on its own; this only opens the panel and keeps the
  // number on the button in step with the boxes.
  var toggle = form.querySelector('.js-filter-toggle');
  var panel = document.getElementById('programFilterPanel');
  var count = form.querySelector('.js-selected-count');
  var closeBtn = form.querySelector('.js-filter-close');

  function setOpen(open) {
    panel.hidden = !open;
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  }

  function syncCount() {
    var ticked = form.querySelectorAll('input[type="checkbox"]:checked').length;
    count.textContent = String(ticked);
    count.hidden = ticked === 0;
  }

  toggle.addEventListener('click', function () { setOpen(panel.hidden); });
  if (closeBtn) closeBtn.addEventListener('click', function () { setOpen(false); });
  form.addEventListener('change', syncCount);

  document.addEventListener('click', function (e) {
    if (!panel.hidden && !form.contains(e.target)) setOpen(false);
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') setOpen(false);
  });

  syncCount();
});
</script>
@endpush

@endsection
