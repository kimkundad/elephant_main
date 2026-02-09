@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">แก้ไขข้อมูลช้าง</h1>
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
                            <form method="POST" action="{{ route('admin.elephants.update', $elephant->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">ชื่อช้าง</label>
                                        <input class="form-control" name="name" value="{{ old('name', $elephant->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">วันที่ช่วยเหลือ</label>
                                        <input class="form-control" type="date" name="rescued_at" value="{{ old('rescued_at', optional($elephant->rescued_at)->format('Y-m-d')) }}">
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-8">
                                        <label class="form-label">ประวัติ</label>
                                        <textarea class="form-control" name="history" rows="6">{{ old('history', $elephant->history) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">ลำดับการแสดง (น้อยก่อน)</label>
                                        <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $elephant->sort_order) }}" min="0">
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-8">
                                        <label class="form-label">เปลี่ยนรูปภาพ (อัปใหม่ 1-3 รูป)</label>
                                        <input class="form-control" type="file" name="images[]" accept="image/*" multiple>
                                        <div class="form-text">
                                            ถ้าไม่เลือก ระบบจะใช้รูปเดิมทั้งหมด
                                            ถ้าอัปโหลดใหม่ ระบบจะลบรูปเดิมทั้งหมดและแทนด้วยชุดใหม่
                                        </div>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-center">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $elephant->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">แสดงผลหน้าเว็บไซต์</label>
                                        </div>
                                    </div>
                                </div>

                                @if(!empty($elephant->images))
                                    <div class="row mb-6">
                                        <div class="col-md-12">
                                            <label class="form-label">รูปปัจจุบัน</label>
                                            <div class="d-flex gap-3 flex-wrap">
                                                @foreach($elephant->images as $img)
                                                    <img src="{{ $img }}" alt="{{ $elephant->name }}" style="width:120px;height:90px;object-fit:cover;border-radius:6px;">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
