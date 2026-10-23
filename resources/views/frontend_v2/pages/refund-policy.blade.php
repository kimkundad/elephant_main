@extends('frontend_v2.layouts.app')

@section('title', __('refund.page_title'))
@section('meta_description', app()->getLocale() === 'th'
  ? 'ยกเลิกก่อนเริ่มโปรแกรม 48 ชั่วโมง คืนเงินเต็มจำนวน อ่านอัตราการคืนเงิน การเลื่อนวัน และระยะเวลาที่เงินเข้า'
  : 'Cancel at least 48 hours before your program and get a 100% refund. Refund rates, rescheduling and how long the money takes.')

@push('styles')
@include('frontend_v2.partials.about-styles')
<style>
  .refund-highlight{
    background:#2b2621;
    color:#fff;
    border-radius:20px;
    padding:26px 28px;
    margin-bottom:34px;
  }
  .refund-highlight__title{
    font-size:22px;
    font-weight:700;
    margin-bottom:8px;
  }
  .refund-highlight__text{
    color:#e6ded2;
    line-height:1.75;
    margin:0;
  }
  .refund-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    background:#fff;
    border:1px solid #e9e1d6;
    border-radius:16px;
    overflow:hidden;
    margin-bottom:10px;
  }
  .refund-table th,
  .refund-table td{
    padding:14px 16px;
    text-align:left;
    vertical-align:top;
    border-bottom:1px solid #f0eae1;
  }
  .refund-table thead th{
    background:#faf7f2;
    font-size:12px;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:#8b8177;
    font-weight:700;
  }
  .refund-table tbody tr:last-child th,
  .refund-table tbody tr:last-child td{ border-bottom:0; }
  .refund-table__notice{ font-weight:700; color:#2b2621; }
  .refund-table__refund{ font-weight:800; color:#b48949; white-space:nowrap; }
  .refund-table__note{ color:#6b6156; }
  .refund-updated{
    font-size:13px;
    color:#8b8177;
    margin-bottom:26px;
  }
  .refund-contact{
    background:#faf7f2;
    border:1px solid #eee7dd;
    border-radius:16px;
    padding:22px;
    margin-top:28px;
  }
  .refund-contact dl{ margin:0; }
  .refund-contact dt{
    font-size:12px;
    letter-spacing:.14em;
    text-transform:uppercase;
    color:#8b8177;
    margin-top:12px;
  }
  .refund-contact dd{ margin:2px 0 0; font-weight:700; color:#2b2621; }
  .refund-contact a{ color:#2b2621; }
  @media (max-width: 600px){
    .refund-table thead{ display:none; }
    .refund-table tr{ display:block; border-bottom:1px solid #f0eae1; }
    .refund-table tr:last-child{ border-bottom:0; }
    .refund-table td{ display:block; border-bottom:0; padding:4px 16px; }
    .refund-table td:first-child{ padding-top:14px; }
    .refund-table td:last-child{ padding-bottom:14px; }
  }
</style>
@endpush

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">{{ __('refund.hero.kicker') }}</div>
    <h1 class="about-hero__title">{{ __('refund.hero.title') }}</h1>
    <p class="about-hero__lead">
      {{ __('refund.hero.lead') }}
    </p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">{{ __('refund.hero.view_tours') }}</a>
      <a href="{{ route('frontend.contact.v2') }}" class="btn-outline-soft">{{ __('refund.hero.contact') }}</a>
    </div>
  </div>
</section>

<section id="refund" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-8">
        <div class="refund-updated">{{ __('refund.updated') }}</div>

        <div class="refund-highlight">
          <div class="refund-highlight__title">{{ __('refund.highlight.title') }}</div>
          <p class="refund-highlight__text">{{ __('refund.highlight.text') }}</p>
        </div>

        <div class="about-eyebrow">{{ __('refund.table.eyebrow') }}</div>
        <h2 class="about-title">{{ __('refund.table.title') }}</h2>
        <p class="about-text">{{ __('refund.table.intro') }}</p>

        <table class="refund-table">
          <thead>
            <tr>
              <th>{{ __('refund.table.head_notice') }}</th>
              <th>{{ __('refund.table.head_refund') }}</th>
              <th>{{ __('refund.table.head_note') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach(__('refund.table.rows') as $row)
              <tr>
                <td class="refund-table__notice">{{ $row['notice'] }}</td>
                <td class="refund-table__refund">{{ $row['refund'] }}</td>
                <td class="refund-table__note">{{ $row['note'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>

        @foreach(__('refund.sections') as $section)
          <div class="about-eyebrow" style="margin-top:28px;">{{ $section['eyebrow'] }}</div>
          <h2 class="about-title">{{ $section['title'] }}</h2>
          <p class="about-text">{{ $section['intro'] }}</p>
          <ul class="about-list about-list--dark">
            @foreach($section['items'] as $item)
              <li><span>&#10003;</span> {{ $item }}</li>
            @endforeach
          </ul>
        @endforeach

        <div class="about-eyebrow" style="margin-top:28px;">{{ __('refund.contact.eyebrow') }}</div>
        <h2 class="about-title">{{ __('refund.contact.title') }}</h2>
        <p class="about-text">{{ __('refund.contact.intro') }}</p>

        <div class="refund-contact">
          <dl>
            @if($siteSetting?->email)
              <dt>{{ __('refund.contact.email_label') }}</dt>
              <dd><a href="mailto:{{ $siteSetting->email }}">{{ $siteSetting->email }}</a></dd>
            @endif
            @if($siteSetting?->phone)
              <dt>{{ __('refund.contact.phone_label') }}</dt>
              <dd><a href="tel:{{ preg_replace('/\s+/', '', $siteSetting->phone) }}">{{ $siteSetting->phone }}</a></dd>
            @endif
            @if($siteSetting?->whatsappUrl())
              <dt>{{ __('refund.contact.whatsapp_label') }}</dt>
              <dd><a href="{{ $siteSetting->whatsappUrl() }}" target="_blank" rel="noopener">{{ $siteSetting->whatsappLabel() }}</a></dd>
            @endif
            @if($siteSetting?->contact_office_hours)
              <dt>{{ __('refund.contact.hours_label') }}</dt>
              <dd>{{ $siteSetting->contact_office_hours }}</dd>
            @endif
          </dl>
        </div>
      </div>

      <div class="col-md-4">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">{{ __('refund.support.title') }}</div>
          <div class="about-cta__text">{{ __('refund.support.text') }}</div>
          <a href="{{ route('frontend.contact.v2') }}" class="btn-primary-soft btn-primary-soft--full">{{ __('refund.support.cta') }}</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
