@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div class="app-toolbar py-3 py-lg-6">
                <div class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title">
                        <h1 class="fs-3 fw-bold">รายการจองทั้งหมด</h1>
                    </div>

                    <a href="{{ route('admin.bookings.create') }}" class="btn btn-primary">
                        + สร้าง Booking ใหม่
                    </a>
                </div>
            </div>

            <div class="app-container container-xxl">
                <div class="card mb-7">
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>วันที่ไปทัวร์</th>
                                    <th>โปรแกรม</th>
                                    <th>Session</th>
                                    <th>ลูกค้า</th>
                                    <th>จำนวนคน</th>
                                    <th>ราคารวม</th>
                                    <th>ส่วนลด</th>
                                    <th>โค้ด</th>
                                    <th>พนักงานขาย</th>
                                    <th>สถานะ</th>
                                    <th>การกระทำ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bookings as $b)
                                    <tr>
                                        <td>{{ $b->date }}</td>
                                        <td>{{ $b->tour?->name }}</td>
                                        <td>
                                            {{ $b->session?->title ?? $b->session?->name }}
                                            <br>
                                            <small>{{ $b->session?->start_time }} - {{ $b->session?->end_time }}</small>
                                        </td>
                                        <td>{{ $b->customer?->full_name ?? $b->customer_name }}</td>
                                        <td>
                                            {{ $b->total_guests }}
                                            <small>
                                                (A: {{ $b->adults }},
                                                C: {{ $b->children }},
                                                I: {{ $b->infants }})
                                            </small>
                                        </td>
                                        <td>{{ number_format($b->total_price, 2) }}</td>
                                        <td>{{ number_format($b->discount_amount ?? 0, 2) }}</td>
                                        <td>{{ $b->discount_code ?? '-' }}</td>
                                        <td>{{ $b->agent?->name ?? '-' }}</td>
                                        <td>{{ $b->status }}</td>
                                        <td class="text-nowrap">
                                            <a href="{{ route('admin.bookings.show', $b->id) }}"
                                                class="btn btn-sm btn-primary me-1">
                                                ดู
                                            </a>

                                            <a href="{{ route('admin.bookings.edit', $b->id) }}"
                                                class="btn btn-sm btn-warning me-1">
                                                แก้ไข
                                            </a>

                                            <a href="{{ route('admin.bookings.cancel', $b->id) }}"
                                                onclick="return confirm('ยืนยันยกเลิก?');"
                                                class="btn btn-sm btn-danger me-1">
                                                ยกเลิก
                                            </a>

                                            <a href="{{ route('admin.bookings.pdf', $b->id) }}" target="_blank"
                                                class="btn btn-sm btn-dark">
                                                PDF
                                            </a>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $bookings->links() }}

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
