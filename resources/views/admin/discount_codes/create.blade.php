@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">เพิ่มโค้ดส่วนลด</h1>
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
                            <form method="POST" action="{{ route('admin.discount-codes.store') }}">
                                @csrf

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">โค้ดส่วนลด</label>
                                        <input class="form-control" name="code" value="{{ old('code') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">จำนวนส่วนลด (บาท)</label>
                                        <input class="form-control" type="number" name="amount" value="{{ old('amount', 500) }}" min="0" step="0.01" required>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">จำนวนครั้งที่ใช้ได้สูงสุด</label>
                                        <input class="form-control" type="number" name="max_uses" value="{{ old('max_uses', 1) }}" min="1" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">เอเจนต์ (ถ้ามี)</label>
                                        <select class="form-control" name="agent_id">
                                            <option value="">ไม่ระบุ</option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                                    {{ $agent->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-6">
                                    <div class="col-md-6">
                                        <label class="form-label">เริ่มใช้ได้ (ถ้ามี)</label>
                                        <input class="form-control" type="datetime-local" name="starts_at" value="{{ old('starts_at') }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">หมดอายุ (ถ้ามี)</label>
                                        <input class="form-control" type="datetime-local" name="ends_at" value="{{ old('ends_at') }}">
                                    </div>
                                </div>

                                <div class="mb-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                        <label class="form-check-label">เปิดใช้งาน</label>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary" type="submit">บันทึก</button>
                                    <a href="{{ route('admin.discount-codes.index') }}" class="btn btn-light">ยกเลิก</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
