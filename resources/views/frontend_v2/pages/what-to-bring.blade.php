@extends('frontend_v2.layouts.app')

@section('title', __('what_to_bring.page_title'))

@push('styles')
@include('frontend_v2.partials.about-styles')
@endpush

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">{{ __('what_to_bring.hero.kicker') }}</div>
    <h1 class="about-hero__title">{{ __('what_to_bring.hero.title') }}</h1>
    <p class="about-hero__lead">
      {{ __('what_to_bring.hero.lead') }}
    </p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">{{ __('what_to_bring.hero.explore_tours') }}</a>
      <a href="#packing-list" class="btn-outline-soft">{{ __('what_to_bring.hero.checklist') }}</a>
    </div>
  </div>
</section>

{{-- PACKING LIST --}}
<section id="packing-list" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow">{{ __('what_to_bring.packing.eyebrow') }}</div>
        <h2 class="about-title">{{ __('what_to_bring.packing.title') }}</h2>
        <p class="about-text">
          {{ __('what_to_bring.packing.intro') }}
        </p>

        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('what_to_bring.packing.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('what_to_bring.packing.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('what_to_bring.packing.items.2') }}</li>
          <li><span>&#10003;</span> {{ __('what_to_bring.packing.items.3') }}</li>
          <li><span>&#10003;</span> {{ __('what_to_bring.packing.items.4') }}</li>
        </ul>
      </div>

      <div class="col-md-6">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('what_to_bring.avoid.title') }}</div>
          <div class="about-cta__text">
            {{ __('what_to_bring.avoid.intro') }}
          </div>
          <ul class="about-list" style="margin-top:16px;">
            <li><span>&#8226;</span> {{ __('what_to_bring.avoid.items.0') }}</li>
            <li><span>&#8226;</span> {{ __('what_to_bring.avoid.items.1') }}</li>
            <li><span>&#8226;</span> {{ __('what_to_bring.avoid.items.2') }}</li>
            <li><span>&#8226;</span> {{ __('what_to_bring.avoid.items.3') }}</li>
          </ul>
          <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft btn-primary-soft--full" style="margin-top:18px;">{{ __('what_to_bring.avoid.cta') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- WEATHER --}}
<section class="about-section about-section--soft">
  <div class="container">
    <div class="row align-items-center about-gap">
      <div class="col-md-7">
        <div class="about-eyebrow">{{ __('what_to_bring.weather.eyebrow') }}</div>
        <h2 class="about-title">{{ __('what_to_bring.weather.title') }}</h2>
        <p class="about-text">
          {{ __('what_to_bring.weather.text') }}
        </p>
      </div>
      <div class="col-md-5">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('what_to_bring.tip.title') }}</div>
          <div class="about-cta__text">
            {{ __('what_to_bring.tip.text') }}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection