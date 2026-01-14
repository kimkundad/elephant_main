@extends('frontend.layouts.app')

@section('content')
<div class="container" style="margin-top:120px; margin-bottom:80px;">

  <div class="row">
    <div class="col-md-12 alignc margin-b48">
      <div class="smalltitle margin-b16">Tours</div>
      <h1 class="home-title">Our Elephant Tours</h1>
    </div>
  </div>

  <div class="row">
    @forelse($tours as $tour)
      <div class="col-md-4 mobile-margin-b32">
        <article class="blog-item blog-item-3col-grid tour-card">

          <a href="{{ route('frontend.tours.show', $tour->slug) }}">
            <div class="post-image">
              <img
                src="{{ $tour->thumbnail }}"
                class="img-fluid"
                alt="{{ $tour->name }}"
              >
            </div>
          </a>

          <div class="post-holder post-content content-grid">
            <h2 class="article-title">
              <a href="{{ route('frontend.tours.show', $tour->slug) }}">
                {{ $tour->name }}
              </a>
            </h2>

            <p>
              {{ \Illuminate\Support\Str::limit(strip_tags($tour->short_description ?? $tour->description), 120) }}
            </p>

            <div class="margin-t16">
              <span class="tour-price-badge">
                From THB {{ number_format($tour->min_price ?? 0) }}
              </span>
            </div>

            <a class="view-more margin-t16"
               href="{{ route('frontend.tours.show', $tour->slug) }}">
              View Details
            </a>
          </div>

        </article>
      </div>
    @empty
      <div class="col-md-12 alignc">
        <p>No tours available at the moment.</p>
      </div>
    @endforelse
  </div>

  <div class="alignc margin-t36">
    {{ $tours->links() }}
  </div>

</div>
@endsection
