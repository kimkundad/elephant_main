<div>
    <!-- Well begun is half done. - Aristotle -->
</div>
@php
  $siteSetting = \App\Models\SiteSetting::first();
  $logoUrl = $siteSetting?->logo_header_url ?: asset('img/logo.webp');
  $pickupTime = $booking->pickupTime();
  $whatsappUrl = $siteSetting?->whatsappUrl();
  $whatsappLabel = $siteSetting?->whatsappLabel();
@endphp

<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
<body style="margin:0;background:#f6f9fc;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica,Arial;">
  <div style="max-width:680px;margin:0 auto;padding:32px 16px;">
    <div style="background:#0b1220;padding:22px 0;border-radius:14px 14px 0 0;text-align:center;margin:-28px -28px 24px -28px;">
      <img src="{{ $logoUrl }}" alt="Small Elephants Sanctuary" style="height:48px;display:inline-block;">
    </div>

    <div style="background:#fff;border-radius:14px;box-shadow:0 8px 24px rgba(0,0,0,.06);padding:28px;">
      <h1 style="margin:0 0 8px;font-size:26px;color:#1a1f36;">Booking Confirmed 🎉</h1>
      <p style="margin:0 0 18px;color:#425466;line-height:1.6;">
        Hi {{ $booking->customer_name }}, your payment was successful. Please keep this QR code for check-in.
      </p>

      <div style="background:#f6f9fc;border:1px solid #e6ebf1;border-radius:12px;padding:16px;margin:18px 0;">
        <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">PROGRAM</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:600;">{{ $booking->tour?->nameIn('en') ?? '-' }}@if($booking->tour?->province) ({{ $booking->tour->province->name('en') }})@endif</div>
          </div>
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">DATE & TIME</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:600;">{{ $booking->date }} / {{ $booking->session?->time_range ?: '-' }}</div>
          </div>
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">PICKUP</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:600;">{{ $booking->pickupLabel() }}</div>
            @if($booking->pickupDetail())
              <div style="font-size:13px;color:#425466;white-space:pre-line;">{{ $booking->pickupDetail() }}</div>
            @endif
            @if($pickupTime)
              <div style="font-size:13px;color:#0f7a43;font-weight:700;margin-top:4px;">{{ $booking->pickupTimeLabel('en') }}: {{ $pickupTime }}</div>
            @endif
          </div>
          <div>
            <div style="font-size:12px;color:#6b7c93;margin-bottom:4px;">TOTAL</div>
            <div style="font-size:16px;color:#1a1f36;font-weight:700;">THB {{ number_format($booking->grand_total ?? $booking->total_price ?? 0, 2) }}</div>
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
          <div style="color:#425466;line-height:1.6;font-size:14px;">Staff can scan this QR code to verify your booking status instantly.</div>

          <a href="{{ $publicUrl }}" style="display:inline-block;margin-top:12px;background:#635bff;color:#fff;text-decoration:none;padding:10px 14px;border-radius:10px;font-weight:600;">
            View booking details
          </a>
        </div>
      </div>

      @if($whatsappUrl)
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin-top:22px;border-collapse:separate;">
          <tr>
            <td style="background:#e7fbf1;border:1px solid #25d366;border-radius:12px;padding:18px 20px;">
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                  <td style="vertical-align:middle;">
                    <div style="font-size:12px;letter-spacing:.06em;color:#0f7a43;font-weight:700;margin-bottom:4px;">WHATSAPP</div>
                    <div style="font-size:16px;color:#0b3b25;font-weight:600;line-height:1.5;">Need help before your trip?</div>
                    <div style="font-size:14px;color:#2f6b4f;line-height:1.6;">Chat with our team{{ $whatsappLabel ? ' at ' . $whatsappLabel : '' }}</div>
                  </td>
                  <td style="vertical-align:middle;text-align:right;white-space:nowrap;padding-left:12px;">
                    <a href="{{ $whatsappUrl }}" style="display:inline-block;background:#25d366;color:#fff;text-decoration:none;padding:12px 18px;border-radius:999px;font-weight:700;font-size:14px;">
                      Chat on WhatsApp
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      @endif

      <hr style="border:none;border-top:1px solid #e6ebf1;margin:22px 0;">

      <p style="margin:0;color:#6b7c93;font-size:12px;line-height:1.6;">If you have any questions, reply to this email
        @if($whatsappUrl) or message us on <a href="{{ $whatsappUrl }}" style="color:#0f7a43;font-weight:600;text-decoration:none;">WhatsApp</a>@endif.</p>
    </div>
  </div>
</body>
</html>
