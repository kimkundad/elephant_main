@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div class="app-toolbar py-3 py-lg-6">
      <div class="app-container container-xxl d-flex flex-stack">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">เพิ่มจังหวัด</h1>
      </div>
    </div>

    <div class="app-content flex-column-fluid">
      <div class="app-container container-xxl">
        <div class="card">
          <div class="card-body">
            <form action="{{ route('admin.provinces.store') }}" method="POST">
              @csrf
              @include('admin.provinces.partials.form', ['province' => null])
              <div class="text-end mt-6">
                <a href="{{ route('admin.provinces.index') }}" class="btn btn-light me-2">ยกเลิก</a>
                <button type="submit" class="btn btn-primary">บันทึก</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
