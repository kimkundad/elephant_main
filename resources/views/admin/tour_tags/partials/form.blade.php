<div class="row g-4">
  <div class="col-md-6">
    <label class="form-label">Name (TH)</label>
    <input type="text" class="form-control @error('name_th') is-invalid @enderror" name="name_th" value="{{ old('name_th', $tag->name_th ?? '') }}" required>
    @error('name_th')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Name (EN)</label>
    <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en', $tag->name_en ?? '') }}">
    @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Slug</label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $tag->slug ?? '') }}" placeholder="auto from name if empty">
    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label">Sort Order</label>
    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" name="sort_order" value="{{ old('sort_order', $tag->sort_order ?? 0) }}" min="0">
    @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-3">
    <label class="form-label d-block">Status</label>
    <div class="form-check form-switch mt-3">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $tag->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label">Active</label>
    </div>
  </div>
</div>

