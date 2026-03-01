@extends('frontend.layouts.app')

@section('content')
<div class="container" style="margin-top:120px; margin-bottom:80px;">
  <div class="row">

    {{-- LEFT: Calendar --}}
    <div class="col-md-4">
      @php
        $m = \Carbon\Carbon::createFromFormat('Y-m', $month);
        $prev = $m->copy()->subMonth()->format('Y-m');
        $next = $m->copy()->addMonth()->format('Y-m');

        $start = $m->copy()->startOfMonth()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $end   = $m->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SATURDAY);
      @endphp

      <div class="tour-calendar-card">
        <div class="calendar-header">
          <div class="calendar-title">Choose date</div>
          <div class="calendar-month">
            <a class="cal-nav" href="{{ route('frontend.tours.show', [$tour->slug, 'month'=>$prev, 'date'=>$selectedDate]) }}">‹</a>
            <span>{{ $m->format('F Y') }}</span>
            <a class="cal-nav" href="{{ route('frontend.tours.show', [$tour->slug, 'month'=>$next, 'date'=>$selectedDate]) }}">›</a>
          </div>
        </div>

        <div class="calendar-grid">
          <div class="cal-dow">Su</div><div class="cal-dow">Mo</div><div class="cal-dow">Tu</div>
          <div class="cal-dow">We</div><div class="cal-dow">Th</div><div class="cal-dow">Fr</div><div class="cal-dow">Sa</div>

          @for($d=$start->copy(); $d->lte($end); $d->addDay())
  @php
    $date = $d->toDateString();
    $inMonth = $d->month === $m->month;
    $isSelected = $date === $selectedDate;
    $isToday = $date === now()->toDateString();

    // ✅ ห้ามย้อนหลัง: วันที่ก่อนวันนี้ = disabled
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
       href="{{ route('frontend.tours.show', [$tour->slug, 'month'=>$month, 'date'=>$date]) }}">
      {{ $d->day }}
    </a>
  @endif
@endfor
        </div>

        <div class="calendar-note">All prices are in Thai baht</div>
      </div>
    </div>

    {{-- RIGHT: Details --}}
    <div class="col-md-8">
      <div class="tour-detail-head">
        <div class="tour-date-line">
          {{ \Carbon\Carbon::parse($selectedDate)->format('l, d F Y') }}
        </div>

        <h1 class="tour-title">{{ $tour->name }}</h1>

        <div class="tour-sub">
          {{ $tour->short_description }}
        </div>

        <div class="tour-price-badge">
          THB {{ number_format($tour->min_price ?? 0) }}
        </div>
      </div>

      <div class="tour-sessions" id="sessions-wrap">
        @include('frontend.partials.tours.sessions', [
          'sessions' => $sessionsForSelected,
          'tour' => $tour,
          'selectedDate' => $selectedDate
        ])
      </div>

      <div class="tour-gallery">
        <img src="{{ $tour->thumbnail }}" class="tour-gimg" alt="{{ $tour->name }}">
        <img src="{{ $tour->thumbnail }}" class="tour-gimg" alt="{{ $tour->name }}">
        <img src="{{ $tour->thumbnail }}" class="tour-gimg" alt="{{ $tour->name }}">
      </div>

      <div class="tour-details-box">
        <div class="tour-details-title">Details</div>
        <div class="tour-details-body">{!! $tour->description !!}</div>
      </div>
    </div>

  </div>
</div>
@endsection
