@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">รายงานพนักงานขาย</h1>
        </div>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card mb-7">
          <div class="card-body">
            <form method="GET" action="{{ route('admin.reports.agents') }}" class="row g-3 align-items-end">
              <div class="col-md-3">
                <label class="form-label">วันที่เริ่มต้น</label>
                <input type="date" name="start_date" class="form-control" value="{{ $start }}">
              </div>
              <div class="col-md-3">
                <label class="form-label">วันที่สิ้นสุด</label>
                <input type="date" name="end_date" class="form-control" value="{{ $end }}">
              </div>
              <div class="col-md-3">
                <label class="form-label">Payment status</label>
                <select name="payment_status" class="form-select">
                  <option value="" {{ $paymentFilter ? '' : 'selected' }}>All</option>
                  <option value="paid" {{ $paymentFilter === 'paid' ? 'selected' : '' }}>Paid only</option>
                  <option value="unpaid" {{ $paymentFilter === 'unpaid' ? 'selected' : '' }}>Unpaid only</option>
                </select>
              </div>
              <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary" type="submit">กรอง</button>
                <a class="btn btn-light" href="{{ route('admin.reports.agents') }}">ล้างค่า</a>
                <a class="btn btn-success"
                   href="{{ route('admin.reports.agents.export', ['start_date' => $start, 'end_date' => $end, 'payment_status' => $paymentFilter]) }}">
                  Export CSV
                </a>
              </div>
            </form>
          </div>
        </div>

        <div class="card mb-7">
          <div class="card-body">
            <h3 class="mb-4">สรุปยอดขายตามพนักงาน</h3>
            <table class="table table-bordered align-middle">
              <thead>
                <tr>
                  <th>พนักงานขาย</th>
                  <th>Sales (paid)</th>
                  <th>Discount (paid)</th>
                  <th>Total bookings</th>
                  <th>Paid</th>
                  <th>Unpaid</th>
                </tr>
              </thead>
              <tbody>
                @forelse($summary as $row)
                  <tr>
                    <td>{{ $row['agent']->name }}</td>
                    <td>{{ number_format($row['total_sales'], 2) }}</td>
                    <td>{{ number_format($row['total_discount'], 2) }}</td>
                    <td>{{ $row['booking_count'] }}</td>
                    <td>{{ $row['paid_count'] }}</td>
                    <td>{{ $row['unpaid_count'] }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center">ยังไม่มีข้อมูล</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <div class="card mb-7">
          <div class="card-body">
            <h3 class="mb-4">รายการจองที่ใช้โค้ดส่วนลด</h3>
            <table class="table table-bordered align-middle">
              <thead>
                <tr>
                  <th>Booking</th>
                  <th>ทัวร์</th>
                  <th>ลูกค้า</th>
                  <th>โค้ด</th>
                  <th>ส่วนลด</th>
                  <th>พนักงานขาย</th>
                  <th>ยอดสุทธิ</th>
                  <th>Payment status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($bookingsWithDiscount as $b)
                  <tr>
                    <td>#{{ $b->id }}</td>
                    <td>{{ $b->tour?->name }}</td>
                    <td>{{ $b->customer_name ?? $b->customer?->full_name }}</td>
                    <td>{{ $b->discount_code }}</td>
                    <td>{{ number_format($b->discount_amount ?? 0, 2) }}</td>
                    <td>{{ $b->agent?->name ?? '-' }}</td>
                    <td>{{ number_format($b->grand_total ?? 0, 2) }}</td>
                    <td>{{ $b->payment_status ?? '-' }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="8" class="text-center">ยังไม่มีรายการที่ใช้ส่วนลด</td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
