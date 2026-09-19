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
                                    <option value="{{ $t->id }}" data-province="{{ $t->province_id }}">{{ $t->name }}</option>
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
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="selfDrive" name="self_drive" value="1" @checked(old('self_drive'))>
                            <label class="form-check-label" for="selfDrive">ลูกค้าเดินทางมาเอง (ไม่ต้องรับส่ง)</label>
                        </div>

                        <div class="mb-3 js-pickup-field">
                            <label class="form-label">สถานที่รับลูกค้า (เฉพาะจังหวัดของทัวร์)</label>
                            <select name="pickup_location_id" id="pickupSelect" class="form-control">
                                <option value="">-- เลือกสถานที่รับ --</option>
                                @foreach($pickupLocations as $p)
                                    <option value="{{ $p->id }}" data-province="{{ $p->province_id }}" @selected((string) old('pickup_location_id') === (string) $p->id)>
                                        {{ $p->name }}{{ $p->is_meeting_point ? ' (Meeting Point)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pickup_location_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 js-pickup-field">
                            <label class="form-label">รายละเอียดจุดรับส่งเพิ่มเติม</label>
                            <textarea name="pickup_note" class="form-control" rows="2" maxlength="1000">{{ old('pickup_note') }}</textarea>
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

                        {{-- PAYMENT --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">สถานะการจอง</label>
                                <select name="status" class="form-select">
                                    <option value="confirmed" @selected(old('status', 'confirmed') === 'confirmed')>Confirmed</option>
                                    <option value="pending" @selected(old('status') === 'pending')>Pending</option>
                                    <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">สถานะการชำระเงิน</label>
                                <select name="payment_status" class="form-select">
                                    <option value="pending" @selected(old('payment_status', 'pending') === 'pending')>ยังไม่ชำระ</option>
                                    <option value="paid" @selected(old('payment_status') === 'paid')>ชำระแล้ว</option>
                                    <option value="failed" @selected(old('payment_status') === 'failed')>ชำระไม่สำเร็จ</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ช่องทางชำระเงิน</label>
                                <select name="payment_channel" class="form-select">
                                    <option value="">-- ยังไม่ระบุ --</option>
                                    <option value="cash" @selected(old('payment_channel') === 'cash')>เงินสด</option>
                                    <option value="transfer" @selected(old('payment_channel') === 'transfer')>โอนเงิน</option>
                                    <option value="card" @selected(old('payment_channel') === 'card')>บัตรเครดิต/เดบิต</option>
                                    <option value="promptpay" @selected(old('payment_channel') === 'promptpay')>QR พร้อมเพย์</option>
                                </select>
                            </div>
                        </div>

                        {{-- AGENT / DISCOUNT --}}
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">พนักงานขาย</label>
                                <select name="agent_id" class="form-select">
                                    <option value="">-- ไม่มี --</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" @selected((string) old('agent_id') === (string) $agent->id)>{{ $agent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">โค้ดส่วนลด</label>
                                <input type="text" name="discount_code" class="form-control" maxlength="50" value="{{ old('discount_code') }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">ส่วนลด (บาท)</label>
                                <input type="number" step="0.01" min="0" name="discount_amount" class="form-control" value="{{ old('discount_amount', 0) }}">
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
                    ${s.title} (${s.time_range})
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
<script>
// Only offer pickup points in the selected tour's province.
(function () {
    const tourSelect = document.getElementById('tourSelect');
    const pickupSelect = document.getElementById('pickupSelect');
    if (!tourSelect || !pickupSelect) return;

    const syncPickupOptions = () => {
        const provinceId = tourSelect.selectedOptions[0]?.dataset.province || '';
        Array.from(pickupSelect.options).forEach((option) => {
            if (!option.value) return;
            const inProvince = option.dataset.province === provinceId;
            option.hidden = !inProvince;
            option.disabled = !inProvince;
        });
        if (pickupSelect.selectedOptions[0]?.disabled) pickupSelect.value = '';
    };

    tourSelect.addEventListener('change', syncPickupOptions);
    syncPickupOptions();
})();

// Self drive means no pickup point at all.
(function () {
    const selfDrive = document.getElementById('selfDrive');
    const fields = document.querySelectorAll('.js-pickup-field');
    if (!selfDrive || !fields.length) return;

    const syncSelfDrive = () => {
        fields.forEach((field) => { field.style.display = selfDrive.checked ? 'none' : ''; });
    };

    selfDrive.addEventListener('change', syncSelfDrive);
    syncSelfDrive();
})();
</script>
@endsection
