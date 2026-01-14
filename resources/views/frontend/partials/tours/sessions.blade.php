@if($sessions->count() === 0)
  <div class="empty-session">No available sessions for this date.</div>
@else
  @foreach($sessions as $s)
    <a class="session-card session-card-link"
       href="{{ route('frontend.booking.create', [
          'tour' => $tour->id,
          'date' => $selectedDate,
          'session' => $s->id
       ]) }}">

      <div class="session-time">
        {{ \Carbon\Carbon::parse($s->start_time)->format('g:ia') }}
      </div>

      <div class="session-info">
        <div class="session-title">{{ $s->title ?? $s->name ?? 'Session' }}</div>
        <div class="session-sub">Remaining: {{ $s->remainingCapacity($selectedDate) }}</div>
      </div>

      <div class="session-action">
        <span class="session-btn">Book</span>
      </div>

    </a>
  @endforeach
@endif
