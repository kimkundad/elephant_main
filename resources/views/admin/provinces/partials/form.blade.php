<div class="row g-4">
  <div class="col-md-6">
    <label class="form-label">ชื่อจังหวัด (TH) *</label>
    <input type="text" class="form-control @error('name_th') is-invalid @enderror" name="name_th" value="{{ old('name_th', $province->name_th ?? '') }}" required>
    @error('name_th')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">ชื่อจังหวัด (EN) *</label>
    <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en', $province->name_en ?? '') }}" required>
    @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label">Slug (ใช้ใน URL)</label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug', $province->slug ?? '') }}" placeholder="เว้นว่าง = สร้างจากชื่อ EN">
    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
  <div class="col-md-6">
    <label class="form-label d-block">สถานะ</label>
    <div class="form-check form-switch mt-3">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" id="province_is_active" {{ old('is_active', $province->is_active ?? true) ? 'checked' : '' }}>
      <label class="form-check-label" for="province_is_active">เปิดใช้งาน (ปิดแล้วทัวร์ในจังหวัดนี้จะไม่แสดงหน้าบ้าน)</label>
    </div>
  </div>
</div>
