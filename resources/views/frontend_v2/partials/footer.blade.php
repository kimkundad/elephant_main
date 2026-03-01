<footer>
    @php
        $localizedAddress = app()->getLocale() === 'th'
            ? ($siteSetting?->address_th ?? $siteSetting?->address ?? '')
            : ($siteSetting?->address_en ?? $siteSetting?->address ?? '');
    @endphp
    <article class="title-footer">
        <div class="logo-footer">
            <a href="{{ route('frontend.home') }}" aria-label="Small Elephants">
                @if(!empty($siteSetting?->logo_footer_url))
                    <img src="{{ $siteSetting->logo_footer_url }}" alt="Small Elephants">
                @else
                    <img src="{{ asset('samet/assets/logo.png') }}" alt="Small Elephants">
                @endif
            </a>
        </div>
        <div class="social-footer">
            @if(!empty($siteSetting?->facebook_url))
                <a href="{{ $siteSetting->facebook_url }}" target="_blank" rel="noopener">Facebook</a>
            @endif
            @if(!empty($siteSetting?->instagram_url))
                <a href="{{ $siteSetting->instagram_url }}" target="_blank" rel="noopener">Instagram</a>
            @endif
        </div>
    </article>
    <article class="contact-footer">
        <div class="contact-footer-row">
            <div class="col-xs-4">
                <h4>Quick link</h4>
                <ul class="menu-footer">
                    <li><a href="{{ route('frontend.home') }}">Home</a></li>
                    <li><a href="{{ route('frontend.program.v2') }}">Programs</a></li>
                    <li><a href="{{ route('frontend.about.v2') }}">About</a></li>
                    <li><a href="{{ route('frontend.contact.v2') }}">Contact</a></li>
                </ul>
            </div>
            <div class="col-xs-4">
                <h4>Contact Us</h4>
                <p>{!! nl2br(e($localizedAddress)) !!}</p>
                <p>
                    <a href="tel:{{ $siteSetting->phone ?? '' }}">T : {{ $siteSetting->phone ?? '-' }}</a><br>
                    <a href="mailto:{{ $siteSetting->email ?? '' }}">M : {{ $siteSetting->email ?? '-' }}</a>
                </p>
            </div>
            <div class="col-xs-4">
                <h4>More Info</h4>
                <ul class="menu-footer">
                    <li><a href="{{ route('frontend.what_to_bring.v2') }}">What to Bring</a></li>
                    <li><a href="{{ route('frontend.support_us.v2') }}">How to Support Us</a></li>
                    <li><a href="{{ route('frontend.terms.v2') }}">Terms &amp; Conditions</a></li>
                    <li><a href="{{ route('frontend.policy.v2') }}">Policy</a></li>
                </ul>
            </div>
        </div>
        <div class="copy-right">
            {{ $siteSetting->copyright_text ?? '&copy; 2026 Small Elephants. All Rights Reserved.' }}
        </div>
    </article>
</footer>


