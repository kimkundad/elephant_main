@extends('frontend_v2.layouts.app')

@section('title', __('policy.page_title'))
@section('meta_description', app()->getLocale() === 'th'
  ? 'อ่านนโยบายความเป็นส่วนตัว การใช้ข้อมูล และการคุ้มครองข้อมูลส่วนบุคคลของผู้ใช้งานเว็บไซต์'
  : 'Read how we collect, use, and protect your personal information when using our website and services.')

@push('styles')
@include('frontend_v2.partials.about-styles')
@endpush

@section('content')
@php
  $policyHeroBackground = \App\Models\PageMedia::url('v2.policy.hero.background', Vite::asset('resources/frontend/images/bg-chang.webp'));
@endphp

{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ $policyHeroBackground }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">{{ __('policy.hero.kicker') }}</div>
    <h1 class="about-hero__title">{{ __('policy.hero.title') }}</h1>
    <p class="about-hero__lead">{{ __('policy.hero.lead') }}</p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft">{{ __('policy.hero.contact_us') }}</a>
      <a href="#policy" class="btn-outline-soft">{{ __('policy.hero.read_policy') }}</a>
    </div>
  </div>
</section>

{{-- POLICY --}}
<section id="policy" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-8">
        <div class="about-eyebrow">{{ __('policy.privacy.eyebrow') }}</div>
        <h2 class="about-title">{{ __('policy.privacy.title') }}</h2>
        <p class="about-text">{{ __('policy.privacy.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('policy.privacy.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('policy.privacy.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('policy.privacy.items.2') }}</li>
          <li><span>&#10003;</span> {{ __('policy.privacy.items.3') }}</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">{{ __('policy.security.eyebrow') }}</div>
        <h2 class="about-title">{{ __('policy.security.title') }}</h2>
        <p class="about-text">{{ __('policy.security.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('policy.security.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('policy.security.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('policy.security.items.2') }}</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">{{ __('policy.website.eyebrow') }}</div>
        <h2 class="about-title">{{ __('policy.website.title') }}</h2>
        <p class="about-text">{{ __('policy.website.intro') }}</p>
        <ul class="about-list about-list--dark">
          <li><span>&#10003;</span> {{ __('policy.website.items.0') }}</li>
          <li><span>&#10003;</span> {{ __('policy.website.items.1') }}</li>
          <li><span>&#10003;</span> {{ __('policy.website.items.2') }}</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">{{ __('policy.cookies.eyebrow') }}</div>
        <h2 class="about-title">{{ __('policy.cookies.title') }}</h2>
        <p class="about-text">{{ __('policy.cookies.intro') }}</p>
      </div>

      <div class="col-md-4">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('policy.cta.title') }}</div>
          <div class="about-cta__text">{{ __('policy.cta.text') }}</div>
          <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft btn-primary-soft--full">{{ __('policy.cta.button') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
