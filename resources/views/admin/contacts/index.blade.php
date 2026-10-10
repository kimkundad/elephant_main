@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">Contact Messages</h1>
        </div>
        <div class="d-flex align-items-center gap-3">
          @if($openCount)
            <span class="badge badge-light-danger fs-7">ยังไม่ตอบ {{ $openCount }}</span>
          @endif
        </div>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card mb-6">
          <div class="card-body">
            <form method="GET" class="d-flex flex-wrap align-items-center gap-3">
              <div class="btn-group">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm {{ $filter ? 'btn-light' : 'btn-primary' }}">ทั้งหมด</a>
                <a href="{{ route('admin.contacts.index', ['status' => 'open']) }}" class="btn btn-sm {{ $filter === 'open' ? 'btn-primary' : 'btn-light' }}">ยังไม่ตอบ</a>
                <a href="{{ route('admin.contacts.index', ['status' => 'handled']) }}" class="btn btn-sm {{ $filter === 'handled' ? 'btn-primary' : 'btn-light' }}">ตอบแล้ว</a>
              </div>

              @if($filter)
                <input type="hidden" name="status" value="{{ $filter }}">
              @endif

              <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm w-auto"
                     placeholder="ค้นหาชื่อ อีเมล เบอร์ หรือข้อความ">
              <button class="btn btn-sm btn-light-primary">ค้นหา</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
              <thead>
                <tr class="fw-bold text-muted">
                  <th>ส่งเมื่อ</th>
                  <th>ผู้ติดต่อ</th>
                  <th>เรื่อง</th>
                  <th>ข้อความ</th>
                  <th>สถานะ</th>
                  <th class="text-end">จัดการ</th>
                </tr>
              </thead>
              <tbody>
                @forelse($contacts as $contact)
                  <tr class="{{ $contact->handled_at ? '' : 'fw-semibold' }}">
                    <td class="text-nowrap">
                      {{ optional($contact->submitted_at ?? $contact->created_at)->format('d M Y') }}
                      <div class="text-muted fs-8">{{ optional($contact->submitted_at ?? $contact->created_at)->format('H:i') }}</div>
                    </td>
                    <td>
                      <div>{{ $contact->name }}</div>
                      <div class="text-muted fs-8">
                        <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                        @if($contact->phone)
                          &middot; <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                        @endif
                      </div>
                    </td>
                    <td>{{ $contact->subject ?: '-' }}</td>
                    <td class="text-muted">{{ \Illuminate\Support\Str::limit($contact->message, 80) }}</td>
                    <td>
                      @if($contact->handled_at)
                        <span class="badge badge-light-success">ตอบแล้ว</span>
                        <div class="text-muted fs-8">{{ $contact->handled_by }}</div>
                      @else
                        <span class="badge badge-light-danger">ยังไม่ตอบ</span>
                      @endif
                    </td>
                    <td class="text-end text-nowrap">
                      <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-light-primary">อ่าน</a>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-6">ยังไม่มีข้อความติดต่อ</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

            <div class="mt-4">{{ $contacts->links() }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
