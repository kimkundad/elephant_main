{{--
  Reports a paid booking to whichever tags the admin has switched on.

  The booking code goes in as the transaction id, so a guest who reloads the
  page or comes back to it later is still counted once by GA4, Meta and TikTok.
--}}
@php
    $tracking = \App\Models\TrackingSetting::current();
    $reportable = $tracking->sendsPurchase() && $booking->payment_status === 'paid';

    if ($reportable) {
        $value = round((float) ($booking->grand_total ?: $booking->total_price), 2);
        $tourName = $booking->tour?->nameIn('en') ?: 'Tour';

        $order = [
            'id' => (string) $booking->public_code,
            'value' => $value,
            'currency' => 'THB',
            'name' => $tourName,
            'tourId' => (string) $booking->tour_id,
            'guests' => (int) $booking->total_guests,
        ];

        $items = [[
            'item_id' => (string) $booking->tour_id,
            'item_name' => $tourName,
            'price' => $value,
            'quantity' => 1,
        ]];
    }
@endphp

@if($reportable)
<script>
  (function () {
    var order = {!! json_encode($order) !!};

    if (typeof gtag === 'function') {
      @if($tracking->ga4Id())
      gtag('event', 'purchase', {
        transaction_id: order.id,
        value: order.value,
        currency: order.currency,
        items: {!! json_encode($items) !!}
      });
      @endif

      @if($tracking->googleAdsSendTo())
      gtag('event', 'conversion', {
        send_to: {!! json_encode($tracking->googleAdsSendTo()) !!},
        transaction_id: order.id,
        value: order.value,
        currency: order.currency
      });
      @endif
    }

    @if($tracking->metaPixelId())
    if (typeof fbq === 'function') {
      fbq('track', 'Purchase', {
        value: order.value,
        currency: order.currency,
        content_type: 'product',
        content_name: order.name,
        content_ids: [order.tourId],
        num_items: order.guests
      }, { eventID: order.id });
    }
    @endif

    @if($tracking->tiktokPixelId())
    if (window.ttq) {
      ttq.track('CompletePayment', {
        content_id: order.tourId,
        content_name: order.name,
        content_type: 'product',
        quantity: order.guests,
        value: order.value,
        currency: order.currency
      }, { event_id: order.id });
    }
    @endif
  })();
</script>
@endif
