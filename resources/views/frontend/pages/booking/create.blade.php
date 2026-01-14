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

        <form method="POST" action="{{ route('frontend.booking.store') }}" class="booking-grid">
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
                        <label class="form-label">ค้นหาโรงแรม / สถานที่ (Google)</label>
                        <input id="searchInput"
                            type="text"
                            class="f-input"
                            placeholder="เช่น Centara, Akyra, Maya Mall"
                            autocomplete="off">
                    </div>

                    {{-- hidden fields จาก Google --}}
                    <input type="hidden" name="google_place_id" id="google_place_id" value="">
                    <input type="hidden" name="google_place_name" id="google_place_name" value="">
                    <input type="hidden" name="google_place_address" id="google_place_address" value="">
                    <input type="hidden" name="google_lat" id="google_lat" value="">
                    <input type="hidden" name="google_lng" id="google_lng" value="">

                    {{-- ผลตรวจสอบกับ DB --}}
                    <input type="hidden" name="pickup_location_id" id="pickup_location_id" value="">

                    <div id="pickupFound" class="tiny" style="display:none; margin-top:8px;">
                        ✅ โรงแรมนี้อยู่ในเขตรับส่งของเรา
                    </div>

                    {{-- ถ้าไม่เจอใน DB -> ให้เลือก Meeting Point --}}
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

                    <div class="sum-total">
                        <span>Total</span>
                        <strong>THB <span id="sum-total">0</span></strong>
                    </div>

                    <button type="submit" class="btn-pay">Book</button>

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
            const PRICES = {
                adult: {{ (int) $prices['adult'] }},
                child: {{ (int) $prices['child'] }},
                infant: 0
            };

            // Adjust these if needed
            const VAT_RATE = 0.07; // 7%
            const FEE_FLAT = 0; // e.g. 50

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
                const total = subtotal + vat + fee;

                document.getElementById('sum-subtotal').textContent = money(subtotal);
                document.getElementById('sum-vat').textContent = money(vat);
                document.getElementById('sum-fee').textContent = money(fee);
                document.getElementById('sum-total').textContent = money(total);
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

            calc();
        })();
    </script>

   <script>
  function initHotelAutocomplete() {
    const input = document.getElementById('searchInput');
    if (!input) return;

    const pickupId = document.getElementById('pickup_location_id');
    const meetingWrap = document.getElementById('meetingWrap');
    const meetingSelect = document.getElementById('meeting_point_id');
    const pickupFound = document.getElementById('pickupFound');

    const gPlaceId = document.getElementById('google_place_id');
    const gName = document.getElementById('google_place_name');
    const gAddr = document.getElementById('google_place_address');
    const gLat = document.getElementById('google_lat');
    const gLng = document.getElementById('google_lng');

    // ✅ จำกัดให้โชว์ผลในเชียงใหม่ (ถ้าต้องการ)
    // ถ้าอยากปล่อยทั้งประเทศ ลบบรรทัด componentRestrictions ออก
    const autocomplete = new google.maps.places.Autocomplete(input, {
      fields: ['place_id', 'name', 'formatted_address', 'geometry'],
      componentRestrictions: { country: 'th' },
      // types: ['establishment'] // ใช้ได้ แต่บางทีหาโรงแรมไม่เจอ ลองเปิด/ปิดดู
    });

    autocomplete.addListener('place_changed', async () => {
      const place = autocomplete.getPlace();
      if (!place || !place.name) return;

      // เก็บข้อมูลจาก Google
      gPlaceId.value = place.place_id || '';
      gName.value = place.name || '';
      gAddr.value = place.formatted_address || '';
      gLat.value = place.geometry?.location?.lat?.() ?? '';
      gLng.value = place.geometry?.location?.lng?.() ?? '';

      // reset สถานะ DB
      pickupId.value = '';
      pickupFound.style.display = 'none';
      meetingWrap.style.display = 'none';
      meetingSelect.value = '';

      // ✅ ตรวจสอบกับ DB ด้วยชื่อโรงแรม (exact match)
      try {
        const res = await fetch(`{{ route('frontend.pickup-locations.resolve') }}?name=${encodeURIComponent(place.name)}`);
        const data = await res.json();

        if (data && data.found) {
          // เจอใน DB -> ใช้ pickup_location_id
          pickupId.value = data.id;
          pickupFound.style.display = 'block';
          meetingWrap.style.display = 'none';
          meetingSelect.value = '';
        } else {
          // ไม่เจอ -> ให้เลือก meeting point
          meetingWrap.style.display = 'block';
        }
      } catch (e) {
        // ถ้า resolve ล้มเหลว -> ให้เลือก meeting point กันพลาด
        meetingWrap.style.display = 'block';
      }
    });
  }
</script>


{{-- Google hotel search --}}
<script>
window.initHotelAutocomplete = function () {
  const input = document.getElementById('searchInput');
  if (!input) return;

  // ===== Chiang Mai City bounds =====
  const chiangMaiBounds = new google.maps.LatLngBounds(
    new google.maps.LatLng(18.730, 98.930), // SW
    new google.maps.LatLng(18.840, 99.050)  // NE
  );

  const autocomplete = new google.maps.places.Autocomplete(input, {
    fields: ['place_id', 'name', 'formatted_address', 'geometry'],
    componentRestrictions: { country: 'th' },
    bounds: chiangMaiBounds,
    strictBounds: true, // ⭐ สำคัญ: นอกกรอบไม่แสดง
    types: ['establishment'], // โรงแรม/สถานที่
  });

  autocomplete.addListener('place_changed', async () => {
    const place = autocomplete.getPlace();
    if (!place || !place.geometry) return;

    // 🔒 ตรวจซ้ำว่าอยู่ใน bounds จริง
    if (!chiangMaiBounds.contains(place.geometry.location)) {
      alert('กรุณาเลือกสถานที่ภายในตัวเมืองเชียงใหม่เท่านั้น');
      input.value = '';
      return;
    }

    // ===== เก็บข้อมูลจาก Google =====
    document.getElementById('google_place_id').value = place.place_id || '';
    document.getElementById('google_place_name').value = place.name || '';
    document.getElementById('google_place_address').value = place.formatted_address || '';
    document.getElementById('google_lat').value = place.geometry.location.lat();
    document.getElementById('google_lng').value = place.geometry.location.lng();

    // ===== ตรวจสอบกับ DB =====
    try {
      const res = await fetch(
        `{{ route('frontend.pickup-locations.resolve') }}?name=${encodeURIComponent(place.name)}`
      );
      const data = await res.json();

      const pickupFound = document.getElementById('pickupFound');
      const meetingWrap = document.getElementById('meetingWrap');
      const pickupId = document.getElementById('pickup_location_id');

      pickupFound.style.display = 'none';
      meetingWrap.style.display = 'none';
      pickupId.value = '';

      if (data && data.found) {
        pickupId.value = data.id;
        pickupFound.style.display = 'block';
      } else {
        meetingWrap.style.display = 'block';
      }
    } catch (e) {
      document.getElementById('meetingWrap').style.display = 'block';
    }
  });
};
</script>


{{-- โหลด Google Places (ต้องใส่ key จริง) --}}
<script
  src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initHotelAutocomplete"
  async defer></script>






@endsection
