@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        <div class="app-toolbar py-3 py-lg-6">
            <div class="app-container container-xxl d-flex flex-stack">
                <div class="page-title">
                    <h1 class="fs-3 fw-bold">สร้าง Booking ใหม่</h1>
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

                    <form method="POST" action="{{ route('admin.bookings.store') }}">
                        @csrf

                        {{-- CUSTOMER --}}
                        <div class="mb-3">
                            <label class="form-label">ลูกค้า</label>
                            <select name="customer_id" id="customerSelect"
        class="form-select form-select-solid" data-control="select2"
        data-placeholder="-- เลือกลูกค้า --" required>
                                <option value="">-- เลือกลูกค้า --</option>
                                @foreach($customers as $c)
    <option value="{{ $c->id }}"
        {{ old('customer_id', $booking->customer_id ?? null) == $c->id ? 'selected' : '' }}>
        {{ $c->full_name }} ({{ $c->email }})
    </option>
@endforeach
                            </select>
                        </div>

                        {{-- TOUR --}}
                        <div class="mb-3">
                            <label class="form-label">โปรแกรมทัวร์</label>
                            <select id="tourSelect" name="tour_id" class="form-control" required>
                                <option value="">-- เลือกโปรแกรม --</option>
                                @foreach($tours as $t)
                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DATE --}}
                        <div class="mb-3">
                            <label class="form-label">วันที่ไปทัวร์</label>
                            <input type="date" id="dateSelect" name="date"
                                   value="{{ date('Y-m-d') }}" class="form-control" required>
                        </div>

                        {{-- SESSION (Dynamic) --}}
                        <div class="mb-3">
                            <label class="form-label">Session (ตามโปรแกรม + ตามวันที่ว่าง)</label>
                            <select id="sessionSelect" name="session_id" class="form-control" required>
                                <option value="">-- กรุณาเลือกโปรแกรม + วันที่ --</option>
                            </select>
                            <div id="capacityInfo" class="text-info mt-2"></div>
                        </div>

                        {{-- PICKUP --}}
                        <div class="mb-3">
                            <label class="form-label">สถานที่รับลูกค้า</label>
                            <select name="pickup_location_id" class="form-control">
                                <option value="">-- เลือกสถานที่รับ --</option>
                                @foreach($pickupLocations as $p)
                                    <option value="{{ $p->id }}">
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
                                       class="form-control" value="1" min="1" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">เด็ก (Children)</label>
                                <input type="number" id="childrenInput" name="children"
                                       class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">เด็กเล็ก (Infants)</label>
                                <input type="number" id="infantsInput" name="infants"
                                       class="form-control" value="0" min="0">
                            </div>
                        </div>

                        <button class="btn btn-primary">บันทึก Booking</button>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection


{{-- AJAX LOADER --}}
@section('scripts')


<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {

    let tourSelect = document.getElementById("tourSelect");
    let dateSelect = document.getElementById("dateSelect");
    let sessionSelect = document.getElementById("sessionSelect");
    let capacityInfo = document.getElementById("capacityInfo");

    function loadSessions() {
        let tour_id = tourSelect.value;
        let date = dateSelect.value;

        console.log("Load sessions for tour:", tour_id, "date:", date);

        if(!tour_id || !date) {
            sessionSelect.innerHTML = `<option value="">-- กรุณาเลือกโปรแกรม + วันที่ --</option>`;
            return;
        }

        // AJAX
fetch("{{ route('admin.bookings.ajax-sessions') }}?tour_id=" + tour_id + "&date=" + date)
    .then(res => res.json())
    .then(data => {

        // ล้างของเก่า + ใส่ placeholder ใหม่ทุกครั้ง
        sessionSelect.innerHTML = `
            <option value="">
                กรุณาเลือกโปรแกรม + วันที่
            </option>
        `;

        if (data.length === 0) {
            sessionSelect.innerHTML += `
                <option value="">-- ไม่พบ Session ที่ว่าง --</option>
            `;
            capacityInfo.innerHTML = "";
            return;
        }

        data.forEach(s => {
            sessionSelect.innerHTML += `
                <option value="${s.id}">
                    ${s.title} (${s.start_time} - ${s.end_time})
                </option>
            `;
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
