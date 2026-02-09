@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">ข้อมูลช้าง</h1>
                    </div>

                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('admin.elephants.create') }}" class="btn btn-primary">+ เพิ่มข้อมูลช้าง</a>
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
                                        <th>ชื่อ</th>
                                        <th>วันที่ช่วยเหลือ</th>
                                        <th>ลำดับ</th>
                                        <th>แสดงผล</th>
                                        <th width="150">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($elephants as $elephant)
                                        <tr>
                                            <td>{{ $elephant->name }}</td>
                                            <td>{{ $elephant->rescued_at ? $elephant->rescued_at->format('d/m/Y') : '-' }}</td>
                                            <td>{{ $elephant->sort_order }}</td>
                                            <td>
                                                <a href="{{ route('admin.elephants.toggle', $elephant->id) }}">
                                                    @if ($elephant->is_active)
                                                        <span class="badge bg-success">เปิด</span>
                                                    @else
                                                        <span class="badge bg-danger">ปิด</span>
                                                    @endif
                                                </a>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.elephants.edit', $elephant->id) }}"
                                                       class="btn btn-sm btn-warning">
                                                        แก้ไข
                                                    </a>
                                                    <button form="delete-{{ $elephant->id }}"
                                                            class="btn btn-sm btn-danger"
                                                            onclick="return confirm('ยืนยันลบ?')">
                                                        ลบ
                                                    </button>
                                                </div>

                                                <form id="delete-{{ $elephant->id }}"
                                                      action="{{ route('admin.elephants.destroy', $elephant->id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">ยังไม่มีข้อมูลช้าง</td>
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
