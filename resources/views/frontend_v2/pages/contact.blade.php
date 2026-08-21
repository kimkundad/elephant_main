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
/* The reCAPTCHA v2 widget renders at a fixed 304px, which overflows the panel
   on the narrowest phones. Scale it down there rather than letting it clip. */
.contact-form .g-recaptcha{
  transform-origin: 0 0;
}
@media (max-width: 360px){
  .contact-form .g-recaptcha{
    transform: scale(.85);
    height: 66px;
  }
}

/* Success dialog shown after the contact form redirects back. */
.contact-modal{
  position:fixed;
  inset:0;
  z-index:1000;
  display:none;
  align-items:center;
  justify-content:center;
  padding:24px;
}
.contact-modal.is-open{
  display:flex;
}
.contact-modal__backdrop{
  position:absolute;
  inset:0;
  background:rgba(28,24,20,.55);
}
.contact-modal__panel{
  position:relative;
  width:min(460px, 100%);
  background:#fff;
  border-radius:18px;
  padding:36px 32px 32px;
  text-align:center;
  box-shadow:0 24px 60px rgba(0,0,0,.24);
  animation: contact-modal-in .28s ease-out both;
}
@keyframes contact-modal-in{
  from{ opacity:0; transform: translateY(14px) scale(.97); }
  to{ opacity:1; transform:none; }
}
.contact-modal__icon{
  width:62px;
  height:62px;
  margin:0 auto 18px;
  border-radius:999px;
  background:#eef6e0;
  color:#7ea22b;
  display:flex;
  align-items:center;
  justify-content:center;
}
.contact-modal__icon svg{
  width:30px;
  height:30px;
}
.contact-modal__title{
  font-size:24px;
  line-height:1.35;
  text-transform:none;
  letter-spacing:0;
  color:#2b2621;
  margin:0 0 12px;
}
.contact-modal__body{
  color:#5f5850;
  font-size:15px;
  line-height:1.8;
  margin:0 0 24px;
}
.contact-modal__btn{
  background:#b58d4f;
  color:#fff;
  border:0;
  padding:12px 34px;
  letter-spacing:.12em;
  text-transform:uppercase;
  font-size:12px;
  cursor:pointer;
}
.contact-modal__btn:hover{
  background:#a37c41;
}
@media (max-width: 480px){
  .contact-modal__panel{ padding:30px 22px 26px; }
  .contact-modal__title{ font-size:21px; }
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
            @if($recaptchaSiteKey !== '')
              <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}"></div>
            @else
              <div class="text-danger">reCAPTCHA is not configured — set RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY in .env</div>
            @endif
            @error('recaptcha')
              <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="contact-btn">Submit</button>
        </form>
      </div>
    </div>
  </div>
</section>


{{-- Success dialog. Rendered only on the redirect that follows a successful submit. --}}
@if(session('contact_success'))
<div class="contact-modal" id="contact-success" role="dialog" aria-modal="true" aria-labelledby="contact-success-title">
  <div class="contact-modal__backdrop" data-contact-modal-close></div>
  <div class="contact-modal__panel">
    <div class="contact-modal__icon" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none">
        <path d="m4.5 12.5 5 5 10-11" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <h2 class="contact-modal__title" id="contact-success-title">{{ __('contact.success_title') }}</h2>
    <p class="contact-modal__body">{{ __('contact.success_body') }}</p>
    <button type="button" class="contact-modal__btn" data-contact-modal-close>{{ __('contact.success_close') }}</button>
  </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush

@if(session('contact_success'))
@push('scripts')
<script>
(function () {
    var modal = document.getElementById('contact-success');
    if (!modal) return;

    function closeModal() {
        modal.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    Array.prototype.forEach.call(
        modal.querySelectorAll('[data-contact-modal-close]'),
        function (el) { el.addEventListener('click', closeModal); }
    );
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' || e.key === 'Esc') closeModal();
    });

    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';

    var btn = modal.querySelector('.contact-modal__btn');
    if (btn) btn.focus();
})();
</script>
@endpush
@endif
