@extends('frontend_v2.layouts.app')

@php
  $tourTranslation = $tour->translation(app()->getLocale());
  $tourName = $tourTranslation->name ?? $tour->name;
  $tourShortDescription = $tourTranslation->short_description ?? $tour->short_description;
  $bookingI18n = [
      'errors' => [
          'selectPickup' => __('booking.errors.pickup_required'),
          'enterFullName' => __('booking.errors.enter_full_name'),
          'enterPhone' => __('booking.errors.enter_phone'),
          'enterEmail' => __('booking.errors.enter_email'),
          'minCharge' => __('booking.errors.min_charge'),
          'discountInvalid' => __('booking.errors.discount_invalid'),
          'discountRequired' => __('booking.errors.discount_required'),
          'discountCheckFailed' => __('booking.errors.discount_check_failed'),
      ],
      'pickup' => [
          'placeholder' => __('booking.create.pickup_select_placeholder'),
          'hint' => __('booking.create.pickup_search_hint'),
          'empty' => __('booking.create.pickup_search_empty'),
      ],
      'ui' => [
          'checking' => __('booking.create.checking'),
          'apply' => __('booking.create.apply'),
          'discountApplied' => __('booking.create.discount_applied'),
          'submitting' => __('booking.create.submitting'),
      ],
  ];
@endphp

@section('title', __('booking.page_title'))
@section('meta_description', app()->getLocale() === 'th'
  ? 'จองโปรแกรม ' . $tourName . ' เลือกวัน เวลา ผู้เข้าร่วม และชำระเงินออนไลน์ได้อย่างปลอดภัย'
  : 'Book ' . $tourName . ' online. Select date, session, guest count, and complete payment securely.')

@push('styles')
<style>
.booking-v2{
  background:#f7f5f1;
  padding:70px 0 90px;
}
.booking-v2 .container{
  padding: 0 16px;
}
.booking-hero{
  display:flex;
  gap:24px;
  align-items:center;
  background:#fff;
  border-radius:18px;
  padding:15px;
  box-shadow:0 18px 40px rgba(0,0,0,.08);
  border:1px solid rgba(0,0,0,.06);
  margin-bottom:24px;
}
.booking-hero-img{
  width:220px;
  height:140px;
  border-radius:12px;
  overflow:hidden;
  flex:0 0 auto;
}
.booking-hero-img img{ width:100%; height:100%; object-fit:cover; }
.booking-hero-title{ font-size:24px; font-weight:800; color:#2b2621; }
.booking-hero-sub{ color:#6b6156; margin:6px 0 8px; }
.booking-hero-desc{ color:#7a7166; font-size:14px; line-height:1.7; }

.booking-grid{
  display:grid;
  grid-template-columns: minmax(0,1fr) minmax(0,360px);
  gap:28px;
}
.card{
  background:#fff;
  border-radius:18px;
  padding:22px;
  border:1px solid rgba(0,0,0,.06);
  box-shadow:0 14px 30px rgba(0,0,0,.06);
  margin-bottom:18px;
}
.booking-left .card,
.booking-right .card{
  padding: 12px;
}
.card-title{
  font-weight:800;
  margin-bottom:14px;
  color:#2b2621;
}
.qty-row{
  display:grid;
  grid-template-columns: 1fr auto auto;
  align-items:center;
  gap:12px;
  padding:10px 0;
  border-bottom:1px solid #eee7dd;
}
.qty-row:last-child{ border-bottom:0; }
.qty-name{ font-weight:700; }
.qty-sub{ font-size:12px; color:#8b8177; }
.qty-price{ font-weight:700; color:#2b2621; }
.qty-ctrl{ display:flex; align-items:center; gap:8px; }
.qty-btn{
  width:30px; height:30px; border-radius:8px; border:1px solid #ddd3c6;
  background:#f3eee6; cursor:pointer; font-weight:700;
}
.qty-input{
  width:56px; text-align:center; padding:6px 8px; border:1px solid #ddd3c6; border-radius:8px;
}
.f-label{ display:block; font-size:13px; color:#6b6156; margin:12px 0 6px; }
.f-input{
  width:100%; padding:10px 12px; border:1px solid #ddd3c6; border-radius:10px; background:#fff;
}
.grid-2{
  display:grid; grid-template-columns: 1fr 1fr; gap:14px;
}
.checkbox{
  display:flex; align-items:flex-start; gap:8px; margin-top:10px; font-size:14px; color:#6b6156;
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
  margin-left: 25px
}
.checkbox input{
  flex: 0 0 auto;
  margin-top: 3px;
  width: 14px;
  height: 14px;
}
.self-drive-check{
  display:flex;
  align-items:center;
  gap:12px;
  margin:4px 0 16px 28px;
      padding: 12px 26px;
  border:1px solid #e6ddd0;
  border-radius:14px;
  background:#faf6ef;
  width:fit-content;
  max-width:100%;
  box-shadow:0 6px 16px rgba(0,0,0,.04);
}
.self-drive-check input{
  flex:0 0 auto;
  width:20px;
  height:20px;
  margin-top:0;
  accent-color:#2b2621;
  cursor:pointer;
}
.self-drive-check span{
  min-width:0;
  display:block;
  font-size:15px;
  font-weight:600;
  color:#4b4238;
  line-height:1.5;
}
.checkbox span,
.checkbox .checkbox-label{
      padding-left: 10px;
  min-width: 0;
  flex: 1 1 auto;
  display: block;
  white-space: normal;
  word-break: break-word;
  line-height: 1.5;
}
.booking-right .sticky{
  position:sticky; top:90px;
}
.sum-row{
  display:flex; justify-content:space-between; padding:6px 0; color:#5f5850;
}
.sum-total{
  display:flex; justify-content:space-between; padding:10px 0; font-weight:800; color:#2b2621;
  border-top:1px solid #eee7dd; margin-top:6px;
}
.btn-pay{
  width:100%; padding:12px 16px; border:0; border-radius:12px; background:#2b2621; color:#fff;
  font-weight:800; margin-top:12px; cursor:pointer;
}
.tiny{ font-size:12px; color:#8b8177; }

@media (max-width: 992px){
  .booking-hero{ flex-direction:column; align-items:flex-start; }
  .booking-hero-img{ width:100%; height:200px; }
  .booking-grid{ grid-template-columns: 1fr; }
  .grid-2{ grid-template-columns: 1fr; }
}
@media (max-width: 575px){
  .booking-hero-title{ font-size:20px; }
  .booking-hero-sub{ font-size:12px; }
  .booking-hero-desc{ font-size:10px; }
  .self-drive-check{
    margin-left:12px;
    width:calc(100% - 12px);
  }
}
@media (min-width: 1200px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 1140px;
    }
}

@media (min-width: 1500px) {
    .container, .elementor-section.elementor-section-boxed > .elementor-container {
        max-width: 1350px;
    }
}

/* Validation feedback. The controller bounces back with withErrors() from six
   places plus validate(); without these the form just reloaded silently. */
.booking-errors{
  grid-column: 1 / -1;
  border:1px solid #e6b3b3;
  background:#fdf3f3;
  border-radius:12px;
  padding:14px 16px;
  color:#8f2f2f;
}
.booking-errors__title{
  font-weight:700;
  margin-bottom:6px;
}
.booking-errors ul{
  margin:0;
  padding-left:18px;
}
.booking-errors li{
  line-height:1.7;
}
.field-error{
  display:block;
  color:#b3261e;
  font-size:13px;
  margin-top:6px;
}
.f-input.is-invalid{
  border-color:#b3261e;
}
.req{ color:#e2572b; font-weight:700; }
textarea.f-input.pickup-note{ box-sizing:border-box; resize:vertical; min-height:84px; font-family:inherit; }

/* Searchable pickup list. The native <select> still carries the value and
   stays visible until the JS takes over, so the form works without JS. */
.pickup-combo{ position:relative; }
.pickup-combo.is-enhanced > select{ position:absolute; opacity:0; width:0; height:0; pointer-events:none; }
.pickup-combo__input{ cursor:text; }
.pickup-combo__panel{
  position:absolute; z-index:40; left:0; right:0; top:calc(100% + 4px);
  background:#fff; border:1px solid #ddd3c6; border-radius:12px;
  box-shadow:0 18px 40px rgba(0,0,0,.12); overflow:auto; max-height:280px; display:none;
}
.pickup-combo.is-open .pickup-combo__panel{ display:block; }
.pickup-combo__group{
  padding:8px 12px; font-size:12px; font-weight:700; color:#8b8177;
  background:#faf6ef; position:sticky; top:0;
}
.pickup-combo__option{ padding:10px 12px; cursor:pointer; font-size:14px; color:#2b2621; }
.pickup-combo__option:hover,
.pickup-combo__option.is-active{ background:#f3eee6; }
.pickup-combo__option.is-selected{ font-weight:700; }
.pickup-combo__empty{ padding:12px; color:#8b8177; font-size:13px; }
.pickup-combo__hint{ font-size:12px; color:#8b8177; margin-top:6px; }

/* Submit loading state: locks the Book button and covers the page so the
   guest can't double-submit while the booking / payment page is prepared. */
.btn-pay.is-loading{
  display:inline-flex; align-items:center; justify-content:center; gap:10px;
  cursor:wait; opacity:.85;
}
.btn-spinner{
  width:16px; height:16px; border-radius:50%;
  border:2px solid rgba(255,255,255,.35); border-top-color:#fff;
  animation:booking-spin .7s linear infinite;
}
.booking-overlay{
  position:fixed; inset:0; z-index:9999;
  display:flex; align-items:center; justify-content:center; padding:16px;
  background:rgba(43,38,33,.55);
  -webkit-backdrop-filter:blur(4px); backdrop-filter:blur(4px);
  opacity:0; visibility:hidden; transition:opacity .25s ease, visibility .25s ease;
}
.booking-overlay.is-visible{ opacity:1; visibility:visible; }
.booking-overlay-card{
  width:100%; max-width:360px; background:#fff; border-radius:18px;
  padding:32px 24px 26px; text-align:center;
  box-shadow:0 24px 60px rgba(0,0,0,.25);
  transform:translateY(12px) scale(.97); transition:transform .3s ease;
}
.booking-overlay.is-visible .booking-overlay-card{ transform:none; }
.booking-loader{
  position:relative; width:64px; height:64px; margin:0 auto 18px;
}
.booking-loader::before,
.booking-loader::after{
  content:""; position:absolute; inset:0; border-radius:50%;
  border:4px solid transparent;
}
.booking-loader::before{ border-color:#f1ebe3; }
.booking-loader::after{
  border-top-color:#b58d4f; border-right-color:#b58d4f;
  animation:booking-spin .9s cubic-bezier(.5,.1,.5,.9) infinite;
}
.booking-overlay-title{ font-size:18px; font-weight:800; color:#2b2621; margin-bottom:8px; }
.booking-overlay-text{ font-size:14px; line-height:1.6; color:#6f655b; }
.booking-overlay-dots{ display:flex; justify-content:center; gap:6px; margin-top:16px; }
.booking-overlay-dots span{
  width:7px; height:7px; border-radius:50%; background:#b58d4f;
  animation:booking-dot 1.2s ease-in-out infinite;
}
.booking-overlay-dots span:nth-child(2){ animation-delay:.15s; }
.booking-overlay-dots span:nth-child(3){ animation-delay:.3s; }
@keyframes booking-spin{ to{ transform:rotate(360deg); } }
@keyframes booking-dot{
  0%, 80%, 100%{ opacity:.25; transform:scale(.8); }
  40%{ opacity:1; transform:scale(1); }
}
@media (prefers-reduced-motion: reduce){
  .booking-loader::after, .btn-spinner, .booking-overlay-dots span{ animation-duration:2.4s; }
}
</style>
@endpush

@section('content')
<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}'); min-height: 250px;">
  <div class="about-hero__overlay"></div>
</section>


<section class="booking-v2">
  <div class="container">
    <div class="booking-hero">
      <div class="booking-hero-img">
        <img src="{{ $tour->thumbnail }}" alt="{{ $tourName }}">
      </div>
      <div>
        <div class="booking-hero-title">{{ $tourName }}</div>
        <div class="booking-hero-sub">
          {{ \Carbon\Carbon::parse($date)->locale(app()->getLocale())->translatedFormat('l, d F Y') }}
          @ {{ $session->time_range }}
          @if($tour->province) &middot; {{ $tour->province->name() }} @endif
        </div>
        <div class="booking-hero-desc">{{ $tourShortDescription }}</div>
      </div>
    </div>

    <form method="POST" action="{{ route('frontend.booking.store') }}" class="booking-grid" id="booking-form">
      @csrf
      <input type="hidden" name="booking_v2" value="1">
      <input type="hidden" name="tour_id" value="{{ $tour->id }}">
      <input type="hidden" name="session_id" value="{{ $session->id }}">
      <input type="hidden" name="date" value="{{ $date }}">

      @if($errors->any())
        <div class="booking-errors" role="alert">
          <div class="booking-errors__title">{{ __('booking.errors.summary_title') }}</div>
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="booking-left">
        <div class="card">
          <div class="card-title">{{ __('booking.create.plan_experience') }}</div>

          <div class="qty-row">
            <div class="qty-label">
              <div class="qty-name">{{ __('booking.create.adults') }}</div>
              <div class="qty-sub">{{ __('booking.create.adults_age') }}</div>
            </div>
            <div class="qty-price">THB <span id="price-adult">{{ number_format($prices['adult']) }}</span></div>
            <div class="qty-ctrl">
              <button type="button" class="qty-btn" data-target="adult" data-delta="-1">-</button>
              <input type="number" name="qty_adult" id="qty-adult" value="1" min="0" class="qty-input">
              <button type="button" class="qty-btn" data-target="adult" data-delta="1">+</button>
            </div>
          </div>

          <div class="qty-row">
            <div class="qty-label">
              <div class="qty-name">{{ __('booking.create.children') }}</div>
              <div class="qty-sub">{{ __('booking.create.children_age') }}</div>
            </div>
            <div class="qty-price">THB <span id="price-child">{{ number_format($prices['child']) }}</span></div>
            <div class="qty-ctrl">
              <button type="button" class="qty-btn" data-target="child" data-delta="-1">-</button>
              <input type="number" name="qty_child" id="qty-child" value="0" min="0" class="qty-input">
              <button type="button" class="qty-btn" data-target="child" data-delta="1">+</button>
            </div>
          </div>

          <div class="qty-row">
            <div class="qty-label">
              <div class="qty-name">{{ __('booking.create.infants') }}</div>
              <div class="qty-sub">{{ __('booking.create.infants_age') }}</div>
            </div>
            <div class="qty-price">{{ __('booking.create.free') }}</div>
            <div class="qty-ctrl">
              <button type="button" class="qty-btn" data-target="infant" data-delta="-1">-</button>
              <input type="number" name="qty_infant" id="qty-infant" value="0" min="0" class="qty-input">
              <button type="button" class="qty-btn" data-target="infant" data-delta="1">+</button>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-title">{{ __('booking.create.additional_info') }}</div>
          <label class="checkbox self-drive-check">
            <input type="checkbox" name="self_drive" id="self_drive" value="1" @checked(old('self_drive'))>
            <span>{{ __('booking.create.self_drive') }}</span>
          </label>

          <div id="pickupFields">
            <label class="f-label" for="pickup_location_id">
              {{ __('booking.create.hotel_pickup') }} <span class="req">*</span>
            </label>
            @php
              $pickupGroups = [
                __('booking.create.pickup_group_hotels') => $pickupLocations->where('is_meeting_point', false),
                __('booking.create.pickup_group_meeting') => $pickupLocations->where('is_meeting_point', true),
              ];
            @endphp
            <div class="pickup-combo" data-pickup-combo>
            <select name="pickup_location_id" id="pickup_location_id" class="f-input @error('pickup_location_id') is-invalid @enderror" required>
              <option value="">{{ __('booking.create.pickup_select_placeholder') }}</option>
              @foreach($pickupGroups as $groupLabel => $groupItems)
                @if($groupItems->isNotEmpty())
                  <optgroup label="{{ $groupLabel }}">
                    @foreach($groupItems as $pickup)
                      <option value="{{ $pickup->id }}" @selected((string) old('pickup_location_id') === (string) $pickup->id)>{{ $pickup->name }}</option>
                    @endforeach
                  </optgroup>
                @endif
              @endforeach
            </select>
            </div>
            @error('pickup_location_id')<span class="field-error">{{ $message }}</span>@enderror

            <label class="f-label" for="pickup_note">{{ __('booking.create.pickup_note_label') }}</label>
            <textarea name="pickup_note" id="pickup_note" class="f-input pickup-note" rows="3" maxlength="1000">{{ old('pickup_note') }}</textarea>
            @error('pickup_note')<span class="field-error">{{ $message }}</span>@enderror
          </div>
        </div>

        <div class="card">
          <div class="card-title">{{ __('booking.create.contact_details') }}</div>
          <div class="grid-2">
            <div>
              <label class="f-label">{{ __('booking.create.full_name') }}</label>
              <input type="text" name="full_name" class="f-input" required>
            </div>
            <div>
              <label class="f-label">{{ __('booking.create.phone') }}</label>
              <input type="text" name="phone" class="f-input" required>
            </div>
          </div>
          <label class="f-label">{{ __('booking.create.email') }}</label>
          <input type="email" name="email" class="f-input" required>
          <label class="checkbox">
            <input type="checkbox" name="newsletter" value="1">
            <span>{{ __('booking.create.newsletter') }}</span>
          </label>
        </div>
      </div>

      <div class="booking-right">
        <div class="card sticky">
          <div class="card-title">{{ __('booking.create.summary') }}</div>
          <div class="sum-row"><span>{{ __('booking.create.subtotal') }}</span><strong>THB <span id="sum-subtotal">0</span></strong></div>
          <div class="sum-row"><span>{{ __('booking.create.vat') }}</span><span>THB <span id="sum-vat">0</span></span></div>
          <div class="sum-row"><span>{{ __('booking.create.fees') }}</span><span>THB <span id="sum-fee">0</span></span></div>
          <div class="sum-row"><span>{{ __('booking.create.discount') }}</span><span>THB <span id="sum-discount">0</span></span></div>
          <div class="sum-total"><span>{{ __('booking.create.total') }}</span><strong>THB <span id="sum-total">0</span></strong></div>

          <div class="card" style="margin-top:12px;">
            <div class="card-title">{{ __('booking.create.discount_code') }}</div>
            <div style="display:flex; gap:8px; align-items:center;">
              <input type="text" name="discount_code" id="discount_code" class="f-input" placeholder="{{ __('booking.create.discount_placeholder') }}">
              <button type="button" id="apply_discount" class="btn-pay" style="padding:10px 14px; width:auto;">{{ __('booking.create.apply') }}</button>
            </div>
            <div id="discount_msg" class="tiny" style="margin-top:6px;"></div>
            @error('discount_code')<span class="field-error">{{ $message }}</span>@enderror
          </div>

          <div class="card" style="margin-top:12px;">
            <div class="card-title">{{ __('booking.create.payment_details') }}</div>
            <div style="margin-top:10px;">
              <label class="f-label">{{ __('booking.create.payment_method') }}</label>
              <select name="payment_channel" class="f-input" required>
                @foreach ($availablePaymentChannels as $paymentChannel)
                <option value="{{ $paymentChannel }}" @selected(old('payment_channel', $availablePaymentChannels[0] ?? 'card') === $paymentChannel)>
                  {{ $paymentChannel === 'promptpay' ? __('booking.create.payment_promptpay') : __('booking.create.payment_card') }}
                </option>
                @endforeach
              </select>
              @error('payment_channel')<span class="field-error">{{ $message }}</span>@enderror
            </div>
          </div>

          <button type="submit" class="btn-pay" id="btn-book">{{ __('booking.create.book') }}</button>
          <div class="tiny">{{ __('booking.create.terms_notice') }}</div>
        </div>
      </div>
    </form>
  </div>
</section>

<div class="booking-overlay" id="booking-overlay" role="status" aria-live="polite" aria-hidden="true">
  <div class="booking-overlay-card">
    <div class="booking-loader" aria-hidden="true"></div>
    <div class="booking-overlay-title">{{ __('booking.create.processing_title') }}</div>
    <div class="booking-overlay-text">{{ __('booking.create.processing_text') }}</div>
    <div class="booking-overlay-dots" aria-hidden="true"><span></span><span></span><span></span></div>
  </div>
</div>

<script>
const BOOKING_I18N = @json($bookingI18n);
</script>

<script>
(function() {
  const form = document.getElementById('booking-form');
  if (!form) return;

  const bookBtn = document.getElementById('btn-book');
  const overlay = document.getElementById('booking-overlay');
  const bookBtnHtml = bookBtn ? bookBtn.innerHTML : '';
  let submitting = false;

  // Hang the overlay directly off <body> so a transformed ancestor (smooth-scroll
  // wrappers etc.) can never turn its position:fixed into a local position.
  if (overlay) document.body.appendChild(overlay);

  // Lock the form once a valid submit goes through: spinner on the button plus a
  // full-page overlay, so repeated clicks / Enter presses can't create extra bookings.
  const startLoading = () => {
    submitting = true;
    if (bookBtn) {
      bookBtn.disabled = true;
      bookBtn.classList.add('is-loading');
      bookBtn.innerHTML = '<span class="btn-spinner" aria-hidden="true"></span><span></span>';
      bookBtn.lastChild.textContent = BOOKING_I18N.ui.submitting;
    }
    if (overlay) {
      overlay.classList.add('is-visible');
      overlay.setAttribute('aria-hidden', 'false');
    }
  };

  const stopLoading = () => {
    submitting = false;
    if (bookBtn) {
      bookBtn.disabled = false;
      bookBtn.classList.remove('is-loading');
      bookBtn.innerHTML = bookBtnHtml;
    }
    if (overlay) {
      overlay.classList.remove('is-visible');
      overlay.setAttribute('aria-hidden', 'true');
    }
  };

  // Coming back with the browser Back button (e.g. from Stripe Checkout) restores
  // this page from the back/forward cache still in its loading state.
  window.addEventListener('pageshow', (e) => {
    if (e.persisted && submitting) stopLoading();
  });

  form.addEventListener('submit', (e) => {
    if (submitting) {
      e.preventDefault();
      return;
    }

    const selfDrive = document.getElementById('self_drive');
    const pickupSelect = document.getElementById('pickup_location_id');
    const fullName = form.querySelector('input[name="full_name"]');
    const phone = form.querySelector('input[name="phone"]');
    const email = form.querySelector('input[name="email"]');
    const discountCode = document.getElementById('discount_code');

    if (!selfDrive?.checked && (!pickupSelect || !pickupSelect.value)) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.selectPickup);
      pickupSelect?.focus();
      return;
    }

    if (!fullName || !fullName.value.trim()) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.enterFullName);
      fullName?.focus();
      return;
    }

    if (!phone || !phone.value.trim()) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.enterPhone);
      phone?.focus();
      return;
    }

    if (!email || !email.value.trim()) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.enterEmail);
      email?.focus();
      return;
    }

    if (window.bookingTotalAfterDiscount !== undefined && window.bookingTotalAfterDiscount < 10) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.minCharge);
      return;
    }

    if (discountCode && discountCode.value.trim() && !window.bookingDiscountValid) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.discountInvalid);
      discountCode.focus();
      return;
    }

    startLoading();
  });
})();

(function() {
  const PRICES = {
    adult: {{ (int) $prices['adult'] }},
    child: {{ (int) $prices['child'] }},
    infant: 0
  };
  const VAT_RATE = 0.07;
  const FEE_FLAT = 0;
  const MIN_CHARGE = 10;

  function clampMin0(n) { return Math.max(0, parseInt(n || 0, 10)); }
  function money(n) { return (Math.round(n)).toLocaleString('en-US'); }

  function calc() {
    const qa = clampMin0(document.getElementById('qty-adult').value);
    const qc = clampMin0(document.getElementById('qty-child').value);
    const qi = clampMin0(document.getElementById('qty-infant').value);

    const subtotal = qa * PRICES.adult + qc * PRICES.child + qi * PRICES.infant;
    const vat = subtotal * VAT_RATE;
    const fee = FEE_FLAT;
    const discount = window.bookingDiscountAmount || 0;
    const total = Math.max(0, subtotal + vat + fee - discount);
    window.bookingTotalAfterDiscount = total;

    document.getElementById('sum-subtotal').textContent = money(subtotal);
    document.getElementById('sum-vat').textContent = money(vat);
    document.getElementById('sum-fee').textContent = money(fee);
    document.getElementById('sum-discount').textContent = money(discount);
    document.getElementById('sum-total').textContent = money(total);

    const bookBtn = document.getElementById('btn-book');
    const msg = document.getElementById('discount_msg');
    if (bookBtn && total > 0 && total < MIN_CHARGE) {
      bookBtn.disabled = true;
      bookBtn.style.opacity = '0.6';
      if (msg) {
        msg.textContent = BOOKING_I18N.errors.minCharge;
        msg.style.color = '#b00020';
      }
    }
  }

  document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.getAttribute('data-target');
      const delta = parseInt(btn.getAttribute('data-delta'), 10);
      const input = document.getElementById('qty-' + target);
      input.value = clampMin0(parseInt(input.value, 10) + delta);
      calc();
    });
  });

  document.querySelectorAll('.qty-input').forEach(inp => {
    inp.addEventListener('input', calc);
  });

  window.bookingDiscountAmount = 0;
  window.bookingDiscountValid = true;
  window.updateBookingTotals = calc;
  calc();
})();
</script>

<script>
(function() {
  const applyBtn = document.getElementById('apply_discount');
  const codeInput = document.getElementById('discount_code');
  const msg = document.getElementById('discount_msg');
  const bookBtn = document.getElementById('btn-book');
  if (!applyBtn || !codeInput) return;

  const setState = (valid, message, amount) => {
    window.bookingDiscountValid = valid;
    window.bookingDiscountAmount = valid ? (amount || 0) : 0;
    if (msg) {
      msg.textContent = message || '';
      msg.style.color = valid ? '#2e7d32' : '#b00020';
    }
    if (bookBtn) {
      if (codeInput.value.trim() && !valid) {
        bookBtn.disabled = true;
        bookBtn.style.opacity = '0.6';
      } else {
        bookBtn.disabled = false;
        bookBtn.style.opacity = '1';
      }
    }
    if (window.updateBookingTotals) window.updateBookingTotals();
  };

  const resetState = () => {
    window.bookingDiscountValid = !codeInput.value.trim();
    window.bookingDiscountAmount = 0;
    if (msg) msg.textContent = '';
    if (bookBtn) {
      if (codeInput.value.trim()) {
        bookBtn.disabled = true;
        bookBtn.style.opacity = '0.6';
      } else {
        bookBtn.disabled = false;
        bookBtn.style.opacity = '1';
      }
    }
    if (window.updateBookingTotals) window.updateBookingTotals();
  };

  codeInput.addEventListener('input', resetState);

  applyBtn.addEventListener('click', async () => {
    const code = codeInput.value.trim();
    if (!code) {
      setState(false, BOOKING_I18N.errors.discountRequired);
      return;
    }

    applyBtn.disabled = true;
    applyBtn.textContent = BOOKING_I18N.ui.checking;

    try {
      const res = await fetch("{{ route('frontend.booking.validate-discount') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
        },
        body: JSON.stringify({ code })
      });

      const data = await res.json();
      if (!res.ok) {
        setState(false, data.message || BOOKING_I18N.errors.discountInvalid);
      } else {
        setState(true, BOOKING_I18N.ui.discountApplied, data.amount || 0);
      }
    } catch (e) {
      setState(false, BOOKING_I18N.errors.discountCheckFailed);
    } finally {
      applyBtn.disabled = false;
      applyBtn.textContent = BOOKING_I18N.ui.apply;
    }
  });
})();
</script>

<script>
(function () {
  const selfDrive = document.getElementById('self_drive');
  const pickupFields = document.getElementById('pickupFields');
  const pickupSelect = document.getElementById('pickup_location_id');
  if (!selfDrive || !pickupFields || !pickupSelect) return;

  // Travelling by themselves means no pickup point is needed.
  const syncPickupMode = () => {
    pickupFields.style.display = selfDrive.checked ? 'none' : '';
    pickupSelect.required = !selfDrive.checked;
  };

  selfDrive.addEventListener('change', syncPickupMode);
  syncPickupMode();
})();
</script>

<script>
// Searchable pickup list. The <select> keeps the value (and works on its own
// when this script does not run), this only puts a filter in front of it.
(function () {
  const wrap = document.querySelector('[data-pickup-combo]');
  const select = document.getElementById('pickup_location_id');
  if (!wrap || !select) return;

  const texts = (typeof BOOKING_I18N !== 'undefined' && BOOKING_I18N.pickup) || {};

  // Read the options once, keeping which group each belongs to.
  const entries = [];
  Array.from(select.querySelectorAll('optgroup')).forEach((group) => {
    Array.from(group.querySelectorAll('option')).forEach((option) => {
      if (!option.value) return;
      entries.push({
        value: option.value,
        label: option.textContent.trim(),
        group: group.label,
        search: option.textContent.trim().toLowerCase(),
      });
    });
  });

  if (entries.length === 0) return;

  const input = document.createElement('input');
  input.type = 'text';
  input.className = 'f-input pickup-combo__input';
  input.autocomplete = 'off';
  input.placeholder = texts.placeholder || '';
  input.setAttribute('role', 'combobox');
  input.setAttribute('aria-expanded', 'false');
  input.setAttribute('aria-autocomplete', 'list');

  const panel = document.createElement('div');
  panel.className = 'pickup-combo__panel';
  panel.setAttribute('role', 'listbox');

  const hint = document.createElement('div');
  hint.className = 'pickup-combo__hint';
  hint.textContent = texts.hint || '';

  wrap.appendChild(input);
  wrap.appendChild(panel);
  wrap.appendChild(hint);
  wrap.classList.add('is-enhanced');

  // A hidden required control blocks the browser's own validation, and our
  // submit handler already checks the value.
  select.removeAttribute('required');

  let matches = [];
  let activeIndex = -1;

  const setSelected = (entry) => {
    select.value = entry ? entry.value : '';
    input.value = entry ? entry.label : '';
    select.dispatchEvent(new Event('change', { bubbles: true }));
  };

  const close = () => {
    wrap.classList.remove('is-open');
    input.setAttribute('aria-expanded', 'false');
    activeIndex = -1;
  };

  const render = (term) => {
    const needle = term.trim().toLowerCase();
    const limit = 100;
    matches = needle === '' ? entries.slice(0, limit) : entries.filter((e) => e.search.includes(needle)).slice(0, limit);

    panel.innerHTML = '';

    if (matches.length === 0) {
      const empty = document.createElement('div');
      empty.className = 'pickup-combo__empty';
      empty.textContent = texts.empty || '';
      panel.appendChild(empty);
      return;
    }

    let lastGroup = null;
    matches.forEach((entry, index) => {
      if (entry.group !== lastGroup) {
        const header = document.createElement('div');
        header.className = 'pickup-combo__group';
        header.textContent = entry.group;
        panel.appendChild(header);
        lastGroup = entry.group;
      }

      const option = document.createElement('div');
      option.className = 'pickup-combo__option' + (select.value === entry.value ? ' is-selected' : '');
      option.textContent = entry.label;
      option.setAttribute('role', 'option');
      option.dataset.index = String(index);
      option.addEventListener('mousedown', (e) => {
        e.preventDefault();
        setSelected(entry);
        close();
      });
      panel.appendChild(option);
    });
  };

  const open = () => {
    render(input.value === selectedLabel() ? '' : input.value);
    wrap.classList.add('is-open');
    input.setAttribute('aria-expanded', 'true');
  };

  const selectedLabel = () => {
    const current = entries.find((e) => e.value === select.value);
    return current ? current.label : '';
  };

  const highlight = (delta) => {
    const options = panel.querySelectorAll('.pickup-combo__option');
    if (options.length === 0) return;

    activeIndex = (activeIndex + delta + options.length) % options.length;
    options.forEach((el, i) => el.classList.toggle('is-active', i === activeIndex));
    options[activeIndex].scrollIntoView({ block: 'nearest' });
  };

  input.addEventListener('focus', open);
  input.addEventListener('input', () => {
    select.value = '';
    render(input.value);
    wrap.classList.add('is-open');
  });

  input.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
      e.preventDefault();
      if (!wrap.classList.contains('is-open')) open();
      highlight(e.key === 'ArrowDown' ? 1 : -1);
      return;
    }

    if (e.key === 'Enter' && wrap.classList.contains('is-open')) {
      e.preventDefault();
      const chosen = matches[activeIndex] ?? (matches.length === 1 ? matches[0] : null);
      if (chosen) {
        setSelected(chosen);
        close();
      }
      return;
    }

    if (e.key === 'Escape') close();
  });

  input.addEventListener('blur', () => {
    // Typing without choosing must not look like a selection.
    window.setTimeout(() => {
      input.value = selectedLabel();
      close();
    }, 120);
  });

  document.addEventListener('click', (e) => {
    if (!wrap.contains(e.target)) close();
  });

  input.value = selectedLabel();
})();
</script>
@endsection
