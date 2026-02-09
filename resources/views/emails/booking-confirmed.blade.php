<div>
    <!-- Well begun is half done. - Aristotle -->
</div>
@php
  // ใส่โลโก้คุณ: แนะนำให้เป็น URL แบบ https หรือ asset() ที่ชี้ไปไฟล์ public
  $logoUrl = asset('img/logo.webp');
@endphp

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="margin:0;background:#f6f9fc;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial;">
  <div style="max-width:680px;margin:0 auto;padding:32px 16px;">
    
        <!-- Logo Header -->
        <div style="
        background:#0b1220;
        padding:22px 0;
        border-radius:14px 14px 0 0;
        text-align:center;
        margin:-28px -28px 24px -28px;
        ">
        <img src="{{ $logoUrl }}"
            alt="Phuket Elephant Sanctuary"
            style="height:48px;display:inline-block;">
        </div>

    <div style="background:#fff;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.06);padding:28px;">
      <h1 style="margin:0 0 8px;font-size:26px;color:#1a1f36;">
        Booking Confirmed 🎉
      </h1>
      <p style="margin:0 0 18px;color:#425466;line-height:1.6;">
        Hi {{ $booking->customer_name }}, your payment was successful. Please keep this QR code for check-in.
      </p>

      <div style="background:#f6f9fc;border:1px solid #e6ebf1;border-radius:12px;padding:16px;margin:18px 0;">
        <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">PROGRAM</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:600;">
              {{ $booking->tour?->name ?? '-' }}
            </div>
          </div>
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">DATE & TIME</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:600;">
              {{ $booking->date }} / {{ $booking->session?->start_time ?? '-' }}
            </div>
          </div>
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">TOTAL</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:700;">
              ฿{{ number_format($booking->grand_total ?? $booking->total_price ?? 0, 2) }}
            </div>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:18px;align-items:center;flex-wrap:wrap;margin-top:14px;">
        <div style="background:#fff;border:1px solid #e6ebf1;border-radius:12px;padding:14px;">
          @php
            /** @var \Illuminate\Mail\Message $message */
            $qrCid = $message->embedData($qrPngBinary, 'qrcode.png', 'image/png');
          @endphp

<img src="{{ $qrCid }}" alt="QR Code" style="width:170px;height:170px;display:block;">
        </div>
        <div style="flex:1;min-width:220px;">
          <div style="font-size:13px;color:#6b7c93;margin-bottom:6px;">SCAN AT CHECK-IN</div>
          <div style="color:#425466;line-height:1.6;font-size:14px;">
            Staff can scan this QR code to verify your booking status instantly.
          </div>

          <a href="{{ $publicUrl }}"
             style="display:inline-block;margin-top:12px;background:#635bff;color:#fff;text-decoration:none;padding:10px 14px;border-radius:10px;font-weight:600;">
            View booking details
          </a>
        </div>
      </div>

      <hr style="border:none;border-top:1px solid #e6ebf1;margin:22px 0;">

      <p style="margin:0;color:#6b7c93;font-size:12px;line-height:1.6;">
        If you have any questions, reply to this email.
      </p>
    </div>
  </div>
</body>
</html>
