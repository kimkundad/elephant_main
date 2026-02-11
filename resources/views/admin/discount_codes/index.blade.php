@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">โค้ดส่วนลด</h1>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('admin.discount-codes.create') }}" class="btn btn-primary">+ เพิ่มโค้ดส่วนลด</a>
                    </div>
                </div>
            </div>

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card mb-7">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>โค้ด</th>
                                        <th>ส่วนลด (THB)</th>
                                        <th>ใช้ได้สูงสุด</th>
                                        <th>ใช้งานแล้ว</th>
                                        <th>เอเจนต์</th>
                                        <th>สถานะ</th>
                                        <th width="150">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($codes as $code)
                                        <tr>
                                            <td>{{ $code->code }}</td>
                                            <td>{{ number_format($code->amount, 2) }}</td>
                                            <td>{{ $code->max_uses }}</td>
                                            <td>{{ $code->used_count }}</td>
                                            <td>{{ $code->agent?->name ?? '-' }}</td>
                                            <td>
                                                @if ($code->is_active)
                                                    <span class="badge bg-success">เปิด</span>
                                                @else
                                                    <span class="badge bg-danger">ปิด</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.discount-codes.edit', $code->id) }}" class="btn btn-sm btn-warning">แก้ไข</a>
                                                    <button form="delete-{{ $code->id }}" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันลบ?')">ลบ</button>
                                                </div>
                                                <form id="delete-{{ $code->id }}" action="{{ route('admin.discount-codes.destroy', $code->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">ยังไม่มีโค้ดส่วนลด</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
