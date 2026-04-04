@php
  $isEdit = isset($media) && $media->exists;
  $selectedKey = old('key', $media->key);
@endphp

<div class="row mb-6">
  <div class="col-md-6">
    <label class="form-label">Media Key</label>
    <input
      class="form-control"
      name="key"
      list="media-key-list"
      value="{{ old('key', $media->key) }}"
      placeholder="v2.home.hero.video"
      required
    >
    <datalist id="media-key-list">
      @foreach($presetKeys as $presetKey)
        <option value="{{ $presetKey }}"></option>
      @endforeach
    </datalist>
  </div>
  <div class="col-md-3">
    <label class="form-label">Locale</label>
    <select class="form-select" name="locale">
      <option value="" @selected(old('locale', $media->locale) === '')>Global</option>
      <option value="th" @selected(old('locale', $media->locale) === 'th')>Thai</option>
      <option value="en" @selected(old('locale', $media->locale) === 'en')>English</option>
    </select>
  </div>
  <div class="col-md-3">
    <label class="form-label">Type</label>
    <select class="form-select" name="type" required>
      <option value="image" @selected(old('type', $media->type) === 'image')>Image</option>
      <option value="video" @selected(old('type', $media->type) === 'video')>Video</option>
      <option value="file" @selected(old('type', $media->type) === 'file')>File</option>
    </select>
  </div>
</div>

<div class="row mb-6">
  <div class="col-md-6">
    <label class="form-label">{{ $isEdit ? 'Replace File' : 'Upload File' }}</label>
    <input class="form-control" type="file" name="media_file" {{ $isEdit ? '' : 'required' }}>
    <div class="form-text">Max file size 50MB.</div>
  </div>
  <div class="col-md-6">
    <label class="form-label d-block">Current File</label>
    @if($isEdit && !empty($media->path))
      <a href="{{ \Illuminate\Support\Facades\Storage::disk($media->disk ?: 'spaces')->url($media->path) }}" target="_blank">
        {{ $media->path }}
      </a>
    @else
      <span class="text-muted">No file uploaded</span>
    @endif
  </div>
</div>

<div class="row mb-6">
  <div class="col-md-4">
    <label class="form-label">Title</label>
    <input class="form-control" name="title" value="{{ old('title', $media->title) }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Alt Text</label>
    <input class="form-control" name="alt_text" value="{{ old('alt_text', $media->alt_text) }}">
  </div>
  <div class="col-md-2">
    <label class="form-label">Sort</label>
    <input class="form-control" type="number" min="0" name="sort_order" value="{{ old('sort_order', $media->sort_order ?? 0) }}">
  </div>
  <div class="col-md-2 d-flex align-items-end">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $media->is_active ?? true))>
      <label class="form-check-label" for="is_active">Active</label>
    </div>
  </div>
</div>

<div class="text-end">
  <a href="{{ route('admin.page-media.index') }}" class="btn btn-light me-2">Cancel</a>
  <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Update' : 'Create' }}</button>
</div>

@if(!empty($keyDescriptions ?? []))
  <div class="card mt-10 border-0 shadow-sm">
    <div class="card-header border-0 pb-0">
      <div>
        <br><br>
        <h3 class="card-title fw-bold m-0">Key Reference</h3>
        <div class="text-muted fs-7 mt-1">คำอธิบายว่าแต่ละ key อยู่ตรงไหนของหน้าเว็บ</div>
      </div>
    </div>
    <div class="card-body pt-6">
      <div class="row g-4">
        @foreach($keyDescriptions as $key => $description)
          <div class="col-md-6 col-xl-4">
            <div
              class="border rounded-3 h-100 px-4 py-4 key-reference-card {{ $selectedKey === $key ? 'border-primary bg-light-primary' : 'border-gray-200' }}"
              data-key-reference-card
              data-key="{{ $key }}"
            >
              <div class="fw-bold text-gray-900 mb-2" style="word-break: break-word;">{{ $key }}</div>
              <div class="text-muted fs-7">{{ $description }}</div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const keyInput = document.querySelector('input[name="key"]');
      const cards = Array.from(document.querySelectorAll('[data-key-reference-card]'));

      if (!keyInput || cards.length === 0) {
        return;
      }

      const syncActiveCard = () => {
        const currentKey = (keyInput.value || '').trim();

        cards.forEach((card) => {
          const isActive = card.dataset.key === currentKey;
          card.classList.toggle('border-primary', isActive);
          card.classList.toggle('bg-light-primary', isActive);
          card.classList.toggle('border-gray-200', !isActive);
        });
      };

      keyInput.addEventListener('input', syncActiveCard);
      keyInput.addEventListener('change', syncActiveCard);
      syncActiveCard();
    });
  </script>
@endif
