@extends('frontend_v2.layouts.app')

@section('title', 'Home V2')
@push('styles')
<style>
.elephant-history{max-height:72px; overflow:hidden;}
</style>
@endpush

@section('content')
@php
    $heroVideo = \App\Models\PageMedia::url('v2.home.hero.video', Vite::asset('resources/frontend/images/112696-695433068_tiny.mp4'));
    $heroPoster = \App\Models\PageMedia::url('v2.home.hero.video_poster', '');
    $welcomeImageOne = \App\Models\PageMedia::url('v2.home.welcome.image_1', Vite::asset('resources/frontend/images/welcome-x1.png'));
    $welcomeImageTwo = \App\Models\PageMedia::url('v2.home.welcome.image_2', Vite::asset('resources/frontend/images/welcome-x2.png'));
    $reviewsBackground = \App\Models\PageMedia::url(
        'v2.home.reviews.background',
        'https://www.phuketelephantsanctuary.org/wp-content/uploads/sites/7659/2025/03/phuketelephantsanctuary_faq_updated_1876a9.jpg'
    );
@endphp
 
    <section class="hero-slider hero-style">
            <div class="index-video-wrapper">
                <video autoplay="" muted="" loop="" playsinline="" src="{{ $heroVideo }}"
                    @if($heroPoster) poster="{{ $heroPoster }}" @endif
                    id="index-video" type="video/mp4">
                </video>
                <article class="text-swiper">
                    <div class="slide-title">
                        <label>{{ \App\Models\SiteText::getValue('home.hero.slide_title', 'Small Elephants') }}</label>
                    </div>
                    <div class="slide-text">
                        <p>{{ \App\Models\SiteText::getValue('home.hero.slide_subtitle', 'บ้านที่ปลอดภัยสำหรับช้างที่ได้รับการช่วยเหลือ — และประสบการณ์ที่ทำให้คุณรักช้างมากขึ้นในทุกก้าวที่เดิน') }}</p>
                    </div>
                </article>
                
                
            </div>
        </section>

        <main>
            <section class="box_primary description">
                <article class="container custom-page-template">

                  {{-- About Small Elephants --}}
                  
                  <div id="home-content-1" class="home-section home-section-1">
                    <div class="">
                        <div class="row align-items-center" style="display: flex; flex-wrap: wrap;">

                          <div class="col-md-6 mobile-order2">
                              <div class="row">
                                <div class="col-6 col-md-6 margin-tn-30">
                                    <img src="{{ $welcomeImageOne }}" alt="Welcome 1">
                                </div>
                                <!-- /col-md-6 -->
                                <div class="col-6 col-md-6">
                                    <img src="{{ $welcomeImageTwo }}" alt="Welcome 2">
                                </div>
                                <!-- /col-md-6 -->
                              </div>
                              <!-- /row -->
                          </div>


                          <!-- <div class="col-md-3">
                              <img src="./index_files/images/welcome-2.png" alt="Welcome 1">
                          </div> -->
                          <!-- /col-md-3 -->
                          <div class="col-md-6 mobile-order1">
                              <div class="alignc">
                                <div class="smalltitle margin-b16">{{ \App\Models\SiteText::getValue('home.welcome.kicker', 'Welcome') }}</div>
                                <h2 class="home-title" style="font-size: 40px;">{{ \App\Models\SiteText::getValue('home.welcome.title', 'Elephants in Phuket') }}</h2>
                                <p>{{ \App\Models\SiteText::getValue('home.welcome.body', 'Experience an unforgettable moment with elephants, learning their lives and ethical care up close.') }}</p>
                                
                              </div>
                          </div>
                          <!-- /col-md-6 -->
                          <!-- <div class="col-md-3">
                              <img src="./index_files/images/welcome-4.png" alt="Welcome 2">
                          </div> -->
                          <!-- /col-md-3 -->
                        </div>
                        <!-- /row -->
                    </div>
                    <!-- /container -->
                  </div>
                  
                </article>

            </section>


            <section class="main ">
                <div class="our-facilities">
                    <article class="container">
                        <h2 data-scroll="" data-scroll-speed="1">{{ \App\Models\SiteText::getValue('home.experience.title', 'Experience') }}</h2>
                        <div class="container-content">
                            <p data-scroll="" data-scroll-speed="1"></p>
                        </div>
                    </article>
                </div>
                <div class="detail-facilities">
                    <div class="col-xs-10">
                        <div class="slider_facilities">
                            @foreach($tours as $tour)
                                <div class="item">
                                    <div class="box-facilities">
                                        <div class="col-xs-5">
                                            <figure>
                                                <img
                                                    src="{{ $tour->thumbnail ? asset($tour->thumbnail) : asset('images/placeholder-tour.jpg') }}"
                                                    alt="{{ $tour->name }}"
                                                >
                                            </figure>
                                        </div>
                                        <div class="col-xs-7">
                                            <div class="content-facilities">
                                                <hgroup>
                                                    <h3>{{ $tour->name }}</h3>
                                                    <h4>{{ $tour->duration ?? '' }}</h4>
                                                </hgroup>
                                                <p>{{ $tour->excerpt ?? '' }}</p>
                                                <a href="{{ route('frontend.tours.show.v2', $tour->slug) }}" class="btn-primary" aria-label="Discover">
                                                    {{ \App\Models\SiteText::getValue('home.actions.book_now', 'Book Now') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
            <br><br><br><br>

                        <section class="main ">
                <article class="our-offers">
                    <div class="container">
                        <div class="box_primary">
                            <h2 data-scroll="" data-scroll-speed="1">{{ \App\Models\SiteText::getValue('home.meet.title', 'Meet Our Elephants') }}</h2>
                            <div class="detail-offers">
                                <div class="slide_item1">
                                    @if($elephants->count() > 0)
                                        @foreach($elephants as $elephant)
                                            <div class="item">
                                                <figure>
                                                    <img class="image-offers" src="{{ $elephant->images[0] ?? '' }}" alt="{{ $elephant->name }}">
                                                    <figcaption class="content-offers">
                                                        <div class="col-xs-4">
                                                            <hgroup>
                                                                <h3>{{ strtoupper($elephant->getTranslated('name', $elephant->name)) }}</h3>
                                                                <h4>
                                                                    @if($elephant->rescued_at)
                                                                        {{ __('common.rescued') }} {{ $elephant->rescued_at->format('d F Y') }}
                                                                    @else
                                                                        {{ __('common.rescued') }} DATE UNKNOWN
                                                                    @endif
                                                                </h4>
                                                            </hgroup>
                                                        </div>
                                                        <div class="col-xs-8">
                                                            <p class="elephant-history">{{ $elephant->getTranslated('history', $elephant->history) }}</p>
                                                            <a href="{{ route('frontend.elephants.v2') }}#{{ $elephant->slug ?? '' }}" class="btn-book" aria-label="Read more" style="margin-top: 10px">
                                                                <span>{{ \App\Models\SiteText::getValue('home.actions.read_more', 'Read more') }}</span>
                                                            </a>
                                                        </div>
                                                    </figcaption>
                                                </figure>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="item">
                                            <figure>
                                                <img class="image-offers" src="https://www.sametnangsheboutique.com/assets/images/offers/img-1.jpg?v=0.1" alt="No elephants">
                                                <figcaption class="content-offers">
                                                    <div class="col-xs-4">
                                                        <hgroup>
                                                            <h3>No Elephants</h3>
                                                        </hgroup>
                                                    </div>
                                                    <div class="col-xs-8">
                                                        <p>No elephants available at the moment.</p>
                                                    </div>
                                                </figcaption>
                                            </figure>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </section>


            <section class="box_primary google-reviews-section parallax" id="home-content-6" style="background-image:url('{{ $reviewsBackground }}');">
                <article class="container custom-page-template" style=" max-width: 1350px;">

                  {{-- About Small Elephants --}}

                  <div class="our-facilities" style="padding: 0px 0px 0px;">
                    <article class="container">
                        <h2 class="gr-title">{{ \App\Models\SiteText::getValue('home.reviews.title', 'What Our Customers Say') }}</h2>
                        
                    </article>
                </div>
                <br><br>

                {{-- แถบสรุปด้านบน --}}
                <div class="gr-summary">
                  <div class="gr-summary-left">
                    <div class="gr-google">Google <span>Reviews</span></div>
                    <div class="gr-score">
                      <span id="gr-rating" class="gr-rating">0.0</span>
                      <span id="gr-stars" class="gr-stars">☆☆☆☆☆</span>
                      <span id="gr-total" class="gr-total">(0)</span>
                    </div>
                  </div>

                  <a id="gr-btn" href="#" target="_blank" class="gr-btn">
                    Review us on Google
                  </a>
                </div>

                {{-- Slider --}}
                <div id="google-review-slider" class="owl-carousel owl-theme testimonial-slider"></div>
                  
                  
                  
                </article>

            </section>

           
            
        </main>


        @push('scripts')
<script>
document.addEventListener("DOMContentLoaded", async () => {
  const sliderEl = document.getElementById('google-review-slider');
  if (!sliderEl) return;

  try {
    const res = await fetch('/api/google-reviews');
    const data = await res.json();

    // summary
    const rating = data.rating ?? 0;
    const total = data.user_ratings_total ?? 0;
    const googleUrl = data.google_url ?? 'https://g.page/r/Cde9V0h9AFuTEAI/review';

    document.getElementById('gr-rating').textContent = Number(rating).toFixed(1);
    document.getElementById('gr-total').textContent = `(${total.toLocaleString()})`;
    document.getElementById('gr-btn').href = googleUrl;

    const fullStars = Math.round(rating);
    document.getElementById('gr-stars').textContent =
      "★".repeat(fullStars) + "☆".repeat(5 - fullStars);

    // items
    if (!data.reviews || !data.reviews.length) {
      sliderEl.innerHTML = `
        <div class="item">
          <div class="testimonial-box" style="text-align:center;">
            <h4 style="margin:0 0 10px;font-weight:900;">Be the first to review us ⭐</h4>
            <a href="${googleUrl}" target="_blank" class="gr-btn" style="display:inline-block;">
              Write Review
            </a>
          </div>
        </div>
      `;
    } else {
      let html = "";
      data.reviews.forEach(r => {
        const stars = "★".repeat(r.rating) + "☆".repeat(5 - r.rating);
        html += `
  <div class="item">
    <div class="testimonial-box">
      <div class="testimonial-author">
        <div class="gr-avatar-wrap">
          <img src="${r.profile_photo_url ?? ''}" alt="">
        </div>
        <div>
          <h5>${r.author_name ?? ''}</h5>
          <span>${r.relative_time_description ?? ''}</span>
        </div>
      </div>
      <div class="testimonial-rating">${stars}</div>
      <p>${r.text ?? ''}</p>
    </div>
  </div>
`;
      });
      sliderEl.innerHTML = html;
    }

    // init owl (ต้องมั่นใจว่า jQuery มาแล้ว)
    if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.owlCarousel) {
      console.error('jQuery / owlCarousel not loaded. Check script paths.');
      return;
    }

    window.jQuery('#google-review-slider').owlCarousel({
  loop: true,
  margin: 24,
  autoplay: true,
  autoplayTimeout: 5000,
  autoplayHoverPause: true,
  nav: true,
  dots: true,
  navText: [
    '<span class="gr-nav gr-prev">‹</span>',
    '<span class="gr-nav gr-next">›</span>'
  ],
  responsive: {
    0:    { items: 1 },
    768:  { items: 2 },
    1024: { items: 3 },
    1280: { items: 4 }
  }
});

  } catch (e) {
    console.error(e);
  }
});

document.addEventListener("DOMContentLoaded", () => {
  if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.owlCarousel) {
    return;
  }

  const $elephant = window.jQuery('.elephant-slider');
  if (!$elephant.length) return;

  $elephant.owlCarousel({
    loop: true,
    margin: 24,
    autoplay: true,
    autoplayTimeout: 5000,
    autoplayHoverPause: true,
    nav: true,
    dots: true,
    navText: [
      '<span class="gr-nav gr-prev">‹</span>',
      '<span class="gr-nav gr-next">›</span>'
    ],
    responsive: {
      0: { items: 1 },
      768: { items: 2 },
      1024: { items: 3 }
    }
  });
});

document.addEventListener('click', function(e){
  const btn = e.target.closest('.gr-more');
  if(!btn) return;
  const full = decodeURIComponent(btn.dataset.full || '');
  alert(full); // ถ้าอยากสวย ทำ modal bootstrap ต่อได้
});
</script>

@endpush

@endsection











