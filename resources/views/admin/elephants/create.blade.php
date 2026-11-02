@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">Add Elephant</h1>
                    </div>
                </div>
            </div>

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card mb-7">
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.elephants.store') }}" enctype="multipart/form-data">
                                @csrf

                                                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">ชื่อช้าง (TH)</label>
                                        <input class="form-control" name="name_th" value="{{ old('name_th') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ชื่อช้าง (EN)</label>
                                        <input class="form-control" name="name_en" value="{{ old('name_en') }}" required>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-12">
                                        <label class="form-label required">โปรแกรมของช้างตัวนี้</label>
                                        <select class="form-select" name="tour_id" required>
                                            <option value="">-- เลือกโปรแกรม --</option>
                                            @foreach($tours as $tour)
                                                <option value="{{ $tour->id }}" @selected((string) old('tour_id', '') === (string) $tour->id)>{{ $tour->name }}{{ $tour->is_active ? '' : ' (ปิดอยู่)' }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">ปุ่ม Read more ของช้างตัวนี้ในหน้าแรกจะพาไปหน้าโปรแกรมนี้ ถ้าโปรแกรมถูกปิด จะพาไปหน้า Our Elephants แทน</div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-12">
                                        <h4 class="mb-1">ที่อยู่ของช้าง</h4>
                                        <div class="form-text mb-4">ข้อมูลภายใน เห็นเฉพาะในหลังบ้าน ไม่แสดงบนเว็บไซต์</div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">จังหวัด</label>
                                        <select class="form-select" name="province_id">
                                            <option value="">-- ไม่ระบุ --</option>
                                            @foreach($provinces as $province)
                                                <option value="{{ $province->id }}" @selected((string) old('province_id', '') === (string) $province->id)>{{ $province->name_th }}{{ $province->is_active ? '' : ' (ปิดอยู่)' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">ปาง</label>
                                        <input class="form-control" name="camp_name" value="{{ old('camp_name', '') }}" maxlength="255">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">ข้อมูลปาง</label>
                                        <textarea class="form-control" name="camp_info" rows="4">{{ old('camp_info', '') }}</textarea>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">วันที่ช่วยเหลือ</label>
                                        <input class="form-control" type="date" name="rescued_at" value="{{ old('rescued_at') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ลำดับการแสดง (น้อยก่อน)</label>
                                        <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                        <div class="form-text">ใช้จัดเรียงการแสดงบนหน้าบ้าน</div>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">ประวัติ (TH)</label>
                                        <textarea class="form-control" name="history_th" rows="6">{{ old('history_th') }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">ประวัติ (EN)</label>
                                        <textarea class="form-control" name="history_en" rows="6">{{ old('history_en') }}</textarea>
                                    </div>
                                </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-8">
                                        <label class="form-label">รูปภาพ (1-3 รูป)</label>
                                        <input class="form-control" type="file" name="images[]" accept="image/*" multiple required>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                            <label class="form-check-label">แสดงผลหน้าเว็บไซต์</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary" type="submit">บันทึก</button>
                                    <a href="{{ route('admin.elephants.index') }}" class="btn btn-light">ยกเลิก</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

