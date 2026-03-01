@if($sessions->count() === 0)
  <div class="empty-session">{{ __('tour_show.no_sessions') }}</div>
@else
  @foreach($sessions as $s)
    <a class="session-card session-card-link"
       href="{{ route('frontend.booking.create.v2', [
          'tour' => $tour->id,
          'date' => $selectedDate,
          'session' => $s->id
       ]) }}">

      <div class="session-time">
        {{ \Carbon\Carbon::parse($s->start_time)->format('g:ia') }}
      </div>

      <div class="session-info">
        <div class="session-title">{{ $s->title ?? $s->name ?? __('tour_show.session_fallback') }}</div>
        <div class="session-sub">{{ __('tour_show.remaining') }}: {{ $s->remainingCapacity($selectedDate) }}</div>
      </div>

      <div class="session-action">
        <span class="session-btn">{{ __('tour_show.book') }}</span>
      </div>

    </a>
  @endforeach
@endif
