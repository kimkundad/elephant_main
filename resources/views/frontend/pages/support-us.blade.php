@extends('frontend.layouts.app')

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">SMALL ELEPHANTS</div>
    <h1 class="about-hero__title">How to Support Us</h1>
    <p class="about-hero__lead">
      ทุกการสนับสนุนช่วยให้ช้างได้มีบ้านที่ปลอดภัย อาหารที่ดี และการดูแลระยะยาว
    </p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">Book a Tour</a>
      <a href="#ways" class="btn-outline-soft">Ways to Help</a>
    </div>
  </div>
</section>

{{-- WAYS --}}
<section id="ways" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-7">
        <div class="about-eyebrow">WHY IT MATTERS</div>
        <h2 class="about-title">ทุกการสนับสนุนคือ “คุณภาพชีวิต” ของช้าง</h2>
        <p class="about-text">
          การดูแลช้างต้องใช้ทั้งทรัพยากรและทีมงานที่เข้าใจสวัสดิภาพสัตว์
          รายได้จากผู้เข้าชมช่วยสนับสนุนอาหารที่เหมาะสม การดูแลสุขภาพ
          พื้นที่ใช้ชีวิต และทีมงานที่คอยดูแลอย่างต่อเนื่อง
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> อาหารที่มีคุณค่าทางโภชนาการสำหรับช้าง</li>
          <li><span>✓</span> เวชภัณฑ์และการดูแลสุขภาพที่จำเป็น</li>
          <li><span>✓</span> การดูแลพื้นที่ป่าและแหล่งน้ำให้ปลอดภัย</li>
          <li><span>✓</span> ทีมงานภาคสนามและผู้ดูแลช้างที่มีประสบการณ์</li>
        </ul>
      </div>
      <div class="col-md-5">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">Your Visit = Direct Support</div>
          <div class="about-cta__text">
            การมาเยี่ยมชมแบบมีจริยธรรมเป็นการสนับสนุนโดยตรง
            เพราะทุกการจองช่วยให้ช้างมีชีวิตที่ดีขึ้นในระยะยาว
          </div>
          <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft btn-primary-soft--full">Book a Tour</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- SUPPORT CTA --}}
<section class="about-section about-section--dark" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-dark__overlay"></div>
  <div class="container">
    <div class="row align-items-center about-gap">
      <div class="col-md-7">
        <div class="about-eyebrow about-eyebrow--light">SUPPORT INITIATIVES</div>
        <h2 class="about-title about-title--light">เลือกสนับสนุนในรูปแบบที่เหมาะกับคุณ</h2>
        <p class="about-sub about-sub--light">
          หากคุณยังไม่สามารถมาเยี่ยมชมได้ คุณยังสามารถช่วยสนับสนุนผ่านกิจกรรมอื่น ๆ
          ไม่ว่าจะเป็นการสนับสนุนด้านอาหาร อุปกรณ์การแพทย์ หรือการดูแลพื้นที่ใช้ชีวิต
        </p>
        <ul class="about-list">
          <li><span>✓</span> สนับสนุนอาหารและโภชนาการประจำวัน</li>
          <li><span>✓</span> สนับสนุนการดูแลสุขภาพและเวชภัณฑ์</li>
          <li><span>✓</span> สนับสนุนการดูแลพื้นที่ป่าและแหล่งน้ำ</li>
          <li><span>✓</span> สนับสนุนค่าใช้จ่ายด้านการดูแลทั่วไป</li>
        </ul>
      </div>
      <div class="col-md-5">
        <div class="about-cta">
          <div class="about-cta__title">Explore Initiatives</div>
          <div class="about-cta__text">
            เรามีรูปแบบการสนับสนุนที่ช่วยให้คุณมีส่วนร่วมได้อย่างตรงจุด
            หากต้องการรายละเอียดเพิ่มเติม สามารถติดต่อทีมงานได้ทันที
          </div>
          <a href="{{ route('frontend.contact') }}" class="btn-primary-soft btn-primary-soft--full">Contact Us</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- FILMS --}}
<section class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow">STORIES</div>
        <h2 class="about-title">สนับสนุนผ่านเรื่องราวของช้าง</h2>
        <p class="about-text">
          เราผลิตสื่อและเรื่องเล่าที่ช่วยให้ผู้ชมเข้าใจชีวิตของช้างอย่างลึกซึ้ง
          รายได้จากสื่อเหล่านี้ถูกนำไปช่วยดูแลช้างอย่างต่อเนื่อง
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> เรื่องเล่าการช่วยเหลือและการฟื้นฟู</li>
          <li><span>✓</span> เบื้องหลังการดูแลช้างในชีวิตประจำวัน</li>
          <li><span>✓</span> ความรู้เกี่ยวกับช้างเอเชียและสวัสดิภาพสัตว์</li>
        </ul>
      </div>
      <div class="col-md-6">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">Learn, Share, Support</div>
          <div class="about-cta__text">
            การรับชมและแบ่งปันเรื่องราว คืออีกหนึ่งวิธีที่ช่วยขยายพลังการสนับสนุนได้
          </div>
          <a href="{{ route('frontend.contact') }}" class="btn-primary-soft btn-primary-soft--full">Ask for Details</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- COMMUNITY --}}
<section class="about-section about-section--soft">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow">COMMUNITY</div>
        <h2 class="about-title">ช่วยกันบอกต่อสิ่งที่ถูกต้อง</h2>
        <p class="about-text">
          การแบ่งปันประสบการณ์และข้อมูลที่ถูกต้องช่วยให้ผู้คนเลือกการท่องเที่ยวที่มีจริยธรรม
          และช่วยลดการท่องเที่ยวที่ทำร้ายสัตว์
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> แชร์ประสบการณ์และความรู้บนโซเชียลมีเดีย</li>
          <li><span>✓</span> แนะนำให้เพื่อน ๆ เลือกการท่องเที่ยวอย่างรับผิดชอบ</li>
          <li><span>✓</span> สนับสนุนธุรกิจท้องถิ่นที่คำนึงถึงสวัสดิภาพสัตว์</li>
        </ul>
      </div>
      <div class="col-md-6">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">Need a Custom Support Plan?</div>
          <div class="about-cta__text">
            หากเป็นองค์กรหรือกลุ่มที่ต้องการสนับสนุนในรูปแบบเฉพาะ
            เรายินดีออกแบบแนวทางร่วมกัน
          </div>
          <a href="{{ route('frontend.contact') }}" class="btn-primary-soft btn-primary-soft--full">Talk to Our Team</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

