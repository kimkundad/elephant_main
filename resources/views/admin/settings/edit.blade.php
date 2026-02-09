@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">ตั้งค่าเว็บไซต์</h1>
        </div>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="card">
          <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}">
              @csrf

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">ข้อความแนะนำใน Footer</label>
                  <textarea class="form-control" name="footer_about" rows="4">{{ old('footer_about', $setting->footer_about) }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">ที่อยู่ (แสดงใน Footer/Contact)</label>
                  <textarea class="form-control" name="address" rows="4">{{ old('address', $setting->address) }}</textarea>
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-4">
                  <label class="form-label">เบอร์โทรศัพท์หลัก</label>
                  <input class="form-control" name="phone" value="{{ old('phone', $setting->phone) }}">
                </div>
                <div class="col-md-4">
                  <label class="form-label">เบอร์โทรศัพท์สำรอง</label>
                  <input class="form-control" name="phone_secondary" value="{{ old('phone_secondary', $setting->phone_secondary) }}">
                </div>
                <div class="col-md-4">
                  <label class="form-label">อีเมลติดต่อ</label>
                  <input class="form-control" type="email" name="email" value="{{ old('email', $setting->email ?? 'infosmallelephants@gmail.com') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">เวลาเปิดทำการ (Footer)</label>
                  <textarea class="form-control" name="hours" rows="3">{{ old('hours', $setting->hours) }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Office hours (หน้า Contact)</label>
                  <input class="form-control" name="contact_office_hours" value="{{ old('contact_office_hours', $setting->contact_office_hours) }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-4">
                  <label class="form-label">Facebook URL</label>
                  <input class="form-control" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}">
                </div>
                <div class="col-md-4">
                  <label class="form-label">Instagram URL</label>
                  <input class="form-control" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}">
                </div>
                <div class="col-md-4">
                  <label class="form-label">WhatsApp / Line</label>
                  <input class="form-control" name="contact_whatsapp_line" value="{{ old('contact_whatsapp_line', $setting->contact_whatsapp_line) }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-8">
                  <label class="form-label">Google Map Embed URL</label>
                  <input class="form-control" name="map_embed_url" value="{{ old('map_embed_url', $setting->map_embed_url) }}">
                  <div class="form-text">ตัวอย่าง: https://www.google.com/maps?q=...&output=embed</div>
                </div>
                <div class="col-md-4">
                  <label class="form-label">ลิขสิทธิ์ (Copyright)</label>
                  <input class="form-control" name="copyright_text" value="{{ old('copyright_text', $setting->copyright_text) }}">
                </div>
              </div>

              <div class="text-end">
                <button class="btn btn-primary" type="submit">บันทึก</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
