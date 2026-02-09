@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">เพิ่มข้อมูลช้าง</h1>
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
                                        <label class="form-label">ชื่อช้าง</label>
                                        <input class="form-control" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">วันที่ช่วยเหลือ</label>
                                        <input class="form-control" type="date" name="rescued_at" value="{{ old('rescued_at') }}">
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-8">
                                        <label class="form-label">ประวัติ</label>
                                        <textarea class="form-control" name="history" rows="6">{{ old('history') }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">ลำดับการแสดง (น้อยก่อน)</label>
                                        <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                                        <div class="form-text">ใช้จัดเรียงการแสดงบนหน้าบ้าน</div>
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
