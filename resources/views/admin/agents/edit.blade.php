@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">แก้ไขพนักงานขาย</h1>
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
                            <form method="POST" action="{{ route('admin.agents.update', $agent->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">ชื่อพนักงานขาย</label>
                                        <input class="form-control" name="name" value="{{ old('name', $agent->name) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">อีเมล</label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email', $agent->email) }}">
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">เบอร์โทร</label>
                                        <input class="form-control" name="phone" value="{{ old('phone', $agent->phone) }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">สถานะ</label>
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $agent->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label">เปิดใช้งาน</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <label class="form-label">หมายเหตุ</label>
                                    <textarea class="form-control" name="notes" rows="4">{{ old('notes', $agent->notes) }}</textarea>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary" type="submit">บันทึก</button>
                                    <a href="{{ route('admin.agents.index') }}" class="btn btn-light">ยกเลิก</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
