@extends('frontend.layouts.app')

@section('title', 'Home')

@push('styles')
<style>
  .google-reviews-section{
    position: relative;
    padding: 80px 0 90px;
  }

  .google-reviews-section:before{
    content:"";
    position:absolute;
    inset:0;
    background: rgba(0,0,0,.45);
    z-index:0;
  }

  .google-reviews-section .container,
  .google-reviews-section .row,
  .google-reviews-section .col-md-12{
    position:relative;
    z-index:2;
  }

  .gr-title{
    text-align:center;
    color:#fff;
    font-size:38px;
    font-weight:700;
    margin-bottom:22px;
  }

  .gr-summary{
    background: rgba(30,30,30,.85);
    border-radius:14px;
    padding:18px 20px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
    margin-bottom:22px;
  }

  .gr-google{
    color:#fff;
    font-weight:700;
    font-size:18px;
  }
  .gr-google span{ font-weight:500; opacity:.9; }

  .gr-score{
    display:flex;
    align-items:center;
    gap:10px;
    margin-top:4px;
    color:#fff;
  }
  .gr-rating{ font-size:26px; font-weight:800; }
  .gr-stars{ color:#9acd32; font-size:18px; letter-spacing:1px; }
  .gr-total{ opacity:.85; }

  .gr-btn{
    background:#9acd32;
    color:#163300;
    font-weight:800;
    padding:10px 18px;
    border-radius:999px;
    text-decoration:none;
    white-space:nowrap;
  }
  .gr-btn:hover{ opacity:.92; color:#163300; }

  .testimonial-box{
    background: rgba(25,25,25,.9);
    padding:18px;
    border-radius:14px;
    color:#fff;
    height:100%;
    min-height: 180px;
  }

  .testimonial-author{
    display:flex;
    align-items:center;
    gap:12px;
    margin-bottom:10px;
  }
  .testimonial-author img{
    width:46px;
    height:46px;
    border-radius:50%;
    object-fit:cover;
    background:#333;
  }
  .testimonial-author h5{
    font-size:14px;
    margin:0;
    font-weight:800;
    color:#fff;
  }
  .testimonial-author span{
    display:block;
    font-size:12px;
    opacity:.8;
    margin-top:2px;
  }

  .testimonial-rating{
    color:#9acd32;
    font-size:16px;
    margin-bottom:10px;
  }

  .testimonial-box p{
    font-size:13px;
    line-height:1.6;
    opacity:.95;
    margin:0;
  }
  /* card */
.gr-card{
  background: rgba(25,25,25,.9);
  border-radius: 14px;
  padding: 18px;
  color:#fff;
  min-height: 210px;
}

.gr-head{ display:flex; gap:12px; align-items:center; margin-bottom:10px; }

.gr-avatar-wrap{ position:relative; width:46px; height:46px; flex:0 0 46px; }
.gr-avatar{ width:46px; height:46px; border-radius:50%; object-fit:cover; background:#333; }

.gr-gbadge{
  position:absolute;
  left:28px;
  bottom:-2px;
  width:20px;
  height:20px;
  border-radius:50%;
  background:#fff;
  color:#1a73e8;
  font-weight:900;
  font-size:12px;
  display:flex;
  align-items:center;
  justify-content:center;
}

.gr-name{ font-weight:800; font-size:14px; line-height:1.2; }
.gr-time{ font-size:12px; opacity:.8; margin-top:2px; }

.gr-stars{ color:#9acd32; letter-spacing:1px; margin:8px 0 10px; }

/* limit text like sample */
.gr-text{
  font-size:13px;
  line-height:1.6;
  opacity:.95;
  display:-webkit-box;
  -webkit-line-clamp: 3;         /* ตัด 3 บรรทัด */
  -webkit-box-orient: vertical;
  overflow:hidden;
  min-height: 62px;              /* ให้ความสูงสม่ำเสมอ */
}

.gr-more{
  margin-top:8px;
  background:transparent;
  border:0;
  color:#9acd32;
  font-weight:800;
  padding:0;
  cursor:pointer;
}
/* place nav buttons */
#google-review-slider .owl-nav{
  position:absolute;
  top:50%;
  left:0;
  right:0;
  transform: translateY(-50%);
  pointer-events:none; /* ให้คลิกเฉพาะปุ่ม */
}

#google-review-slider .owl-nav button{
  pointer-events:auto;
  position:absolute;
  width:44px;
  height:44px;
  border-radius:50%;
  border:0;
  background:#9acd32 !important;
  color:#163300 !important;
  font-size:26px;
  font-weight:900;
  display:flex;
  align-items:center;
  justify-content:center;
  opacity:.95;
}

#google-review-slider .owl-nav .owl-prev{ left:-22px; }   /* ให้โผล่นอกกรอบซ้าย */
#google-review-slider .owl-nav .owl-next{ right:-22px; }  /* ให้โผล่นอกกรอบขวา */

#google-review-slider .owl-nav button:hover{ opacity:1; }

/* Our Tours - new layout */
.tours-section{
  padding-top: 10px;
}
.tours-heading{
  text-align:center;
  margin-bottom: 28px;
}
.tours-feature{
  display:flex;
  flex-wrap:wrap;
  background:#0c0c0c;
  color:#fff;
  border-radius:16px;
  overflow:hidden;
  margin-bottom:28px;
}
.tours-feature__media{
  flex: 1 1 56%;
  min-height: 320px;
  position:relative;
}
.tours-feature__media img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}
.tours-feature__content{
  flex: 1 1 44%;
  padding:28px 28px 24px;
  display:flex;
  flex-direction:column;
  justify-content:center;
}
.tours-kicker{
  font-size:12px;
  letter-spacing:.12em;
  text-transform:uppercase;
  color:#7fd3ff;
  font-weight:700;
  margin-bottom:8px;
}
.tours-title{
  font-size:28px;
  font-weight:700;
  margin:0 0 12px;
  color:#fff;
}
.tours-desc{
  font-size:15px;
  line-height:1.7;
  color:rgba(255,255,255,.82);
  margin-bottom:16px;
}
.tours-link{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:8px 18px;
  border-radius:999px;
  background:#a8db3a;
  color:#0f1a00;
  font-weight:700;
  text-decoration:none;
  text-transform:uppercase;
  letter-spacing:.08em;
  font-size:12px;
}
.tours-link:hover{
  background:#9acd32;
  color:#0f1a00;
}
.tours-grid{
  display:grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap:24px;
}
.tour-card{
  display:flex;
  flex-direction:column;
  gap:12px;
}
.tour-card__media{
  width:100%;
  aspect-ratio: 4 / 3;
  background:#111;
  border-radius:8px;
  overflow:hidden;
}
.tour-card__media img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}
.tour-card__title{
  font-size:18px;
  font-weight:700;
  margin:0;
  color:#111;
}
.tour-card__text{
  font-size:14px;
  line-height:1.6;
  color:#444;
  margin:0;
  display:-webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow:hidden;
}
.tour-card__link{
  align-self:flex-start;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:8px 18px;
  border-radius:999px;
  background:#a8db3a;
  color:#0f1a00;
  font-weight:700;
  text-decoration:none;
  text-transform:uppercase;
  letter-spacing:.08em;
  font-size:12px;
}
.tour-card__link:hover{ background:#9acd32; color:#0f1a00; }

@media (max-width: 1200px){
  .tours-grid{ grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media (max-width: 992px){
  .tours-feature{ border-radius:12px; }
  .tours-feature__media{ min-height: 260px; }
  .tours-grid{ grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 576px){
  .tours-feature__content{ padding:22px; }
  .tours-title{ font-size:22px; }
  .tours-grid{ grid-template-columns: 1fr; }
}

/* Meet Our Elephants */
.elephants-section{
  background:#e9e9e9;
  padding:70px 0;
  position:relative;
  overflow:hidden;
}
.elephants-section:before{
  content:"";
  position:absolute;
  inset:auto -10% -40% -10%;
  height:60%;
  background: radial-gradient(ellipse at center, rgba(0,0,0,0.18), rgba(0,0,0,0) 70%);
  opacity:.35;
}
.elephants-title{
  text-align:center;
  font-size:30px;
  font-weight:700;
  margin-bottom:28px;
  color:#333;
}
.elephant-slider .item{
  padding:10px 8px 20px;
}
.elephant-card{
  background:#2f2f2f;
  color:#fff;
  border-radius:12px;
  overflow:hidden;
  box-shadow:0 8px 18px rgba(0,0,0,.18);
  display:flex;
  flex-direction:column;
  height:100%;
}
.elephant-card__media{
  width:100%;
  aspect-ratio: 4 / 3;
  overflow:hidden;
}
.elephant-card__media img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}
.elephant-card__body{
  padding:16px 18px 18px;
  display:flex;
  flex-direction:column;
  gap:8px;
}
.elephant-name{
  text-align:center;
  font-size:18px;
  font-weight:700;
  letter-spacing:.08em;
}
.elephant-rescued{
  text-align:center;
  font-size:12px;
  text-transform:uppercase;
  color:#d7d7d7;
}
.elephant-desc{
  font-size:13px;
  line-height:1.6;
  color:#f1f1f1;
  display:-webkit-box;
  -webkit-line-clamp: 6;
  -webkit-box-orient: vertical;
  overflow:hidden;
  min-height: 125px;
}
.elephant-btn{
  margin-top:10px;
  align-self:center;
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding:8px 20px;
  border-radius:999px;
  background:#a8db3a;
  color:#0f1a00;
  font-weight:700;
  text-decoration:none;
  text-transform:uppercase;
  letter-spacing:.08em;
  font-size:12px;
}
.elephant-btn:hover{
  background:#9acd32;
  color:#0f1a00;
}
.elephant-slider .owl-nav{
  position:absolute;
  top:40%;
  left:-10px;
  right:-10px;
  display:flex;
  justify-content:space-between;
  pointer-events:none;
}
.elephant-slider .owl-nav button{
  pointer-events:auto;
  width:44px;
  height:44px;
  border-radius:50%;
  border:0;
  background:#fff !important;
  color:#333 !important;
  font-size:26px;
  font-weight:900;
  box-shadow:0 4px 12px rgba(0,0,0,.15);
}
.elephant-slider .owl-dots{
  margin-top:10px;
}
.elephant-slider .owl-dot span{
  width:8px;
  height:8px;
}

/* Section 4 - Ethical Promise */
.promise-section{
  position:relative;
  padding:80px 0;
  color:#fff;
  overflow:hidden;
}
.promise-card-wrap{
  background: rgba(0,0,0,.55);
  border-radius:18px;
  padding:32px 30px;
  box-shadow:0 10px 30px rgba(0,0,0,.35);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
}
.promise-kicker{
  font-size:12px;
  letter-spacing:.16em;
  text-transform:uppercase;
  color:#9acd32;
  font-weight:700;
  margin-bottom:10px;
}
.promise-title{
  font-size:36px;
  font-weight:700;
  line-height:1.15;
  margin:0 0 14px;
}
.promise-lead{
  font-size:16px;
  line-height:1.8;
  color:rgba(255,255,255,.82);
  margin-bottom:22px;
}
.promise-cta{
  display:inline-flex;
  align-items:center;
  gap:10px;
  padding:10px 22px;
  border-radius:999px;
  background:#a8db3a;
  color:#0f1a00;
  font-weight:700;
  text-decoration:none;
  text-transform:uppercase;
  letter-spacing:.08em;
  font-size:12px;
}
.promise-cta:hover{ background:#9acd32; color:#0f1a00; }
.promise-grid{
  display:grid;
  grid-template-columns: repeat(2, minmax(0,1fr));
  gap:18px;
}
.promise-card{
  background: rgba(255,255,255,.08);
  border:1px solid rgba(255,255,255,.12);
  border-radius:14px;
  padding:16px;
  min-height:140px;
  box-shadow:0 8px 18px rgba(0,0,0,.2);
}
.promise-card__title{
  font-size:14px;
  font-weight:700;
  text-transform:uppercase;
  letter-spacing:.08em;
  color:#9acd32;
  margin-bottom:6px;
}
.promise-card__text{
  font-size:14px;
  line-height:1.6;
  color:rgba(255,255,255,.85);
  margin:0;
}
.promise-note{
  margin-top:14px;
  font-size:12px;
  color:rgba(255,255,255,.7);
}
@media (max-width: 992px){
  .promise-title{ font-size:30px; }
  .promise-grid{ margin-top:20px; }
}
@media (max-width: 576px){
  .promise-title{ font-size:24px; }
  .promise-grid{ grid-template-columns: 1fr; }
}

</style>
@endpush

@section('content')


    <!-- HOME SLIDER -->
   <div class="video-container" style="    margin-bottom: 108px;">
      <video id="video" class="video" preload="metadata" autoplay="" loop="" muted=""
         poster="https://matchthemes.com/demowp/caverta/wp-content/uploads/caverta-video-img.jpg">
         <source src="{{ Vite::asset('resources/frontend/images/112696-695433068_tiny.mp4') }}" type="video/mp4">
      </video>
      <div class="slider-caption">
         <div class="slider-text">
            <div class="slider-text">
               <div class="intro-txt">Elephants in Phuket</div>
               <h2>Elephants</h2>
               <p>Small Elephants is the first ethical elephant sanctuary in Phuket.</p>
               <a href="#" class="slider-btn">Contact Us</a>
            </div>
         </div>
      </div>
   </div>
   <!-- /HOME SLIDER -->
   <!-- WRAP CONTENT -->
   <div id="wrap-content" class="page-content custom-page-template">
      <!-- SECTION 1 -->
      <div id="home-content-1" class="home-section home-section-1">
         <div class="container">
            <div class="row align-items-center">

               <div class="col-md-6 mobile-order2">
                  <div class="row">
                     <div class="col-6 col-md-6 margin-tn-30">
                        <img src="{{ Vite::asset('resources/frontend/images/welcome-x1.png') }}" alt="Welcome 1">
                     </div>
                     <!-- /col-md-6 -->
                     <div class="col-6 col-md-6">
                        <img src="{{ Vite::asset('resources/frontend/images/welcome-x2.png') }}" alt="Welcome 2">
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
                     <div class="smalltitle margin-b16">Welcome</div>
                     <h2 class="home-title">Elephants in Phuket</h2>
                     <p>For a truly memorable dining experience, cuisine and atmosphere are paired as thoughtfully as
                        food and wine. Ut enim ad minim veniam, quis nostrud exercitation ullamco. Quia consequuntur
                        magni dolores eos qui ratione voluptatem sequi nesciunt. Animi, id est laborum et dolorum fuga.
                        Nam libero.</p>
                     <p><a class="view-more margin-t24" href="">Book a Table</a></p>
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
      <!-- /SECTION 1 -->
      <!-- SECTION 2 -->
      <div id="home-content-2" class="home-section home-section-2 parallax">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="alignc">
                     <div class="smalltitle white margin-b16">Come and See</div>
                     <h2>We create delicious experiences</h2>
                  </div>
               </div>
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div>
      <!-- /SECTION 2 -->
      <!-- SECTION 3 -->


      <div id="home-content-28" class="home-section home-section-28 tours-section">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="tours-heading">
          <div class="smalltitle margin-b16">Tours</div>
          <h2 class="home-title">Our Tours</h2>
        </div>
      </div>
    </div>

    @if($tours->count() > 0)
      @php
        $featuredTour = $tours->first();
        $otherTours = $tours->slice(1);
      @endphp

      <div class="tours-feature">
        <a class="tours-feature__media" href="{{ route('frontend.tours.show', $featuredTour->slug) }}">
          <img
            src="{{ $featuredTour->thumbnail ? asset($featuredTour->thumbnail) : asset('images/placeholder-tour.jpg') }}"
            alt="{{ $featuredTour->name }}"
            loading="lazy"
          >
        </a>
        <div class="tours-feature__content">
          <div class="tours-kicker">Featured Tour</div>
          <h3 class="tours-title">{{ $featuredTour->name }}</h3>
          <p class="tours-desc">{{ $featuredTour->excerpt ?? '' }}</p>
          <a class="tours-link" href="{{ route('frontend.tours.show', $featuredTour->slug) }}">Book now ›</a>
        </div>
      </div>

      @if($otherTours->count() > 0)
      <div class="tours-grid">
        @foreach($otherTours as $tour)
        <div class="tour-card">
          <a class="tour-card__media" href="{{ route('frontend.tours.show', $tour->slug) }}">
            <img
              src="{{ $tour->thumbnail ? asset($tour->thumbnail) : asset('images/placeholder-tour.jpg') }}"
              alt="{{ $tour->name }}"
              loading="lazy"
            >
          </a>
          <h4 class="tour-card__title">{{ $tour->name }}</h4>
          <p class="tour-card__text">{{ $tour->excerpt ?? '' }}</p>
          <a class="tour-card__link" href="{{ route('frontend.tours.show', $tour->slug) }}">Book now ›</a>
        </div>
        @endforeach
      </div>
      @endif
    @else
      <div class="alignc">
        <p>ยังไม่มีโปรแกรมทัวร์ให้แสดงในตอนนี้</p>
      </div>
    @endif

  </div>
</div>

      <!-- /SECTION 3 -->
      <!-- SECTION 4 -->
      <div id="home-content-4" class="home-section promise-section parallax"  style="margin-bottom: 1px;">
         <div class="container">
            <div class="promise-card-wrap">
               <div class="row align-items-center">
                  <div class="col-lg-6">
                     <div class="promise-kicker">Ethical Sanctuary</div>
                     <h2 class="promise-title">Experience Elephants With Respect, Not Rides</h2>
                     <p class="promise-lead">
                        เรามอบประสบการณ์ที่ปลอดภัยและเคารพธรรมชาติของช้าง
                        เน้นการสังเกตและเรียนรู้ พร้อมเรื่องราวการช่วยเหลือของช้างแต่ละตัว
                     </p>
                     <a href="{{ route('frontend.tours.index') }}" class="promise-cta">Book Now</a>
                     <div class="promise-note">จองรอบล่วงหน้าเพื่อจำกัดจำนวนผู้เข้าชมในแต่ละวัน</div>
                  </div>
                  <div class="col-lg-6">
                     <div class="promise-grid">
                        <div class="promise-card">
                           <div class="promise-card__title">No Riding</div>
                           <p class="promise-card__text">เน้นการดูและเรียนรู้พฤติกรรมตามธรรมชาติ ไม่ขี่ ไม่บังคับ</p>
                        </div>
                        <div class="promise-card">
                           <div class="promise-card__title">Forest Walk</div>
                           <p class="promise-card__text">เดินป่าศึกษาธรรมชาติ พบช้างในพื้นที่เปิดอย่างเคารพ</p>
                        </div>
                        <div class="promise-card">
                           <div class="promise-card__title">Meet Their Stories</div>
                           <p class="promise-card__text">รู้จักประวัติและวันที่ได้รับการช่วยเหลือของช้างแต่ละตัว</p>
                        </div>
                        <div class="promise-card">
                           <div class="promise-card__title">Small Groups</div>
                           <p class="promise-card__text">จำกัดจำนวนผู้เข้าชมเพื่อความสงบและสวัสดิภาพของช้าง</p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div>
      <!-- /SECTION 4 -->
      <!-- SECTION 5 -->

      <div id="home-content-5" class="home-section elephants-section" style="margin-bottom: 1px;">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <h2 class="elephants-title">Meet Our Elephants</h2>
               </div>
            </div>

            @if($elephants->count() > 0)
            <div class="owl-carousel owl-theme elephant-slider">
               @foreach($elephants as $elephant)
               <div class="item">
                  <div class="elephant-card">
                     <div class="elephant-card__media">
                        <img src="{{ $elephant->images[0] ?? '' }}" alt="{{ $elephant->name }}">
                     </div>
                     <div class="elephant-card__body">
                        <div class="elephant-name">{{ strtoupper($elephant->name) }}</div>
                        <div class="elephant-rescued">
                           @if($elephant->rescued_at)
                              RESCUED {{ $elephant->rescued_at->format('d F Y') }}
                           @else
                              RESCUED DATE UNKNOWN
                           @endif
                        </div>
                        <div class="elephant-desc">{{ $elephant->history }}</div>
                        <a href="{{ route('frontend.elephants') }}#{{ $elephant->slug ?? '' }}" class="elephant-btn">Read more ›</a>
                     </div>
                  </div>
               </div>
               @endforeach
            </div>
            @else
            <div class="alignc">
               <p>ยังไม่มีข้อมูลช้างให้แสดงในตอนนี้</p>
            </div>
            @endif
         </div>
      </div>

      {{-- <div id="home-content-3" class="home-section home-section-3">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="alignc">
                     <div class="smalltitle margin-b16">Explore How We’re Different</div>
                     <h2 class="home-title">Pioneering Ethical Elephant Tourism In Phuket</h2>
                     <div class="width60">Small Elephants is the first ethical elephant sanctuary in Phuket.
                        Set on 30 acres of lush tropical jungle
                        bordering the Khao Prae Teao National Park, we provide a final home for retired working
                        elephants. Observe how elephants rehabilitate into
                        forest life after decades of hard work, and experience how incredible the largest land mammal on
                        earth is during a day at our sanctuary.</div>
                  </div>
                  <!-- MENU TAB -->
                  <ul class="nav nav-tabs menuTab" id="menuTab" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link active" id="starters-tab" data-toggle="tab" href="#starters" role="tab"
                           aria-controls="starters" aria-selected="true">STARTERS</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="main-course-tab" data-toggle="tab" href="#main-course" role="tab"
                           aria-controls="main-course" aria-selected="false">MAIN COURSE</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="soups-tab" data-toggle="tab" href="#soups" role="tab"
                           aria-controls="soups" aria-selected="false">SOUPS</a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" id="desserts-tab" data-toggle="tab" href="#desserts" role="tab"
                           aria-controls="desserts" aria-selected="false">DESSERTS</a>
                     </li>
                  </ul>
                  <div class="tab-content" id="menuTabContent">
                     <!-- STARTERS -->
                     <div class="tab-pane fade show active" id="starters" role="tabpanel"
                        aria-labelledby="starters-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Tomato Bruschetta</span><span class="menu-price">$4.00</span>
                              </h4>
                              <div class="menu-text">Tomatoes, Olive Oil, Cheese</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Avocado &amp; Mango Salsa</span><span
                                    class="menu-price">$5.00</span></h4>
                              <div class="menu-text">Avocado, Mango, Tomatoes</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Marinated Grilled Shrimp</span><span
                                    class="menu-price">$7.00</span></h4>
                              <div class="menu-text">Fresh Shrimp, Oive Oil, Tomato Sauce</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Baked Potato Skins</span><span
                                    class="menu-price">$9.00</span></h4>
                              <div class="menu-text">Potatoes, Oil, Garlic</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Maitake Mushroom</span><span class="menu-price">$10.00</span>
                              </h4>
                              <div class="menu-text">Whipped Miso, Yaki Sauce, Sesame</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Lobster Picante</span><span class="menu-price">$12.00</span>
                              </h4>
                              <div class="menu-text">Grilled Corn Elote, Queso Cotija, Chili</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Jambon Iberico</span><span class="menu-price">$10.00</span>
                              </h4>
                              <div class="menu-text">Smoked Tomato Aioli, Idizabal Cheese, Spiced Pine Nuts</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Garlic Baked Cheese</span><span
                                    class="menu-price">$12.00</span></h4>
                              <div class="menu-text">Finnish Squeaky Cheese, Eggplant Conserva, Black Pepper</div>
                           </li>
                        </ul>
                     </div>
                     <!-- MAIN COURSE -->
                     <div class="tab-pane fade" id="main-course" role="tabpanel" aria-labelledby="main-course-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Braised Pork Chops</span><span
                                    class="menu-price">$21.00</span></h4>
                              <div class="menu-text">4 bone-in pork chops, olive oil, garlic, onion </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Coconut Fried Chicken </span><span
                                    class="menu-price">$19.00</span></h4>
                              <div class="menu-text">8 chicken pieces, coconut milk, oil </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Chicken with Garlic &amp; Tomatoes </span><span
                                    class="menu-price">$15.00</span></h4>
                              <div class="menu-text">Chicken, cherry tomatoes, olive oil, dry white wine</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Prime Rib</span><span class="menu-price">$25.00</span></h4>
                              <div class="menu-text">Rib, rosemary, black pepper, red wine </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Sriracha Beef Skewers</span><span
                                    class="menu-price">$18.00</span></h4>
                              <div class="menu-text">Beef, garlic, sesame oil, vinegar </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Crispy Tuna Fregola</span><span
                                    class="menu-price">$22.00</span></h4>
                              <div class="menu-text">Fregola, Baby Arugula Roasted, Green Olives, Pine Nuts</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Charred Lamb Ribs</span><span
                                    class="menu-price">$20.00</span></h4>
                              <div class="menu-text">Za’atar, Turkish BBQ, Sesame Yoghurt</div>
                           </li>
                        </ul>
                     </div>
                     <!-- SOUPS -->
                     <div class="tab-pane fade" id="soups" role="tabpanel" aria-labelledby="soups-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Terrific Turkey Chili</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Turkey, oregano, tomato paste, peppers </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Cream of Asparagus Soup</span><span
                                    class="menu-price">$12.00</span></h4>
                              <div class="menu-text">Asparagus, potato, celery, onion, pepper </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Creamy Chicken &amp; Wild Rice Soup</span><span
                                    class="menu-price">$9.00</span></h4>
                              <div class="menu-text">Cooked chicken, salt, butter, heavy cream </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Italian Sausage Tortellini</span><span
                                    class="menu-price">$8.00</span></h4>
                              <div class="menu-text">Cheese tortellini, sausage, garlic, carrots, zucchini</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Italian Sausage Soup</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Italian sausage, garlic, carrots, zucchini </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Ham and Potato Soup</span><span
                                    class="menu-price">$11.00</span></h4>
                              <div class="menu-text">Potatoes, ham, celery, onion, milk </div>
                           </li>
                        </ul>
                     </div>
                     <!-- DESSERTS -->
                     <div class="tab-pane fade" id="desserts" role="tabpanel" aria-labelledby="desserts-tab">
                        <ul class="food-menu menu-2cols">
                           <li>
                              <h4><span class="menu-title">Summer Berry and Coconut Tart</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Raspberries, blackberries, blueberries, graham cracker</div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Double Chocolate Cupcakes</span><span
                                    class="menu-price">$12.00</span></h4>
                              <div class="menu-text">Chocolate, eggs, vanilla, milk </div>
                           </li>
                           <li>
                              <h4><span class="menu-title">Pumpkin Cookies Cream Cheese</span><span
                                    class="menu-price">$10.00</span></h4>
                              <div class="menu-text">Pumpkin, sugar, butter, eggs </div>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <!-- /MENU TAB -->
               </div>
               <!-- /col-md-12 -->
            </div>
            <!-- /row -->
         </div>
         <!-- /container -->
      </div> --}}



      <!-- /SECTION 5 -->
      <!-- SECTION 6 -->
      <!-- SECTION 6 : GOOGLE REVIEWS -->
<div id="home-content-6" class="home-section home-section-6 parallax google-reviews-section" style="margin-bottom:0;">
  <div class="container">
    <div class="row">
      <div class="col-md-12">

        <h2 class="gr-title">What Our Customers Say</h2>

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

      </div>
    </div>
  </div>
</div>
<!-- /SECTION 6 -->


   </div>
   <!-- /WRAP CONTENT -->


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
