@extends('frontend.layouts.app')

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">SMALL ELEPHANTS</div>
    <h1 class="about-hero__title">Terms &amp; Conditions</h1>
    <p class="about-hero__lead">
      เงื่อนไขการจองเพื่อให้การเดินทางราบรื่นและปลอดภัยสำหรับทุกคน
    </p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.tours.index') }}" class="btn-primary-soft">View Tours</a>
      <a href="#terms" class="btn-outline-soft">Read Terms</a>
    </div>
  </div>
</section>

{{-- TERMS --}}
<section id="terms" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-8">
        <div class="about-eyebrow">BOOKING</div>
        <h2 class="about-title">เงื่อนไขการจอง</h2>
        <p class="about-text">
          โปรดอ่านเงื่อนไขต่อไปนี้ก่อนทำการจอง เพื่อให้การเดินทางเป็นไปอย่างราบรื่น
          เมื่อคุณทำการจอง ถือว่าคุณยอมรับข้อตกลงและเงื่อนไขดังต่อไปนี้
        </p>

        <ul class="about-list about-list--dark">
          <li><span>✓</span> การจองจะสมบูรณ์เมื่อชำระเงินสำเร็จและได้รับอีเมลยืนยัน</li>
          <li><span>✓</span> กรุณาตรวจสอบชื่อผู้เข้าร่วม, วันที่, รอบเวลา และจำนวนผู้เข้าร่วมให้ถูกต้อง</li>
          <li><span>✓</span> กรุณามาถึงจุดนัดหมายก่อนเวลาเริ่มทัวร์อย่างน้อย 15 นาที</li>
          <li><span>✓</span> การไม่เข้าร่วมตามเวลาที่กำหนดอาจถือเป็นการไม่มาใช้บริการ</li>
          <li><span>✓</span> หากมีการเปลี่ยนแปลงผู้ร่วมทัวร์ โปรดแจ้งล่วงหน้า</li>
          <li><span>✓</span> เราขอสงวนสิทธิ์ปรับเวลาหรือเปลี่ยนแผนเพื่อความปลอดภัย</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">CANCELLATION</div>
        <h2 class="about-title">การยกเลิกและการคืนเงิน</h2>
        <p class="about-text">
          นโยบายการยกเลิกช่วยให้เราบริหารจัดการดูแลช้างและทีมงานได้อย่างเหมาะสม
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> กรุณาติดต่อเราเพื่อสอบถามการยกเลิกหรือการเปลี่ยนวัน</li>
          <li><span>✓</span> การคืนเงินขึ้นอยู่กับช่วงเวลาที่แจ้งและเงื่อนไขการชำระเงิน</li>
          <li><span>✓</span> หากต้องการเลื่อนวัน โปรดแจ้งล่วงหน้าเพื่อเช็ครอบที่ว่าง</li>
          <li><span>✓</span> กรณีสภาพอากาศหรือเหตุสุดวิสัย เราจะแจ้งทางเลือกที่เหมาะสม</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">PAYMENT</div>
        <h2 class="about-title">การชำระเงิน</h2>
        <p class="about-text">
          การชำระเงินผ่านผู้ให้บริการที่ได้มาตรฐาน ระบบจะยืนยันสถานะการชำระเงินโดยอัตโนมัติ
          หากเกิดปัญหาในการชำระเงิน โปรดติดต่อทีมงานเพื่อช่วยตรวจสอบ
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> โปรดเก็บอีเมลยืนยันการชำระเงินไว้เป็นหลักฐาน</li>
          <li><span>✓</span> การชำระเงินไม่สำเร็จจะไม่ยืนยันการจอง</li>
          <li><span>✓</span> หากมีการคืนเงิน ระบบจะแจ้งรายละเอียดเพิ่มเติมให้ทราบ</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">CONDUCT</div>
        <h2 class="about-title">การเข้าร่วมกิจกรรม</h2>
        <p class="about-text">
          เราสนับสนุนการท่องเที่ยวอย่างมีจริยธรรม โปรดปฏิบัติตามคำแนะนำของไกด์
          และเคารพพื้นที่ของช้างและผู้ร่วมทัวร์ท่านอื่น
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> ห้ามให้อาหารช้างเองโดยไม่ได้รับอนุญาต</li>
          <li><span>✓</span> ห้ามเข้าใกล้ช้างโดยไม่มีไกด์หรือผู้ดูแล</li>
          <li><span>✓</span> หลีกเลี่ยงการใช้เสียงดังหรือพฤติกรรมที่รบกวนสัตว์</li>
          <li><span>✓</span> เด็กต้องอยู่ในการดูแลของผู้ปกครองตลอดเวลา</li>
        </ul>
      </div>

      <div class="col-md-4">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">Need help?</div>
          <div class="about-cta__text">
            หากมีคำถามเกี่ยวกับการจอง การยกเลิก หรือการคืนเงิน
            สามารถติดต่อทีมงานได้ตลอดเวลา
          </div>
          <a href="{{ route('frontend.contact') }}" class="btn-primary-soft btn-primary-soft--full">Contact Support</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

