@extends('frontend.layouts.app')

@section('content')
<div class="container" style="margin-top:140px; margin-bottom:120px; max-width:820px;">

  <div class="booking-confirm-card">

    {{-- ICON --}}
    <div class="confirm-icon">✓</div>

    <h1 class="confirm-title">Booking Confirmed</h1>
    <p class="confirm-sub">
      Thank you for your reservation.<br>
      We’ve received your booking details.
    </p>

    {{-- SUMMARY --}}
    <div class="confirm-box">
      <div class="row">
        <div class="col-md-6">
          <div class="label">Booking Reference</div>
          <div class="value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</div>

          <div class="label">Tour</div>
          <div class="value">{{ $booking->tour->name ?? '-' }}</div>

          <div class="label">Date</div>
          <div class="value">
            {{ \Carbon\Carbon::parse($booking->date)->format('l, d F Y') }}
          </div>

          <div class="label">Session</div>
          <div class="value">
            {{ optional($booking->session)->start_time }}
          </div>
        </div>

        <div class="col-md-6">
          <div class="label">Guest name</div>
          <div class="value">{{ $booking->customer_name }}</div>

          <div class="label">Phone</div>
          <div class="value">{{ $booking->customer_phone }}</div>

          <div class="label">Email</div>
          <div class="value">{{ $booking->customer_email }}</div>

          <div class="label">Pickup location</div>
          <div class="value">
            {{ optional($booking->pickupLocation)->name ?? '-' }}
          </div>
        </div>
      </div>
    </div>

    {{-- NEXT STEP --}}
    <div class="confirm-note">
      Our team will contact you shortly to reconfirm your pickup details.
      <br>
      Please keep this reference number for future communication.
    </div>

    {{-- ACTION --}}
    <div class="confirm-actions">
      <a href="{{ route('frontend.home') }}" class="btn-outline">
        Back to Home
      </a>
    </div>

  </div>
</div>
@endsection
