@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid">

    <div class="d-flex flex-column flex-column-fluid">

        <div class="app-toolbar py-3 py-lg-6">
            <div class="app-container container-xxl d-flex flex-stack">
                <h1 class="fs-3 fw-bold">รายการจุดรับส่ง</h1>
                <a href="{{ route('admin.pickup-locations.create') }}" class="btn btn-primary">
                    + เพิ่มจุดรับส่ง
                </a>
            </div>
        </div>

        <div class="app-container container-xxl">
            <div class="card">
                <div class="card-body">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ชื่อโรงแรม / จุดรับ</th>
                                <th>ประเภท</th>
                                <th>สถานะ</th>
                                <th>แผนที่</th>
                                <th width="150">จัดการ</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($locations as $loc)
                                <tr>
                                    <td>{{ $loc->name }}</td>
                                    <td>
                                        @if($loc->is_meeting_point)
                                            <span class="badge bg-info">Meeting Point</span>
                                        @else
                                            <span class="badge bg-secondary">Hotel Pickup</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($loc->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($loc->latitude)
                                            <a target="_blank"
                                               href="https://www.google.com/maps?q={{ $loc->latitude }},{{ $loc->longitude }}">
                                               ดูแผนที่
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        <a href="{{ route('admin.pickup-locations.edit', $loc->id) }}"
                                           class="btn btn-warning btn-sm">
                                            แก้ไข
                                        </a>

                                        <form action="{{ route('admin.pickup-locations.destroy', $loc->id) }}"
                                              method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-sm"
                                                    onclick="return confirm('ลบรายการนี้?')">
                                                ลบ
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $locations->links() }}

                </div>
            </div>
        </div>

    </div>

</div>
@endsection
