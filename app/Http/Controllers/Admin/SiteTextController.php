<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteText;
use Illuminate\Http\Request;

class SiteTextController extends Controller
{
    public function editHome()
    {
        return view('admin.site_texts.home', [
            'th' => $this->getTexts('th'),
            'en' => $this->getTexts('en'),
        ]);
    }

    public function editAbout()
    {
        return view('admin.site_texts.about', [
            'th' => $this->getTexts('th'),
            'en' => $this->getTexts('en'),
        ]);
    }

    public function updateHome(Request $request)
    {
        $data = $request->validate([
            'seo_title_th' => 'required|string|max:255',
            'seo_title_en' => 'required|string|max:255',
            'seo_description_th' => 'required|string|max:1000',
            'seo_description_en' => 'required|string|max:1000',
            'slide_title_th' => 'required|string|max:255',
            'slide_subtitle_th' => 'required|string|max:1000',
            'slide_title_en' => 'required|string|max:255',
            'slide_subtitle_en' => 'required|string|max:1000',
            'welcome_kicker_th' => 'required|string|max:255',
            'welcome_kicker_en' => 'required|string|max:255',
            'welcome_title_th' => 'required|string|max:255',
            'welcome_title_en' => 'required|string|max:255',
            'welcome_body_th' => 'required|string|max:2000',
            'welcome_body_en' => 'required|string|max:2000',
            'experience_title_th' => 'required|string|max:255',
            'experience_title_en' => 'required|string|max:255',
            'meet_title_th' => 'required|string|max:255',
            'meet_title_en' => 'required|string|max:255',
            'reviews_title_th' => 'required|string|max:255',
            'reviews_title_en' => 'required|string|max:255',
            'book_now_th' => 'required|string|max:255',
            'book_now_en' => 'required|string|max:255',
            'read_more_th' => 'required|string|max:255',
            'read_more_en' => 'required|string|max:255',
            'contact_title_th' => 'required|string|max:255',
            'contact_title_en' => 'required|string|max:255',
            'contact_lead_th' => 'required|string|max:1500',
            'contact_lead_en' => 'required|string|max:1500',
        ]);

        $this->upsertText('home', 'seo', 'home.seo.title', 'th', $data['seo_title_th']);
        $this->upsertText('home', 'seo', 'home.seo.title', 'en', $data['seo_title_en']);
        $this->upsertText('home', 'seo', 'home.seo.description', 'th', $data['seo_description_th']);
        $this->upsertText('home', 'seo', 'home.seo.description', 'en', $data['seo_description_en']);

        $this->upsertText('home', 'hero', 'home.hero.slide_title', 'th', $data['slide_title_th']);
        $this->upsertText('home', 'hero', 'home.hero.slide_subtitle', 'th', $data['slide_subtitle_th']);
        $this->upsertText('home', 'hero', 'home.hero.slide_title', 'en', $data['slide_title_en']);
        $this->upsertText('home', 'hero', 'home.hero.slide_subtitle', 'en', $data['slide_subtitle_en']);

        $this->upsertText('home', 'welcome', 'home.welcome.kicker', 'th', $data['welcome_kicker_th']);
        $this->upsertText('home', 'welcome', 'home.welcome.kicker', 'en', $data['welcome_kicker_en']);
        $this->upsertText('home', 'welcome', 'home.welcome.title', 'th', $data['welcome_title_th']);
        $this->upsertText('home', 'welcome', 'home.welcome.title', 'en', $data['welcome_title_en']);
        $this->upsertText('home', 'welcome', 'home.welcome.body', 'th', $data['welcome_body_th']);
        $this->upsertText('home', 'welcome', 'home.welcome.body', 'en', $data['welcome_body_en']);

        $this->upsertText('home', 'experience', 'home.experience.title', 'th', $data['experience_title_th']);
        $this->upsertText('home', 'experience', 'home.experience.title', 'en', $data['experience_title_en']);
        $this->upsertText('home', 'meet', 'home.meet.title', 'th', $data['meet_title_th']);
        $this->upsertText('home', 'meet', 'home.meet.title', 'en', $data['meet_title_en']);
        $this->upsertText('home', 'reviews', 'home.reviews.title', 'th', $data['reviews_title_th']);
        $this->upsertText('home', 'reviews', 'home.reviews.title', 'en', $data['reviews_title_en']);
        $this->upsertText('home', 'actions', 'home.actions.book_now', 'th', $data['book_now_th']);
        $this->upsertText('home', 'actions', 'home.actions.book_now', 'en', $data['book_now_en']);
        $this->upsertText('home', 'actions', 'home.actions.read_more', 'th', $data['read_more_th']);
        $this->upsertText('home', 'actions', 'home.actions.read_more', 'en', $data['read_more_en']);
        $this->upsertText('home', 'contact', 'home.contact.title', 'th', $data['contact_title_th']);
        $this->upsertText('home', 'contact', 'home.contact.title', 'en', $data['contact_title_en']);
        $this->upsertText('home', 'contact', 'home.contact.lead', 'th', $data['contact_lead_th']);
        $this->upsertText('home', 'contact', 'home.contact.lead', 'en', $data['contact_lead_en']);

        return redirect()
            ->route('admin.site-texts.home')
            ->with('success', 'บันทึกข้อความเรียบร้อย');
    }

    public function updateAbout(Request $request)
    {
        $data = $request->validate([
            'hero_kicker_th' => 'required|string|max:255',
            'hero_kicker_en' => 'required|string|max:255',
            'hero_title_th' => 'required|string|max:255',
            'hero_title_en' => 'required|string|max:255',
            'hero_lead_th' => 'required|string|max:1500',
            'hero_lead_en' => 'required|string|max:1500',

            'story_eyebrow_th' => 'required|string|max:255',
            'story_eyebrow_en' => 'required|string|max:255',
            'story_title_th' => 'required|string|max:255',
            'story_title_en' => 'required|string|max:255',
            'story_paragraphs_th' => 'nullable|string',
            'story_paragraphs_en' => 'nullable|string',
            'story_caption_th' => 'nullable|string|max:1000',
            'story_caption_en' => 'nullable|string|max:1000',

            'principles_eyebrow_th' => 'required|string|max:255',
            'principles_eyebrow_en' => 'required|string|max:255',
            'principles_title_th' => 'required|string|max:255',
            'principles_title_en' => 'required|string|max:255',
            'principles_sub_th' => 'nullable|string|max:1500',
            'principles_sub_en' => 'nullable|string|max:1500',

            'principle1_title_th' => 'required|string|max:255',
            'principle1_title_en' => 'required|string|max:255',
            'principle1_text_th' => 'nullable|string|max:1000',
            'principle1_text_en' => 'nullable|string|max:1000',
            'principle2_title_th' => 'required|string|max:255',
            'principle2_title_en' => 'required|string|max:255',
            'principle2_text_th' => 'nullable|string|max:1000',
            'principle2_text_en' => 'nullable|string|max:1000',
            'principle3_title_th' => 'required|string|max:255',
            'principle3_title_en' => 'required|string|max:255',
            'principle3_text_th' => 'nullable|string|max:1000',
            'principle3_text_en' => 'nullable|string|max:1000',

            'experience_eyebrow_th' => 'required|string|max:255',
            'experience_eyebrow_en' => 'required|string|max:255',
            'experience_title_th' => 'required|string|max:255',
            'experience_title_en' => 'required|string|max:255',
            'experience_sub_th' => 'nullable|string|max:1500',
            'experience_sub_en' => 'nullable|string|max:1500',
            'experience_bullets_th' => 'nullable|string',
            'experience_bullets_en' => 'nullable|string',
            'experience_cta_title_th' => 'required|string|max:255',
            'experience_cta_title_en' => 'required|string|max:255',
            'experience_cta_text_th' => 'nullable|string|max:1000',
            'experience_cta_text_en' => 'nullable|string|max:1000',
            'experience_cta_button_th' => 'required|string|max:255',
            'experience_cta_button_en' => 'required|string|max:255',
        ]);

        $this->upsertText('about', 'hero', 'about.hero.kicker', 'th', $data['hero_kicker_th']);
        $this->upsertText('about', 'hero', 'about.hero.kicker', 'en', $data['hero_kicker_en']);
        $this->upsertText('about', 'hero', 'about.hero.title', 'th', $data['hero_title_th']);
        $this->upsertText('about', 'hero', 'about.hero.title', 'en', $data['hero_title_en']);
        $this->upsertText('about', 'hero', 'about.hero.lead', 'th', $data['hero_lead_th']);
        $this->upsertText('about', 'hero', 'about.hero.lead', 'en', $data['hero_lead_en']);

        $storyTh = [
            'eyebrow' => $data['story_eyebrow_th'],
            'title' => $data['story_title_th'],
            'paragraphs' => $this->linesToArray($data['story_paragraphs_th'] ?? ''),
            'caption' => $data['story_caption_th'] ?? '',
        ];
        $storyEn = [
            'eyebrow' => $data['story_eyebrow_en'],
            'title' => $data['story_title_en'],
            'paragraphs' => $this->linesToArray($data['story_paragraphs_en'] ?? ''),
            'caption' => $data['story_caption_en'] ?? '',
        ];
        $this->upsertText('about', 'story', 'about.story', 'th', json_encode($storyTh, JSON_UNESCAPED_UNICODE));
        $this->upsertText('about', 'story', 'about.story', 'en', json_encode($storyEn, JSON_UNESCAPED_UNICODE));

        $principlesTh = [
            'eyebrow' => $data['principles_eyebrow_th'],
            'title' => $data['principles_title_th'],
            'sub' => $data['principles_sub_th'] ?? '',
            'cards' => [
                ['title' => $data['principle1_title_th'], 'text' => $data['principle1_text_th'] ?? ''],
                ['title' => $data['principle2_title_th'], 'text' => $data['principle2_text_th'] ?? ''],
                ['title' => $data['principle3_title_th'], 'text' => $data['principle3_text_th'] ?? ''],
            ],
        ];
        $principlesEn = [
            'eyebrow' => $data['principles_eyebrow_en'],
            'title' => $data['principles_title_en'],
            'sub' => $data['principles_sub_en'] ?? '',
            'cards' => [
                ['title' => $data['principle1_title_en'], 'text' => $data['principle1_text_en'] ?? ''],
                ['title' => $data['principle2_title_en'], 'text' => $data['principle2_text_en'] ?? ''],
                ['title' => $data['principle3_title_en'], 'text' => $data['principle3_text_en'] ?? ''],
            ],
        ];
        $this->upsertText('about', 'principles', 'about.principles', 'th', json_encode($principlesTh, JSON_UNESCAPED_UNICODE));
        $this->upsertText('about', 'principles', 'about.principles', 'en', json_encode($principlesEn, JSON_UNESCAPED_UNICODE));

        $experienceTh = [
            'eyebrow' => $data['experience_eyebrow_th'],
            'title' => $data['experience_title_th'],
            'sub' => $data['experience_sub_th'] ?? '',
            'bullets' => $this->linesToArray($data['experience_bullets_th'] ?? ''),
            'cta_title' => $data['experience_cta_title_th'],
            'cta_text' => $data['experience_cta_text_th'] ?? '',
            'cta_button' => $data['experience_cta_button_th'],
        ];
        $experienceEn = [
            'eyebrow' => $data['experience_eyebrow_en'],
            'title' => $data['experience_title_en'],
            'sub' => $data['experience_sub_en'] ?? '',
            'bullets' => $this->linesToArray($data['experience_bullets_en'] ?? ''),
            'cta_title' => $data['experience_cta_title_en'],
            'cta_text' => $data['experience_cta_text_en'] ?? '',
            'cta_button' => $data['experience_cta_button_en'],
        ];
        $this->upsertText('about', 'experience', 'about.experience', 'th', json_encode($experienceTh, JSON_UNESCAPED_UNICODE));
        $this->upsertText('about', 'experience', 'about.experience', 'en', json_encode($experienceEn, JSON_UNESCAPED_UNICODE));

        return redirect()
            ->route('admin.site-texts.about')
            ->with('success', 'บันทึกข้อความเรียบร้อย');
    }

    private function getTexts(string $locale): array
    {
        return SiteText::query()
            ->where('locale', $locale)
            ->pluck('value', 'key')
            ->toArray();
    }

    private function upsertText(string $page, string $section, string $key, string $locale, string $value): void
    {
        SiteText::updateOrCreate(
            ['key' => $key, 'locale' => $locale],
            ['page' => $page, 'section' => $section, 'value' => $value]
        );
    }

    private function linesToArray(string $text): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $text))));
    }
}

