@extends('frontend_v2.layouts.app')

@php
  $tourTranslation = $tour->translation(app()->getLocale());
  $tourName = $tourTranslation->name ?? $tour->name ?? __('tour_show.page_title');
  $tourShortDescription = $tourTranslation->short_description ?? $tour->short_description;
  $tourDescription = $tourTranslation->description ?? $tour->description;
@endphp

@section('title', $tourName)
@section('meta_description', app()->getLocale() === 'th'
  ? ('รายละเอียดโปรแกรม ' . $tourName . ' เวลา ราคา และรอบกิจกรรมครบถ้วน จองออนไลน์ได้ทันที')
  : ('View full details of ' . $tourName . ' including schedule, pricing, and session availability. Book online in minutes.'))

@push('styles')
<style>
.tour-v2{
  background:#f7f5f1;
  padding:70px 0 90px;
}
.tour-grid{
  display:grid;
  grid-template-columns: minmax(0, 360px) minmax(0, 1fr);
  gap:40px;
  align-items:start;
}
.tour-calendar-card{
  background:#fff;
  border-radius:18px;
  box-shadow:0 18px 40px rgba(0,0,0,.08);
  padding:22px;
  border:1px solid rgba(0,0,0,.06);
}
.calendar-header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  margin-bottom:12px;
}
.calendar-title{
  font-size:14px;
  text-transform:uppercase;
  letter-spacing:.2em;
  color:#777;
}
.calendar-month{
  display:flex;
  align-items:center;
  gap:10px;
  font-weight:700;
  color:#2b2621;
}
.cal-nav{
  width:28px;
  height:28px;
  border-radius:999px;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  text-decoration:none;
  background:#eee7dd;
  color:#2b2621;
  font-weight:700;
}
.calendar-grid{
  display:grid;
  grid-template-columns: repeat(7, 1fr);
  gap:6px;
  font-size:13px;
}
.cal-dow{
  text-align:center;
  color:#999;
  font-weight:700;
  padding:6px 0;
}
.cal-day{
  text-align:center;
  padding:8px 0;
  border-radius:8px;
  color:#2b2621;
  text-decoration:none;
}
.cal-day.is-out{ color:#c9c2b9; }
.cal-day.is-disabled{ color:#c9c2b9; background:#f3f0ea; cursor:not-allowed; }
.cal-day.is-today{ border:1px solid #b58d4f; }
.cal-day.is-selected{ background:#b58d4f; color:#fff; }
.calendar-note{
  margin-top:12px;
  font-size:12px;
  color:#888;
}

.tour-detail-head{
  margin-bottom:22px;
}
.tour-date-line{
  font-size:12px;
  letter-spacing:.22em;
  text-transform:uppercase;
  color:#8a7f73;
  margin-bottom:10px;
}
.tour-title{
  font-size:42px;
  line-height:1.1;
  margin-bottom:12px;
  color:#2b2621;
}
.tour-sub{
  color:#6b6156;
  line-height:1.8;
  margin-bottom:14px;
}
.tour-price-badge{
  display:inline-block;
  background:#2b2621;
  color:#fff;
  padding:8px 14px;
  border-radius:999px;
  font-size:13px;
  letter-spacing:.08em;
}
.tour-gallery{
  display:grid;
  grid-template-columns: repeat(3, 1fr);
  gap:12px;
  margin:26px 0;
}
.tour-gimg{
  width:100%;
  height:160px;
  object-fit:cover;
  border-radius:12px;
}
.tour-details-box{
  background:#fff;
  border-radius:18px;
  padding:24px;
  border:1px solid rgba(0,0,0,.06);
  box-shadow:0 18px 40px rgba(0,0,0,.06);
}
.tour-details-title{
  font-weight:800;
  margin-bottom:10px;
}
.tour-details-body{
  color:#4b4238;
  line-height:1.9;
}

@media (max-width: 992px){
  .tour-grid{ grid-template-columns: 1fr; }
  .tour-title{ font-size:34px; }
  .tour-gallery{ grid-template-columns: 1fr 1fr; }
}
@media (max-width: 575px){
  .tour-gallery{ grid-template-columns: 1fr; }
  .tour-gimg{ height:200px; }
  .tour-title{ font-size: 26px; }
  .session-card.session-card-link{
    padding: 10px 12px;
  }
  .session-time{
    min-width: 68px;
    font-size: 13px;
  }
  .session-title{ font-size:15px; }
  .session-sub{ font-size: 11px; }
  .session-card.session-card-link .session-btn{
    padding: 8px 12px;
    font-size: 13px;
  }
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
</style>
@endpush

@section('content')

{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
</section>

<section class="tour-v2">
  <div class="container">
    <div class="tour-grid">
      {{-- LEFT: Calendar --}}
      <div>
        @php
          $m = \Carbon\Carbon::createFromFormat('Y-m', $month);
          $prev = $m->copy()->subMonth()->format('Y-m');
          $next = $m->copy()->addMonth()->format('Y-m');

          $start = $m->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
          $end   = $m->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
        @endphp

        <div class="tour-calendar-card">
          <div class="calendar-header">
            <div class="calendar-title">{{ __('tour_show.choose_date') }}</div>
            <div class="calendar-month">
              <a class="cal-nav" href="{{ route('frontend.tours.show.v2', [$tour->slug, 'month'=>$prev, 'date'=>$selectedDate]) }}">&lsaquo;</a>
              <span>{{ $m->locale(app()->getLocale())->translatedFormat('F Y') }}</span>
              <a class="cal-nav" href="{{ route('frontend.tours.show.v2', [$tour->slug, 'month'=>$next, 'date'=>$selectedDate]) }}">&rsaquo;</a>
            </div>
          </div>

          <div class="calendar-grid">
            <div class="cal-dow">{{ __('tour_show.dow.su') }}</div><div class="cal-dow">{{ __('tour_show.dow.mo') }}</div><div class="cal-dow">{{ __('tour_show.dow.tu') }}</div>
            <div class="cal-dow">{{ __('tour_show.dow.we') }}</div><div class="cal-dow">{{ __('tour_show.dow.th') }}</div><div class="cal-dow">{{ __('tour_show.dow.fr') }}</div><div class="cal-dow">{{ __('tour_show.dow.sa') }}</div>

            @for($d=$start->copy(); $d->lte($end); $d->addDay())
              @php
                $date = $d->toDateString();
                $inMonth = $d->month === $m->month;
                $isSelected = $date === $selectedDate;
                $isToday = $date === now()->toDateString();
                $isPast = $d->lt(now()->startOfDay());

                $cls = 'cal-day';
                if(!$inMonth) $cls .= ' is-out';
                if($isPast) $cls .= ' is-disabled';
                if($isToday) $cls .= ' is-today';
                if($isSelected) $cls .= ' is-selected';
              @endphp

              @if(!$inMonth || $isPast)
                <span class="{{ $cls }}">{{ $d->day }}</span>
              @else
                <a class="{{ $cls }}"
                   href="{{ route('frontend.tours.show.v2', [$tour->slug, 'month'=>$month, 'date'=>$date]) }}">
                  {{ $d->day }}
                </a>
              @endif
            @endfor
          </div>

          <div class="calendar-note">{{ __('tour_show.all_prices_thb') }}</div>
        </div>
      </div>

      {{-- RIGHT: Details --}}
      <div >
        <div class="tour-detail-head">
          <div class="tour-date-line">
            {{ \Carbon\Carbon::parse($selectedDate)->locale(app()->getLocale())->translatedFormat('l, d F Y') }}
          </div>

          <h1 class="tour-title">{{ $tourName }}</h1>

          <div class="tour-sub">
            {{ $tourShortDescription }}
          </div>

          <div class="tour-price-badge">
            THB {{ number_format($tour->min_price ?? 0) }}
          </div>
        </div>

        <div class="tour-sessions" id="sessions-wrap">
          @include('frontend_v2.partials.tours.sessions', [
            'sessions' => $sessionsForSelected,
            'tour' => $tour,
            'selectedDate' => $selectedDate
          ])
        </div>

        <div class="tour-gallery">
          <img src="{{ $tour->thumbnail }}" class="tour-gimg" alt="{{ $tourName }}">
          <img src="{{ $tour->thumbnail }}" class="tour-gimg" alt="{{ $tourName }}">
          <img src="{{ $tour->thumbnail }}" class="tour-gimg" alt="{{ $tourName }}">
        </div>

        <div class="tour-details-box">
          <div class="tour-details-title">{{ __('tour_show.details') }}</div>
          <div class="tour-details-body">{!! $tourDescription !!}</div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
