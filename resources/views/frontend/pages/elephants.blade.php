@extends('frontend.layouts.app')

@section('title', 'Our Elephants')

@push('styles')
<style>
.elephants-hero{
  background: #ededed;
  padding: 70px 0 30px;
  text-align:center;
}
.elephants-hero h1{
  font-size:36px;
  font-weight:700;
  margin:0 0 10px;
}
.elephants-hero p{
  color:#555;
  max-width:780px;
  margin:0 auto;
}
.elephants-list{
  background:#e7e7e7;
  padding: 20px 0 80px;
}
.elephant-block{
  display:grid;
  grid-template-columns: 1.05fr 0.95fr;
  gap:0;
  background:#777;
  color:#fff;
  border-radius:10px;
  overflow:hidden;
  margin:0 auto 34px;
  box-shadow:0 12px 26px rgba(0,0,0,.18);
}
.elephant-block__media{
  min-height: 420px;
}
.elephant-block__media img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
}
.elephant-block__content{
  padding:28px 34px;
  display:flex;
  flex-direction:column;
  justify-content:center;
  text-align:center;
}
.elephant-block__title{
  font-size:22px;
  font-weight:700;
  margin:0 0 6px;
  color:#b6e04a;
  text-transform:uppercase;
  letter-spacing:.12em;
}
.elephant-block__rescued{
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:.08em;
  opacity:.85;
  margin-bottom:16px;
}
.elephant-block__text{
  font-size:14px;
  line-height:1.8;
  color:#f1f1f1;
  margin:0 auto;
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
.elephant-block:nth-child(even){
  grid-template-columns: 0.95fr 1.05fr;
}
.elephant-block:nth-child(even) .elephant-block__media{
  order:2;
}
@media (max-width: 992px){
  .elephant-block{
    grid-template-columns: 1fr;
  }
  .elephant-block:nth-child(even) .elephant-block__media{
    order:0;
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
    <h1 class="about-hero__title">Meet Our Elephants</h1>
    <p class="about-hero__lead">
      เรื่องราวการช่วยเหลือและการดูแลช้างของเรา เพื่อชีวิตที่สงบและเป็นธรรมชาติของพวกเขา
    </p>

    <div class="about-hero__actions">
     
      
    </div>
  </div>
</section>




<div class="elephants-list">
  <div class="container">
    @forelse($elephants as $elephant)
      <div class="elephant-block" id="{{ $elephant->slug ?? '' }}">
        <div class="elephant-block__content">
          <div class="elephant-block__title">{{ $elephant->name }}</div>
          <div class="elephant-block__rescued">
            @if($elephant->rescued_at)
              Rescued {{ $elephant->rescued_at->format('d F Y') }}
            @else
              Rescued date unknown
            @endif
          </div>
          <div class="elephant-block__text">{{ $elephant->history }}</div>

          @if(!empty($elephant->images) && count($elephant->images) > 1)
            <div class="elephant-gallery">
              @foreach($elephant->images as $img)
                <img src="{{ $img }}" alt="{{ $elephant->name }}">
              @endforeach
            </div>
          @endif
        </div>
        <div class="elephant-block__media">
          <img src="{{ $elephant->images[0] ?? '' }}" alt="{{ $elephant->name }}">
        </div>
      </div>
    @empty
      <p class="text-center">ยังไม่มีข้อมูลช้างให้แสดงในตอนนี้</p>
    @endforelse
  </div>
</div>
@endsection
