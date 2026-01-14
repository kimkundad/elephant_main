@extends('frontend.layouts.app')

@section('content')
{{-- HERO --}}
<section class="contact-hero" style="background-image:url('{{ asset('frontend/images/bg-chang.webp') }}')">
  <div class="contact-hero__overlay"></div>
  <div class="container contact-hero__inner">
    <div class="contact-hero__kicker">PHUKET ELEPHANT SANCTUARY</div>
    <h1 class="contact-hero__title">Contact</h1>
    <p class="contact-hero__lead">
      หากมีคำถามเกี่ยวกับทัวร์ การเดินทาง หรือการจอง กรุณาส่งข้อความหาเราได้เลย
    </p>
  </div>
</section>

<div class="container" style="margin-top:40px; margin-bottom:80px;">

  @if(session('success'))
    <div class="contact-alert">
      ✅ {{ session('success') }}
    </div>
  @endif

  <div class="row contact-gap">
    {{-- LEFT: form --}}
    <div class="col-md-7">
      <div class="contact-card">
        <div class="contact-card__title">Send us a message</div>
        <div class="contact-card__sub">
          We’ll get back to you shortly. For quick answers, please check our FAQ.
        </div>

        <form method="POST" action="{{ route('frontend.contact.store') }}">
          @csrf

          <div class="contact-field">
            <label class="contact-label">Name <span>*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" class="contact-input" placeholder="Full name" required>
            @error('name') <div class="contact-error">{{ $message }}</div> @enderror
          </div>

          <div class="contact-field">
            <label class="contact-label">Email <span>*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" class="contact-input" placeholder="you@example.com" required>
            @error('email') <div class="contact-error">{{ $message }}</div> @enderror
          </div>

          <div class="contact-field">
            <label class="contact-label">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="contact-input" placeholder="+66 ...">
            @error('phone') <div class="contact-error">{{ $message }}</div> @enderror
          </div>

          <div class="contact-field">
            <label class="contact-label">Subject <span>*</span></label>
            <input type="text" name="subject" value="{{ old('subject') }}" class="contact-input" placeholder="Booking / Tour inquiry" required>
            @error('subject') <div class="contact-error">{{ $message }}</div> @enderror
          </div>

          <div class="contact-field">
            <label class="contact-label">Message <span>*</span></label>
            <textarea name="message" rows="6" class="contact-input contact-textarea" placeholder="Tell us how we can help..." required>{{ old('message') }}</textarea>
            @error('message') <div class="contact-error">{{ $message }}</div> @enderror
          </div>

          <button class="contact-btn" type="submit">Submit</button>
        </form>
      </div>
    </div>

    {{-- RIGHT: contact info --}}
    <div class="col-md-5">
      <div class="contact-side">
        <div class="contact-side__title">To speak with us</div>

        <div class="contact-side__item">
          <div class="contact-side__label">Office hours</div>
          <div class="contact-side__value">08:30 – 17:30 (Thailand time)</div>
        </div>

        <div class="contact-side__item">
          <div class="contact-side__label">Email</div>
          <div class="contact-side__value"><a href="mailto:info@phuketeps.org">info@phuketeps.org</a></div>
        </div>

        <div class="contact-side__item">
          <div class="contact-side__label">Phone</div>
          <div class="contact-side__value">
            <a href="tel:+6676529099">+66 76 529 099</a><br>
            <a href="tel:+6662778411">+66 62 778 411</a>
          </div>
        </div>

        <div class="contact-side__item">
          <div class="contact-side__label">WhatsApp / Line</div>
          <div class="contact-side__value">Message us anytime</div>
        </div>

        <div class="contact-side__hint">
          Tip: หากคุณต้องการจองทัวร์ แนะนำให้เลือกโปรแกรมจากหน้า Tours แล้วทำการจองตามวันและเวลาได้ทันที
        </div>

        <a href="{{ route('frontend.tours.index') }}" class="contact-side__cta">View Tours</a>
      </div>
    </div>
  </div>
</div>

{{-- MAP --}}
<section class="contact-map">
  <iframe
    src="https://www.google.com/maps?q=Phuket%20Elephant%20Sanctuary&output=embed"
    loading="lazy"
    referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>
@endsection
