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
  display:grid;
  grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
  gap:40px;
  align-items:start;
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
.contact-map{
  position:relative;
  width:100%;
  height:100%;
  min-height: 520px;
  border-radius:8px;
  overflow:hidden;
  box-shadow:0 18px 40px rgba(0,0,0,.12);
}
.contact-map iframe{
  width:100%;
  height:100%;
  border:0;
}
@media (max-width: 992px){
  .contact-grid{ grid-template-columns: 1fr; }
  .contact-form .form-row{ grid-template-columns: 1fr; }
  .contact-map{ min-height: 380px; }
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

{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}'); min-height: 250px;">
  <div class="about-hero__overlay"></div>
  
</section>

<section class="contact-v2">
  <div class="container">
    <div class="contact-grid">
      <div>
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

        <form class="contact-form" method="POST" action="{{ route('frontend.contact.v2.store') }}">
          @csrf
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
            <label class="mb-2 d-block">คำถามยืนยัน: {{ $captchaQuestion ?? session('contact_captcha_question') }} = ?</label>
            <input type="number" name="captcha_answer" value="{{ old('captcha_answer') }}" placeholder="ใส่คำตอบเพื่อยืนยันว่าไม่ใช่บอท" required>
            @error('captcha_answer')
              <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="contact-btn">Submit</button>
        </form>
      </div>

      <div class="contact-map">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3780.843403272615!2d98.62749707592903!3d18.626111965940375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30da533186882e05%3A0x935b007d4857bdd7!2ssmallelephants!5e0!3m2!1sen!2sth!4v1772260162789!5m2!1sen!2sth"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </div>
  </div>
</section>

@endsection
