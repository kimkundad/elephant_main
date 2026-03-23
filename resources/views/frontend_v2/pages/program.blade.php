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

@media (min-width: 1200px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 1140px;
    }
}

/* Program list v2 */
.program-list{
  padding: 80px 0 40px;
  background:#f7f5f1;
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
.program-media{
  width:100%;
  aspect-ratio: 4 / 3;
  overflow:hidden;
  background:#eae6e1;
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
}
.program-title{
height: 50px;
    overflow: hidden;
    font-size: 16px;
  font-weight:700;
  color:#fff;
  margin-bottom:12px;
}
.program-meta{
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  font-size:14px;
  letter-spacing:.08em;
  text-transform:uppercase;
  color:#d7d7d7;
}
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
  .program-title{ font-size:22px; }
  .program-filter__result{ font-size:16px; }
}
@media (max-width: 767px){
  .program-content{
    min-height:0;
  }
  .program-title{
    font-size:15px;
    margin-bottom:8px;
  }
}
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">SMALL ELEPHANTS</div>
    <h1 class="about-hero__title">Our Elephant Tours</h1>

  </div>
</section>


<section id="program-list" class="program-list">
  <div class="container">
    <div class="program-filter">
      <div class="program-filter__rail">
        <button type="button" class="program-filter__prev js-filter-prev" aria-label="Scroll filters left">&#8249;</button>
        <div class="program-filter__row">
          <button type="button" class="program-chip program-chip--lead js-filter-reset">
            <span>&#9881;</span>
            <span>{{ app()->getLocale() === 'en' ? 'Filters' : 'ตัวกรอง' }}</span>
            <span class="program-chip__count js-selected-count">{{ count($selectedTags ?? []) }}</span>
          </button>
          @foreach(($availableTags ?? collect()) as $tag)
            @php($active = in_array($tag->slug, $selectedTags ?? [], true))
            <button type="button" class="program-chip program-chip--selectable js-filter-chip {{ $active ? 'is-active' : '' }}" data-tag-slug="{{ $tag->slug }}">
              {{ $tag->label }} <span class="program-chip__close">&times;</span>
            </button>
          @endforeach
        </div>
        <button type="button" class="program-filter__next js-filter-next" aria-label="Scroll filters">&#8250;</button>
      </div>
      <div class="program-filter__result">
        <strong class="js-result-count">{{ $tours->count() }}</strong> {{ app()->getLocale() === 'en' ? 'results' : 'ผลลัพธ์' }}
        <span class="program-filter__info">i</span>
      </div>
    </div>

    <div class="program-grid owl-carousel owl-theme js-program-slider">
      @forelse($tours as $tour)
        <div class="program-item js-program-item" data-tour-tags="{{ $tour->tags->pluck('slug')->implode(',') }}">
          <div class="program-media">
            <img src="{{ $tour->thumbnail }}" alt="{{ $tour->name }}">
          </div>
          <div class="program-content">
            <div class="program-title">{{ $tour->name }}</div>
            <div class="program-meta">
              <span>From THB {{ number_format($tour->min_price ?? 0) }}</span>
            </div>
            <div class="program-desc">
              {{ \Illuminate\Support\Str::limit(strip_tags($tour->short_description ?? $tour->description), 220) }}
            </div>
            <a class="btn-primary" href="{{ route('frontend.tours.show.v2', $tour->slug) }}">Book Now</a>
          </div>
        </div>
      @empty
        <p>No tours available at the moment.</p>
      @endforelse
    </div>
    <p class="program-empty js-program-empty" hidden>{{ app()->getLocale() === 'en' ? 'No matching tours found.' : 'ไม่พบโปรแกรมที่ตรงกับตัวกรอง' }}</p>
  </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var row = document.querySelector('.program-filter__row');
  var prevBtn = document.querySelector('.js-filter-prev');
  var nextBtn = document.querySelector('.js-filter-next');
  var chips = Array.prototype.slice.call(document.querySelectorAll('.js-filter-chip'));
  var resetBtn = document.querySelector('.js-filter-reset');
  var selectedCountEl = document.querySelector('.js-selected-count');
  var resultCountEl = document.querySelector('.js-result-count');
  var items = Array.prototype.slice.call(document.querySelectorAll('.js-program-item'));
  var emptyState = document.querySelector('.js-program-empty');
  var sliderSelector = '.js-program-slider';
  var sliderEl = document.querySelector(sliderSelector);
  var slider = window.jQuery ? window.jQuery(sliderSelector) : null;

  if (!row || !prevBtn || !nextBtn) return;

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
        0: { items: 1 },
        768: { items: 2 },
        1024: { items: 3 }
      }
    });
  }

  function renderProgramSlider() {
    if (!sliderEl) return;

    if (slider && slider.length && slider.hasClass('owl-loaded')) {
      slider.trigger('destroy.owl.carousel');
    }

    sliderEl.innerHTML = '';
    items.forEach(function (item) {
      if (!item.hidden) {
        sliderEl.appendChild(item);
      }
    });

    slider = window.jQuery ? window.jQuery(sliderSelector) : null;
    initProgramSlider();
  }

  function syncButtons() {
    var hasOverflow = row.scrollWidth > row.clientWidth + 1;
    var atStart = row.scrollLeft <= 1;
    var atEnd = row.scrollLeft + row.clientWidth >= row.scrollWidth - 2;
    prevBtn.classList.toggle('is-disabled', !hasOverflow || atStart);
    nextBtn.classList.toggle('is-disabled', !hasOverflow || atEnd);
  }

  prevBtn.addEventListener('click', function () {
    if (prevBtn.classList.contains('is-disabled')) return;
    row.scrollBy({ left: -260, behavior: 'smooth' });
  });

  nextBtn.addEventListener('click', function () {
    if (nextBtn.classList.contains('is-disabled')) return;
    row.scrollBy({ left: 260, behavior: 'smooth' });
  });

  row.addEventListener('scroll', syncButtons, { passive: true });
  window.addEventListener('resize', syncButtons);
  syncButtons();

  if (!chips.length || !items.length) return;

  function getSelectedTags() {
    return chips
      .filter(function (chip) { return chip.classList.contains('is-active'); })
      .map(function (chip) { return chip.getAttribute('data-tag-slug') || ''; })
      .filter(Boolean);
  }

  function updateUrl(selectedTags) {
    var url = new URL(window.location.href);
    url.searchParams.delete('tags[]');
    url.searchParams.delete('tags');
    selectedTags.forEach(function (tag) {
      url.searchParams.append('tags[]', tag);
    });
    window.history.replaceState({}, '', url.toString());
    return url;
  }

  function clearQueryAndReload(selectedTags) {
    var url = new URL(window.location.href);
    url.searchParams.delete('q');
    url.searchParams.delete('tags[]');
    url.searchParams.delete('tags');
    selectedTags.forEach(function (tag) {
      url.searchParams.append('tags[]', tag);
    });
    window.location.href = url.toString();
  }

  function applyFilter() {
    var selectedTags = getSelectedTags();
    var visibleCount = 0;
    var currentUrl = new URL(window.location.href);
    var hasSearchQuery = !!(currentUrl.searchParams.get('q') || '').trim();

    items.forEach(function (item) {
      var itemTags = (item.getAttribute('data-tour-tags') || '')
        .split(',')
        .map(function (tag) { return tag.trim(); })
        .filter(Boolean);

      var matched = selectedTags.length === 0 || selectedTags.some(function (tag) {
        return itemTags.indexOf(tag) !== -1;
      });

      item.hidden = !matched;
      if (matched) visibleCount += 1;
    });

    if (selectedCountEl) selectedCountEl.textContent = String(selectedTags.length);
    if (resultCountEl) resultCountEl.textContent = String(visibleCount);
    if (emptyState) emptyState.hidden = visibleCount !== 0;

    if (hasSearchQuery && selectedTags.length === 0) {
      clearQueryAndReload([]);
      return;
    }

    updateUrl(selectedTags);
    renderProgramSlider();
  }

  chips.forEach(function (chip) {
    chip.addEventListener('click', function () {
      chip.classList.toggle('is-active');
      applyFilter();
    });
  });

  if (resetBtn) {
    resetBtn.addEventListener('click', function () {
      chips.forEach(function (chip) { chip.classList.remove('is-active'); });
      applyFilter();
    });
  }

  applyFilter();
});
</script>
@endpush

@endsection
