@extends('frontend_v2.layouts.app')

@section('title', \App\Models\SiteText::getValue('home.seo.title', 'Small Elephants Sanctuary Chiang Mai | Ethical Elephant Experiences'))
@section('meta_description', \App\Models\SiteText::getValue('home.seo.description', 'Visit our ethical elephant sanctuary in Chiang Mai. Learn, connect, and spend meaningful time with elephants through safe and respectful programs.'))
@section('og_title', \App\Models\SiteText::getValue('home.seo.title', 'Small Elephants Sanctuary Chiang Mai | Ethical Elephant Experiences'))
@section('og_description', \App\Models\SiteText::getValue('home.seo.description', 'Visit our ethical elephant sanctuary in Chiang Mai. Learn, connect, and spend meaningful time with elephants through safe and respectful programs.'))
@push('styles')
<style>
.elephant-history{max-height:72px; overflow:hidden;}

.experience-section-v2{
    padding: 70px 0 40px;
    position: relative;
    overflow: hidden;
}

.experience-section-v2 .container{
    width: 100%;
    max-width: 1170px;
}

.experience-section-v2 .elephants-title{
    text-align: center;
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 28px;
    color: #22223b;
}

.experience-section-v2 .experience-slider .item{
    padding: 10px 8px 20px;
}

.experience-section-v2 .elephant-card{
    background: #2f2f2f;
    color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 18px rgba(0,0,0,.18);
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 0;
}

.experience-section-v2 .elephant-card__media{
    width: 100%;
    aspect-ratio: 4 / 3;
    overflow: hidden;
}

.experience-section-v2 .elephant-card__media img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.experience-section-v2 .elephant-card__body{
    padding: 16px 18px 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-height: 220px;
}

.experience-section-v2 .elephant-name{
height: 50px;
    overflow: hidden;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
}

.experience-section-v2 .elephant-rescued{
    text-align: center;
    font-size: 12px;
    text-transform: uppercase;
    color: #d7d7d7;
    letter-spacing: .06em;
}

.experience-section-v2 .elephant-desc{
    font-size: 13px;
    line-height: 1.6;
    color: #f1f1f1;
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical;
    overflow: hidden;
    flex: 1 1 auto;
    height: 62px;
}

.experience-section-v2 .btn-book{
    margin-top: 10px;
    align-self: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: calc(100% - 36px);
    min-height: 46px;
    border-radius: 999px;
    background: #b5db2a;
    color: #fff;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-size: 13px;
}

.experience-section-v2 .btn-book:hover{
    background: #a6cb24;
    color: #fff;
}

.experience-section-v2 .experience-slider .owl-nav{
    position: absolute;
    top: 40%;
    left: 8px;
    right: 8px;
    display: flex;
    justify-content: space-between;
    pointer-events: none;
}

.experience-section-v2 .experience-slider .owl-nav button{
    pointer-events: auto;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 0;
    background: #fff !important;
    color: #333 !important;
    font-size: 26px;
    font-weight: 900;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}

.experience-section-v2 .experience-slider .owl-dots{
    margin-top: 10px;
}

.experience-section-v2 .experience-slider .owl-dot span{
    width: 8px;
    height: 8px;
}

.meet-elephants-section-v2{
    padding: 48px 0 40px;
    position: relative;
    overflow: hidden;
}

.meet-elephants-section-v2 .container{
    width: 100%;
    max-width: 1170px;
}

.meet-elephants-section-v2 .elephants-title{
    text-align: center;
    font-size: 30px;
    font-weight: 700;
    margin-bottom: 28px;
    color: #22223b;
}

.meet-elephants-section-v2 .meet-elephants-slider .item{
    padding: 10px 8px 20px;
}

.meet-elephants-section-v2 .elephant-card{
    background: #2f2f2f;
    color: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 18px rgba(0,0,0,.18);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.meet-elephants-section-v2 .elephant-card__media{
    width: 100%;
    aspect-ratio: 4 / 3;
    overflow: hidden;
}

.meet-elephants-section-v2 .elephant-card__media img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.meet-elephants-section-v2 .elephant-card__body{
    padding: 16px 18px 18px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-height: 320px;
}

.meet-elephants-section-v2 .elephant-name{
    text-align: center;
    font-size: 16px;
    font-weight: 700;
    color: #fff;
}

.meet-elephants-section-v2 .elephant-rescued{
    text-align: center;
    font-size: 12px;
    text-transform: uppercase;
    color: #d7d7d7;
    letter-spacing: .06em;
}

.meet-elephants-section-v2 .elephant-desc{
    font-size: 13px;
    line-height: 1.6;
    color: #f1f1f1;
    display: -webkit-box;
    -webkit-line-clamp: 6;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 125px;
    flex: 1 1 auto;
}

.meet-elephants-section-v2 .btn-book{
    margin-top: 10px;
    align-self: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: calc(100% - 36px);
    min-height: 46px;
    border-radius: 999px;
    background: #b5db2a;
    color: #fff;
    font-weight: 700;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-size: 13px;
}

.meet-elephants-section-v2 .btn-book:hover{
    background: #a6cb24;
    color: #fff;
}

.meet-elephants-section-v2 .meet-elephants-slider .owl-nav{
    position: absolute;
    top: 40%;
    left: 8px;
    right: 8px;
    display: flex;
    justify-content: space-between;
    pointer-events: none;
}

.meet-elephants-section-v2 .meet-elephants-slider .owl-nav button{
    pointer-events: auto;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 0;
    background: #fff !important;
    color: #333 !important;
    font-size: 26px;
    font-weight: 900;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
}

.meet-elephants-section-v2 .meet-elephants-slider .owl-dots{
    margin-top: 10px;
}

.meet-elephants-section-v2 .meet-elephants-slider .owl-dot span{
    width: 8px;
    height: 8px;
}

@media (max-width: 767px){
    .experience-section-v2{
        padding: 48px 0 24px;
    }

    .experience-section-v2 .elephant-card__body{
        min-height: 0;
    }

    .meet-elephants-section-v2{
        padding: 32px 0 24px;
    }

    .meet-elephants-section-v2 .elephant-card__body{
        min-height: 0;
    }
}

.hero-slider.hero-style{
    position: relative;
    z-index: 1;
    overflow: visible;
}

.hero-discovery{
    position: absolute;
    left: 50%;
    bottom: 200px;
    transform: translateX(-50%);
    width: min(960px, calc(100% - 40px));
    z-index: 4;
    text-align: center;
}

.hero-discovery__inner{
    max-width: 760px;
    margin: 0 auto;
}

.hero-discovery__title{
    color: #fff;
    font-size: 62px;
    line-height: .98;
    font-weight: 800;
    letter-spacing: -.03em;
    margin: 0 0 22px;
    text-shadow: 0 10px 30px rgba(0, 0, 0, .28);
}

.hero-discovery__search{
    position: relative;
    z-index: 30;
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(255, 255, 255, .96);
    border-radius: 999px;
    padding: 10px 10px 10px 26px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .18);
}

.hero-discovery__field{
    flex: 1 1 auto;
    min-width: 0;
    border: 0;
    background: transparent;
    color: #222;
    font-size: 17px;
    outline: none;
}

.hero-discovery__field::placeholder{
    color: #777;
}

.hero-discovery__button{
    flex: 0 0 auto;
    border: 0;
    border-radius: 999px;
    background: #0f8a4a;
    color: #fff;
    font-weight: 700;
    font-size: 17px;
    padding: 16px 30px;
    cursor: pointer;
    transition: transform .2s ease, background .2s ease;
}

.hero-discovery__button:hover{
    background: #0c733e;
    transform: translateY(-1px);
}

.hero-discovery__autocomplete{
    position: absolute;
    top: 0;
    left: 0;
    width: min(760px, calc(100vw - 24px));
    z-index: 9999;
    background: rgba(255, 255, 255, .98);
    border-radius: 26px;
    padding: 16px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, .2);
    text-align: left;
}

.box_primary.description{
    position: relative;
    z-index: 1;
}

.hero-discovery__autocomplete[hidden]{
    display: none;
}

.hero-discovery__autocomplete-title{
    font-size: 14px;
    color: #526072;
    margin-bottom: 10px;
    padding: 0 6px;
}

.hero-discovery__suggestions{
    display: flex;
    flex-direction: column;
    gap: 6px;
    max-height: 320px;
    overflow: auto;
}

.hero-discovery__suggestion{
    width: 100%;
    border: 0;
    background: transparent;
    display: flex;
    align-items: center;
    gap: 14px;
    text-align: left;
    padding: 12px 14px;
    border-radius: 18px;
    cursor: pointer;
}

.hero-discovery__suggestion:hover,
.hero-discovery__suggestion.is-active{
    background: #eef2f5;
}

.hero-discovery__suggestion-icon{
    width: 42px;
    height: 42px;
    border-radius: 14px;
    background: #dfe4ea;
    color: #274160;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 42px;
    font-size: 20px;
}

.hero-discovery__suggestion-label{
    display: block;
    font-size: 20px;
    line-height: 1.15;
    color: #13233a;
    font-weight: 700;
}

.hero-discovery__suggestion-sub{
    display: block;
    font-size: 14px;
    color: #607086;
    margin-top: 4px;
}

#home-content-1 {
    padding: 60px 0 40px;
    position: relative;
    z-index: 1;
}

#home-content-1 .welcome-intro-row {
    align-items: center;
}

#home-content-1 .welcome-intro-copy {
    order: 1;
}

#home-content-1 .welcome-intro-copy .alignc {
    text-align: right;
    max-width: 460px;
    margin-left: auto;
}

#home-content-1 .welcome-intro-copy .smalltitle {
    letter-spacing: .24em;
    text-transform: uppercase;
    color: #b49a72;
    font-size: 12px;
}

#home-content-1 .welcome-intro-copy .home-title {
    font-size: 56px !important;
    line-height: 1.08;
    margin-bottom: 24px;
}

#home-content-1 .welcome-intro-copy p {
    font-size: 18px;
    line-height: 1.9;
    color: #6f6b66;
    margin-bottom: 0;
}

#home-content-1 .welcome-intro-actions {
    display: flex;
        justify-content: end;
    align-items: center;
    flex-wrap: wrap;
    gap: 18px;
    margin-top: 34px;
}

#home-content-1 .welcome-intro-about {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 150px;
    padding: 15px 26px;
    border-radius: 8px;
    background: #c49a5a;
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    font-size: 18px;
    transition: background .2s ease;
}

#home-content-1 .welcome-intro-about:hover {
    background: #b48949;
    color: #fff;
}

#home-content-1 .welcome-intro-phone {
    display: inline-flex;
    align-items: center;
    gap: 14px;
    color: #1c1a17;
    text-decoration: none;
    font-size: 18px;
}

#home-content-1 .welcome-intro-phone:hover {
    color: #1c1a17;
    text-decoration: none;
}

#home-content-1 .welcome-intro-phone-icon {
    width: 58px;
    height: 58px;
    border-radius: 999px;
    background: #111;
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    line-height: 1;
    flex: 0 0 58px;
}

#home-content-1 .welcome-intro-phone-icon svg {
    width: 24px;
    height: 24px;
    display: block;
}

#home-content-1 .welcome-intro-phone-label {
    font-size: 18px;
    letter-spacing: .01em;
}

#home-content-1 .welcome-intro-image {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding-right: 15px;
    padding-left: 15px;
}

#home-content-1 .welcome-intro-image img {
    width: 100%;
    max-width: 290px;
    height: 440px;
    object-fit: cover;
    border-radius: 10px;
    display: block;
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.12);
}

#home-content-1 .welcome-intro-image-a {
    order: 2;
}

#home-content-1 .welcome-intro-image-b {
    order: 3;
}

@media (min-width: 991px) {
    #home-content-1 .welcome-intro-row {
        min-height: 520px;
        justify-content: center;
        margin-left: auto;
        margin-right: auto;
        max-width: 1380px;
    }

    #home-content-1 .welcome-intro-copy {
        flex: 0 0 38%;
        max-width: 38%;
        padding-right: 36px;
    }

    #home-content-1 .welcome-intro-image {
        flex: 0 0 22%;
        max-width: 22%;
        padding-left: 18px;
        padding-right: 18px;
    }

    #home-content-1 .welcome-intro-image-a {
        margin-left: 0;
        margin-right: 14px;
    }

    #home-content-1 .welcome-intro-copy .alignc {
        text-align: right;
        margin-left: 0;
        margin-right: auto;
        max-width: 500px;
    }

    #home-content-1 .welcome-intro-copy .home-title {
        font-size: 64px !important;
        line-height: 1.02;
    }

    #home-content-1 .welcome-intro-copy p {
        max-width: 460px;
        margin-left: auto;
        margin-right: auto;
    }

    #home-content-1 .welcome-intro-actions {
        justify-content: center;
    }

    #home-content-1 .welcome-intro-image img {
        max-width: 250px;
        height: 440px;
    }

    #home-content-1 .welcome-intro-image-a {
        margin-top: 72px;
    }

    #home-content-1 .welcome-intro-image-b {
        margin-top: -24px;
        margin-left: 14px;
    }
}

@media (min-width: 768px) and (max-width: 990px) {
    #home-content-1 {
        padding: 72px 0 24px;
    }

    #home-content-1 .welcome-intro-copy {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 36px;
    }

    #home-content-1 .welcome-intro-copy .alignc {
        max-width: 100%;
    }

    #home-content-1 .welcome-intro-copy .home-title {
        font-size: 46px !important;
    }

    #home-content-1 .welcome-intro-image {
        flex: 0 0 50%;
        max-width: 50%;
    }

    #home-content-1 .welcome-intro-image img {
        max-width: 100%;
        height: 340px;
    }

    #home-content-1 .welcome-intro-image-a {
        margin-top: 36px;
    }
}

@media (max-width: 767px) {
    .hero-discovery{
        width: calc(100% - 24px);
        bottom: 34px;
    }

    .hero-discovery__title{
        font-size: 34px;
        margin-bottom: 14px;
    }

    .hero-discovery__search{
        flex-direction: column;
        align-items: stretch;
        border-radius: 24px;
        padding: 16px;
        gap: 10px;
    }

    .hero-discovery__field{
        font-size: 15px;
        padding: 2px 0;
    }

    .hero-discovery__button{
        width: 100%;
        padding: 14px 18px;
        font-size: 15px;
    }

    .hero-discovery__autocomplete{
        border-radius: 20px;
        padding: 12px;
    }

    .hero-discovery__suggestion{
        padding: 10px;
        gap: 10px;
    }

    .hero-discovery__suggestion-icon{
        width: 36px;
        height: 36px;
        flex-basis: 36px;
        border-radius: 12px;
        font-size: 18px;
    }

    .hero-discovery__suggestion-label{
        font-size: 16px;
    }

    .hero-discovery__suggestion-sub{
        font-size: 12px;
    }

    #home-content-1 {
        padding: 56px 0 10px;
    }
     .gr-title {
    font-size: 32px;
}

    #home-content-1 .welcome-intro-copy,
    #home-content-1 .welcome-intro-image {
        flex: 0 0 100%;
        max-width: 100%;
    }

    #home-content-1 .welcome-intro-copy {
        margin-bottom: 28px;
    }

    #home-content-1 .welcome-intro-copy .alignc {
        max-width: 100%;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
    }

    #home-content-1 .welcome-intro-copy .home-title {
        font-size: 34px !important;
        margin-bottom: 16px;
    }

    #home-content-1 .welcome-intro-copy p {
        font-size: 16px;
        line-height: 1.8;
    }

    #home-content-1 .welcome-intro-actions {
        gap: 14px;
        margin-top: 24px;
    }

    #home-content-1 .welcome-intro-about {
        min-width: 136px;
        padding: 13px 20px;
        font-size: 16px;
    }

    #home-content-1 .welcome-intro-phone {
        font-size: 16px;
    }

    #home-content-1 .welcome-intro-phone-icon {
        width: 52px;
        height: 52px;
        flex-basis: 52px;
        font-size: 20px;
    }

    #home-content-1 .welcome-intro-image {
        margin-bottom: 18px;
    }

    #home-content-1 .welcome-intro-image img {
        max-width: 100%;
        height: auto;
        aspect-ratio: 4 / 5;
    }

    .single-post-content img,
    .custom-page-template img {
        width: 100%;
        border-radius: 0.375rem;
        box-sizing: border-box;
    }

}

.elephant-history{
    overflow: hidden;
    height: 70px;
}
.box_primary {
    position: relative;
    padding: 20px 150px;
    display: block;
    width: 100%;
}
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
    $heroSearchValue = request()->query('q', '');
@endphp
 
    <section class="hero-slider hero-style">
            <div class="index-video-wrapper">
                <video autoplay="" muted="" loop="" playsinline="" src="{{ $heroVideo }}"
                    @if($heroPoster) poster="{{ $heroPoster }}" @endif
                    id="index-video" type="video/mp4">
                </video>
               
                

                <div class="hero-discovery">
                    <div class="hero-discovery__inner">
                        <h1 class="hero-discovery__title">{{ \App\Models\SiteText::getValue('home.hero.slide_title', 'Small Elephants') }}</h1>
                        <form class="hero-discovery__search" method="GET" action="{{ route('frontend.program') }}">
                            <input type="hidden" name="tags[]" id="hero-tag-slug" value="">
                            <input
                                id="hero-search-input"
                                class="hero-discovery__field"
                                type="text"
                                name="q"
                                value="{{ $heroSearchValue }}"
                                placeholder="{{ \App\Models\SiteText::getValue('home.hero.discovery_placeholder', 'Find places and things to do') }}"
                                autocomplete="off"
                                aria-label="Search programs"
                            >
                            <button class="hero-discovery__button" type="submit">
                                {{ \App\Models\SiteText::getValue('home.hero.discovery_cta', 'Search') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <div class="hero-discovery__autocomplete" id="hero-autocomplete" hidden>
            <div class="hero-discovery__autocomplete-title">{{ app()->getLocale() === 'en' ? 'Suggestions' : 'คำแนะนำ' }}</div>
            <div class="hero-discovery__suggestions" id="hero-suggestions">
                @foreach(($heroTags ?? collect()) as $tag)
                    <button
                        type="button"
                        class="hero-discovery__suggestion"
                        data-tag-slug="{{ $tag->slug }}"
                        data-tag-label="{{ $tag->label }}"
                    >
                        <span class="hero-discovery__suggestion-icon">⌖</span>
                        <span>
                            <span class="hero-discovery__suggestion-label">{{ $tag->label }}</span>
                            <span class="hero-discovery__suggestion-sub">{{ app()->getLocale() === 'en' ? 'Tour category' : 'หมวดหมู่กิจกรรม' }}</span>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>

        <main>
            <section class="box_primary description">
                <article class="container custom-page-template">

                  {{-- About Small Elephants --}}
                  
                  <div id="home-content-1" class="home-section home-section-1">
                    <div class="">
                        <div class="row align-items-center welcome-intro-row" style="display: flex; flex-wrap: wrap;">

                          

                          <div class="col-lg-3 offset-lg-1 col-md-6 mb-20 mt-45 welcome-intro-image welcome-intro-image-a">
                                    <img src="{{ $welcomeImageOne }}" alt="Welcome 1">
                                </div>
                                <!-- /col-md-6 -->
                                <div class="col-lg-3 col-md-6 mb-20 welcome-intro-image welcome-intro-image-b">
                                    <img src="{{ $welcomeImageTwo }}" alt="Welcome 2">
                                </div>


                          <!-- <div class="col-md-3">
                              <img src="./index_files/images/welcome-2.png" alt="Welcome 1">
                          </div> -->
                          <!-- /col-md-3 -->
                          <div class="col-lg-5 col-md-12 mb-15 welcome-intro-copy">
                              <div class="alignc">
                                <div class="smalltitle margin-b16">{{ \App\Models\SiteText::getValue('home.welcome.kicker', 'Welcome') }}</div>
                                <h2 class="home-title" style="font-size: 40px;">{{ \App\Models\SiteText::getValue('home.welcome.title', 'Elephants in Phuket') }}</h2>
                                <p>{{ \App\Models\SiteText::getValue('home.welcome.body', 'Experience an unforgettable moment with elephants, learning their lives and ethical care up close.') }}</p>
                                <div class="welcome-intro-actions">
                                    <a href="{{ route('frontend.about.v2') }}" class="welcome-intro-about">
                                        {{ \App\Models\SiteText::getValue('home.welcome.about_cta', 'About') }}
                                    </a>
                                    <a href="tel:{{ $siteSetting->phone ?? '' }}" class="welcome-intro-phone">
                                        <span class="welcome-intro-phone-icon" aria-hidden="true">
                                            <svg viewBox="0 0 24 24" fill="none">
                                                <path d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20a1 1 0 0 1-1 1C10.06 21 3 13.94 3 5a1 1 0 0 1 1-1h3.5c.55 0 1 .45 1 1 0 1.24.2 2.45.57 3.57.12.35.03.74-.24 1.02l-2.21 2.2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                        <span class="welcome-intro-phone-label">{{ $siteSetting->phone ?? '+1 800 000 111' }}</span>
                                    </a>
                                </div>
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

            <div class="home-section experience-section-v2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="elephants-title">{{ \App\Models\SiteText::getValue('home.experience.title', 'Experience') }}</h2>
                        </div>
                    </div>
                    @if($tours->count() > 0)
                        <div class="owl-carousel owl-theme experience-slider">
                            @foreach($tours as $tour)
                                <div class="item">
                                    <div class="elephant-card">
                                        <div class="elephant-card__media">
                                            <img
                                                src="{{ $tour->thumbnail ? asset($tour->thumbnail) : asset('images/placeholder-tour.jpg') }}"
                                                alt="{{ $tour->name }}"
                                            >
                                        </div>
                                        <div class="elephant-card__body">
                                            <div class="elephant-name">{{ $tour->name }}</div>
                                            <div class="elephant-rescued">{{ strtoupper($tour->duration ?? 'PROGRAM') }}</div>
                                            <span>From THB {{ number_format($tour->min_price ?? 0) }}</span>
                                            <div class="elephant-desc">{{ \Illuminate\Support\Str::limit(strip_tags($tour->excerpt ?? $tour->short_description ?? $tour->description ?? ''), 170) }}</div>
                                            <a href="{{ route('frontend.tours.show.v2', $tour->slug) }}" class="btn-book" aria-label="Read more">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alignc">
                            <p>No experiences available right now.</p>
                        </div>
                    @endif
                </div>
            </div>
            <br>

            <div class="home-section meet-elephants-section-v2">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="elephants-title">{{ \App\Models\SiteText::getValue('home.meet.title', 'Meet Our Elephants') }}</h2>
                        </div>
                    </div>
                    @if($elephants->count() > 0)
                    <div class="owl-carousel owl-theme meet-elephants-slider">
                        @foreach($elephants as $elephant)
                        <div class="item">
                            <div class="elephant-card">
                                <div class="elephant-card__media">
                                    <img src="{{ $elephant->images[0] ?? '' }}" alt="{{ $elephant->name }}">
                                </div>
                                <div class="elephant-card__body">
                                    <div class="elephant-name">{{ $elephant->getTranslated('name', $elephant->name) }}</div>
                                    <div class="elephant-rescued">
                                        @if($elephant->rescued_at)
                                            {{ __('common.rescued') }} {{ $elephant->rescued_at->format('d F Y') }}
                                        @else
                                            {{ __('common.rescued') }} DATE UNKNOWN
                                        @endif
                                    </div>
                                    <div class="elephant-desc">{{ $elephant->getTranslated('history', $elephant->history) }}</div>
                                    <a href="{{ route('frontend.elephants.v2') }}#{{ $elephant->slug ?? '' }}" class="btn-book" aria-label="Read more">
                                        {{ \App\Models\SiteText::getValue('home.actions.read_more', 'Read more') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alignc">
                        <p>No elephants available at the moment.</p>
                    </div>
                    @endif
                </div>
            </div>


            <section class="box_primary google-reviews-section parallax" id="home-content-6" style="background-image:url('{{ $reviewsBackground }}'); padding: 150px 0 80px;">
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
                    <div class="gr-google">Customer <span>Reviews</span></div>
                    <div class="gr-score">
                      <span id="gr-rating" class="gr-rating">0.0</span>
                      <span id="gr-stars" class="gr-stars">☆☆☆☆☆</span>
                      <span id="gr-total" class="gr-total">(0)</span>
                    </div>
                  </div>
                </div>

                {{-- Slider --}}
                <div id="google-review-slider" class="owl-carousel owl-theme testimonial-slider"></div>
                  
                  
                  
                </article>

            </section>

           
            
        </main>


        @push('scripts')
<script>
document.addEventListener("DOMContentLoaded", async () => {
  const heroSearchInput = document.getElementById('hero-search-input');
  const heroAutocomplete = document.getElementById('hero-autocomplete');
  const heroSuggestions = Array.from(document.querySelectorAll('.hero-discovery__suggestion'));
  const heroTagSlug = document.getElementById('hero-tag-slug');
  const heroSearchForm = heroSearchInput ? heroSearchInput.closest('form') : null;
  let activeSuggestionIndex = -1;
  let heroAutoSubmitTimer = null;

  const positionHeroAutocomplete = () => {
    if (!heroAutocomplete || !heroSearchForm || heroAutocomplete.hidden) return;
    const rect = heroSearchForm.getBoundingClientRect();
    const viewportPadding = window.innerWidth <= 767 ? 12 : 20;
    const width = Math.min(rect.width, window.innerWidth - (viewportPadding * 2));
    const left = Math.min(
      Math.max(rect.left + window.scrollX, window.scrollX + viewportPadding),
      window.scrollX + window.innerWidth - width - viewportPadding
    );

    heroAutocomplete.style.top = `${rect.bottom + window.scrollY + 12}px`;
    heroAutocomplete.style.left = `${left}px`;
    heroAutocomplete.style.width = `${width}px`;
  };

  const closeHeroAutocomplete = () => {
    if (!heroAutocomplete) return;
    heroAutocomplete.hidden = true;
    activeSuggestionIndex = -1;
    heroSuggestions.forEach((suggestion) => suggestion.classList.remove('is-active'));
  };

  const openHeroAutocomplete = () => {
    if (!heroAutocomplete) return;
    heroAutocomplete.hidden = false;
    positionHeroAutocomplete();
  };

  const updateHeroSuggestions = () => {
    if (!heroSearchInput || !heroAutocomplete) return;
    const keyword = heroSearchInput.value.trim().toLowerCase();
    let visibleCount = 0;

    heroSuggestions.forEach((suggestion) => {
      const label = (suggestion.dataset.tagLabel || '').toLowerCase();
      const slug = (suggestion.dataset.tagSlug || '').toLowerCase();
      const matched = keyword === '' || label.includes(keyword) || slug.includes(keyword);
      suggestion.hidden = !matched;
      suggestion.classList.remove('is-active');
      if (matched) visibleCount += 1;
    });

    heroAutocomplete.hidden = visibleCount === 0;
    activeSuggestionIndex = -1;
    positionHeroAutocomplete();
  };

  const selectHeroSuggestion = (suggestion) => {
    if (!heroSearchInput || !heroTagSlug || !suggestion) return;
    heroSearchInput.value = suggestion.dataset.tagLabel || '';
    heroTagSlug.value = suggestion.dataset.tagSlug || '';
    closeHeroAutocomplete();

    if (heroAutoSubmitTimer) {
      window.clearTimeout(heroAutoSubmitTimer);
    }

    heroAutoSubmitTimer = window.setTimeout(() => {
      heroSearchForm?.submit();
    }, 350);
  };

  if (heroSearchInput && heroAutocomplete && heroTagSlug && heroSearchForm) {
    heroSearchInput.addEventListener('focus', () => {
      updateHeroSuggestions();
      openHeroAutocomplete();
    });

    heroSearchInput.addEventListener('input', () => {
      heroTagSlug.value = '';
      updateHeroSuggestions();
    });

    heroSearchInput.addEventListener('keydown', (event) => {
      const visibleSuggestions = heroSuggestions.filter((suggestion) => !suggestion.hidden);
      if (!visibleSuggestions.length) return;

      if (event.key === 'ArrowDown') {
        event.preventDefault();
        activeSuggestionIndex = (activeSuggestionIndex + 1) % visibleSuggestions.length;
      } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        activeSuggestionIndex = activeSuggestionIndex <= 0 ? visibleSuggestions.length - 1 : activeSuggestionIndex - 1;
      } else if (event.key === 'Enter' && activeSuggestionIndex >= 0) {
        event.preventDefault();
        selectHeroSuggestion(visibleSuggestions[activeSuggestionIndex]);
        return;
      } else if (event.key === 'Escape') {
        closeHeroAutocomplete();
        return;
      } else {
        return;
      }

      visibleSuggestions.forEach((suggestion, index) => {
        suggestion.classList.toggle('is-active', index === activeSuggestionIndex);
      });
    });

    heroSuggestions.forEach((suggestion) => {
      suggestion.addEventListener('click', () => selectHeroSuggestion(suggestion));
    });

    heroSearchForm.addEventListener('submit', () => {
      if (!heroTagSlug.value.trim()) {
        heroSearchForm.querySelectorAll('input[name="tags[]"]').forEach((input) => {
          if (input !== heroTagSlug) input.remove();
        });
      }
    });

    document.addEventListener('click', (event) => {
      if (!heroSearchForm.contains(event.target) && !heroAutocomplete.contains(event.target)) {
        closeHeroAutocomplete();
      }
    });

    window.addEventListener('resize', positionHeroAutocomplete);
    window.addEventListener('scroll', positionHeroAutocomplete, { passive: true });
  }

  const sliderEl = document.getElementById('google-review-slider');
  if (!sliderEl) return;

  const escapeHtml = (value) => String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');

  const avatarSvgs = {
    classic: '<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="32" cy="22" r="12" fill="currentColor" opacity=".96"/><path d="M13 54c0-10.493 8.507-19 19-19s19 8.507 19 19" fill="currentColor" opacity=".96"/></svg>',
    soft: '<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="32" cy="21" r="11" fill="currentColor" opacity=".96"/><path d="M17 53c1.8-8.8 8.1-14 15-14s13.2 5.2 15 14" fill="currentColor" opacity=".96"/></svg>',
    round: '<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="32" cy="21.5" r="10.5" fill="currentColor" opacity=".96"/><path d="M15 53c0-9.4 7.6-17 17-17s17 7.6 17 17" fill="currentColor" opacity=".96"/></svg>',
  };

  const buildAvatarMarkup = (review) => {
    if (review.profile_photo_url) {
      return `<img src="${escapeHtml(review.profile_photo_url)}" alt="${escapeHtml(review.author_name ?? 'Review avatar')}">`;
    }

    const color = escapeHtml(review.avatar_color ?? '#A678A6');
    const variant = review.avatar_variant ?? 'classic';
    const svg = avatarSvgs[variant] || avatarSvgs.classic;

    return `<div class="gr-avatar-fallback" style="background:${color};">${svg}</div>`;
  };

  try {
    const res = await fetch('/api/google-reviews');
    const data = await res.json();

    // summary
    const rating = data.rating ?? 0;
    const total = data.user_ratings_total ?? 0;

    document.getElementById('gr-rating').textContent = Number(rating).toFixed(1);
    document.getElementById('gr-total').textContent = `(${total.toLocaleString()})`;

    const fullStars = Math.round(rating);
    document.getElementById('gr-stars').textContent =
      "★".repeat(fullStars) + "☆".repeat(5 - fullStars);

    // items
    if (!data.reviews || !data.reviews.length) {
      sliderEl.innerHTML = `
        <div class="item">
          <div class="testimonial-box" style="text-align:center;">
            <h4 style="margin:0;font-weight:900;">Our first customer review will appear here soon ⭐</h4>
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
          ${buildAvatarMarkup(r)}
        </div>
        <div>
          <h5>${escapeHtml(r.author_name ?? '')}</h5>
          <span>${escapeHtml(r.relative_time_description ?? '')}</span>
        </div>
      </div>
      <div class="testimonial-rating">${stars}</div>
      <p>${escapeHtml(r.text ?? '')}</p>
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
  if (false && window.jQuery && window.jQuery.fn && window.jQuery.fn.slick) {
    const $experience = window.jQuery('.detail-facilities.experience-cards .slider_facilities');

    if ($experience.length) {
      if ($experience.hasClass('slick-initialized')) {
        $experience.slick('unslick');
      }

      $experience.slick({
        infinite: true,
        dots: true,
        arrows: true,
        autoplay: true,
        autoplaySpeed: 4200,
        speed: 900,
        slidesToShow: 3,
        slidesToScroll: 1,
        adaptiveHeight: false,
        prevArrow: '<div><span class="slick-icon slick-prev" aria-hidden="true">&#8592;</span></div>',
        nextArrow: '<div><span class="slick-icon slick-next" aria-hidden="true">&#8594;</span></div>',
        responsive: [
          {
            breakpoint: 1200,
            settings: {
              slidesToShow: 2,
              slidesToScroll: 1
            }
          },
          {
            breakpoint: 992,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1
            }
          }
        ]
      });
    }
  }

  if (!window.jQuery || !window.jQuery.fn || !window.jQuery.fn.owlCarousel) {
    return;
  }

  const $facilities = window.jQuery('.slider_facilities--disabled');
  if ($facilities.length) {
    $facilities.owlCarousel({
      loop: true,
      margin: 26,
      autoplay: true,
      autoplayTimeout: 4200,
      autoplayHoverPause: true,
      smartSpeed: 850,
      nav: true,
      dots: true,
      navText: ['<span>‹</span>', '<span>›</span>'],
      responsive: {
        0: { items: 1, margin: 18, stagePadding: 18, nav: false },
        768: { items: 2, margin: 22, stagePadding: 26, nav: true },
        1200: { items: 3, margin: 26, stagePadding: 0, nav: true }
      }
    });
  }

  const $elephant = window.jQuery('.experience-slider');
  if ($elephant.length) {

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
  }

  const $meetElephants = window.jQuery('.meet-elephants-slider');
  if ($meetElephants.length) {
    $meetElephants.owlCarousel({
      loop: true,
      margin: 24,
      autoplay: true,
      autoplayTimeout: 5000,
      autoplayHoverPause: true,
      nav: true,
      dots: true,
      navText: [
        '<span class="gr-nav gr-prev">&lsaquo;</span>',
        '<span class="gr-nav gr-next">&rsaquo;</span>'
      ],
      responsive: {
        0: { items: 1 },
        768: { items: 2 },
        1024: { items: 3 }
      }
    });
  }
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












