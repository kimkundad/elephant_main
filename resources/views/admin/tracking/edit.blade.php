@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">Tracking &amp; Tags</h1>
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

        <form method="POST" action="{{ route('admin.tracking.update') }}">
          @csrf

          <div class="card mb-6">
            <div class="card-header">
              <h3 class="card-title">Google</h3>
            </div>
            <div class="card-body">
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">GA4 Measurement ID</label>
                  <input type="text" name="ga4_measurement_id" class="form-control" placeholder="G-XXXXXXXXXX"
                         value="{{ old('ga4_measurement_id', $tracking->ga4_measurement_id) }}">
                  <div class="form-text">Google Analytics 4 — หาได้ที่ Admin → Data streams → Measurement ID</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Google Tag Manager Container ID</label>
                  <input type="text" name="gtm_container_id" class="form-control" placeholder="GTM-XXXXXXX"
                         value="{{ old('gtm_container_id', $tracking->gtm_container_id) }}">
                  <div class="form-text">ใส่เฉพาะกรณีใช้ GTM จัดการแท็กเอง (ปล่อยว่างได้)</div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label class="form-label">Google Ads Conversion ID</label>
                  <input type="text" name="google_ads_conversion_id" class="form-control" placeholder="AW-123456789"
                         value="{{ old('google_ads_conversion_id', $tracking->google_ads_conversion_id) }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Google Ads Purchase Label</label>
                  <input type="text" name="google_ads_purchase_label" class="form-control" placeholder="AbC-D_efGhIjK"
                         value="{{ old('google_ads_purchase_label', $tracking->google_ads_purchase_label) }}">
                  <div class="form-text">ส่วนหลัง “/” ของ conversion label ต้องใส่คู่กับ Conversion ID</div>
                </div>
              </div>
            </div>
          </div>

          <div class="card mb-6">
            <div class="card-header">
              <h3 class="card-title">Meta (Facebook / Instagram) &amp; TikTok</h3>
            </div>
            <div class="card-body">
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Meta Pixel ID</label>
                  <input type="text" name="meta_pixel_id" class="form-control" placeholder="1234567890123456"
                         value="{{ old('meta_pixel_id', $tracking->meta_pixel_id) }}">
                  <div class="form-text">Events Manager → Data sources → Pixel ID (ตัวเลข 15–16 หลัก)</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Facebook Domain Verification</label>
                  <input type="text" name="facebook_domain_verification" class="form-control"
                         value="{{ old('facebook_domain_verification', $tracking->facebook_domain_verification) }}">
                  <div class="form-text">ค่าในแท็ก &lt;meta name="facebook-domain-verification"&gt; ใส่เฉพาะค่า ไม่ต้องใส่แท็ก</div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <label class="form-label">TikTok Pixel ID</label>
                  <input type="text" name="tiktok_pixel_id" class="form-control"
                         value="{{ old('tiktok_pixel_id', $tracking->tiktok_pixel_id) }}">
                </div>
              </div>
            </div>
          </div>

          <div class="card mb-6">
            <div class="card-header">
              <h3 class="card-title">การจองสำเร็จ (Conversion)</h3>
            </div>
            <div class="card-body">
              <label class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" name="track_purchase" value="1"
                       @checked(old('track_purchase', $tracking->track_purchase ?? true))>
                <span class="form-check-label fw-semibold">ส่งข้อมูลการจองสำเร็จไปยังแท็กด้านบน</span>
              </label>
              <div class="form-text mt-3">
                เมื่อลูกค้าชำระเงินสำเร็จและมาถึงหน้ายืนยันการจอง ระบบจะส่ง
                <code>purchase</code> (GA4 / Google Ads), <code>Purchase</code> (Meta Pixel) และ
                <code>CompletePayment</code> (TikTok) พร้อมยอดเงิน สกุลเงิน THB ชื่อทัวร์
                และเลขที่การจองเป็น transaction id ซึ่งใช้กันนับซ้ำเวลาลูกค้ารีเฟรชหน้า
              </div>
            </div>
          </div>

          <div class="card mb-6">
            <div class="card-header">
              <h3 class="card-title">สคริปต์เพิ่มเติม</h3>
            </div>
            <div class="card-body">
              @if($canEditSnippets)
                <div class="alert alert-warning">
                  โค้ดในช่องนี้ถูกใส่ลงทุกหน้าของเว็บแบบดิบ ๆ วางเฉพาะโค้ดจากผู้ให้บริการที่เชื่อถือได้เท่านั้น
                </div>
                <div class="mb-6">
                  <label class="form-label">โค้ดใน &lt;head&gt;</label>
                  <textarea name="head_html" class="form-control font-monospace" rows="6"
                            placeholder="<!-- เช่น Hotjar, Clarity, LINE Tag -->">{{ old('head_html', $tracking->head_html) }}</textarea>
                </div>
                <div>
                  <label class="form-label">โค้ดก่อนปิด &lt;/body&gt;</label>
                  <textarea name="body_html" class="form-control font-monospace" rows="6">{{ old('body_html', $tracking->body_html) }}</textarea>
                </div>
              @else
                <div class="alert alert-light-primary mb-0">
                  เฉพาะ SuperAdmin เท่านั้นที่แก้สคริปต์เพิ่มเติมได้
                  @if($tracking->head_html || $tracking->body_html)
                    <div class="mt-2 text-muted">ตอนนี้มีสคริปต์ที่ตั้งไว้แล้ว</div>
                  @endif
                </div>
              @endif
            </div>
          </div>

          <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">บันทึก</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
