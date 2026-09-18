@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div class="app-toolbar py-3 py-lg-6">
      <div class="app-container container-xxl d-flex flex-stack">
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">จังหวัด</h1>
        <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary">+ เพิ่มจังหวัด</a>
      </div>
    </div>

    <div class="app-content flex-column-fluid">
      <div class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="card">
          <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
              <thead>
                <tr class="fw-bold text-muted">
                  <th>ชื่อ (TH)</th>
                  <th>ชื่อ (EN)</th>
                  <th>Slug</th>
                  <th>ทัวร์</th>
                  <th>จุดรับส่ง</th>
                  <th>สถานะ</th>
                  <th class="text-end">จัดการ</th>
                </tr>
              </thead>
              <tbody>
                @forelse($provinces as $province)
                  <tr>
                    <td>{{ $province->name_th }}</td>
                    <td>{{ $province->name_en }}</td>
                    <td><code>{{ $province->slug }}</code></td>
                    <td><span class="badge badge-light-primary">{{ $province->tours_count }}</span></td>
                    <td><span class="badge badge-light-primary">{{ $province->pickup_locations_count }}</span></td>
                    <td>
                      @if($province->is_active)
                        <span class="badge badge-light-success">เปิด</span>
                      @else
                        <span class="badge badge-light-secondary">ปิด</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <a href="{{ route('admin.provinces.edit', $province) }}" class="btn btn-sm btn-light-primary">แก้ไข</a>
                      <form action="{{ route('admin.provinces.destroy', $province) }}" method="POST" class="d-inline" onsubmit="return confirm('ลบจังหวัดนี้?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light-danger">ลบ</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="7" class="text-center text-muted">ยังไม่มีจังหวัด</td></tr>
                @endforelse
              </tbody>
            </table>

            <div class="mt-4">{{ $provinces->links('pagination::bootstrap-5') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
