@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">{{ $contact->subject ?: 'Contact Message' }}</h1>
        </div>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-light">ย้อนกลับ</a>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-6">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title fw-bold">ข้อความ</h3>
                <div class="card-toolbar">
                  @if($contact->handled_at)
                    <span class="badge badge-light-success">ตอบแล้ว</span>
                  @else
                    <span class="badge badge-light-danger">ยังไม่ตอบ</span>
                  @endif
                </div>
              </div>
              <div class="card-body">
                <p style="white-space:pre-line; margin:0;">{{ $contact->message }}</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">ผู้ติดต่อ</h3>
              </div>
              <div class="card-body">
                <table class="table table-row-dashed align-middle mb-0 fs-7">
                  <tr>
                    <td class="text-muted">ชื่อ</td>
                    <td class="fw-bold">{{ $contact->name }}</td>
                  </tr>
                  <tr>
                    <td class="text-muted">อีเมล</td>
                    <td class="fw-bold"><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                  </tr>
                  <tr>
                    <td class="text-muted">เบอร์โทร</td>
                    <td class="fw-bold">
                      @if($contact->phone)
                        <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                        @if($contact->phone_country)
                          <span class="text-muted fw-normal">({{ $contact->phone_country }})</span>
                        @endif
                      @else
                        -
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">ส่งเมื่อ</td>
                    <td class="fw-bold">{{ optional($contact->submitted_at ?? $contact->created_at)->format('d M Y H:i') }}</td>
                  </tr>
                  @if($contact->handled_at)
                    <tr>
                      <td class="text-muted">ตอบโดย</td>
                      <td class="fw-bold">
                        {{ $contact->handled_by ?: '-' }}
                        <div class="text-muted fw-normal fs-8">{{ $contact->handled_at->format('d M Y H:i') }}</div>
                      </td>
                    </tr>
                  @endif
                </table>
              </div>
            </div>

            <div class="card">
              <div class="card-body d-flex flex-column gap-3">
                <a href="mailto:{{ $contact->email }}?subject={{ rawurlencode('Re: ' . ($contact->subject ?: 'Your message')) }}"
                   class="btn btn-primary">ตอบกลับทางอีเมล</a>

                <form method="POST" action="{{ route('admin.contacts.toggle', $contact) }}">
                  @csrf
                  <button class="btn btn-light-success w-100">
                    {{ $contact->handled_at ? 'ย้ายกลับไปยังไม่ตอบ' : 'ทำเครื่องหมายว่าตอบแล้ว' }}
                  </button>
                </form>

                <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}"
                      onsubmit="return confirm('ลบข้อความนี้?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-light-danger w-100">ลบข้อความ</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
