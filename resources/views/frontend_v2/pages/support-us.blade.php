@extends('frontend_v2.layouts.app')

@section('title', __('support_us.page_title'))

@push('styles')
@include('frontend_v2.partials.about-styles')
@endpush

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">{{ __('support_us.hero.kicker') }}</div>
    <h1 class="about-hero__title">{{ __('support_us.hero.title') }}</h1>
    <p class="about-hero__lead">{{ __('support_us.hero.lead') }}</p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">{{ __('support_us.hero.book_tour') }}</a>
      <a href="#ways" class="btn-outline-soft">{{ __('support_us.hero.ways_to_help') }}</a>
    </div>
  </div>
</section>

{{-- WAYS --}}
<section id="ways" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-7">
        <div class="about-eyebrow">{{ __('support_us.ways.eyebrow') }}</div>
        <h2 class="about-title">{{ __('support_us.ways.title') }}</h2>
        <p class="about-text">{{ __('support_us.ways.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('support_us.ways.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.ways.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.ways.items.2') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.ways.items.3') }}</li>
        </ul>
      </div>
      <div class="col-md-5">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('support_us.ways.card_title') }}</div>
          <div class="about-cta__text">{{ __('support_us.ways.card_text') }}</div>
          <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft btn-primary-soft--full">{{ __('support_us.ways.card_cta') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- SUPPORT CTA --}}
<section class="about-section about-section--dark" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-dark__overlay"></div>
  <div class="container">
    <div class="row align-items-center about-gap">
      <div class="col-md-7">
        <div class="about-eyebrow about-eyebrow--light">{{ __('support_us.initiatives.eyebrow') }}</div>
        <h2 class="about-title about-title--light">{{ __('support_us.initiatives.title') }}</h2>
        <p class="about-sub about-sub--light">{{ __('support_us.initiatives.intro') }}</p>
        <ul class="about-list">
          <li><span>&#10003;</span> {{ __('support_us.initiatives.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.initiatives.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.initiatives.items.2') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.initiatives.items.3') }}</li>
        </ul>
      </div>
      <div class="col-md-5">
        <div class="about-cta">
          <div class="about-cta__title">{{ __('support_us.initiatives.card_title') }}</div>
          <div class="about-cta__text">{{ __('support_us.initiatives.card_text') }}</div>
          <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft btn-primary-soft--full">{{ __('support_us.initiatives.card_cta') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- FILMS --}}
<section class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow">{{ __('support_us.stories.eyebrow') }}</div>
        <h2 class="about-title">{{ __('support_us.stories.title') }}</h2>
        <p class="about-text">{{ __('support_us.stories.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('support_us.stories.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.stories.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.stories.items.2') }}</li>
        </ul>
      </div>
      <div class="col-md-6">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('support_us.stories.card_title') }}</div>
          <div class="about-cta__text">{{ __('support_us.stories.card_text') }}</div>
          <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft btn-primary-soft--full">{{ __('support_us.stories.card_cta') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- COMMUNITY --}}
<section class="about-section about-section--soft">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow">{{ __('support_us.community.eyebrow') }}</div>
        <h2 class="about-title">{{ __('support_us.community.title') }}</h2>
        <p class="about-text">{{ __('support_us.community.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('support_us.community.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.community.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('support_us.community.items.2') }}</li>
        </ul>
      </div>
      <div class="col-md-6">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('support_us.community.card_title') }}</div>
          <div class="about-cta__text">{{ __('support_us.community.card_text') }}</div>
          <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft btn-primary-soft--full">{{ __('support_us.community.card_cta') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection