@extends('frontend.layouts.app')

@section('title', 'Home')

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
            </div>
         </div>
      </div>
   </div>
   <!-- /HOME SLIDER -->

   <!-- EXPERIENCE SHOWCASE -->
   <section class="exp-showcase">
      <div class="exp-left">
         <img src="{{ Vite::asset('resources/frontend/images/img-1.jpg') }}" alt="Experience Lobby">
         <button class="exp-nav exp-nav--left" aria-label="Previous">
            <span>←</span>
         </button>
      </div>
      <div class="exp-right">
         <div class="exp-meta">
            <span>EXPERIENCE</span>
            <span class="exp-count">04/06</span>
         </div>
         <h2 class="exp-title">LOBBY</h2>
         <div class="exp-sub">OPEN EVERYDAY 24 HR.</div>
         <p class="exp-body">
            Experience the magic of Phang Nga Bay at Sametnangshe Boutique Hotel.
            Book your stay and experience the difference.
         </p>
         <a class="exp-cta" href="#">DISCOVER</a>
         <div class="exp-right-ghost">
            <img src="{{ Vite::asset('resources/frontend/images/img-1.jpg') }}" alt="">
         </div>
         <button class="exp-nav exp-nav--right" aria-label="Next">
            <span>→</span>
         </button>
      </div>
   </section>
   <!-- /EXPERIENCE SHOWCASE -->



@endsection
