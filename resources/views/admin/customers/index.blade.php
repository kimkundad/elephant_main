@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">รายชื่อลูกค้าทั้งหมด</h1>
                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('admin.customers.create') }}" class="btn btn-primary">
                            + เพิ่มลูกค้า
                        </a>
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
                                <th>ชื่อ</th>
                                <th>Email</th>
                                <th>เบอร์โทร</th>
                                <th>สัญชาติ</th>
                                <th>สร้างโดย</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($customers as $c)
                                <tr>
                                    <td>{{ $c->full_name }}</td>
                                    <td>{{ $c->email }}</td>
                                    <td>{{ $c->phone }}</td>
                                    <td>{{ $c->nationality ?? '-' }}</td>
                                    <td>{{ $c->creator?->name }}</td>
                                    <td>
                                        <a href="{{ route('admin.customers.edit', $c->id) }}" class="btn btn-warning btn-sm">แก้ไข</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    {{ $customers->links() }}

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
