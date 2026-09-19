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

                            <div class="mb-3">
                                <label class="form-label">จังหวัด *</label>
                                <select name="province_id" class="form-select @error('province_id') is-invalid @enderror" required>
                                    <option value="">-- เลือกจังหวัด --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->id }}" @selected((string) old('province_id', $pickup_location->province_id) === (string) $province->id)>{{ $province->name_th }}</option>
                                    @endforeach
                                </select>
                                @error('province_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
