@extends('frontend.layouts.app')

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">SMALL ELEPHANTS</div>
    <h1 class="about-hero__title">What to Bring</h1>
    <p class="about-hero__lead">
      เตรียมตัวให้พร้อมเพื่อวันแห่งความทรงจำ — สบายตัว ปลอดภัย และเคารพธรรมชาติ
    </p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">Explore Tours</a>
      <a href="#packing-list" class="btn-outline-soft">Checklist</a>
    </div>
  </div>
</section>

{{-- PACKING LIST --}}
<section id="packing-list" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-6">
        <div class="about-eyebrow">CHECKLIST</div>
        <h2 class="about-title">สิ่งที่ควรนำมา</h2>
        <p class="about-text">
          ทัวร์ของเราใช้เวลาอยู่กลางธรรมชาติหลายชั่วโมง แนะนำให้เตรียมของต่อไปนี้เพื่อความสะดวก
          และช่วยให้คุณสนุกกับการเรียนรู้และสังเกตช้างอย่างมีความสุข
        </p>

        <ul class="about-list about-list--dark">
          <li><span>✓</span> เสื้อผ้าที่ระบายอากาศดี และรองเท้าที่เดินสบาย</li>
          <li><span>✓</span> หมวก/แว่นกันแดด/ครีมกันแดด</li>
          <li><span>✓</span> ขวดน้ำส่วนตัว (เราเตรียมน้ำเติมให้)</li>
          <li><span>✓</span> ยากันยุง</li>
          <li><span>✓</span> กล้องหรือมือถือสำหรับภาพความทรงจำ</li>
        </ul>
      </div>

      <div class="col-md-6">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">ควรหลีกเลี่ยง</div>
          <div class="about-cta__text">
            เพื่อความปลอดภัยของคุณและช้าง เราขอให้หลีกเลี่ยงของใช้หรือพฤติกรรมต่อไปนี้:
          </div>
          <ul class="about-list" style="margin-top:16px;">
            <li><span>•</span> น้ำหอม/สเปรย์กลิ่นแรง</li>
            <li><span>•</span> การให้อาหารเองโดยไม่ได้รับอนุญาต</li>
            <li><span>•</span> การเข้าใกล้ช้างโดยไม่มีไกด์</li>
            <li><span>•</span> โดรน (รบกวนสัตว์และผู้เข้าชม)</li>
          </ul>
          <a href="{{ route('frontend.contact') }}" class="btn-primary-soft btn-primary-soft--full" style="margin-top:18px;">Ask Us Before You Go</a>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- WEATHER --}}
<section class="about-section about-section--soft">
  <div class="container">
    <div class="row align-items-center about-gap">
      <div class="col-md-7">
        <div class="about-eyebrow">WEATHER</div>
        <h2 class="about-title">ฝนแดดในเชียงใหม่</h2>
        <p class="about-text">
          อากาศเปลี่ยนได้ระหว่างวัน แนะนำให้พกเสื้อคลุมบาง ๆ หรือเสื้อกันฝนพับได้
          โดยเฉพาะช่วงฤดูฝน (ประมาณ พ.ค.–ต.ค.)
        </p>
      </div>
      <div class="col-md-5">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">Tip เล็ก ๆ</div>
          <div class="about-cta__text">
            ถ้าเดินเยอะ แนะนำรองเท้าที่มีดอกยางกันลื่น และพกถุงกันน้ำสำหรับมือถือ
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

