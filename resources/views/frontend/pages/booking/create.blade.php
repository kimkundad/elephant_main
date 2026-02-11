@extends('frontend.layouts.app')

@section('content')
    <div class="container" style="margin-top:120px; margin-bottom:80px;">

        {{-- Header summary --}}
        <div class="booking-header">
            <div class="booking-hero">
                <div class="booking-hero-img">
                    <img src="{{ $tour->thumbnail }}" alt="{{ $tour->name }}">
                </div>
                <div class="booking-hero-meta">
                    <div class="booking-hero-title">{{ $tour->name }}</div>
                    <div class="booking-hero-sub">
                        {{ \Carbon\Carbon::parse($date)->format('l, d F Y') }}
                        @ {{ \Carbon\Carbon::parse($session->start_time)->format('g:ia') }}
                        @if (!empty($session->end_time))
                            – {{ \Carbon\Carbon::parse($session->end_time)->format('g:ia') }}
                        @endif
                    </div>
                    <div class="booking-hero-desc">{{ $tour->short_description }}</div>
                </div>
            </div>
        </div>


        <form method="POST" action="{{ route('frontend.booking.store') }}" class="booking-grid" id="booking-form">
            @csrf

            <input type="hidden" name="tour_id" value="{{ $tour->id }}">
            <input type="hidden" name="session_id" value="{{ $session->id }}">
            <input type="hidden" name="date" value="{{ $date }}">

            {{-- LEFT --}}
            <div class="booking-left">
                <div class="card">
                    <div class="card-title">Plan your experience</div>

                    <div class="qty-row">
                        <div class="qty-label">
                            <div class="qty-name">Adults</div>
                            <div class="qty-sub">Ages 13+</div>
                        </div>
                        <div class="qty-price">THB <span id="price-adult">{{ number_format($prices['adult']) }}</span>
                        </div>
                        <div class="qty-ctrl">
                            <button type="button" class="qty-btn" data-target="adult" data-delta="-1">−</button>
                            <input type="number" name="qty_adult" id="qty-adult" value="1" min="0"
                                class="qty-input">
                            <button type="button" class="qty-btn" data-target="adult" data-delta="1">+</button>
                        </div>
                    </div>

                    <div class="qty-row">
                        <div class="qty-label">
                            <div class="qty-name">Children</div>
                            <div class="qty-sub">Ages 4–12</div>
                        </div>
                        <div class="qty-price">THB <span id="price-child">{{ number_format($prices['child']) }}</span>
                        </div>
                        <div class="qty-ctrl">
                            <button type="button" class="qty-btn" data-target="child" data-delta="-1">−</button>
                            <input type="number" name="qty_child" id="qty-child" value="0" min="0"
                                class="qty-input">
                            <button type="button" class="qty-btn" data-target="child" data-delta="1">+</button>
                        </div>
                    </div>

                    <div class="qty-row">
                        <div class="qty-label">
                            <div class="qty-name">Infants</div>
                            <div class="qty-sub">Ages 0–3</div>
                        </div>
                        <div class="qty-price">Free</div>
                        <div class="qty-ctrl">
                            <button type="button" class="qty-btn" data-target="infant" data-delta="-1">−</button>
                            <input type="number" name="qty_infant" id="qty-infant" value="0" min="0"
                                class="qty-input">
                            <button type="button" class="qty-btn" data-target="infant" data-delta="1">+</button>
                        </div>
                    </div>
                </div>
                

                <div class="card">
                    <div class="card-title">Additional information</div>

                    <label class="f-label">Hotel Pick up & Drop Off</label>

                    <div class="mb-3" style="position:relative;">
                        <label class="form-label">ค้นหาโรงแรม / ที่พัก (Google) — เฉพาะในตัวเมืองเชียงใหม่</label>
                        <input id="searchInput"
                            type="text"
                            class="f-input"
                            placeholder="พิมพ์ชื่อโรงแรม/ที่พัก แล้วเลือกจากรายการ"
                            autocomplete="off"
                            required>

                        <div class="tiny" style="margin-top:8px; color:#666;">
                            * ถ้าไม่พบในรายการ (เช่น Airbnb) ระบบจะให้กรอกที่อยู่และปักหมุดแทน
                        </div>
                    </div>

                    {{-- hidden fields (Google / Manual pin) --}}
                    <input type="hidden" name="google_place_id" id="google_place_id" value="">
                    <input type="hidden" name="google_place_name" id="google_place_name" value="">
                    <input type="hidden" name="google_place_address" id="google_place_address" value="">
                    <input type="hidden" name="google_lat" id="google_lat" value="">
                    <input type="hidden" name="google_lng" id="google_lng" value="">
                    <input type="hidden" name="pickup_source" id="pickup_source" value="">
                    <input type="hidden" name="pickup_out_of_bounds" id="pickup_out_of_bounds" value="0">

                    {{-- ✅ อยู่ในเขตรับส่ง --}}
                    <div id="pickupFound" class="tiny" style="display:none; margin-top:8px;">
                        ✅ โรงแรมนี้อยู่ในเขตรับส่งของเรา
                    </div>

                    {{-- ❌ ไม่พบใน Google -> กรอกที่อยู่ + ปักหมุด --}}
                    <div id="manualWrap" style="display:none; margin-top:12px;">
                        <label class="f-label">If your hotel is not listed, please provide address below:</label>
                            <input type="text" name="manual_address" id="manual_address" class="f-input"
                               placeholder="เช่น M Social Hotel, 199 Soi Rat Uthit 200 Pi 1, ...">

                        <div class="tiny" style="margin-top:8px; color:#666;">
                            กรุณาปักหมุดที่พักของคุณให้ตรง เพื่อให้เราตรวจสอบเขตรับ-ส่งได้
                        </div>

                        <div id="manualMap" style="height:260px; border-radius:12px; overflow:hidden; margin-top:10px; border:1px solid #e6e6e6;"></div>

                        <div class="tiny" style="margin-top:8px; color:#666;">
                            พิกัดที่ปัก: <span id="manualLatLngText">-</span>
                        </div>
                    </div>

                    {{-- ถ้า “อยู่นอกเขตรับส่ง” -> ให้เลือก Meeting Point --}}
                    <div id="meetingWrap" style="display:none; margin-top:12px;">
                        <label class="f-label">โรงแรมนี้อยู่นอกเขตรับส่ง กรุณาเลือก “จุดนัดรับ”</label>
                        <select name="meeting_point_id" id="meeting_point_id" class="f-input">
                            <option value="">Please select meeting point</option>
                            @foreach($meetingPoints as $mp)
                                <option value="{{ $mp->id }}">{{ $mp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="card">
                    <div class="card-title">Contact details</div>

                    <div class="grid-2">
                        <div>
                            <label class="f-label">Full name *</label>
                            <input type="text" name="full_name" class="f-input" required>
                        </div>
                        <div>
                            <label class="f-label">Phone number *</label>
                            <input type="text" name="phone" class="f-input" required>
                        </div>
                    </div>

                    <label class="f-label">Email address *</label>
                    <input type="email" name="email" class="f-input" required>

                    <label class="checkbox">
                        <input type="checkbox" name="newsletter" value="1">
                        <span>Get future email updates</span>
                    </label>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="booking-right">
                <div class="card sticky">
                    <div class="card-title">Summary</div>

                    <div class="sum-row">
                        <span>Subtotal</span>
                        <strong>THB <span id="sum-subtotal">0</span></strong>
                    </div>
                    <div class="sum-row">
                        <span>VAT</span>
                        <span>THB <span id="sum-vat">0</span></span>
                    </div>
                    <div class="sum-row">
                        <span>Fees</span>
                        <span>THB <span id="sum-fee">0</span></span>
                    </div>
                    <div class="sum-row">
                        <span>Discount</span>
                        <span>THB <span id="sum-discount">0</span></span>
                    </div>

                    <div class="sum-total">
                        <span>Total</span>
                        <strong>THB <span id="sum-total">0</span></strong>
                    </div>

                    <div class="card" style="margin-top:12px;">
                    <div class="card-title">Discount code</div>
                    <div style="display:flex; gap:8px; align-items:center;">
                        <input type="text" name="discount_code" id="discount_code" class="f-input" placeholder="กรอกโค้ดส่วนลด">
                        <button type="button" id="apply_discount" class="btn-pay" style="padding:10px 14px; width:auto;">Apply</button>
                    </div>
                    <div id="discount_msg" class="tiny" style="margin-top:6px; color:#666;"></div>
                    </div>

                    {{-- Payment details --}}
                    <div class="card" style="margin-top:12px;">
                    <div class="card-title">Payment details</div>
                   

                    <div style="margin-top:10px;">
                        <label class="f-label">Payment method</label>
                        <select name="payment_channel" class="f-input" required>
                        <option value="card">Credit/Debit Card</option>
                        <option value="promptpay">QR Code (PromptPay)</option>
                        </select>
                    </div>
                    </div>

                    <button type="submit" class="btn-pay" id="btn-book">Book</button>

                    <div class="tiny">
                        By completing this transaction, you agree to our booking terms.
                    </div>
                </div>
            </div>

        </form>
    </div>


        {{-- Simple pricing JS --}}
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
                    alert('กรุณากรอกชื่อโรงแรม/ที่พัก');
                    searchInput?.focus();
                    return;
                }

                if (manualWrap && manualWrap.style.display !== 'none') {
                    if (!manualAddress || !manualAddress.value.trim()) {
                        e.preventDefault();
                        alert('กรุณากรอกที่อยู่ที่พัก');
                        manualAddress?.focus();
                        return;
                    }
                    if (!latEl || !lngEl || !latEl.value || !lngEl.value) {
                        e.preventDefault();
                        alert('กรุณาปักหมุดที่พักของคุณบนแผนที่');
                        return;
                    }
                }

                if ((meetingWrap && meetingWrap.style.display !== 'none') || (outEl && outEl.value === '1')) {
                    if (!meetingSelect || !meetingSelect.value) {
                        e.preventDefault();
                        alert('กรุณาเลือกจุดนัดรับ');
                        meetingSelect?.focus();
                        return;
                    }
                }

                if (!fullName || !fullName.value.trim()) {
                    e.preventDefault();
                    alert('กรุณากรอกชื่อ-นามสกุล');
                    fullName?.focus();
                    return;
                }

                if (!phone || !phone.value.trim()) {
                    e.preventDefault();
                    alert('กรุณากรอกเบอร์โทรศัพท์');
                    phone?.focus();
                    return;
                }

                if (!email || !email.value.trim()) {
                    e.preventDefault();
                    alert('กรุณากรอกอีเมล');
                    email?.focus();
                    return;
                }

                if (window.bookingTotalAfterDiscount !== undefined && window.bookingTotalAfterDiscount < 10) {
                    e.preventDefault();
                    alert('ยอดชำระหลังหักส่วนลดต้องไม่น้อยกว่า 10 บาท');
                    return;
                }

                if (discountCode && discountCode.value.trim() && !window.bookingDiscountValid) {
                    e.preventDefault();
                    alert('โค้ดส่วนลดไม่ถูกต้องหรือหมดอายุแล้ว');
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

            // Adjust these if needed
            const VAT_RATE = 0.07; // 7%
            const FEE_FLAT = 0; // e.g. 50
            const MIN_CHARGE = 10;

            function clampMin0(n) {
                return Math.max(0, parseInt(n || 0, 10));
            }

            function money(n) {
                return (Math.round(n)).toLocaleString('en-US');
            }

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
                        msg.textContent = 'ยอดชำระหลังหักส่วนลดต้องไม่น้อยกว่า 10 บาท';
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
                if (window.updateBookingTotals) {
                    window.updateBookingTotals();
                }
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
                if (window.updateBookingTotals) {
                    window.updateBookingTotals();
                }
            };

            codeInput.addEventListener('input', () => {
                resetState();
            });

            applyBtn.addEventListener('click', async () => {
                const code = codeInput.value.trim();
                if (!code) {
                    setState(false, 'กรุณากรอกโค้ดส่วนลด');
                    return;
                }

                applyBtn.disabled = true;
                applyBtn.textContent = 'Checking...';

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
                        setState(false, data.message || 'โค้ดส่วนลดไม่ถูกต้องหรือหมดอายุแล้ว');
                    } else {
                        setState(true, 'โค้ดส่วนลดใช้ได้', data.amount || 0);
                    }
                } catch (e) {
                    setState(false, 'ไม่สามารถตรวจสอบโค้ดส่วนลดได้');
                } finally {
                    applyBtn.disabled = false;
                    applyBtn.textContent = 'Apply';
                }
            });
        })();
    </script>

{{-- Google hotel search + Manual pin --}}
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

  // ===== Chiang Mai City bounds =====
  const bounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(18.760, 98.955), // SW (tighter)
    new google.maps.LatLng(18.815, 99.025)  // NE (tighter)
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

  // ========= Google Autocomplete (lodging only) =========
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

    // ตรวจซ้ำว่าอยู่ใน bounds จริง
    if (!bounds.contains(place.geometry.location)) {
      alert('กรุณาเลือกโรงแรม/ที่พักภายในตัวเมืองเชียงใหม่เท่านั้น');
      input.value = '';
      clearGoogle();
      clearLatLng();
      hideAllStatus();
      manualWrap.style.display = 'none';
      if (manualAddress) manualAddress.required = false;
      return;
    }

    // hide manual (เพราะเลือกจาก Google สำเร็จ)
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

  // ========= Manual map pin =========
  let map = null;
  let marker = null;
  const geocoder = new google.maps.Geocoder();

  let boundsRect = null;

  const ensureMap = () => {
    if (map) return;

    const mapEl = document.getElementById('manualMap');
    map = new google.maps.Map(mapEl, {
      center: { lat: 18.7883, lng: 98.9853 }, // Chiang Mai (center-ish)
      zoom: 13,
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: false,
    });

    boundsRect = new google.maps.Rectangle({
      bounds: bounds,
      map: map,
      strokeColor: '#d32f2f',
      strokeOpacity: 0.9,
      strokeWeight: 2,
      fillOpacity: 0,
      clickable: false,
    });

    // คลิกเพื่อปักหมุด
    map.addListener('click', (e) => {
      placeMarker(e.latLng);
    });
  };

  const placeMarker = (latLng) => {
    if (!marker) {
      marker = new google.maps.Marker({
        position: latLng,
        map: map,
        draggable: true,
      });
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

  // ถ้าพิมพ์แล้วไม่ได้เลือกจากรายการ -> แสดง manual
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

  // geocode ที่อยู่คร่าวๆ เพื่อเลื่อนแผนที่ (ถ้าหาได้)
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

        // ปักให้ก่อน 1 ครั้ง แล้ว user ลากแก้ได้
        placeMarker(loc);
      });
    }, 700);
  });
};
</script>

{{-- โหลด Google Places (ต้องใส่ key จริง) --}}
<script
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initHotelAutocomplete"
  async defer></script>






@endsection
