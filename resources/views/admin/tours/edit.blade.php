@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">
                        แก้ไขโปรแกรมทัวร์
                    </h1>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card mb-7">
                    <div class="card-body">

                        <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">ชื่อโปรแกรมทัวร์ *</label>
                                <input type="text" name="name" class="form-control" value="{{ $tour->name }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">คำอธิบายสั้น</label>
                                <textarea name="short_description" class="form-control" rows="3">{{ $tour->short_description }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">รายละเอียดเต็ม</label>
                                <textarea name="description" class="form-control" rows="5">{{ $tour->description }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ราคาเริ่มต้น *</label>
                                    <input type="number" name="min_price" class="form-control" value="{{ $tour->min_price }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ราคาสูงสุด *</label>
                                    <input type="number" name="max_price" class="form-control" value="{{ $tour->max_price }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">รูปหลักปัจจุบัน</label><br>
                                <img src="{{ $tour->thumbnail }}" width="150" class="rounded mb-3">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">อัปโหลดรูปหลักใหม่ (ถ้ามี)</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">สถานะการแสดงผล</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" @selected($tour->is_active)>เปิด</option>
                                    <option value="0" @selected(!$tour->is_active)>ปิด</option>
                                </select>
                            </div>

                            <button class="btn btn-primary">บันทึกการแก้ไข</button>
                            <a href="{{ route('admin.tours.index') }}" class="btn btn-light">ยกเลิก</a>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
