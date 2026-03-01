@extends('frontend_v2.layouts.app')

@section('title', 'About')

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

.about-section{
  padding: 74px 0;
}
.about-section--soft{
  background: #f6f6f4;
}

.about-gap{ row-gap: 26px; }

.about-eyebrow{
  font-size:12px;
  letter-spacing:.24em;
  color:#777;
  margin-bottom:12px;
}
.about-eyebrow--light{ color:rgba(255,255,255,.75); }

.about-title{
  font-size:36px;
  line-height:1.15;
  margin-bottom:14px;
  color:#111;
}
.about-title--light{ color:#fff; }

.about-sub{
  color:#666;
  max-width: 860px;
  margin: 0 auto 18px;
  line-height:1.8;
}
.about-sub--light{ color:rgba(255,255,255,.85); }

.about-text{
  color:#444;
  line-height:1.9;
  margin-bottom:14px;
  font-size:16px;
}

.about-photo-grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:14px;
}
.about-photo{
  border-radius:16px;
  min-height: 180px;
  background-size: cover;
  background-position:center;
  box-shadow:0 22px 50px rgba(0,0,0,.14);
  position:relative;
  overflow:hidden;
}
.about-photo::after{
  content:"";
  position:absolute;
  inset:0;
  background: radial-gradient(ellipse at center, rgba(0,0,0,0) 25%, rgba(0,0,0,.45) 100%);
}
.about-photo--big{ min-height: 270px; grid-row: span 2; }
.about-photo--wide{ grid-column: span 2; min-height: 190px; }

.about-caption{
  margin-top: 14px;
  color:#666;
  font-style: italic;
  line-height:1.7;
}

.about-cards{ margin-top: 22px; }
.about-card{
  background:#fff;
  border-radius:18px;
  padding:26px 22px;
  height:100%;
  box-shadow:0 20px 50px rgba(0,0,0,.10);
  border:1px solid rgba(0,0,0,.04);
}
.about-card__icon{
  width:44px;
  height:44px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:14px;
  background:rgba(0,0,0,.05);
  margin-bottom:14px;
  font-size:18px;
}
.about-card__title{
  font-size:18px;
  margin-bottom:8px;
  font-weight:800;
}
.about-card__text{
  color:#555;
  line-height:1.8;
  margin:0;
}

.about-impact{
  display:grid;
  grid-template-columns: repeat(4, 1fr);
  gap:14px;
}
.about-impact__item{
  border-radius:18px;
  padding:18px 16px;
  border:1px solid rgba(0,0,0,.06);
  background:#fff;
  box-shadow:0 18px 40px rgba(0,0,0,.08);
  text-align:center;
}
.about-impact__num{
  font-size:28px;
  font-weight:900;
  color:#111;
}
.about-impact__label{
  margin-top:4px;
  color:#666;
  font-size:13px;
}

.about-section--dark{
  position:relative;
  background-size:cover;
  background-position:center;
  padding: 86px 0;
}
.about-dark__overlay{
  position:absolute;
  inset:0;
  background:
    radial-gradient(ellipse at center, rgba(0,0,0,.15) 0%, rgba(0,0,0,.78) 100%),
    linear-gradient(to bottom, rgba(0,0,0,.35), rgba(0,0,0,.65));
}
.about-section--dark .container{ position:relative; z-index:1; }

.about-list{
  list-style:none;
  padding:0;
  margin: 20px 0 0;
}
.about-list li{
  display:flex;
  gap:10px;
  color:rgba(255,255,255,.88);
  line-height:1.9;
  padding:8px 0;
  border-bottom:1px solid rgba(255,255,255,.10);
}
.about-list--dark li{
  color:#222;
  border-bottom:1px solid rgba(0,0,0,.10);
}
.about-list--dark li span{
  color:#111;
}
.about-list li span{
  display:inline-flex;
  width:22px;
  height:22px;
  border-radius:999px;
  align-items:center;
  justify-content:center;
  background:rgba(255,255,255,.12);
  flex:0 0 22px;
}

.about-cta{
  background:rgba(255,255,255,.06);
  border:1px solid rgba(255,255,255,.14);
  border-radius:18px;
  padding:28px 22px;
  backdrop-filter: blur(6px);
}
.about-cta--light{
  background:#ffffff;
  border:1px solid #e6e6e6;
  box-shadow:0 14px 30px rgba(0,0,0,.08);
  backdrop-filter: none;
}
.about-cta__title{
  color:#fff;
  font-size:20px;
  font-weight:900;
  margin-bottom:8px;
}
.about-cta__text{
  color:rgba(255,255,255,.85);
  line-height:1.8;
  margin-bottom:16px;
}
.about-cta--light .about-cta__title{ color:#111; }
.about-cta--light .about-cta__text{ color:#444; }
.about-cta--light .about-list li{ color:#444; }
.about-cta--light .about-list li span{ color:#111; }
.btn-primary-soft--full{
  width:100%;
  text-align:center;
}

/* responsive */
@media (max-width: 991px){
  .about-hero__title{ font-size:40px; }
  .about-impact{ grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575px){
  .about-hero{ min-height: 480px; }
  .about-hero__title{ font-size:34px; }
  .about-section{ padding: 56px 0; }
  .about-title{ font-size:28px; }
}

.container,
.elementor-section.elementor-section-boxed > .elementor-container {
    width: 100%;
    margin-right: auto;
    margin-left: auto;
    padding-right: 15px;
    padding-left: 15px;
}
@media (min-width: 576px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 540px;
    }
}
@media (min-width: 768px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 720px;
    }
}
@media (min-width: 992px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 960px;
    }
}
@media (min-width: 1200px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 1140px;
    }
}
@media (min-width: 1400px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 1320px;
    }
}
@media (min-width: 768px) {
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
.row > [class^="col-"],
.row > [class*=" col-"] {
    width: 100%;
    padding-right: 15px;
    padding-left: 15px;
}


</style>
@endpush

@section('content')
@php
  $heroKicker = \App\Models\SiteText::getValue('about.hero.kicker', 'SMALL ELEPHANTS');
  $heroTitle = \App\Models\SiteText::getValue('about.hero.title', 'About Our Ethical Sanctuary');
  $heroLead = \App\Models\SiteText::getValue('about.hero.lead', '');
  $story = json_decode(\App\Models\SiteText::getValue('about.story', '{}'), true) ?: [];
  $principles = json_decode(\App\Models\SiteText::getValue('about.principles', '{}'), true) ?: [];
  $experience = json_decode(\App\Models\SiteText::getValue('about.experience', '{}'), true) ?: [];
  $storyParagraphs = is_array($story['paragraphs'] ?? null) ? $story['paragraphs'] : [];
  $experienceBullets = is_array($experience['bullets'] ?? null) ? $experience['bullets'] : [];

  $aboutHeroBackground = \App\Models\PageMedia::url('v2.about.hero.background', Vite::asset('resources/frontend/images/bg-chang.webp'));
  $aboutStoryImage1 = \App\Models\PageMedia::url('v2.about.story.image_1_big', Vite::asset('resources/frontend/images/welcome-4.png'));
  $aboutStoryImage2 = \App\Models\PageMedia::url('v2.about.story.image_2', Vite::asset('resources/frontend/images/img1.webp'));
  $aboutStoryImage3 = \App\Models\PageMedia::url('v2.about.story.image_3', Vite::asset('resources/frontend/images/img2.webp'));
  $aboutStoryImage4 = \App\Models\PageMedia::url('v2.about.story.image_4_wide', Vite::asset('resources/frontend/images/img3.webp'));
  $aboutExperienceBackground = \App\Models\PageMedia::url('v2.about.experience.background', Vite::asset('resources/frontend/images/bg-chang.webp'));
@endphp
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ $aboutHeroBackground }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">{{ $heroKicker }}</div>
    <h1 class="about-hero__title">{{ $heroTitle }}</h1>
    @if($heroLead)
      <p class="about-hero__lead">{{ $heroLead }}</p>
    @endif

    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">Explore Tours</a>
      <a href="#our-story" class="btn-outline-soft">Our Story</a>
    </div>
  </div>
</section>

{{-- STORY --}}
<section id="our-story" class="about-section">
  <div class="container">
    <div class="row align-items-center about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow">{{ $story['eyebrow'] ?? 'OUR STORY' }}</div>
        <h2 class="about-title">{{ $story['title'] ?? 'Our Story' }}</h2>
        @foreach($storyParagraphs as $paragraph)
          <p class="about-text">{{ $paragraph }}</p>
        @endforeach
      </div>

      <div class="col-md-6">
        <div class="about-photo-grid">
          <div class="about-photo about-photo--big" style="background-image:url('{{ $aboutStoryImage1 }}')"></div>
          <div class="about-photo" style="background-image:url('{{ $aboutStoryImage2 }}')"></div>
          <div class="about-photo" style="background-image:url('{{ $aboutStoryImage3 }}')"></div>
          <div class="about-photo about-photo--wide" style="background-image:url('{{ $aboutStoryImage4 }}')"></div>
        </div>
        @if(!empty($story['caption']))
          <div class="about-caption">{{ $story['caption'] }}</div>
        @endif
      </div>
    </div>
  </div>
</section>

{{-- VALUES --}}
<section class="about-section about-section--soft">
  <div class="container">
    <div class="row">
      <div class="col-md-12 alignc">
        <div class="about-eyebrow">{{ $principles['eyebrow'] ?? 'OUR PRINCIPLES' }}</div>
        <h2 class="about-title">{{ $principles['title'] ?? 'Our Principles' }}</h2>
        @if(!empty($principles['sub']))
          <p class="about-sub">{{ $principles['sub'] }}</p>
        @endif
      </div>
    </div>

    <div class="row about-cards" style="display: flex; flex-wrap: wrap;">
      @foreach(($principles['cards'] ?? []) as $index => $card)
        <div class="col-md-4 mobile-margin-b32" style="width: 100%; margin-bottom: 32px;">
          <div class="about-card">
            <div class="about-card__icon">
              @if($index === 0)
                &#127807;
              @elseif($index === 1)
                &#129505;
              @else
                &#128218;
              @endif
            </div>
            <h3 class="about-card__title">{{ $card['title'] ?? '' }}</h3>
            <p class="about-card__text">{{ $card['text'] ?? '' }}</p>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- WHAT TO EXPECT --}}
<section class="about-section about-section--dark" style="background-image:url('{{ $aboutExperienceBackground }}')">
  <div class="about-dark__overlay"></div>
  <div class="container">
    <div class="row align-items-center about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow about-eyebrow--light">{{ $experience['eyebrow'] ?? 'YOUR EXPERIENCE' }}</div>
        <h2 class="about-title about-title--light">{{ $experience['title'] ?? 'What you will feel here' }}</h2>
        @if(!empty($experience['sub']))
          <p class="about-sub about-sub--light">{{ $experience['sub'] }}</p>
        @endif

        <ul class="about-list">
          @foreach($experienceBullets as $bullet)
            <li><span>&#10003;</span> {{ $bullet }}</li>
          @endforeach
        </ul>
      </div>

      <div class="col-md-6">
        <div class="about-cta">
          <div class="about-cta__title">{{ $experience['cta_title'] ?? 'Ready to meet our elephants?' }}</div>
          @if(!empty($experience['cta_text']))
            <div class="about-cta__text">{{ $experience['cta_text'] }}</div>
          @endif
          <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft btn-primary-soft--full">
            {{ $experience['cta_button'] ?? 'View Tours' }}
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
