@php
  $siteSetting = \App\Models\SiteSetting::first();
  $logoUrl = $siteSetting?->logo_header_url;
  $guestParts = array_filter([
      $booking->adults ? $booking->adults . ' adults' : null,
      $booking->children ? $booking->children . ' children' : null,
      $booking->infants ? $booking->infants . ' infants' : null,
  ]);
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Confirmation &middot; Small Elephants</title>
  <link rel="icon" href="{{ asset('favicon.ico?v=2') }}" sizes="any">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png?v=2') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png?v=2') }}">
  <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png?v=2') }}">
  <style>
    body { margin:0; background:#f6f9fc; font-family: -apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial; }
    .wrap { max-width:720px; margin:0 auto; padding:28px 16px; }
    .card { background:#fff; border-radius:16px; box-shadow:0 10px 30px rgba(2,12,27,.08); padding:22px; }
    .top { background:#0b1220; border-radius:16px 16px 0 0; padding:18px; margin:-22px -22px 18px; color:#fff; }
    .title { font-size:20px; font-weight:700; }
    .muted { color:#6b7280; font-size:13px; }
    .grid { display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-top:14px; }
    .row { padding:12px; border:1px solid #eef2f7; border-radius:12px; }
    .label { font-size:12px; color:#64748b; margin-bottom:6px; }
    .value { font-size:14px; font-weight:600; color:#0f172a; }
    .ok { display:inline-block; padding:6px 10px; border-radius:999px; background:#ecfdf5; color:#065f46; font-weight:700; font-size:12px; }
    .bad { display:inline-block; padding:6px 10px; border-radius:999px; background:#fef2f2; color:#991b1b; font-weight:700; font-size:12px; }

    /* Front desk check-in */
    .checkin { margin-top:18px; border-radius:14px; padding:16px; border:1px solid #e5e7eb; }
    .checkin--done { background:#ecfdf5; border-color:#a7f3d0; }
    .checkin--todo { background:#f8fafc; }
    .checkin h2 { margin:0 0 4px; font-size:16px; }
    .checkin .hint { font-size:13px; color:#64748b; }
    .checkin form { display:flex; flex-wrap:wrap; gap:8px; margin-top:12px; }
    .checkin input { flex:1 1 150px; min-width:0; padding:11px 12px; border:1px solid #cbd5e1; border-radius:10px; font-size:15px; }
    .btn { padding:11px 18px; border:0; border-radius:10px; font-weight:700; font-size:15px; cursor:pointer; }
    .btn--go { background:#059669; color:#fff; }
    .btn--undo { background:transparent; color:#64748b; text-decoration:underline; padding:4px 0; font-size:13px; }
    .warn { margin-top:10px; padding:10px 12px; border-radius:10px; background:#fffbeb; color:#92400e; font-size:13px; }
    .err { margin-top:10px; padding:10px 12px; border-radius:10px; background:#fef2f2; color:#991b1b; font-size:13px; }

    /* Sending state, so the staff member knows the tap registered */
    .btn[disabled] { opacity:.75; cursor:wait; }
    .btn--go { display:inline-flex; align-items:center; gap:8px; }
    .spinner {
      width:15px; height:15px; border-radius:50%;
      border:2px solid rgba(255,255,255,.4); border-top-color:#fff;
      animation: spin .7s linear infinite;
    }
    .btn--undo .spinner { border-color:rgba(100,116,139,.35); border-top-color:#64748b; }
    @keyframes spin { to { transform: rotate(360deg); } }
    @media (prefers-reduced-motion: reduce) { .spinner { animation-duration: 2.4s; } }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="top">
        @if($logoUrl)
          <img src="{{ $logoUrl }}" alt="Small Elephants" style="height:40px;display:block;margin-bottom:10px;">
        @endif
        <div class="title">Small Elephants</div>
        <div class="muted" style="color:#9aa4b2;">Booking verification</div>
      </div>

      <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
        <div>
          <div class="label">Booking #</div>
          <div class="value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>

        @if($booking->payment_status === 'paid')
          <span class="ok">PAID ✅</span>
        @else
          <span class="bad">NOT PAID</span>
        @endif
      </div>

      <div class="grid">
        <div class="row">
          <div class="label">Customer</div>
          <div class="value">{{ $booking->customer_name }}</div>
        </div>
        <div class="row">
          <div class="label">Phone</div>
          <div class="value">
            @if($booking->customer_phone)
              <a href="tel:{{ $booking->customer_phone }}" style="color:inherit;">{{ $booking->customer_phone }}</a>
            @else
              -
            @endif
          </div>
        </div>
        <div class="row">
          <div class="label">Email</div>
          <div class="value">{{ $booking->customer_email }}</div>
        </div>
        <div class="row">
          <div class="label">Guests</div>
          <div class="value">
            {{ $booking->total_guests }}
            @if($guestParts)
              <span class="muted" style="font-weight:400;">({{ implode(', ', $guestParts) }})</span>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="label">Date</div>
          <div class="value">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</div>
        </div>
        <div class="row">
          <div class="label">Session</div>
          <div class="value">
            {{ $booking->session?->title ?? $booking->session?->name ?? '-' }}
            <span class="muted" style="font-weight:400;">{{ $booking->session?->time_range }}</span>
          </div>
        </div>
        <div class="row">
          <div class="label">Package</div>
          <div class="value">{{ optional($booking->tour)->name ?? '-' }}</div>
        </div>
        <div class="row">
          <div class="label">Province</div>
          <div class="value">{{ $booking->tour?->province?->name('en') ?? '-' }}</div>
        </div>
        <div class="row">
          <div class="label">Pickup</div>
          <div class="value">{{ $booking->pickupLabel() }}</div>
          @if($booking->pickupDetail())
            <div class="muted" style="margin-top:4px; white-space:pre-line;">{{ $booking->pickupDetail() }}</div>
          @endif
        </div>
        <div class="row">
          <div class="label">Amount</div>
          <div class="value">
            THB {{ number_format($booking->grand_total ?? $booking->total_price ?? 0, 2) }}
            @if(($booking->discount_amount ?? 0) > 0)
              <span class="muted" style="font-weight:400;">(discount THB {{ number_format($booking->discount_amount, 2) }})</span>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="label">Payment</div>
          <div class="value">
            {{ strtoupper($booking->payment_channel ?? '-') }}
            @if($booking->paid_at)
              <span class="muted" style="font-weight:400;">{{ \Carbon\Carbon::parse($booking->paid_at)->format('d M Y H:i') }}</span>
            @endif
          </div>
        </div>
      </div>

      @if($booking->checked_in_at)
        <div class="checkin checkin--done">
          <h2>✅ Checked in</h2>
          <div class="hint">
            {{ $booking->checked_in_at->format('d M Y H:i') }}
            @if($booking->checked_in_by) &middot; by {{ $booking->checked_in_by }} @endif
          </div>

          @if($checkInEnabled)
            <form method="POST" action="{{ route('booking.public.check-in.undo', $booking->public_code) }}"
                  onsubmit="return confirm('Undo this check-in?')">
              @csrf
              <input type="password" name="pin" inputmode="numeric" placeholder="Staff PIN" required>
              <button class="btn btn--undo" type="submit">Undo check-in</button>
            </form>
          @endif
        </div>
      @elseif($booking->payment_status !== 'paid')
        <div class="checkin checkin--todo">
          <h2>Check-in unavailable</h2>
          <div class="hint">This booking is not paid yet.</div>
        </div>
      @elseif($booking->status === 'cancelled')
        <div class="checkin checkin--todo">
          <h2>Check-in unavailable</h2>
          <div class="hint">This booking was cancelled.</div>
        </div>
      @elseif($checkInEnabled)
        <div class="checkin checkin--todo">
          <h2>Guest arrived?</h2>
          <div class="hint">Staff only: enter the PIN to record the arrival.</div>

          @if(!\Carbon\Carbon::parse($booking->date)->isToday())
            <div class="warn">
              Heads up: this booking is for {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}, not today.
            </div>
          @endif

          <form method="POST" action="{{ route('booking.public.check-in', $booking->public_code) }}">
            @csrf
            <input type="password" name="pin" inputmode="numeric" placeholder="Staff PIN" required>
            <input type="text" name="staff_name" placeholder="Staff name (optional)">
            <button class="btn btn--go" type="submit">Check in</button>
          </form>
        </div>
      @endif

      @if($errors->any())
        <div class="err">{{ $errors->first() }}</div>
      @endif
      @if(session('checkin_success'))
        <div class="warn" style="background:#ecfdf5;color:#065f46;">Check-in saved.</div>
      @endif
      @if(session('checkin_undone'))
        <div class="warn">Check-in removed.</div>
      @endif

      <div style="margin-top:16px;" class="muted">
        Booked on {{ $booking->created_at?->format('d M Y H:i') ?? '-' }}.
        This page is public for QR verification. Do not share it publicly.
      </div>
    </div>
  </div>

  <script>
  // Check-in posts to the server, which can take a moment on a phone's data
  // connection: show the button is working and block a second tap.
  (function () {
    document.querySelectorAll('.checkin form').forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (event.defaultPrevented) return;

        var button = form.querySelector('button');
        if (!button || button.disabled) {
          event.preventDefault();
          return;
        }

        button.dataset.label = button.textContent.trim();
        button.disabled = true;
        button.innerHTML = '<span class="spinner"></span><span>Sending...</span>';
      });
    });

    // Coming back with the Back button restores the page mid-send.
    window.addEventListener('pageshow', function (event) {
      if (!event.persisted) return;

      document.querySelectorAll('.checkin button[data-label]').forEach(function (button) {
        button.disabled = false;
        button.textContent = button.dataset.label;
      });
    });
  })();
  </script>
</body>
</html>
