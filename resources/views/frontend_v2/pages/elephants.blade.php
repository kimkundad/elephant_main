@extends('frontend_v2.layouts.app')

@section('title', __('elephants.title'))

@push('styles')
<style>
.elephants-hero{
  padding: 70px 0 30px;
  text-align:center;
}
.elephants-list{
  background:#ffffff;
  padding: 20px 0 80px;
}
.elephant-block{
  display:grid;
  grid-template-columns: 1.05fr 0.95fr;
  gap:0;
  background:#ffffff;
  color:#313131;
  border-radius:0;
  overflow:hidden;
  margin:0 auto 34px;
  box-shadow:none;
}
.elephant-block__media{
  min-height: 420px;
  order:1;
}
.elephant-block__media img{
  width:100%;
  height:100%;
  max-height:800px;
  object-fit:cover;
  display:block;
}
.elephant-block__content{
  padding:28px 34px;
  display:flex;
  flex-direction:column;
  justify-content:center;
  text-align:center;
  align-items:center;
  order:2;
}
.elephant-block__title{
  font-size:38px;
  font-weight:700;
  margin:0 0 6px;
  color:#302d29;
  text-transform:uppercase;
  letter-spacing:.12em;
}
.elephant-block__rescued{
  font-size:18px;
  text-transform:uppercase;
  letter-spacing:.08em;
  color:#bba383;
  opacity:1;
  margin-bottom:16px;
}
.elephant-block__text{
  font-size:16px;
  line-height:1.8;
  color:#313131;
  margin:0;
  max-width:520px;
}
.elephant-gallery{
  margin-top:18px;
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  justify-content:center;
}
.elephant-gallery img{
  width:120px;
  height:80px;
  object-fit:cover;
  border-radius:6px;
}
.elephant-block:nth-child(even) .elephant-block__media{
  order:2;
}
.elephant-block:nth-child(even) .elephant-block__content{
  order:1;
  text-align:center;
  align-items:center;
}
@media (max-width: 992px){
  .elephant-block{
    grid-template-columns: 1fr;
  }
  .elephant-block:nth-child(even) .elephant-block__media{
    order:0;
  }
  .elephant-block:nth-child(even) .elephant-block__content{
    order:0;
  }
  .elephant-block__media{
    order:0;
  }
  .elephant-block__content{
    order:0;
    align-items:center;
  }
  .elephant-block__media{
    min-height: 300px;
  }
  .elephant-block__content{
    padding:24px 22px;
  }
}
</style>
@endpush

@section('content')

<section class="about-hero" style="background-image:url('{{ Vite::asset('resources/frontend/images/bg-chang.webp') }}')">
  <div class="about-hero__overlay"></div>
  <div class="container about-hero__inner">
    <div class="about-hero__kicker">SMALL ELEPHANTS</div>
    <h1 class="about-hero__title">{{ __('elephants.title') }}</h1>
    <p class="about-hero__lead">
      {{ __('elephants.lead') }}
    </p>
  </div>
</section>

<div class="elephants-list">
  <div class="container">
    @forelse($elephants as $elephant)
      <div class="elephant-block" id="{{ $elephant->slug ?? '' }}">
        <div class="elephant-block__content">
          <div class="elephant-block__title">{{ $elephant->getTranslated('name', $elephant->name) }}</div>
          <div class="elephant-block__rescued">
            @if($elephant->rescued_at)
              {{ __('common.rescued') }} {{ $elephant->rescued_at->format('d F Y') }}
            @else
              {{ __('common.rescued') }} {{ __('elephants.rescued_unknown') }}
            @endif
          </div>
          <div class="elephant-block__text">{{ $elephant->getTranslated('history', $elephant->history) }}</div>

          @if(!empty($elephant->images) && count($elephant->images) > 1)
            <div class="elephant-gallery">
              @foreach($elephant->images as $img)
                <img src="{{ $img }}" alt="{{ $elephant->getTranslated('name', $elephant->name) }}">
              @endforeach
            </div>
          @endif
        </div>
        <div class="elephant-block__media">
          <img src="{{ $elephant->images[0] ?? '' }}" alt="{{ $elephant->getTranslated('name', $elephant->name) }}">
        </div>
      </div>
    @empty
      <p class="text-center">{{ __('elephants.empty') }}</p>
    @endforelse
  </div>
</div>
@endsection
