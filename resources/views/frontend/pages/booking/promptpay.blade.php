@extends('frontend.layouts.app')

@section('content')
<div class="container" style="margin-top:120px; margin-bottom:80px; max-width:720px;">
  <div class="card">
    <div class="card-title">Scan to Pay (PromptPay)</div>

    <p>Booking #{{ $booking->id }}</p>
    <p>Amount due now: <strong>THB {{ number_format($booking->amount_due_now, 2) }}</strong></p>

    @if($qrPng)
      <div style="text-align:center; margin:20px 0;">
        <img src="{{ $qrPng }}" alt="PromptPay QR" style="max-width:320px; width:100%;">
      </div>
    @else
      <p style="color:red;">ไม่พบ QR จาก Stripe (โปรดติดต่อแอดมิน)</p>
    @endif

    <div id="status" style="margin-top:10px;">Waiting for payment...</div>
  </div>
</div>




<script>
  async function poll() {
    try {
      const res = await fetch("{{ route('frontend.booking.payment_status', $booking->id) }}", {
        headers: { 'Accept': 'application/json' }
      });
      const data = await res.json();

      // แสดงสถานะจาก DB + Stripe (ถ้ามี)
      const text = [
        'payment_status: ' + (data.payment_status ?? '-'),
        'booking_status: ' + (data.booking_status ?? '-'),
        'stripe_status: ' + (data.stripe_status ?? data.status ?? '-'),
      ].join(' | ');

      document.getElementById('status').innerText = text;

      // ✅ เงื่อนไข redirect: ดู DB เป็นหลัก
      if (data.payment_status === 'paid' || data.booking_status === 'confirmed') {
        window.location.href = "{{ route('frontend.booking.confirmed', $booking->id) }}";
        return;
      }
    } catch (e) {}

    setTimeout(poll, 2000); // แนะนำ 2 วิ
  }

  poll();
</script>

@endsection
