@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">จัดการข้อความหน้า Home (2 ภาษา)</h1>
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
            <form method="POST" action="{{ route('admin.site-texts.home.update') }}">
              @csrf

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Slide Title (TH)</label>
                  <input class="form-control" name="slide_title_th"
                         value="{{ old('slide_title_th', $th['home.hero.slide_title'] ?? 'Small Elephants') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Slide Title (EN)</label>
                  <input class="form-control" name="slide_title_en"
                         value="{{ old('slide_title_en', $en['home.hero.slide_title'] ?? 'Small Elephants') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Slide Subtitle (TH)</label>
                  <textarea class="form-control" name="slide_subtitle_th" rows="4">{{ old('slide_subtitle_th', $th['home.hero.slide_subtitle'] ?? 'บ้านที่ปลอดภัยสำหรับช้างที่ได้รับการช่วยเหลือ — และประสบการณ์ที่ทำให้คุณรักช้างมากขึ้นในทุกก้าวที่เดิน') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Slide Subtitle (EN)</label>
                  <textarea class="form-control" name="slide_subtitle_en" rows="4">{{ old('slide_subtitle_en', $en['home.hero.slide_subtitle'] ?? 'A safe home for rescued elephants — and an experience that makes you love elephants more with every step.') }}</textarea>
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-12">
                  <h4 class="mb-4">Welcome Section</h4>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Welcome Kicker (TH)</label>
                  <input class="form-control" name="welcome_kicker_th"
                         value="{{ old('welcome_kicker_th', $th['home.welcome.kicker'] ?? 'ยินดีต้อนรับ') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Welcome Kicker (EN)</label>
                  <input class="form-control" name="welcome_kicker_en"
                         value="{{ old('welcome_kicker_en', $en['home.welcome.kicker'] ?? 'Welcome') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Welcome Title (TH)</label>
                  <input class="form-control" name="welcome_title_th"
                         value="{{ old('welcome_title_th', $th['home.welcome.title'] ?? 'ช้างในภูเก็ต') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Welcome Title (EN)</label>
                  <input class="form-control" name="welcome_title_en"
                         value="{{ old('welcome_title_en', $en['home.welcome.title'] ?? 'Elephants in Phuket') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Welcome Body (TH)</label>
                  <textarea class="form-control" name="welcome_body_th" rows="5">{{ old('welcome_body_th', $th['home.welcome.body'] ?? 'สัมผัสประสบการณ์ที่น่าจดจำกับช้างอย่างใกล้ชิด เรียนรู้วิถีชีวิตและการดูแลอย่างมีจริยธรรม') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Welcome Body (EN)</label>
                  <textarea class="form-control" name="welcome_body_en" rows="5">{{ old('welcome_body_en', $en['home.welcome.body'] ?? 'Experience an unforgettable moment with elephants, learning their lives and ethical care up close.') }}</textarea>
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-12">
                  <h4 class="mb-4">Section Titles</h4>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Experience Title (TH)</label>
                  <input class="form-control" name="experience_title_th"
                         value="{{ old('experience_title_th', $th['home.experience.title'] ?? 'ประสบการณ์') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Experience Title (EN)</label>
                  <input class="form-control" name="experience_title_en"
                         value="{{ old('experience_title_en', $en['home.experience.title'] ?? 'Experience') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Meet Our Elephants Title (TH)</label>
                  <input class="form-control" name="meet_title_th"
                         value="{{ old('meet_title_th', $th['home.meet.title'] ?? 'พบกับช้างของเรา') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Meet Our Elephants Title (EN)</label>
                  <input class="form-control" name="meet_title_en"
                         value="{{ old('meet_title_en', $en['home.meet.title'] ?? 'Meet Our Elephants') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Reviews Title (TH)</label>
                  <input class="form-control" name="reviews_title_th"
                         value="{{ old('reviews_title_th', $th['home.reviews.title'] ?? 'เสียงจากผู้เข้าชม') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Reviews Title (EN)</label>
                  <input class="form-control" name="reviews_title_en"
                         value="{{ old('reviews_title_en', $en['home.reviews.title'] ?? 'What Our Customers Say') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-12">
                  <h4 class="mb-4">Buttons</h4>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Book Now (TH)</label>
                  <input class="form-control" name="book_now_th"
                         value="{{ old('book_now_th', $th['home.actions.book_now'] ?? 'จองตอนนี้') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Book Now (EN)</label>
                  <input class="form-control" name="book_now_en"
                         value="{{ old('book_now_en', $en['home.actions.book_now'] ?? 'Book Now') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Read More (TH)</label>
                  <input class="form-control" name="read_more_th"
                         value="{{ old('read_more_th', $th['home.actions.read_more'] ?? 'อ่านต่อ') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Read More (EN)</label>
                  <input class="form-control" name="read_more_en"
                         value="{{ old('read_more_en', $en['home.actions.read_more'] ?? 'Read more') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-12">
                  <h4 class="mb-4">Contact Page (V2)</h4>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Contact Title (TH)</label>
                  <input class="form-control" name="contact_title_th"
                         value="{{ old('contact_title_th', $th['home.contact.title'] ?? 'ติดต่อเรา') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Contact Title (EN)</label>
                  <input class="form-control" name="contact_title_en"
                         value="{{ old('contact_title_en', $en['home.contact.title'] ?? 'Contact Us') }}">
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Contact Lead (TH)</label>
                  <textarea class="form-control" name="contact_lead_th" rows="4">{{ old('contact_lead_th', $th['home.contact.lead'] ?? 'กรุณากรอกแบบฟอร์มเพื่อส่งข้อความถึงเรา ทีมงานจะติดต่อกลับโดยเร็วที่สุด') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Contact Lead (EN)</label>
                  <textarea class="form-control" name="contact_lead_en" rows="4">{{ old('contact_lead_en', $en['home.contact.lead'] ?? 'Please fill out the form to get in contact with us. As soon as possible, our staff will contact you back.') }}</textarea>
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



