{{--
  Marketing tags, all of them set in admin → Tracking & Tags.

  Nothing here fires on its own: analytics and ads storage stay denied until the
  visitor accepts in the cookie banner, which then calls gtag/fbq again from
  resources/js/cookie-consent.js.
--}}
@php($tracking = \App\Models\TrackingSetting::current())

@if($tracking->facebookDomainVerification())
<meta name="facebook-domain-verification" content="{{ $tracking->facebookDomainVerification() }}">
@endif

@if($tracking->ga4Id() || $tracking->googleAdsId() || $tracking->gtmId() || $tracking->metaPixelId() || $tracking->tiktokPixelId())
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}

  // What the visitor has already agreed to, read straight from the banner's
  // cookie so the very first page view uses it.
  window.__consent = (function () {
    var state = { analytics: false, marketing: false };
    try {
      var match = document.cookie.match(/(?:^|;\s*)cc_cookie=([^;]*)/);
      var saved = match && JSON.parse(decodeURIComponent(match[1]));
      var categories = (saved && saved.categories) || [];
      state.analytics = categories.indexOf('analytics') !== -1;
      state.marketing = categories.indexOf('marketing') !== -1;
    } catch (e) {}
    return state;
  })();

  // Consent Mode v2.
  gtag('consent', 'default', {
    ad_storage: window.__consent.marketing ? 'granted' : 'denied',
    ad_user_data: window.__consent.marketing ? 'granted' : 'denied',
    ad_personalization: window.__consent.marketing ? 'granted' : 'denied',
    analytics_storage: window.__consent.analytics ? 'granted' : 'denied',
    wait_for_update: 500
  });

  gtag('js', new Date());
</script>
@endif

@if($tracking->ga4Id() || $tracking->googleAdsId())
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $tracking->ga4Id() ?: $tracking->googleAdsId() }}"></script>
<script>
  @if($tracking->ga4Id())
  gtag('config', @json($tracking->ga4Id()));
  @endif
  @if($tracking->googleAdsId())
  gtag('config', @json($tracking->googleAdsId()));
  @endif
</script>
@endif

@if($tracking->gtmId())
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});
var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer',@json($tracking->gtmId()));</script>
@endif

@if($tracking->metaPixelId())
<script>
  !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
  n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
  document,'script','https://connect.facebook.net/en_US/fbevents.js');

  // Held back until the visitor accepts marketing cookies.
  if (!window.__consent || !window.__consent.marketing) { fbq('consent', 'revoke'); }
  fbq('init', @json($tracking->metaPixelId()));
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id={{ $tracking->metaPixelId() }}&ev=PageView&noscript=1"></noscript>
@endif

@if($tracking->tiktokPixelId())
<script>
  !function (w, d, t) { w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=r+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};
    ttq.load(@json($tracking->tiktokPixelId()));
    ttq.page();
  }(window, document, 'ttq');
</script>
@endif

@if($tracking->head_html)
{!! $tracking->head_html !!}
@endif
