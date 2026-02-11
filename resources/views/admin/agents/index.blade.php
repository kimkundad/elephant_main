@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">พนักงานขาย (Agents)</h1>
                    </div>
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('admin.agents.create') }}" class="btn btn-primary">+ เพิ่มพนักงานขาย</a>
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
                                        <th>อีเมล</th>
                                        <th>เบอร์โทร</th>
                                        <th>ยอดขายรวม</th>
                                        <th>จำนวนจอง</th>
                                        <th>สถานะ</th>
                                        <th width="150">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($agents as $agent)
                                        <tr>
                                            <td>{{ $agent->name }}</td>
                                            <td>{{ $agent->email ?? '-' }}</td>
                                            <td>{{ $agent->phone ?? '-' }}</td>
                                            <td>{{ number_format($agent->sales_total ?? 0, 2) }}</td>
                                            <td>{{ $agent->bookings_count ?? 0 }}</td>
                                            <td>
                                                @if ($agent->is_active)
                                                    <span class="badge bg-success">เปิด</span>
                                                @else
                                                    <span class="badge bg-danger">ปิด</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-sm btn-warning">แก้ไข</a>
                                                    <button form="delete-{{ $agent->id }}" class="btn btn-sm btn-danger" onclick="return confirm('ยืนยันลบ?')">ลบ</button>
                                                </div>
                                                <form id="delete-{{ $agent->id }}" action="{{ route('admin.agents.destroy', $agent->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">ยังไม่มีข้อมูลพนักงานขาย</td>
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
