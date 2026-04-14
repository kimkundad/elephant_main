@extends('frontend_v2.layouts.app')

@section('title', 'Contact V2')
@section('meta_description', app()->getLocale() === 'th'
  ? 'สอบถามข้อมูลโปรแกรม การเดินทาง และการจอง ทีมงานพร้อมช่วยเหลือและตอบกลับโดยเร็ว'
  : 'Get in touch for program details, directions, and booking support. Our team is happy to help.')

@push('styles')
<style>
.contact-v2{
  background:#f7f5f1;
  padding:70px 0 90px;
}
.contact-grid{
  max-width:820px;
  margin:0 auto;
}
.contact-title{
  font-size:46px;
  letter-spacing:.04em;
  text-transform:uppercase;
  color:#2b2621;
  margin-bottom:16px;
}
.contact-lead{
  color:#5f5850;
  line-height:1.8;
  margin-bottom:16px;
}
.contact-info{
  color:#4f4942;
  font-size:14px;
  line-height:1.8;
}
.contact-form{
  margin-top:26px;
}
.contact-panel{
  background:#fff;
  border-radius:18px;
  padding:28px;
  border:1px solid rgba(0,0,0,.06);
  box-shadow:0 18px 40px rgba(0,0,0,.08);
}
.contact-form .single-form{
  margin-bottom:16px;
}
.contact-form input,
.contact-form textarea{
  width:100%;
  border:1px solid #e2ddd6;
  background:#fff;
  padding:12px 14px;
  font-size:14px;
  outline:none;
}
.contact-form textarea{ min-height:140px; resize:vertical; }
.contact-form .form-row{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:16px;
}
.contact-btn{
  display:inline-block;
  background:#b58d4f;
  color:#fff;
  border:0;
  padding:12px 26px;
  letter-spacing:.12em;
  text-transform:uppercase;
  font-size:12px;
}
@media (max-width: 992px){
  .contact-form .form-row{ grid-template-columns: 1fr; }
  .contact-title{ font-size:34px; }
}
@media (min-width: 1200px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 1140px;
    }
}

@media (min-width: 1500px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 1350px;
    }
}
@media (min-width: 768px) {
    .col-md-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding-right: 15px;
    padding-left: 15px;
    }
}
.row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -15px;
    margin-left: -15px;
}
</style>
@endpush

@section('content')
@php
  $contactTitleDefault = app()->getLocale() === 'en' ? 'Contact Us' : 'ติดต่อเรา';
  $contactLeadDefault = app()->getLocale() === 'en'
    ? 'Please fill out the form to get in contact with us. As soon as possible, our staff will contact you back.'
    : 'กรุณากรอกแบบฟอร์มเพื่อส่งข้อความถึงเรา ทีมงานจะติดต่อกลับโดยเร็วที่สุด';
@endphp

@php
  $contactHeroBackground = \App\Models\PageMedia::url('v2.contact.hero.background', Vite::asset('resources/frontend/images/bg-chang.webp'));
@endphp

{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ $contactHeroBackground }}'); min-height: 250px;">
  <div class="about-hero__overlay"></div>
  
</section>

<section class="contact-v2">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-panel">
        <div class="contact-title">{{ \App\Models\SiteText::getValue('home.contact.title', $contactTitleDefault) }}</div>
        <div class="contact-lead">{{ \App\Models\SiteText::getValue('home.contact.lead', $contactLeadDefault) }}</div>
        <div class="contact-info">
          {{ $siteSetting->address ?? '' }}<br>
          T : {{ $siteSetting->phone ?? '-' }}<br>
          M : {{ $siteSetting->email ?? '-' }}
        </div>

        @if(session('success'))
          <div class="alert alert-success" style="margin-top:16px;">{{ session('success') }}</div>
        @endif
        @error('form')
          <div class="alert alert-danger" style="margin-top:16px;">{{ $message }}</div>
        @enderror

        <form class="contact-form" method="POST" action="{{ route('frontend.contact.v2.store') }}">
          @csrf
          <input type="hidden" name="form_issued_at" value="{{ $contactFormIssuedAt }}">
          <input type="hidden" name="form_issued_signature" value="{{ $contactFormIssuedSignature }}">
          <div style="position:absolute;left:-9999px;opacity:0;pointer-events:none;" aria-hidden="true">
            <label for="contact_website">Website</label>
            <input id="contact_website" type="text" name="website" tabindex="-1" autocomplete="off">
          </div>
          <div class="single-form">
            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject" required>
            @error('subject')<div class="text-danger mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="form-row">
            <div class="single-form">
              <input type="text" name="name" value="{{ old('name') }}" placeholder="Name" required>
              @error('name')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="single-form">
              <input type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
              @error('email')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="form-row">
            <div class="single-form">
              <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone">
              @error('phone')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            </div>
          </div>
          <div class="single-form">
            <textarea name="message" placeholder="Comments" required>{{ old('message') }}</textarea>
            @error('message')<div class="text-danger mt-1">{{ $message }}</div>@enderror
          </div>

          <div class="single-form">
            <label class="mb-2 d-block">คำถามยืนยัน: {{ $captchaQuestion }} = ?</label>
            <input type="hidden" name="captcha_left" value="{{ $captchaLeft }}">
            <input type="hidden" name="captcha_right" value="{{ $captchaRight }}">
            <input type="hidden" name="captcha_signature" value="{{ $captchaSignature }}">
            <input type="number" name="captcha_answer" value="{{ old('captcha_answer') }}" placeholder="ใส่คำตอบเพื่อยืนยันว่าไม่ใช่บอท" required>
            @error('captcha_answer')
              <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="contact-btn">Submit</button>
        </form>
      </div>
    </div>
  </div>
</section>

@endsection
