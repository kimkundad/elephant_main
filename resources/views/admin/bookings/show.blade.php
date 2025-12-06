@extends('partials.admin.template')

@section('content')
<div class="app-container container-xxl">
    <div class="card mb-7">
        <div class="card-body">

            <a href="{{ route('admin.bookings.pdf', $booking->id) }}" class="btn btn-dark mb-3" target="_blank">
                🖨️ ดาวน์โหลดใบจอง (PDF)
            </a>

            <h3>ข้อมูลการจอง #{{ $booking->id }}</h3>

            <div class="mt-4">
                <strong>วันที่ไปทัวร์:</strong> {{ $booking->date }} <br>
                <strong>โปรแกรม:</strong> {{ $booking->tour->name }} <br>
                <strong>Session:</strong> {{ $booking->session->title }}
                ({{ $booking->session->start_time }} - {{ $booking->session->end_time }})
            </div>

            <div class="mt-4">
                <strong>ลูกค้า:</strong> {{ $booking->customer->full_name }} <br>
                Email: {{ $booking->customer->email }} <br>
                Phone: {{ $booking->customer->phone }}
            </div>

            <div class="mt-4">
                <strong>Pickup:</strong> {{ $booking->pickupLocation?->name ?? '-' }} <br>
            </div>

            <div class="mt-4">
                <strong>จำนวนคน:</strong> {{ $booking->total_guests }} <br>
                (A: {{ $booking->adults }}, C: {{ $booking->children }}, I: {{ $booking->infants }})
            </div>

            <div class="mt-4">
                <strong>ราคาทั้งหมด:</strong> THB {{ number_format($booking->total_price, 2) }}
            </div>

            <div class="mt-4">
                <strong>สถานะ:</strong> {{ $booking->status }}
            </div>

        </div>
    </div>
</div>
@endsection
