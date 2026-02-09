<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Confirmation</title>
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
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="top">
        <div class="title">Phuket Elephant Sanctuary</div>
        <div class="muted">Booking verification</div>
      </div>

      <div style="display:flex; justify-content:space-between; align-items:center; gap:12px;">
        <div>
          <div class="label">Booking #</div>
          <div class="value">{{ $booking->id }}</div>
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
          <div class="label">Email</div>
          <div class="value">{{ $booking->customer_email }}</div>
        </div>
        <div class="row">
          <div class="label">Date</div>
          <div class="value">{{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</div>
        </div>
        <div class="row">
          <div class="label">Session</div>
          <div class="value">{{ optional($booking->session)->start_time ?? '-' }}</div>
        </div>
        <div class="row">
          <div class="label">Package</div>
          <div class="value">{{ optional($booking->tour)->name ?? '-' }}</div>
        </div>
        <div class="row">
          <div class="label">Amount</div>
          <div class="value">THB {{ number_format($booking->amount_due_now ?? $booking->grand_total ?? 0, 2) }}</div>
        </div>
      </div>

      <div style="margin-top:16px;" class="muted">
        This page is public for QR verification. Do not share it publicly.
      </div>
    </div>
  </div>
</body>
</html>
