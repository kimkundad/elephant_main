@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">แก้ไขข้อมูลลูกค้า</h1>
                    </div>



                </div>
            </div>

            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">
                    <div class="card mb-7">
                        <div class="card-body">

                    <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล</label>
                        <input type="text" name="full_name" class="form-control"
                               value="{{ $customer->full_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ $customer->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">เบอร์โทร</label>
                        <input type="text" name="phone" class="form-control"
                               value="{{ $customer->phone }}" required>
                    </div>


                    <button class="btn btn-primary">บันทึกข้อมูล</button>

                </form>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
