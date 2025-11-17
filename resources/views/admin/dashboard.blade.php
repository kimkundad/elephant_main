@extends('partials.admin.template')

@section('content')
<div class="container-xxl">
    <h1 class="mb-5">Dashboard</h1>

    @role('superAdmin')
        <div class="alert alert-primary">
            คุณคือ Super Admin (เห็นข้อมูลทั้งหมด)
        </div>
    @endrole

    @role('admin')
        <div class="alert alert-info">
            คุณคือ Admin (จัดการ Tours / Bookings / Pages)
        </div>
    @endrole

    {{-- ตัวอย่างจอการจองวันนี้ --}}
    <div class="row">
        <div class="col-md-4">
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">Bookings Today</h3>
                </div>
                <div class="card-body">
                    {{ $bookingsToday ?? 0 }}
                </div>
            </div>
        </div>
        {{-- etc... --}}
    </div>
</div>
@endsection
