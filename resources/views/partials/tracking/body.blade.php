{{-- The pieces that belong just inside <body>, set in admin → Tracking & Tags. --}}
@php($tracking = \App\Models\TrackingSetting::current())

@if($tracking->gtmId())
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $tracking->gtmId() }}"
  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

@if($tracking->body_html)
{!! $tracking->body_html !!}
@endif
