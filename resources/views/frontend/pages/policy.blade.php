@extends('frontend.layouts.app')

@section('content')
{{-- HERO --}}
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">SMALL ELEPHANTS</div>
    <h1 class="about-hero__title">Policy</h1>
    <p class="about-hero__lead">
      แนวทางการดูแลข้อมูลส่วนบุคคล ความปลอดภัย และการใช้งานเว็บไซต์
    </p>
    <div class="about-hero__actions">
      <a href="{{ route('frontend.contact') }}" class="btn-primary-soft">Contact Us</a>
      <a href="#policy" class="btn-outline-soft">Read Policy</a>
    </div>
  </div>
</section>

{{-- POLICY --}}
<section id="policy" class="about-section">
  <div class="container">
    <div class="row align-items-start about-gap">
      <div class="col-md-8">
        <div class="about-eyebrow">PRIVACY</div>
        <h2 class="about-title">นโยบายความเป็นส่วนตัว</h2>
        <p class="about-text">
          เราให้ความสำคัญกับความเป็นส่วนตัวของผู้ใช้ทุกคน ข้อมูลที่เก็บรวบรวมมีไว้เพื่อให้บริการ
          อำนวยความสะดวกในการจอง และติดต่อสื่อสารอย่างจำเป็นเท่านั้น
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> เก็บข้อมูลพื้นฐานที่จำเป็น เช่น ชื่อ เบอร์โทร อีเมล และรายละเอียดการจอง</li>
          <li><span>✓</span> ใช้ข้อมูลเพื่อยืนยันการจอง แจ้งเตือน และปรับปรุงบริการ</li>
          <li><span>✓</span> ไม่ขายหรือเผยแพร่ข้อมูลส่วนบุคคลให้บุคคลที่สามโดยไม่ได้รับอนุญาต</li>
          <li><span>✓</span> สามารถติดต่อเราเพื่อแก้ไขหรือลบข้อมูลได้ตามความเหมาะสม</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">SECURITY</div>
        <h2 class="about-title">ความปลอดภัยของการชำระเงิน</h2>
        <p class="about-text">
          การชำระเงินดำเนินการผ่านผู้ให้บริการที่ได้มาตรฐาน
          เราไม่จัดเก็บข้อมูลบัตรเครดิตของลูกค้าไว้บนระบบของเรา
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> ระบบชำระเงินจัดการโดยผู้ให้บริการภายนอกที่ได้รับความน่าเชื่อถือ</li>
          <li><span>✓</span> ข้อมูลบัตรหรือช่องทางชำระเงินจะไม่ถูกบันทึกไว้บนระบบของเรา</li>
          <li><span>✓</span> หากพบความผิดปกติ โปรดติดต่อเราเพื่อช่วยตรวจสอบ</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">WEBSITE</div>
        <h2 class="about-title">การใช้งานเว็บไซต์</h2>
        <p class="about-text">
          เนื้อหาและภาพถ่ายบนเว็บไซต์เป็นทรัพย์สินของเรา
          โปรดขออนุญาตก่อนนำไปใช้ซ้ำในเชิงพาณิชย์
        </p>
        <ul class="about-list about-list--dark">
          <li><span>✓</span> ห้ามคัดลอกหรือดัดแปลงเนื้อหาเพื่อการค้าโดยไม่ได้รับอนุญาต</li>
          <li><span>✓</span> เราสงวนสิทธิ์ปรับปรุงเนื้อหาและเงื่อนไขโดยไม่ต้องแจ้งล่วงหน้า</li>
          <li><span>✓</span> ลิงก์ภายนอกอยู่ภายใต้นโยบายของเว็บไซต์นั้น ๆ</li>
        </ul>

        <div class="about-eyebrow" style="margin-top:28px;">COOKIES</div>
        <h2 class="about-title">คุกกี้และการวิเคราะห์</h2>
        <p class="about-text">
          เราอาจใช้คุกกี้เพื่อช่วยให้เว็บไซต์ทำงานได้อย่างเหมาะสม และเพื่อการวิเคราะห์การใช้งาน
          คุณสามารถจัดการการตั้งค่าคุกกี้ผ่านเบราว์เซอร์ของคุณได้
        </p>
      </div>

      <div class="col-md-4">
        <div class="about-cta about-cta--light">
          <div class="about-cta__title">Questions?</div>
          <div class="about-cta__text">
            หากต้องการสอบถามรายละเอียดเพิ่มเติมเกี่ยวกับนโยบายนี้
            ติดต่อเราได้ทุกช่องทาง
          </div>
          <a href="{{ route('frontend.contact') }}" class="btn-primary-soft btn-primary-soft--full">Get in Touch</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

