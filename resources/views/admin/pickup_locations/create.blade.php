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
                            <label class="form-label">จังหวัด *</label>
                            <select name="province_id" class="form-select @error('province_id') is-invalid @enderror" required>
                                <option value="">-- เลือกจังหวัด --</option>
                                @foreach($provinces as $province)
                                    <option value="{{ $province->id }}" @selected((string) old('province_id') === (string) $province->id)>{{ $province->name_th }}</option>
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

