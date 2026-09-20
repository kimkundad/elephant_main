@extends('partials.admin.template')

@php
    /** Money in short form: 12,500 / 1.2M */
    $money = fn ($value) => number_format((float) $value, 0);

    $changeBadge = function (?float $change) {
        if ($change === null) {
            return ['badge-light-secondary', '—'];
        }

        $class = $change >= 0 ? 'badge-light-success' : 'badge-light-danger';
        $sign = $change >= 0 ? '+' : '';

        return [$class, $sign . $change . '%'];
    };

    // Build the 12-month area chart as inline SVG: no chart library needed.
    $chartWidth = 720;
    $chartHeight = 220;
    $rows = $chart['rows'];
    $step = $rows->count() > 1 ? $chartWidth / ($rows->count() - 1) : $chartWidth;

    $points = $rows->map(function ($row, $index) use ($chart, $chartHeight, $step) {
        $x = round($index * $step, 1);
        $y = round($chartHeight - ($row['revenue'] / $chart['max_revenue'] * ($chartHeight - 20)), 1);

        return $x . ',' . $y;
    })->implode(' ');

    $areaPoints = $rows->count() > 0
        ? '0,' . $chartHeight . ' ' . $points . ' ' . round(($rows->count() - 1) * $step, 1) . ',' . $chartHeight
        : '';
@endphp

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    <div class="app-toolbar py-3 py-lg-6">
      <div class="app-container container-xxl d-flex flex-stack flex-wrap gap-3">
        <div class="page-title d-flex flex-column justify-content-center">
          <h1 class="page-heading text-dark fw-bold fs-2 my-0">ภาพรวมระบบ</h1>
          <div class="text-muted fs-7 mt-1">ข้อมูล ณ {{ now()->format('d/m/Y H:i') }}</div>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light">การจองทั้งหมด</a>
          <a href="{{ route('admin.bookings.create') }}" class="btn btn-sm btn-primary">+ สร้าง Booking</a>
        </div>
      </div>
    </div>

    <div class="app-content flex-column-fluid">
      <div class="app-container container-xxl">

        {{-- KPI --}}
        <div class="row g-5 g-xl-6 mb-6">
          @php([$revenueClass, $revenueText] = $changeBadge($kpi['revenue_change']))
          @php([$bookingClass, $bookingText] = $changeBadge($kpi['bookings_change']))

          <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
              <div class="card-body">
                <div class="text-muted fs-7">รายได้เดือนนี้ (ชำระแล้ว)</div>
                <div class="d-flex align-items-end justify-content-between mt-2">
                  <span class="fs-2 fw-bold text-dark">฿{{ $money($kpi['revenue']) }}</span>
                  <span class="badge {{ $revenueClass }}">{{ $revenueText }}</span>
                </div>
                <div class="text-muted fs-8 mt-1">เทียบเดือนก่อน</div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
              <div class="card-body">
                <div class="text-muted fs-7">การจองเดือนนี้</div>
                <div class="d-flex align-items-end justify-content-between mt-2">
                  <span class="fs-2 fw-bold text-dark">{{ number_format($kpi['bookings']) }}</span>
                  <span class="badge {{ $bookingClass }}">{{ $bookingText }}</span>
                </div>
                <div class="text-muted fs-8 mt-1">เทียบเดือนก่อน</div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
              <div class="card-body">
                <div class="text-muted fs-7">ผู้เข้าร่วมทัวร์วันนี้</div>
                <div class="fs-2 fw-bold text-dark mt-2">{{ number_format($kpi['guests_today']) }} คน</div>
                <div class="text-muted fs-8 mt-1">
                  {{ $kpi['departures_today'] }} การจอง &middot;
                  เช็คอินแล้ว {{ $kpi['checked_in_today'] }}/{{ $kpi['departures_today'] }}
                </div>
              </div>
            </div>
          </div>

          <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
              <div class="card-body">
                <div class="text-muted fs-7">รอชำระเงิน</div>
                <div class="fs-2 fw-bold text-dark mt-2">{{ number_format($kpi['unpaid']) }}</div>
                <div class="text-muted fs-8 mt-1">รวม ฿{{ $money($kpi['unpaid_amount']) }}</div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-5 g-xl-6">

          {{-- CHART --}}
          <div class="col-xl-8">
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">รายได้และการจอง 12 เดือน</h3>
              </div>
              <div class="card-body">
                <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" preserveAspectRatio="none"
                     style="width:100%;height:220px;" role="img" aria-label="กราฟรายได้ 12 เดือน">
                  <defs>
                    <linearGradient id="revenueFill" x1="0" x2="0" y1="0" y2="1">
                      <stop offset="0%" stop-color="#3e97ff" stop-opacity="0.35"></stop>
                      <stop offset="100%" stop-color="#3e97ff" stop-opacity="0"></stop>
                    </linearGradient>
                  </defs>
                  @if($areaPoints)
                    <polygon points="{{ $areaPoints }}" fill="url(#revenueFill)"></polygon>
                    <polyline points="{{ $points }}" fill="none" stroke="#3e97ff" stroke-width="3"
                              stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke"></polyline>
                  @endif
                </svg>

                <div class="d-flex justify-content-between text-muted fs-8 mt-2">
                  @foreach($chart['rows'] as $row)
                    <span>{{ $row['label'] }}</span>
                  @endforeach
                </div>

                <div class="separator my-5"></div>

                <div class="table-responsive">
                  <table class="table table-row-dashed align-middle mb-0 fs-7">
                    <thead>
                      <tr class="text-muted">
                        <th>เดือน</th>
                        @foreach($chart['rows'] as $row)
                          <th class="text-end">{{ $row['label'] }}</th>
                        @endforeach
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="text-muted">รายได้ (฿)</td>
                        @foreach($chart['rows'] as $row)
                          <td class="text-end">{{ $money($row['revenue']) }}</td>
                        @endforeach
                      </tr>
                      <tr>
                        <td class="text-muted">การจอง</td>
                        @foreach($chart['rows'] as $row)
                          <td class="text-end">{{ $row['bookings'] }}</td>
                        @endforeach
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            {{-- UPCOMING --}}
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">ทัวร์วันนี้และพรุ่งนี้</h3>
              </div>
              <div class="card-body pt-4">
                @forelse($upcoming as $departure)
                  <div class="d-flex flex-stack flex-wrap gap-2 mb-5">
                    <div class="me-3">
                      <div class="fw-bold text-dark">{{ $departure['tour'] }}</div>
                      <div class="text-muted fs-7">
                        {{ \Carbon\Carbon::parse($departure['date'])->format('d/m/Y') }}
                        &middot; {{ $departure['session'] }} {{ $departure['time'] }}
                        &middot; {{ $departure['bookings'] }} การจอง
                        &middot; เช็คอิน {{ $departure['checked_in'] }}/{{ $departure['bookings'] }}
                      </div>
                    </div>
                    <div class="text-end" style="min-width:180px;">
                      <div class="fw-bold">{{ $departure['guests'] }}/{{ $departure['capacity'] ?: '-' }} คน</div>
                      <div class="progress h-6px mt-2" style="width:180px;">
                        <div class="progress-bar {{ $departure['percent'] >= 90 ? 'bg-danger' : 'bg-primary' }}"
                             style="width: {{ $departure['percent'] }}%"></div>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="text-muted">ไม่มีทัวร์ในวันนี้และพรุ่งนี้</div>
                @endforelse
              </div>
            </div>

            {{-- RECENT BOOKINGS --}}
            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">การจองล่าสุด</h3>
                <div class="card-toolbar">
                  <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light">ดูทั้งหมด</a>
                </div>
              </div>
              <div class="card-body pt-4 table-responsive">
                <table class="table table-row-dashed align-middle mb-0">
                  <thead>
                    <tr class="text-muted fs-7">
                      <th>รหัส</th>
                      <th>ลูกค้า</th>
                      <th>ทัวร์ / วันที่</th>
                      <th class="text-end">ยอด</th>
                      <th class="text-end">สถานะ</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentBookings as $booking)
                      <tr>
                        <td>
                          <a href="{{ route('admin.bookings.show', $booking->id) }}" class="fw-bold">
                            #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}
                          </a>
                        </td>
                        <td>{{ $booking->customer_name ?? $booking->customer?->full_name ?? '-' }}</td>
                        <td>
                          <div>{{ $booking->tour?->name ?? '-' }}</div>
                          <div class="text-muted fs-8">
                            {{ \Carbon\Carbon::parse($booking->date)->format('d/m/Y') }} {{ $booking->session?->time_range }}
                          </div>
                        </td>
                        <td class="text-end">฿{{ $money($booking->grand_total ?? $booking->total_price) }}</td>
                        <td class="text-end">
                          <span class="badge {{ $booking->payment_status === 'paid' ? 'badge-light-success' : ($booking->payment_status === 'failed' ? 'badge-light-danger' : 'badge-light-warning') }}">
                            {{ $booking->payment_status ?? '-' }}
                          </span>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="5" class="text-center text-muted py-6">ยังไม่มีการจอง</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          {{-- SIDE --}}
          <div class="col-xl-4">

            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">สิ่งที่ต้องจัดการ</h3>
              </div>
              <div class="card-body pt-4">
                <div class="d-flex flex-stack mb-4">
                  <a href="{{ route('admin.reviews.index') }}" class="text-muted">รีวิวที่ยังไม่เผยแพร่</a>
                  <span class="badge {{ $attention['reviews_pending'] > 0 ? 'badge-light-warning' : 'badge-light-success' }}">
                    {{ $attention['reviews_pending'] }}
                  </span>
                </div>
                <div class="d-flex flex-stack mb-4">
                  <span class="text-muted">ข้อความติดต่อ (7 วัน)</span>
                  <span class="badge badge-light-primary">{{ $attention['contacts_new'] }}</span>
                </div>

                <div class="separator my-4"></div>

                <div class="text-muted fs-7 mb-3">Error ล่าสุด (Stripe / อีเมล)</div>
                @forelse($attention['errors'] as $log)
                  <div class="mb-3">
                    <div class="fw-bold fs-7 text-danger">{{ $log->channel }} &middot; {{ $log->event }}</div>
                    <div class="text-muted fs-8 text-truncate" title="{{ $log->message }}">{{ $log->message }}</div>
                    <div class="text-muted fs-8">{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</div>
                  </div>
                @empty
                  <div class="text-muted fs-7">ไม่มี error ใน 7 วันที่ผ่านมา</div>
                @endforelse
              </div>
            </div>

            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">ทัวร์ขายดีเดือนนี้</h3>
              </div>
              <div class="card-body pt-4">
                @forelse($topTours as $tour)
                  <div class="d-flex flex-stack mb-4">
                    <div class="me-3">
                      <div class="fw-bold text-dark">{{ $tour['name'] }}</div>
                      <div class="text-muted fs-8">
                        {{ $tour['guests'] }} คน
                        @if($tour['province']) &middot; {{ $tour['province'] }} @endif
                      </div>
                    </div>
                    <div class="text-end">
                      <div class="fw-bold">{{ $tour['bookings'] }} การจอง</div>
                      <div class="text-muted fs-8">฿{{ $money($tour['revenue']) }}</div>
                    </div>
                  </div>
                @empty
                  <div class="text-muted">ยังไม่มีการจองเดือนนี้</div>
                @endforelse
              </div>
            </div>

            <div class="card mb-6">
              <div class="card-header">
                <h3 class="card-title fw-bold">สัดส่วนเดือนนี้</h3>
              </div>
              <div class="card-body pt-4">
                <div class="text-muted fs-7 mb-3">ช่องทางชำระเงิน</div>
                @forelse($channels as $channel)
                  <div class="mb-4">
                    <div class="d-flex flex-stack fs-7">
                      <span>{{ $channel['label'] === 'unknown' ? 'ไม่ระบุ' : strtoupper($channel['label']) }}</span>
                      <span class="fw-bold">{{ $channel['count'] }} ({{ $channel['percent'] }}%)</span>
                    </div>
                    <div class="progress h-6px mt-2">
                      <div class="progress-bar bg-primary" style="width: {{ $channel['percent'] }}%"></div>
                    </div>
                  </div>
                @empty
                  <div class="text-muted fs-7 mb-4">ยังไม่มีข้อมูล</div>
                @endforelse

                <div class="separator my-4"></div>

                <div class="text-muted fs-7 mb-3">จังหวัด</div>
                @forelse($provinces as $province)
                  <div class="mb-4">
                    <div class="d-flex flex-stack fs-7">
                      <span>{{ $province['label'] }}</span>
                      <span class="fw-bold">{{ $province['count'] }} ({{ $province['percent'] }}%)</span>
                    </div>
                    <div class="progress h-6px mt-2">
                      <div class="progress-bar bg-success" style="width: {{ $province['percent'] }}%"></div>
                    </div>
                  </div>
                @empty
                  <div class="text-muted fs-7">ยังไม่มีข้อมูล</div>
                @endforelse
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
