@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Header --}}
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">

                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading fs-3 fw-bold">แก้ไขจุดรับ–ส่งลูกค้า</h1>
                </div>

            </div>
        </div>

        {{-- Content --}}
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card mb-7">
                    <div class="card-body">

                        {{-- ฟอร์มแก้ไข --}}
                        <form method="POST" action="{{ route('admin.pickup-locations.update', $pickup_location->id) }}">
                            @csrf
                            @method('PUT')

                            {{-- ชื่อโรงแรม --}}
                            <div class="mb-3">
                                <label class="form-label">ชื่อโรงแรม / จุดรับส่ง</label>
                                <input type="text" name="name" id="hotelName"
                                       value="{{ old('name', $pickup_location->name) }}"
                                       class="form-control" required>
                            </div>



                            {{-- ช่องค้นหาโรงแรม --}}
                            <div class="mb-3">
                                <label class="form-label">ค้นหาโรงแรม / สถานที่ จาก Google</label>
                                <input id="searchInput" type="text" class="form-control"
                                       placeholder="ค้นหาโรงแรม เช่น Centara, Maya Mall, Akyra">
                            </div>

                            {{-- แผนที่ --}}
                            <label class="form-label">เลือกตำแหน่งบนแผนที่</label>
                            <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Latitude</label>
                                    <input type="text" id="latitude" name="latitude"
                                           value="{{ old('latitude', $pickup_location->latitude) }}"
                                           class="form-control" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Longitude</label>
                                    <input type="text" id="longitude" name="longitude"
                                           value="{{ old('longitude', $pickup_location->longitude) }}"
                                           class="form-control" required>
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
                   {{ old('is_active', $pickup_location->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">
                เปิดใช้งาน (Active)
            </label>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-check form-switch">
            <input class="form-check-input"
                   type="checkbox"
                   id="is_meeting_point"
                   name="is_meeting_point"
                   value="1"
                   {{ old('is_meeting_point', $pickup_location->is_meeting_point) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_meeting_point">
                เป็น “จุดนัดรับ” (Meeting Point)
            </label>
        </div>
    </div>
</div>



                            <Br>

                            <button class="btn btn-primary mt-4">อัปเดตข้อมูล</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection


{{-- Google Maps Script --}}
@section('scripts')
<script>
let map, marker, autocomplete;

function initMap() {

    // ค่าเริ่มต้นจากข้อมูลในฐานข้อมูล
    const defaultPos = {
        lat: parseFloat("{{ $pickup_location->latitude }}"),
        lng: parseFloat("{{ $pickup_location->longitude }}")
    };

    map = new google.maps.Map(document.getElementById("map"), {
        center: defaultPos,
        zoom: 14,
    });

    marker = new google.maps.Marker({
        map: map,
        position: defaultPos,
        draggable: true
    });

    // ขยับ marker → update lat/lng
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
            alert("ไม่พบข้อมูลตำแหน่งจาก Google");
            return;
        }

        // zoom ไปยังตำแหน่ง
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(16);
        }

        marker.setPosition(place.geometry.location);
        updateLatLng(place.geometry.location);

        // ⭐ ใส่ชื่อโรงแรมอัตโนมัติ
        document.getElementById("hotelName").value = place.name;
    });
}

function updateLatLng(latLng) {
    document.getElementById("latitude").value = latLng.lat();
    document.getElementById("longitude").value = latLng.lng();
}
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCsgTpv6fjOh2YRd1X0N92QdIV76A0gPX0&libraries=places&callback=initMap"
        async defer></script>
@endsection
