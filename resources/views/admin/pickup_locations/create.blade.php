@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid">

    <div class="d-flex flex-column flex-column-fluid">

        <div class="app-toolbar py-3">
            <div class="app-container container-xxl d-flex flex-stack">
                <h1 class="fs-3">เพิ่มจุดรับส่งลูกค้า</h1>
            </div>
        </div>

        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.pickup-locations.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">ชื่อโรงแรม / จุดรับส่ง</label>
                            <input type="text" name="name" id="hotelName" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ค้นหาโรงแรม / สถานที่</label>
                            <input id="searchInput" type="text" class="form-control" placeholder="เช่น Centara, Akyra, Maya Mall">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">เลือกตำแหน่งบนแผนที่</label>
                            <div id="map" style="height: 350px;"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Latitude</label>
                                <input type="text" id="latitude" name="latitude" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Longitude</label>
                                <input type="text" id="longitude" name="longitude" class="form-control">
                            </div>
                        </div>

                        {{-- สถานะ / ประเภท --}}
<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input"
                   type="checkbox"
                   id="is_active"
                   name="is_active"
                   value="1"
                   {{ old('is_active', 1) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                เปิดใช้งาน (Active)
            </label>
        </div>
        <small class="text-muted">ถ้าปิด จะไม่แสดงให้ลูกค้าเลือกในหน้า Booking</small>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input"
                   type="checkbox"
                   id="is_meeting_point"
                   name="is_meeting_point"
                   value="1"
                   {{ old('is_meeting_point', 0) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_meeting_point">
                เป็น “จุดนัดรับ” (Meeting Point)
            </label>
        </div>
        <small class="text-muted">ใช้กรณีลูกค้าอยู่นอกเขตโรงแรมรับส่ง ให้มารอที่จุดนี้</small>
    </div>
</div>

<br>

                        <button class="btn btn-primary">บันทึกข้อมูล</button>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script>
let map, marker, autocomplete;

function initMap() {

    const defaultPos = { lat: 18.7883, lng: 98.9853 };

    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultPos,
        zoom: 13,
    });

    marker = new google.maps.Marker({
        map: map,
        position: defaultPos,
        draggable: true
    });

    marker.addListener("dragend", function (event) {
        updateLatLng(event.latLng);
    });

    // Autocomplete
    const input = document.getElementById("searchInput");
    autocomplete = new google.maps.places.Autocomplete(input, {
        types: ["establishment"],
        componentRestrictions: { country: "th" }
    });

    autocomplete.bindTo("bounds", map);

    autocomplete.addListener("place_changed", function () {
        const place = autocomplete.getPlace();

        if (!place.geometry) {
            alert("ไม่พบตำแหน่งของสถานที่นี้");
            return;
        }

        // เลื่อนแผนที่ไปตำแหน่งของโรงแรม
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(16);
        }

        marker.setPosition(place.geometry.location);
        updateLatLng(place.geometry.location);

        // ⭐ อัปเดตชื่อโรงแรมโดยอัตโนมัติ
        document.getElementById("hotelName").value = place.name;
    });
}

// อัปเดต lat/lng ลง input
function updateLatLng(latLng) {
    document.getElementById("latitude").value = latLng.lat();
    document.getElementById("longitude").value = latLng.lng();
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsgTpv6fjOh2YRd1X0N92QdIV76A0gPX0&libraries=places&callback=initMap"
        async defer></script>
@endsection
