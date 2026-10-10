{{-- The tour cards, on their own so a filter change can fetch just these. --}}
      @forelse($tours as $tour)
        @php($tr = $tour->translation())
        <div class="program-item js-program-item" data-tour-tags="{{ json_encode($tour->tags->pluck('slug')->values()->all()) }}">
          <a class="program-media" href="{{ route('frontend.tours.show.v2', $tour->slug) }}" tabindex="-1" aria-hidden="true">
            <img src="{{ $tour->thumbnail }}" alt="{{ $tr?->name ?? $tour->name }}">
          </a>
          <div class="program-content">
            <div class="program-title"><a href="{{ route('frontend.tours.show.v2', $tour->slug) }}">{{ $tr?->name ?? $tour->name }}</a></div>
            <div class="program-meta">{{ strtoupper(__('common.program')) }}@if($tour->province) &middot; {{ $tour->province->name() }}@endif</div>
            <span class="program-price">
              <b>{{ __('common.price_adult') }} THB {{ number_format($tour->price_adult ?? 0) }}</b>
              <i>{{ __('common.price_child') }} THB {{ number_format($tour->price_child ?? 0) }}</i>
            </span>
            <div class="program-desc">
              {{ \Illuminate\Support\Str::limit(strip_tags($tr?->short_description ?? $tr?->description ?? $tour->short_description ?? $tour->description ?? ''), 220) }}
            </div>
            <a class="btn-primary" href="{{ route('frontend.tours.show.v2', $tour->slug) }}">{{ __('common.book_now') }}</a>
          </div>
        </div>
      @empty
        <p>{{ __('common.no_tours_list') }}</p>
      @endforelse
