@extends('partials.admin.template')

@section('content')
@php
  $storyTh = json_decode($th['about.story'] ?? '{}', true) ?: [];
  $storyEn = json_decode($en['about.story'] ?? '{}', true) ?: [];
  $principlesTh = json_decode($th['about.principles'] ?? '{}', true) ?: [];
  $principlesEn = json_decode($en['about.principles'] ?? '{}', true) ?: [];
  $experienceTh = json_decode($th['about.experience'] ?? '{}', true) ?: [];
  $experienceEn = json_decode($en['about.experience'] ?? '{}', true) ?: [];
@endphp
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">จัดการข้อความหน้า About (2 ภาษา)</h1>
        </div>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="card">
          <div class="card-body">
            <form method="POST" action="{{ route('admin.site-texts.about.update') }}">
              @csrf

              <h4 class="mb-4">Hero</h4>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Kicker (TH)</label>
                  <input class="form-control" name="hero_kicker_th" value="{{ old('hero_kicker_th', $th['about.hero.kicker'] ?? 'SMALL ELEPHANTS') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Kicker (EN)</label>
                  <input class="form-control" name="hero_kicker_en" value="{{ old('hero_kicker_en', $en['about.hero.kicker'] ?? 'SMALL ELEPHANTS') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Title (TH)</label>
                  <input class="form-control" name="hero_title_th" value="{{ old('hero_title_th', $th['about.hero.title'] ?? 'บ้านของช้างอย่างมีจริยธรรม') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Title (EN)</label>
                  <input class="form-control" name="hero_title_en" value="{{ old('hero_title_en', $en['about.hero.title'] ?? 'About Our Ethical Sanctuary') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Lead (TH)</label>
                  <textarea class="form-control" name="hero_lead_th" rows="3">{{ old('hero_lead_th', $th['about.hero.lead'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Lead (EN)</label>
                  <textarea class="form-control" name="hero_lead_en" rows="3">{{ old('hero_lead_en', $en['about.hero.lead'] ?? '') }}</textarea>
                </div>
              </div>

              <h4 class="mb-4">Story</h4>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Eyebrow (TH)</label>
                  <input class="form-control" name="story_eyebrow_th" value="{{ old('story_eyebrow_th', $storyTh['eyebrow'] ?? 'เรื่องราวของเรา') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Eyebrow (EN)</label>
                  <input class="form-control" name="story_eyebrow_en" value="{{ old('story_eyebrow_en', $storyEn['eyebrow'] ?? 'OUR STORY') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Title (TH)</label>
                  <input class="form-control" name="story_title_th" value="{{ old('story_title_th', $storyTh['title'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Title (EN)</label>
                  <input class="form-control" name="story_title_en" value="{{ old('story_title_en', $storyEn['title'] ?? '') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Paragraphs (TH) - บรรทัดละ 1 ย่อหน้า</label>
                  <textarea class="form-control" name="story_paragraphs_th" rows="6">{{ old('story_paragraphs_th', isset($storyTh['paragraphs']) ? implode("\n", $storyTh['paragraphs']) : '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Paragraphs (EN) - 1 line = 1 paragraph</label>
                  <textarea class="form-control" name="story_paragraphs_en" rows="6">{{ old('story_paragraphs_en', isset($storyEn['paragraphs']) ? implode("\n", $storyEn['paragraphs']) : '') }}</textarea>
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Caption (TH)</label>
                  <textarea class="form-control" name="story_caption_th" rows="2">{{ old('story_caption_th', $storyTh['caption'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Caption (EN)</label>
                  <textarea class="form-control" name="story_caption_en" rows="2">{{ old('story_caption_en', $storyEn['caption'] ?? '') }}</textarea>
                </div>
              </div>

              <h4 class="mb-4">Principles</h4>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Eyebrow (TH)</label>
                  <input class="form-control" name="principles_eyebrow_th" value="{{ old('principles_eyebrow_th', $principlesTh['eyebrow'] ?? 'แนวทางของเรา') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Eyebrow (EN)</label>
                  <input class="form-control" name="principles_eyebrow_en" value="{{ old('principles_eyebrow_en', $principlesEn['eyebrow'] ?? 'OUR PRINCIPLES') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Title (TH)</label>
                  <input class="form-control" name="principles_title_th" value="{{ old('principles_title_th', $principlesTh['title'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Title (EN)</label>
                  <input class="form-control" name="principles_title_en" value="{{ old('principles_title_en', $principlesEn['title'] ?? '') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Sub (TH)</label>
                  <textarea class="form-control" name="principles_sub_th" rows="3">{{ old('principles_sub_th', $principlesTh['sub'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Sub (EN)</label>
                  <textarea class="form-control" name="principles_sub_en" rows="3">{{ old('principles_sub_en', $principlesEn['sub'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Card 1 Title (TH)</label>
                  <input class="form-control" name="principle1_title_th" value="{{ old('principle1_title_th', $principlesTh['cards'][0]['title'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Card 1 Title (EN)</label>
                  <input class="form-control" name="principle1_title_en" value="{{ old('principle1_title_en', $principlesEn['cards'][0]['title'] ?? '') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Card 1 Text (TH)</label>
                  <textarea class="form-control" name="principle1_text_th" rows="3">{{ old('principle1_text_th', $principlesTh['cards'][0]['text'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Card 1 Text (EN)</label>
                  <textarea class="form-control" name="principle1_text_en" rows="3">{{ old('principle1_text_en', $principlesEn['cards'][0]['text'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Card 2 Title (TH)</label>
                  <input class="form-control" name="principle2_title_th" value="{{ old('principle2_title_th', $principlesTh['cards'][1]['title'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Card 2 Title (EN)</label>
                  <input class="form-control" name="principle2_title_en" value="{{ old('principle2_title_en', $principlesEn['cards'][1]['title'] ?? '') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Card 2 Text (TH)</label>
                  <textarea class="form-control" name="principle2_text_th" rows="3">{{ old('principle2_text_th', $principlesTh['cards'][1]['text'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Card 2 Text (EN)</label>
                  <textarea class="form-control" name="principle2_text_en" rows="3">{{ old('principle2_text_en', $principlesEn['cards'][1]['text'] ?? '') }}</textarea>
                </div>
              </div>

              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Card 3 Title (TH)</label>
                  <input class="form-control" name="principle3_title_th" value="{{ old('principle3_title_th', $principlesTh['cards'][2]['title'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Card 3 Title (EN)</label>
                  <input class="form-control" name="principle3_title_en" value="{{ old('principle3_title_en', $principlesEn['cards'][2]['title'] ?? '') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Card 3 Text (TH)</label>
                  <textarea class="form-control" name="principle3_text_th" rows="3">{{ old('principle3_text_th', $principlesTh['cards'][2]['text'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Card 3 Text (EN)</label>
                  <textarea class="form-control" name="principle3_text_en" rows="3">{{ old('principle3_text_en', $principlesEn['cards'][2]['text'] ?? '') }}</textarea>
                </div>
              </div>

              <h4 class="mb-4">Experience</h4>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Eyebrow (TH)</label>
                  <input class="form-control" name="experience_eyebrow_th" value="{{ old('experience_eyebrow_th', $experienceTh['eyebrow'] ?? 'ประสบการณ์ของคุณ') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Eyebrow (EN)</label>
                  <input class="form-control" name="experience_eyebrow_en" value="{{ old('experience_eyebrow_en', $experienceEn['eyebrow'] ?? 'YOUR EXPERIENCE') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Title (TH)</label>
                  <input class="form-control" name="experience_title_th" value="{{ old('experience_title_th', $experienceTh['title'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Title (EN)</label>
                  <input class="form-control" name="experience_title_en" value="{{ old('experience_title_en', $experienceEn['title'] ?? '') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Sub (TH)</label>
                  <textarea class="form-control" name="experience_sub_th" rows="3">{{ old('experience_sub_th', $experienceTh['sub'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Sub (EN)</label>
                  <textarea class="form-control" name="experience_sub_en" rows="3">{{ old('experience_sub_en', $experienceEn['sub'] ?? '') }}</textarea>
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">Bullets (TH) - บรรทัดละ 1 ข้อ</label>
                  <textarea class="form-control" name="experience_bullets_th" rows="4">{{ old('experience_bullets_th', isset($experienceTh['bullets']) ? implode("\n", $experienceTh['bullets']) : '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Bullets (EN) - 1 line = 1 item</label>
                  <textarea class="form-control" name="experience_bullets_en" rows="4">{{ old('experience_bullets_en', isset($experienceEn['bullets']) ? implode("\n", $experienceEn['bullets']) : '') }}</textarea>
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">CTA Title (TH)</label>
                  <input class="form-control" name="experience_cta_title_th" value="{{ old('experience_cta_title_th', $experienceTh['cta_title'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">CTA Title (EN)</label>
                  <input class="form-control" name="experience_cta_title_en" value="{{ old('experience_cta_title_en', $experienceEn['cta_title'] ?? '') }}">
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">CTA Text (TH)</label>
                  <textarea class="form-control" name="experience_cta_text_th" rows="3">{{ old('experience_cta_text_th', $experienceTh['cta_text'] ?? '') }}</textarea>
                </div>
                <div class="col-md-6">
                  <label class="form-label">CTA Text (EN)</label>
                  <textarea class="form-control" name="experience_cta_text_en" rows="3">{{ old('experience_cta_text_en', $experienceEn['cta_text'] ?? '') }}</textarea>
                </div>
              </div>
              <div class="row mb-6">
                <div class="col-md-6">
                  <label class="form-label">CTA Button (TH)</label>
                  <input class="form-control" name="experience_cta_button_th" value="{{ old('experience_cta_button_th', $experienceTh['cta_button'] ?? '') }}">
                </div>
                <div class="col-md-6">
                  <label class="form-label">CTA Button (EN)</label>
                  <input class="form-control" name="experience_cta_button_en" value="{{ old('experience_cta_button_en', $experienceEn['cta_button'] ?? '') }}">
                </div>
              </div>

              <div class="text-end">
                <button class="btn btn-primary" type="submit">บันทึก</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
