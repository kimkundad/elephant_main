@php
  $avatarColor = old('avatar_color', $review->avatar_color ?: \App\Models\Review::randomAvatarColor());
  $avatarVariant = old('avatar_variant', $review->avatar_variant ?: \App\Models\Review::randomAvatarVariant());
@endphp

<style>
  .review-avatar-preview {
    width: 84px;
    height: 84px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,.18);
  }
  .review-avatar-preview svg {
    width: 52px;
    height: 52px;
  }
  .review-stars-preview {
    color: #f97316;
    letter-spacing: .12em;
    font-size: 18px;
  }
</style>

<div class="row g-4">
  <div class="col-xl-8">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="row g-4">
          <div class="col-md-6">
            <label class="form-label">Tour</label>
            <select class="form-select @error('tour_id') is-invalid @enderror" name="tour_id">
              <option value="">All tours / general</option>
              @foreach(($tours ?? []) as $tour)
                <option value="{{ $tour->id }}" @selected((int) old('tour_id', $review->tour_id ?? 0) === (int) $tour->id)>{{ $tour->name }}</option>
              @endforeach
            </select>
            @error('tour_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Reviewer Name</label>
            <input type="text" class="form-control @error('author_name') is-invalid @enderror" name="author_name" value="{{ old('author_name', $review->author_name ?? '') }}" required>
            @error('author_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6">
            <label class="form-label">Reviewer Email</label>
            <input type="email" class="form-control @error('author_email') is-invalid @enderror" name="author_email" value="{{ old('author_email', $review->author_email ?? '') }}">
            @error('author_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label">Stars</label>
            <select class="form-select @error('rating') is-invalid @enderror" name="rating" id="rating">
              @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" @selected((int) old('rating', $review->rating ?? 5) === $i)>{{ $i }} star{{ $i > 1 ? 's' : '' }}</option>
              @endfor
            </select>
            @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-3">
            <label class="form-label">Review Date</label>
            <input type="datetime-local" class="form-control @error('reviewed_at') is-invalid @enderror" name="reviewed_at" value="{{ old('reviewed_at', optional($review->reviewed_at)->format('Y-m-d\\TH:i')) }}">
            @error('reviewed_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-12">
            <label class="form-label">Review Text</label>
            <textarea class="form-control @error('review_text') is-invalid @enderror" name="review_text" rows="8" required>{{ old('review_text', $review->review_text ?? '') }}</textarea>
            @error('review_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-4">
            <label class="form-label">Sort Order</label>
            <input type="number" class="form-control @error('sort_order') is-invalid @enderror" min="0" name="sort_order" value="{{ old('sort_order', $review->sort_order ?? 0) }}">
            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Avatar Color</label>
            <select class="form-select @error('avatar_color') is-invalid @enderror" name="avatar_color" id="avatar_color">
              @foreach(\App\Models\Review::AVATAR_COLORS as $color)
                <option value="{{ $color }}" @selected($avatarColor === $color)>{{ $color }}</option>
              @endforeach
            </select>
            @error('avatar_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4">
            <label class="form-label">Avatar Style</label>
            <select class="form-select @error('avatar_variant') is-invalid @enderror" name="avatar_variant" id="avatar_variant">
              @foreach(\App\Models\Review::AVATAR_VARIANTS as $variant)
                <option value="{{ $variant }}" @selected($avatarVariant === $variant)>{{ ucfirst($variant) }}</option>
              @endforeach
            </select>
            @error('avatar_variant')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="col-md-12">
            <div class="form-check form-switch mt-2">
              <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $review->is_active ?? true) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">Active</label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-4">
    <div class="card shadow-sm h-100">
      <div class="card-body">
        <div class="fw-bold fs-5 mb-4">Preview</div>
        <div class="d-flex align-items-start gap-4">
          <div id="review-avatar-preview" class="review-avatar-preview" style="background: {{ $avatarColor }};"></div>
          <div class="flex-grow-1">
            <div id="review-author-preview" class="fw-bold fs-4 mb-1">{{ old('author_name', $review->author_name ?? 'Guest name') }}</div>
            <div id="review-stars-preview" class="review-stars-preview mb-2">{{ str_repeat('★', (int) old('rating', $review->rating ?? 5)) }}</div>
            <div class="text-muted fs-7 mb-3">{{ old('reviewed_at', optional($review->reviewed_at)->format('d M Y H:i')) ?: now()->format('d M Y H:i') }}</div>
            <div id="review-text-preview" class="text-gray-700">{{ old('review_text', $review->review_text ?? 'Review preview will appear here.') }}</div>
          </div>
        </div>
        <div class="separator my-6"></div>
        <button type="button" class="btn btn-light-primary btn-sm" id="randomize-avatar">Randomize Avatar</button>
        <div class="form-text mt-3">Avatar will be generated automatically on the website using the selected color and style.</div>
      </div>
    </div>
  </div>
</div>

<div class="text-end mt-8">
  <a href="{{ route('admin.reviews.index') }}" class="btn btn-light me-2">Cancel</a>
  <button class="btn btn-primary" type="submit">{{ $review->exists ? 'Update' : 'Create' }}</button>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const avatarMarkup = {
      classic: '<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="32" cy="22" r="12" fill="currentColor" opacity=".95"/><path d="M13 54c0-10.493 8.507-19 19-19s19 8.507 19 19" fill="currentColor" opacity=".95"/></svg>',
      soft: '<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="32" cy="21" r="11" fill="currentColor" opacity=".95"/><path d="M17 53c1.8-8.8 8.1-14 15-14s13.2 5.2 15 14" fill="currentColor" opacity=".95"/></svg>',
      round: '<svg viewBox="0 0 64 64" fill="none" aria-hidden="true"><circle cx="32" cy="21.5" r="10.5" fill="currentColor" opacity=".95"/><path d="M15 53c0-9.4 7.6-17 17-17s17 7.6 17 17" fill="currentColor" opacity=".95"/></svg>',
    };

    const colorInput = document.getElementById('avatar_color');
    const variantInput = document.getElementById('avatar_variant');
    const nameInput = document.querySelector('input[name="author_name"]');
    const ratingInput = document.getElementById('rating');
    const textInput = document.querySelector('textarea[name="review_text"]');
    const previewAvatar = document.getElementById('review-avatar-preview');
    const previewAuthor = document.getElementById('review-author-preview');
    const previewStars = document.getElementById('review-stars-preview');
    const previewText = document.getElementById('review-text-preview');
    const randomizeButton = document.getElementById('randomize-avatar');
    const colors = @json(\App\Models\Review::AVATAR_COLORS);
    const variants = @json(\App\Models\Review::AVATAR_VARIANTS);

    const randomItem = (items) => items[Math.floor(Math.random() * items.length)];

    const syncPreview = () => {
      if (previewAvatar && colorInput && variantInput) {
        previewAvatar.style.background = colorInput.value || '#A678A6';
        previewAvatar.innerHTML = avatarMarkup[variantInput.value] || avatarMarkup.classic;
      }

      if (previewAuthor && nameInput) {
        previewAuthor.textContent = nameInput.value.trim() || 'Guest name';
      }

      if (previewStars && ratingInput) {
        previewStars.textContent = '★'.repeat(Number(ratingInput.value || 5));
      }

      if (previewText && textInput) {
        previewText.textContent = textInput.value.trim() || 'Review preview will appear here.';
      }
    };

    [colorInput, variantInput, nameInput, ratingInput, textInput].forEach((input) => {
      input?.addEventListener('input', syncPreview);
      input?.addEventListener('change', syncPreview);
    });

    randomizeButton?.addEventListener('click', function () {
      if (colorInput) colorInput.value = randomItem(colors);
      if (variantInput) variantInput.value = randomItem(variants);
      syncPreview();
    });

    syncPreview();
  });
</script>
