@extends('frontend_v2.layouts.app')

@php
  $tourTranslation = optional($booking->tour)->translation(app()->getLocale());
  $tourName = $tourTranslation->name ?? optional($booking->tour)->name ?? '-';
@endphp

@section('title', __('booking.confirmed.title'))
@section('meta_description', app()->getLocale() === 'th'
  ? 'การจองของคุณเสร็จสมบูรณ์แล้ว ตรวจสอบรายละเอียดทัวร์และการเตรียมตัวก่อนเดินทาง'
  : 'Your booking is confirmed. Review your tour details and get ready for your visit.')

@push('styles')
<style>
.confirm-v2{
  background:#f7f5f1;
  padding:80px 0 100px;
}
.confirm-card{
  background:#fff;
  border-radius:20px;
  padding:36px;
  max-width:900px;
  margin:0 auto;
  box-shadow:0 20px 50px rgba(0,0,0,.08);
  border:1px solid rgba(0,0,0,.06);
  text-align:center;
}
.confirm-icon{
  width:64px; height:64px; border-radius:999px;
  margin:0 auto 16px;
  background:#2b2621; color:#fff; display:flex; align-items:center; justify-content:center;
  font-size:28px; font-weight:800;
}
.confirm-title{
  font-size:32px; color:#2b2621; margin-bottom:8px;
}
.confirm-sub{
  color:#6b6156; line-height:1.7; margin-bottom:22px;
  white-space: pre-line;
}
.confirm-box{
  text-align:left;
  background:#faf7f2;
  border-radius:16px;
  padding:22px;
  border:1px solid #eee7dd;
}
.confirm-grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap:18px;
}
.label{ font-size:12px; letter-spacing:.18em; text-transform:uppercase; color:#8b8177; margin-bottom:4px; }
.value{ font-weight:700; color:#2b2621; margin-bottom:10px; }
.confirm-note{
  margin:18px 0 24px;
  color:#6b6156;
  white-space: pre-line;
}
.confirm-actions .btn-outline{
  display:inline-block;
  padding:12px 22px;
  border-radius:999px;
  border:1px solid #cdbda9;
  color:#2b2621;
  text-decoration:none;
}
@media (max-width: 768px){
  .confirm-grid{ grid-template-columns: 1fr; }
  .confirm-card{ padding:26px; }
}
</style>
@endpush

@section('content')
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}'); min-height: 250px;">
  <div class="about-hero__overlay"></div>
</section>
<section class="confirm-v2">
  <div class="container">
    <div class="confirm-card">
      <div class="confirm-icon">&#10003;</div>
      <h1 class="confirm-title">{{ __('booking.confirmed.heading') }}</h1>
      <p class="confirm-sub">{{ __('booking.confirmed.sub') }}</p>

      <div class="confirm-box">
        <div class="confirm-grid">
          <div>
            <div class="label">{{ __('booking.confirmed.ref') }}</div>
            <div class="value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>

            <div class="label">{{ __('booking.confirmed.tour') }}</div>
            <div class="value">{{ $tourName }}</div>

            <div class="label">{{ __('booking.confirmed.date') }}</div>
            <div class="value">{{ \Carbon\Carbon::parse($booking->date)->locale(app()->getLocale())->translatedFormat('l, d F Y') }}</div>

            <div class="label">{{ __('booking.confirmed.session') }}</div>
            <div class="value">{{ optional($booking->session)->start_time }}</div>
          </div>

          <div>
            <div class="label">{{ __('booking.confirmed.guest_name') }}</div>
            <div class="value">{{ $booking->customer_name }}</div>

            <div class="label">{{ __('booking.confirmed.phone') }}</div>
            <div class="value">{{ $booking->customer_phone }}</div>

            <div class="label">{{ __('booking.confirmed.email') }}</div>
            <div class="value">{{ $booking->customer_email }}</div>

            <div class="label">{{ __('booking.confirmed.pickup_location') }}</div>
            <div class="value">{{ optional($booking->pickupLocation)->name ?? '-' }}</div>
          </div>
        </div>
      </div>

      <div class="confirm-note">{{ __('booking.confirmed.note') }}</div>

      <div class="confirm-actions">
        <a href="{{ route('frontend.home.v2') }}" class="btn-outline">{{ __('booking.confirmed.back_home') }}</a>
      </div>
    </div>
  </div>
</section>
@endsection
