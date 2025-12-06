@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">รายการโปรแกรมทัวร์</h1>
                    </div>

                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        {{-- เพิ่มปุ่มหรือฟิลเตอร์อื่นๆ ได้ที่นี่ --}}
                         <a href="{{ route('admin.tours.create') }}" class="btn btn-primary">+ สร้างโปรแกรม</a>
                    </div>
                </div>
            </div>

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="card mb-7">
                        <div class="card-body">


                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ชื่อโปรแกรม</th>
                                        <th>ช่วงราคา</th>
                                        <th>แสดงผล</th>
                                        <th width="150">จัดการ</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($tours as $tour)
                                        <tr>
                                            <td>{{ $tour->name }}</td>
                                            <td>{{ number_format($tour->min_price) }} -
                                                {{ number_format($tour->max_price) }}</td>

                                            <td>
                                                <a href="{{ route('admin.tours.toggle', $tour->id) }}">
                                                    @if ($tour->is_active)
                                                        <span class="badge bg-success">เปิด</span>
                                                    @else
                                                        <span class="badge bg-danger">ปิด</span>
                                                    @endif
                                                </a>
                                            </td>

                                            <td>
    <div class="btn-group">

        <a href="{{ route('admin.tours.sessions.index', $tour->id) }}"
           class="btn btn-sm btn-primary">
            Sessions
        </a>

        <a href="{{ route('admin.tours.edit', $tour->id) }}"
           class="btn btn-sm btn-warning">
           แก้ไข
        </a>

        <button form="delete-{{ $tour->id }}"
                class="btn btn-sm btn-danger"
                onclick="return confirm('ยืนยันลบ?')">
            ลบ
        </button>
    </div>

    <form id="delete-{{ $tour->id }}"
          action="{{ route('admin.tours.destroy', $tour->id) }}"
          method="POST">
        @csrf
        @method('DELETE')
    </form>
</td>

                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
