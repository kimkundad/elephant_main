@extends('frontend_v2.layouts.app')

@section('title', __('booking.promptpay.title'))

@push('styles')
<style>
.promptpay-v2{
  background:#f7f5f1;
  padding:80px 0 100px;
}
.promptpay-card{
  background:#fff;
  border-radius:20px;
  padding:32px;
  max-width:720px;
  margin:0 auto;
  box-shadow:0 20px 50px rgba(0,0,0,.08);
  border:1px solid rgba(0,0,0,.06);
}
.promptpay-title{
  font-size:24px;
  font-weight:800;
  color:#2b2621;
  margin-bottom:6px;
}
.promptpay-sub{
  color:#6b6156;
  margin-bottom:16px;
}
.promptpay-amount{
  font-weight:800;
  color:#2b2621;
}
.promptpay-qr{
  text-align:center;
  margin:20px 0;
}
.promptpay-qr img{
  max-width:320px;
  width:100%;
}
.promptpay-status{
  margin-top:10px;
  font-size:14px;
  color:#6b6156;
}
</style>
@endpush

@section('content')
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}'); min-height: 250px;">
  <div class="about-hero__overlay"></div>
</section>
<section class="promptpay-v2">
  <div class="container">
    <div class="promptpay-card">
      <div class="promptpay-title">{{ __('booking.promptpay.scan_title') }}</div>
      <div class="promptpay-sub">{{ __('booking.promptpay.booking', ['id' => $booking->id]) }}</div>
      <div class="promptpay-sub">
        {{ __('booking.promptpay.amount_due_now') }}
        <span class="promptpay-amount">THB {{ number_format($booking->amount_due_now, 2) }}</span>
      </div>

      @if($qrPng)
        <div class="promptpay-qr">
          <img src="{{ $qrPng }}" alt="PromptPay QR">
        </div>
      @else
        <p style="color:#b00020;">{{ __('booking.promptpay.qr_missing') }}</p>
      @endif

      <div id="status" class="promptpay-status">{{ __('booking.promptpay.waiting') }}</div>
    </div>
  </div>
</section>

<script>
  async function poll() {
    try {
      const res = await fetch("{{ route('frontend.booking.payment_status', $booking->id) }}", {
        headers: { 'Accept': 'application/json' }
      });
      const data = await res.json();

      const text = [
        "{{ __('booking.promptpay.status_payment') }}: " + (data.payment_status ?? '-'),
        "{{ __('booking.promptpay.status_booking') }}: " + (data.booking_status ?? '-'),
        "{{ __('booking.promptpay.status_stripe') }}: " + (data.stripe_status ?? data.status ?? '-'),
      ].join(' | ');

      document.getElementById('status').innerText = text;

      if (data.payment_status === 'paid' || data.booking_status === 'confirmed') {
        window.location.href = "{{ route('frontend.booking.confirmed.v2', $booking->id) }}";
        return;
      }
    } catch (e) {}

    setTimeout(poll, 2000);
  }

  poll();
</script>
@endsection
