{{--
  A round flag, drawn inline.

  Emoji flags are no good here: Windows ships no flag glyphs, so Chrome renders
  🇬🇧 as the letters "GB" for most of our visitors. Plain SVG looks the same
  everywhere. English uses the Union Jack rather than any one country's flag.
--}}
@php($size = $size ?? 24)

<svg class="flag-icon" width="{{ $size }}" height="{{ $size }}" viewBox="0 0 32 32" aria-hidden="true" focusable="false">
    <defs>
        <clipPath id="flag-clip-{{ $locale }}-{{ $size }}">
            <circle cx="16" cy="16" r="16"/>
        </clipPath>
    </defs>
    <g clip-path="url(#flag-clip-{{ $locale }}-{{ $size }})">
        @if($locale === 'th')
            {{-- Red, white, blue, white, red, the blue band double height. --}}
            <rect width="32" height="32" fill="#A51931"/>
            <rect y="5.33" width="32" height="21.34" fill="#F4F5F8"/>
            <rect y="10.67" width="32" height="10.66" fill="#2D2A4A"/>
        @else
            <rect width="32" height="32" fill="#012169"/>
            <path d="M0 0 32 32 M32 0 0 32" stroke="#FFF" stroke-width="7"/>
            <path d="M0 0 32 32 M32 0 0 32" stroke="#C8102E" stroke-width="2.6"/>
            <path d="M16 0v32 M0 16h32" stroke="#FFF" stroke-width="10.6"/>
            <path d="M16 0v32 M0 16h32" stroke="#C8102E" stroke-width="6.4"/>
        @endif
    </g>
</svg>
