@extends('frontend_v2.layouts.app')

@php
  $tourTranslation = $tour->translation(app()->getLocale());
  $tourName = $tourTranslation->name ?? $tour->name;
  $tourShortDescription = $tourTranslation->short_description ?? $tour->short_description;
  $bookingI18n = [
      'errors' => [
          'selectHotel' => __('booking.errors.select_hotel'),
          'selectManualAddress' => __('booking.errors.select_manual_address'),
          'pinLocation' => __('booking.errors.pin_location'),
          'selectMeetingPoint' => __('booking.errors.select_meeting_point'),
          'enterFullName' => __('booking.errors.enter_full_name'),
          'enterPhone' => __('booking.errors.enter_phone'),
          'enterEmail' => __('booking.errors.enter_email'),
          'minCharge' => __('booking.errors.min_charge'),
          'discountInvalid' => __('booking.errors.discount_invalid'),
          'discountRequired' => __('booking.errors.discount_required'),
          'outOfBounds' => __('booking.errors.out_of_bounds'),
          'discountCheckFailed' => __('booking.errors.discount_check_failed'),
      ],
      'ui' => [
          'checking' => __('booking.create.checking'),
          'apply' => __('booking.create.apply'),
          'discountApplied' => __('booking.create.discount_applied'),
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
.checkbox span,
.checkbox .checkbox-label{
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
          @ {{ \Carbon\Carbon::parse($session->start_time)->format('g:ia') }}
          @if (!empty($session->end_time))
            &ndash; {{ \Carbon\Carbon::parse($session->end_time)->format('g:ia') }}
          @endif
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
          <label class="f-label">{{ __('booking.create.hotel_pickup') }}</label>

          <div class="mb-3" style="position:relative;">
            <label class="form-label">{{ __('booking.create.search_hotel_label') }}</label>
            <input id="searchInput" type="text" class="f-input" placeholder="{{ __('booking.create.search_hotel_placeholder') }}" autocomplete="off" required>
            <div class="tiny" style="margin-top:8px;">{{ __('booking.create.search_hotel_help') }}</div>
          </div>

          <input type="hidden" name="google_place_id" id="google_place_id" value="">
          <input type="hidden" name="google_place_name" id="google_place_name" value="">
          <input type="hidden" name="google_place_address" id="google_place_address" value="">
          <input type="hidden" name="google_lat" id="google_lat" value="">
          <input type="hidden" name="google_lng" id="google_lng" value="">
          <input type="hidden" name="pickup_source" id="pickup_source" value="">
          <input type="hidden" name="pickup_out_of_bounds" id="pickup_out_of_bounds" value="0">

          <div id="pickupFound" class="tiny" style="display:none; margin-top:8px;">{{ __('booking.create.pickup_found') }}</div>

          <div id="manualWrap" style="display:none; margin-top:12px;">
            <label class="f-label">{{ __('booking.create.manual_address_label') }}</label>
            <input type="text" name="manual_address" id="manual_address" class="f-input" placeholder="{{ __('booking.create.manual_address_placeholder') }}">
            <div class="tiny" style="margin-top:8px;">{{ __('booking.create.manual_address_help') }}</div>
            <div id="manualMap" style="height:260px; border-radius:12px; overflow:hidden; margin-top:10px; border:1px solid #e6e6e6;"></div>
            <div class="tiny" style="margin-top:8px;">{{ __('booking.create.manual_latlng') }} <span id="manualLatLngText">-</span></div>
          </div>

          <div id="meetingWrap" style="display:none; margin-top:12px;">
            <label class="f-label">{{ __('booking.create.meeting_label') }}</label>
            <select name="meeting_point_id" id="meeting_point_id" class="f-input">
              <option value="">{{ __('booking.create.meeting_placeholder') }}</option>
              @foreach($meetingPoints as $mp)
                <option value="{{ $mp->id }}">{{ $mp->name }}</option>
              @endforeach
            </select>
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
            </div>
          </div>

          <button type="submit" class="btn-pay" id="btn-book">{{ __('booking.create.book') }}</button>
          <div class="tiny">{{ __('booking.create.terms_notice') }}</div>
        </div>
      </div>
    </form>
  </div>
</section>

<script>
const BOOKING_I18N = @json($bookingI18n);
</script>

<script>
(function() {
  const form = document.getElementById('booking-form');
  if (!form) return;

  form.addEventListener('submit', (e) => {
    const searchInput = document.getElementById('searchInput');
    const fullName = form.querySelector('input[name="full_name"]');
    const phone = form.querySelector('input[name="phone"]');
    const email = form.querySelector('input[name="email"]');
    const discountCode = document.getElementById('discount_code');

    const manualWrap = document.getElementById('manualWrap');
    const manualAddress = document.getElementById('manual_address');
    const meetingWrap = document.getElementById('meetingWrap');
    const meetingSelect = document.getElementById('meeting_point_id');
    const latEl = document.getElementById('google_lat');
    const lngEl = document.getElementById('google_lng');
    const outEl = document.getElementById('pickup_out_of_bounds');

    if (!searchInput || !searchInput.value.trim()) {
      e.preventDefault();
      alert(BOOKING_I18N.errors.selectHotel);
      searchInput?.focus();
      return;
    }

    if (manualWrap && manualWrap.style.display !== 'none') {
      if (!manualAddress || !manualAddress.value.trim()) {
        e.preventDefault();
        alert(BOOKING_I18N.errors.selectManualAddress);
        manualAddress?.focus();
        return;
      }
      if (!latEl || !lngEl || !latEl.value || !lngEl.value) {
        e.preventDefault();
        alert(BOOKING_I18N.errors.pinLocation);
        return;
      }
    }

    if ((meetingWrap && meetingWrap.style.display !== 'none') || (outEl && outEl.value === '1')) {
      if (!meetingSelect || !meetingSelect.value) {
        e.preventDefault();
        alert(BOOKING_I18N.errors.selectMeetingPoint);
        meetingSelect?.focus();
        return;
      }
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
window.initHotelAutocomplete = function () {
  const input = document.getElementById('searchInput');
  if (!input) return;

  const manualWrap = document.getElementById('manualWrap');
  const manualAddress = document.getElementById('manual_address');
  const manualLatLngText = document.getElementById('manualLatLngText');

  const pickupFound = document.getElementById('pickupFound');
  const meetingWrap = document.getElementById('meetingWrap');
  const meetingSelect = document.getElementById('meeting_point_id');

  const placeIdEl = document.getElementById('google_place_id');
  const placeNameEl = document.getElementById('google_place_name');
  const placeAddrEl = document.getElementById('google_place_address');
  const latEl = document.getElementById('google_lat');
  const lngEl = document.getElementById('google_lng');
  const sourceEl = document.getElementById('pickup_source');
  const outEl = document.getElementById('pickup_out_of_bounds');

  const bounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(18.760, 98.955),
    new google.maps.LatLng(18.815, 99.025)
  );

  const isWithinBounds = (lat, lng) => (
    lat >= 18.760 && lat <= 18.815 && lng >= 98.955 && lng <= 99.025
  );

  const setHidden = (el, v) => { if (el) el.value = (v ?? ''); };

  const hideAllStatus = () => {
    pickupFound.style.display = 'none';
    meetingWrap.style.display = 'none';
  };

  const clearGoogle = () => {
    setHidden(placeIdEl, '');
    setHidden(placeNameEl, '');
    setHidden(placeAddrEl, '');
  };

  const clearLatLng = () => {
    setHidden(latEl, '');
    setHidden(lngEl, '');
    setHidden(outEl, '0');
    if (manualLatLngText) manualLatLngText.textContent = '-';
  };

  const applyLatLng = (lat, lng) => {
    setHidden(latEl, lat);
    setHidden(lngEl, lng);

    const inBounds = isWithinBounds(lat, lng);
    if (inBounds) {
      pickupFound.style.display = 'block';
      meetingWrap.style.display = 'none';
      setHidden(outEl, '0');
      if (meetingSelect) meetingSelect.value = '';
    } else {
      pickupFound.style.display = 'none';
      meetingWrap.style.display = 'block';
      setHidden(outEl, '1');
    }

    if (manualLatLngText) manualLatLngText.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6);
  };

  const autocomplete = new google.maps.places.Autocomplete(input, {
    fields: ['place_id', 'name', 'formatted_address', 'geometry'],
    componentRestrictions: { country: 'th' },
    bounds: bounds,
    strictBounds: true,
    types: ['lodging'],
  });

  autocomplete.addListener('place_changed', () => {
    const place = autocomplete.getPlace();
    if (!place || !place.geometry || !place.geometry.location) return;

    if (!bounds.contains(place.geometry.location)) {
      alert(BOOKING_I18N.errors.outOfBounds);
      input.value = '';
      clearGoogle();
      clearLatLng();
      hideAllStatus();
      manualWrap.style.display = 'none';
      if (manualAddress) manualAddress.required = false;
      return;
    }

    manualWrap.style.display = 'none';
    if (manualAddress) manualAddress.required = false;

    setHidden(sourceEl, 'google');
    setHidden(placeIdEl, place.place_id || '');
    setHidden(placeNameEl, place.name || '');
    setHidden(placeAddrEl, place.formatted_address || '');

    const lat = place.geometry.location.lat();
    const lng = place.geometry.location.lng();
    applyLatLng(lat, lng);
  });

  let map = null;
  let marker = null;
  const geocoder = new google.maps.Geocoder();

  const ensureMap = () => {
    if (map) return;
    const mapEl = document.getElementById('manualMap');
    map = new google.maps.Map(mapEl, {
      center: { lat: 18.7883, lng: 98.9853 },
      zoom: 13,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false,
    });
    new google.maps.Rectangle({
      bounds: bounds,
      map: map,
      strokeColor: '#d32f2f',
      strokeOpacity: 0.9,
      strokeWeight: 2,
      fillOpacity: 0,
      clickable: false,
    });
    map.addListener('click', (e) => {
      placeMarker(e.latLng);
    });
  };

  const placeMarker = (latLng) => {
    if (!marker) {
      marker = new google.maps.Marker({ position: latLng, map: map, draggable: true });
      marker.addListener('dragend', () => {
        const p = marker.getPosition();
        applyLatLng(p.lat(), p.lng());
      });
    } else {
      marker.setPosition(latLng);
    }
    map.panTo(latLng);
    applyLatLng(latLng.lat(), latLng.lng());
  };

  const showManual = () => {
    manualWrap.style.display = 'block';
    if (manualAddress) manualAddress.required = true;
    hideAllStatus();
    clearGoogle();
    clearLatLng();
    setHidden(sourceEl, 'manual');
    ensureMap();
  };

  let t = null;
  input.addEventListener('input', () => {
    hideAllStatus();
    clearGoogle();
    setHidden(sourceEl, '');
    clearLatLng();

    if (t) clearTimeout(t);
    t = setTimeout(() => {
      const val = (input.value || '').trim();
      if (val.length >= 2) {
        if (!placeIdEl.value) showManual();
      } else {
        manualWrap.style.display = 'none';
        if (manualAddress) manualAddress.required = false;
      }
    }, 600);
  });

  input.addEventListener('blur', () => {
    const val = (input.value || '').trim();
    if (val.length >= 2 && !placeIdEl.value) {
      showManual();
    }
  });

  let gTimer = null;
  manualAddress?.addEventListener('input', () => {
    if (gTimer) clearTimeout(gTimer);
    gTimer = setTimeout(() => {
      const addr = (manualAddress.value || '').trim();
      if (!addr) return;
      geocoder.geocode({
        address: addr,
        componentRestrictions: { country: 'TH' },
      }, (results, status) => {
        if (status !== 'OK' || !results || !results[0]) return;
        const loc = results[0].geometry.location;
        ensureMap();
        map.setCenter(loc);
        map.setZoom(15);
        placeMarker(loc);
      });
    }, 700);
  });
};
</script>

<script
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initHotelAutocomplete"
  async defer></script>
@endsection
