@php
    $leadHours = (float) ($tour->pickup_lead_hours ?? 0);
    $leadLabel = rtrim(rtrim(number_format($leadHours, 2), '0'), '.');
@endphp

<div class="alert {{ $leadHours > 0 ? 'alert-primary' : 'alert-secondary' }} d-flex align-items-center mb-4">
    <i class="ki-duotone ki-time fs-2x me-3">
        <span class="path1"></span><span class="path2"></span>
    </i>
    <div>
        <div class="fw-bold">เวลารับลูกค้า (Pickup time)</div>
        @if ($leadHours > 0)
            <div class="fs-6">
                ลูกค้าต้องมาถึงจุดรับส่งเวลา
                <span class="fw-bold fs-4 js-pickup-time">--:--</span>
                <span class="text-muted">(ก่อนเริ่มทัวร์ {{ $leadLabel }} ชม. ตามที่ตั้งไว้ในโปรแกรมทัวร์)</span>
            </div>
        @else
            <div class="fs-6">
                โปรแกรมนี้ยังไม่ได้ตั้งชั่วโมงก่อนเริ่มทัวร์
                <a href="{{ route('admin.tours.edit', $tour->id) }}">ตั้งค่าที่หน้าแก้ไขโปรแกรม</a>
                แล้วระบบจะคำนวณเวลารับให้เอง
            </div>
        @endif
    </div>
</div>

@if ($leadHours > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const leadMinutes = Math.round({{ $leadHours }} * 60);
            const startInput = document.querySelector('input[name="start_time"]');
            const output = document.querySelector('.js-pickup-time');

            if (!startInput || !output) {
                return;
            }

            function render() {
                const match = startInput.value.match(/^(\d{1,2}):(\d{2})$/);

                if (!match) {
                    output.textContent = '--:--';
                    return;
                }

                const minutes = (Number(match[1]) * 60 + Number(match[2]) - leadMinutes + 1440) % 1440;
                const pad = (n) => String(n).padStart(2, '0');

                output.textContent = pad(Math.floor(minutes / 60)) + ':' + pad(minutes % 60);
            }

            startInput.addEventListener('input', render);
            render();
        });
    </script>
@endif
