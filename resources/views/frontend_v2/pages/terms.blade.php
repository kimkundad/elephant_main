@extends('frontend_v2.layouts.app')

@section('title', __('terms.page_title'))
@section('meta_description', app()->getLocale() === 'th'
  ? 'ข้อกำหนดการใช้งานเว็บไซต์ การจอง การชำระเงิน และเงื่อนไขการยกเลิก'
  : 'Review our website terms, booking conditions, payment terms, and cancellation policy.')

@push('styles')
@include('frontend_v2.partials.about-styles')
@endpush

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">{{ __('terms.hero.kicker') }}</div>
    <h1 class="about-hero__title">{{ __('terms.hero.title') }}</h1>
    <p class="about-hero__lead">
      {{ __('terms.hero.lead') }}
    </p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">{{ __('terms.hero.view_tours') }}</a>
      <a href="#terms" class="btn-outline-soft">{{ __('terms.hero.read_terms') }}</a>
    </div>
  </div>
</section>

{{-- TERMS --}}
<section id="terms" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-8">
        <div class="about-eyebrow">{{ __('terms.booking.eyebrow') }}</div>
        <h2 class="about-title">{{ __('terms.booking.title') }}</h2>
        <p class="about-text">{{ __('terms.booking.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('terms.booking.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('terms.booking.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('terms.booking.items.2') }}</li>
          <li><span>&#10003;</span> {{ __('terms.booking.items.3') }}</li>
          <li><span>&#10003;</span> {{ __('terms.booking.items.4') }}</li>
          <li><span>&#10003;</span> {{ __('terms.booking.items.5') }}</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">{{ __('terms.cancellation.eyebrow') }}</div>
        <h2 class="about-title">{{ __('terms.cancellation.title') }}</h2>
        <p class="about-text">{{ __('terms.cancellation.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('terms.cancellation.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('terms.cancellation.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('terms.cancellation.items.2') }}</li>
          <li><span>&#10003;</span> {{ __('terms.cancellation.items.3') }}</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">{{ __('terms.payment.eyebrow') }}</div>
        <h2 class="about-title">{{ __('terms.payment.title') }}</h2>
        <p class="about-text">{{ __('terms.payment.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('terms.payment.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('terms.payment.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('terms.payment.items.2') }}</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">{{ __('terms.conduct.eyebrow') }}</div>
        <h2 class="about-title">{{ __('terms.conduct.title') }}</h2>
        <p class="about-text">{{ __('terms.conduct.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('terms.conduct.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('terms.conduct.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('terms.conduct.items.2') }}</li>
          <li><span>&#10003;</span> {{ __('terms.conduct.items.3') }}</li>
        </ul>
      </div>

      <div class="col-md-4">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('terms.support.title') }}</div>
          <div class="about-cta__text">{{ __('terms.support.text') }}</div>
          <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft btn-primary-soft--full">{{ __('terms.support.cta') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
