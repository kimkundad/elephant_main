@extends('partials.admin.template')

@php
    $statusBadges = [
        'confirmed' => 'badge-light-success',
        'pending'   => 'badge-light-warning',
        'cancelled' => 'badge-light-danger',
    ];
    $paymentBadges = [
        'paid'        => 'badge-light-success',
        'pending'     => 'badge-light-warning',
        'awaiting_qr' => 'badge-light-info',
        'failed'      => 'badge-light-danger',
    ];
    $statusBadge = $statusBadges[$booking->status] ?? 'badge-light-secondary';
    $paymentBadge = $paymentBadges[$booking->payment_status] ?? 'badge-light-secondary';
    $customerName = $booking->customer?->full_name ?? $booking->customer_name ?? '-';
    $customerEmail = $booking->customer?->email ?? $booking->customer_email;
    $customerPhone = $booking->customer?->phone ?? $booking->customer_phone;
@endphp

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    {{-- Toolbar --}}
    <div class="app-toolbar py-3 py-lg-6">
      <div class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center">
          <h1 class="page-heading text-dark fw-bold fs-2 my-0">
            การจอง #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
          </h1>
          <div class="text-muted fs-7 mt-1">
            จองเมื่อ {{ $booking->created_at?->format('d/m/Y H:i') ?? '-' }}
            @if($booking->creator)
              &middot; โดย {{ $booking->creator->name }}
            @endif
          </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light">ย้อนกลับ</a>
          <a href="{{ route('admin.bookings.edit', $booking->id) }}" class="btn btn-sm btn-light-primary">แก้ไข</a>
          <a href="{{ route('admin.bookings.pdf', $booking->id) }}" class="btn btn-sm btn-light-info" target="_blank">ใบจอง (PDF)</a>
          @if($booking->status !== 'cancelled')
            <a href="{{ route('admin.bookings.cancel', $booking->id) }}" class="btn btn-sm btn-light-danger"
               onclick="return confirm('ยกเลิกการจองนี้?')">ยกเลิกการจอง</a>
          @endif
        </div>
      </div>
    </div>

    <div class="app-content flex-column-fluid">
      <div class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-6 g-xl-9">

          {{-- LEFT --}}
          <div class="col-lg-8">

            {{-- Tour --}}
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">รายละเอียดทัวร์</h3>
              </div>
              <div class="card-body pt-4">
                <table class="table table-row-dashed align-middle mb-0">
                  <tr>
                    <td class="text-muted w-200px">โปรแกรม</td>
                    <td class="fw-bold">
                      {{ $booking->tour?->name ?? '-' }}
                      @if($booking->tour?->province)
                        <span class="badge badge-light-primary ms-2">{{ $booking->tour->province->name_th }}</span>
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">วันที่ไปทัวร์</td>
                    <td class="fw-bold">{{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }}</td>
                  </tr>
                  <tr>
                    <td class="text-muted">รอบเวลา</td>
                    <td class="fw-bold">
                      {{ $booking->session?->title ?? $booking->session?->name ?? '-' }}
                      <span class="text-muted fw-normal">{{ $booking->session?->time_range }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">จำนวนผู้เข้าร่วม</td>
                    <td>
                      <span class="fw-bold fs-5 me-3">{{ $booking->total_guests }} คน</span>
                      <span class="badge badge-light me-1">ผู้ใหญ่ {{ $booking->adults }}</span>
                      <span class="badge badge-light me-1">เด็ก {{ $booking->children }}</span>
                      <span class="badge badge-light">ทารก {{ $booking->infants }}</span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">จุดรับส่ง</td>
                    <td class="fw-bold">
                      {{-- The admin UI is Thai, so don't reuse the guest-facing translation here. --}}
                      {{ $booking->self_drive ? 'เดินทางไปเอง (Self Drive)' : $booking->pickupLabel() }}
                      @if($booking->pickupLocation?->is_meeting_point)
                        <span class="badge badge-light-info ms-2">Meeting Point</span>
                      @endif
                      @if($booking->pickupDetail())
                        <div class="text-muted fw-normal mt-1" style="white-space:pre-line;">{{ $booking->pickupDetail() }}</div>
                      @endif
                    </td>
                  </tr>
                </table>
              </div>
            </div>

            {{-- Customer --}}
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">ข้อมูลลูกค้า</h3>
                @if($booking->customer)
                  <div class="card-toolbar">
                    <a href="{{ route('admin.customers.edit', $booking->customer->id) }}" class="btn btn-sm btn-light">โปรไฟล์ลูกค้า</a>
                  </div>
                @endif
              </div>
              <div class="card-body pt-4">
                <table class="table table-row-dashed align-middle mb-0">
                  <tr>
                    <td class="text-muted w-200px">ชื่อผู้จอง</td>
                    <td class="fw-bold">{{ $customerName }}</td>
                  </tr>
                  <tr>
                    <td class="text-muted">อีเมล</td>
                    <td class="fw-bold">
                      @if($customerEmail)
                        <a href="mailto:{{ $customerEmail }}">{{ $customerEmail }}</a>
                      @else
                        -
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">เบอร์โทร</td>
                    <td class="fw-bold">
                      @if($customerPhone)
                        <a href="tel:{{ $customerPhone }}">{{ $customerPhone }}</a>
                      @else
                        -
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">อีเมลยืนยัน</td>
                    <td>
                      @if($booking->confirmation_email_sent_at)
                        <span class="badge badge-light-success">ส่งแล้ว {{ \Carbon\Carbon::parse($booking->confirmation_email_sent_at)->format('d/m/Y H:i') }}</span>
                      @else
                        <span class="badge badge-light-secondary">ยังไม่ได้ส่ง</span>
                      @endif
                    </td>
                  </tr>
                </table>
              </div>
            </div>

          </div>

          {{-- RIGHT --}}
          <div class="col-lg-4">

            {{-- Status --}}
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">สถานะ</h3>
              </div>
              <div class="card-body pt-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <span class="text-muted">สถานะการจอง</span>
                  <span class="badge {{ $statusBadge }} fs-7">{{ $booking->status ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <span class="text-muted">สถานะการชำระเงิน</span>
                  <span class="badge {{ $paymentBadge }} fs-7">{{ $booking->payment_status ?? '-' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <span class="text-muted">ช่องทางชำระ</span>
                  <span class="fw-bold">{{ strtoupper($booking->payment_channel ?? '-') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                  <span class="text-muted">ชำระเมื่อ</span>
                  <span class="fw-bold">{{ $booking->paid_at ? \Carbon\Carbon::parse($booking->paid_at)->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="text-muted">เช็คอินหน้างาน</span>
                  @if($booking->checked_in_at)
                    <span class="badge badge-light-success fs-7">
                      {{ $booking->checked_in_at->format('d/m/Y H:i') }}
                      @if($booking->checked_in_by) &middot; {{ $booking->checked_in_by }} @endif
                    </span>
                  @else
                    <span class="badge badge-light-secondary fs-7">ยังไม่เช็คอิน</span>
                  @endif
                </div>
              </div>
            </div>

            {{-- Money --}}
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">สรุปราคา</h3>
              </div>
              <div class="card-body pt-4">
                <div class="d-flex justify-content-between mb-3">
                  <span class="text-muted">ราคาก่อนภาษี</span>
                  <span>THB {{ number_format($booking->subtotal ?? 0, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                  <span class="text-muted">VAT</span>
                  <span>THB {{ number_format($booking->vat_amount ?? 0, 2) }}</span>
                </div>
                @if(($booking->fee_amount ?? 0) > 0)
                  <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">ค่าธรรมเนียม</span>
                    <span>THB {{ number_format($booking->fee_amount, 2) }}</span>
                  </div>
                @endif
                @if(($booking->discount_amount ?? 0) > 0)
                  <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">
                      ส่วนลด
                      @if($booking->discount_code)
                        <span class="badge badge-light-success ms-1">{{ $booking->discount_code }}</span>
                      @endif
                    </span>
                    <span class="text-danger">- THB {{ number_format($booking->discount_amount, 2) }}</span>
                  </div>
                @endif

                <div class="separator my-4"></div>

                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold fs-5">ยอดรวม</span>
                  <span class="fw-bold fs-3 text-dark">THB {{ number_format($booking->grand_total ?? $booking->total_price ?? 0, 2) }}</span>
                </div>

                @if(($booking->amount_pay_later ?? 0) > 0)
                  <div class="d-flex justify-content-between mt-3">
                    <span class="text-muted">ชำระตอนนี้ / ชำระภายหลัง</span>
                    <span>THB {{ number_format($booking->amount_due_now ?? 0, 2) }} / THB {{ number_format($booking->amount_pay_later, 2) }}</span>
                  </div>
                @endif

                <div class="d-flex justify-content-between mt-4">
                  <span class="text-muted">พนักงานขาย</span>
                  <span class="fw-bold">{{ $booking->agent?->name ?? '-' }}</span>
                </div>
              </div>
            </div>

            {{-- System --}}
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">ข้อมูลระบบ</h3>
              </div>
              <div class="card-body pt-4 fs-7">
                <div class="d-flex justify-content-between mb-3">
                  <span class="text-muted">หน้ายืนยันของลูกค้า</span>
                  @if($booking->public_code)
                    <a href="{{ route('booking.public', $booking->public_code) }}" target="_blank">เปิดดู</a>
                  @else
                    <span>-</span>
                  @endif
                </div>
                <div class="d-flex justify-content-between mb-3">
                  <span class="text-muted">Stripe PaymentIntent</span>
                  <span class="text-truncate ms-3" style="max-width:170px;" title="{{ $booking->stripe_payment_intent_id }}">
                    {{ $booking->stripe_payment_intent_id ?? '-' }}
                  </span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                  <span class="text-muted">Stripe Session</span>
                  <span class="text-truncate ms-3" style="max-width:170px;" title="{{ $booking->stripe_session_id }}">
                    {{ $booking->stripe_session_id ?? '-' }}
                  </span>
                </div>
                <div class="d-flex justify-content-between">
                  <span class="text-muted">แก้ไขล่าสุด</span>
                  <span>{{ $booking->updated_at?->format('d/m/Y H:i') ?? '-' }}</span>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
