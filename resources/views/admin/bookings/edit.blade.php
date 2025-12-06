@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- HEADER --}}
        <div class="app-toolbar py-3 py-lg-6">
            <div class="app-container container-xxl d-flex flex-stack">
                <div class="page-title">
                    <h1 class="fs-3 fw-bold">แก้ไข Booking #{{ $booking->id }}</h1>
                </div>
            </div>
        </div>

        <div class="app-container container-xxl">
            <div class="card mb-7">
                <div class="card-body">

                    {{-- ERROR DISPLAY --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM --}}
                    <form method="POST" action="{{ route('admin.bookings.update', $booking->id) }}">
                        @csrf

                        {{-- CUSTOMER --}}
                        <div class="mb-3">
                            <label class="form-label">ลูกค้า</label>
                            <select name="customer_id" class="form-control" required>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}"
                                        {{ $booking->customer_id == $c->id ? 'selected' : '' }}>
                                        {{ $c->full_name }} ({{ $c->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- TOUR --}}
                        <div class="mb-3">
                            <label class="form-label">โปรแกรมทัวร์</label>
                            <select id="tourSelect" name="tour_id" class="form-control" required>
                                @foreach($tours as $t)
                                    <option value="{{ $t->id }}"
                                        {{ $booking->tour_id == $t->id ? 'selected' : '' }}>
                                        {{ $t->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DATE --}}
                        <div class="mb-3">
                            <label class="form-label">วันที่ไปทัวร์</label>
                            <input type="date" id="dateSelect" name="date"
                                   value="{{ $booking->date }}" class="form-control" required>
                        </div>

                        {{-- SESSION (AJAX) --}}
                        <div class="mb-3">
                            <label class="form-label">Session (ตามโปรแกรม + วันที่)</label>
                            <select id="sessionSelect" name="session_id" class="form-control" required>
                                {{-- Preload ค่าเดิม --}}
                                <option value="{{ $booking->session_id }}">
                                    {{ $booking->session->title }}
                                    ({{ $booking->session->start_time }} - {{ $booking->session->end_time }})
                                </option>
                            </select>

                            <div id="capacityInfo" class="text-info mt-2"></div>
                        </div>

                        {{-- PICKUP --}}
                        <div class="mb-3">
                            <label class="form-label">สถานที่รับลูกค้า</label>
                            <select name="pickup_location_id" class="form-control">
                                <option value="">-- เลือกสถานที่รับ --</option>
                                @foreach($pickupLocations as $p)
                                    <option value="{{ $p->id }}"
                                        {{ $booking->pickup_location_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }} ({{ $p->pickup_time }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- PEOPLE --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ผู้ใหญ่ (Adults)</label>
                                <input type="number" id="adultsInput" name="adults"
                                       class="form-control"
                                       value="{{ $booking->adults }}" min="1" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">เด็ก (Children)</label>
                                <input type="number" id="childrenInput" name="children"
                                       class="form-control"
                                       value="{{ $booking->children }}" min="0">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">เด็กเล็ก (Infants)</label>
                                <input type="number" id="infantsInput" name="infants"
                                       class="form-control"
                                       value="{{ $booking->infants }}" min="0">
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="mb-3">
                            <label class="form-label">สถานะ</label>
                            <select name="status" class="form-control">
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <button class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection



{{-- AJAX LOADER --}}
@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {

    let tourSelect = document.getElementById("tourSelect");
    let dateSelect = document.getElementById("dateSelect");
    let sessionSelect = document.getElementById("sessionSelect");
    let capacityInfo = document.getElementById("capacityInfo");

    function loadSessions() {
        let tour_id = tourSelect.value;
        let date = dateSelect.value;

        if(!tour_id || !date) {
            return;
        }

        fetch("{{ route('admin.bookings.ajax-sessions') }}?tour_id=" + tour_id + "&date=" + date)
            .then(res => res.json())
            .then(data => {
                sessionSelect.innerHTML = "";

                if(data.length === 0) {
                    sessionSelect.innerHTML =
                        `<option value="">-- ไม่พบ Session ที่ว่าง --</option>`;
                    capacityInfo.innerHTML = "";
                    return;
                }

                data.forEach(s => {
                    sessionSelect.innerHTML += `
                        <option value="${s.id}">
                            ${s.title} (${s.start_time} - ${s.end_time})
                        </option>`;
                });

                capacityInfo.innerHTML = "เลือก Session เพื่อดูจำนวนที่ว่าง";
            });
    }

    function updateCapacity() {
        let session_id = sessionSelect.value;
        let date = dateSelect.value;

        if(!session_id || !date) {
            capacityInfo.innerHTML = "";
            return;
        }

        fetch("{{ route('admin.bookings.ajax-capacity') }}?session_id=" + session_id + "&date=" + date)
            .then(res => res.json())
            .then(data => {
                capacityInfo.innerHTML =
                    `<strong>เหลือที่ว่าง:</strong> ${data.remaining} คน`;
            });
    }

    tourSelect.addEventListener("change", loadSessions);
    dateSelect.addEventListener("change", loadSessions);
    sessionSelect.addEventListener("change", updateCapacity);
});
</script>
@endsection
