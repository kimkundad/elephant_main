@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">
                        Edit Tour Program
                    </h1>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card mb-7">
                    <div class="card-body">

                        <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-4 mb-1">
                                <div class="col-md-6">
                                    <label class="form-label">Program Name (TH) *</label>
                                    <input type="text" name="name_th" class="form-control" value="{{ old('name_th', $translationTh->name ?? $tour->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Program Name (EN) *</label>
                                    <input type="text" name="name_en" class="form-control" value="{{ old('name_en', $translationEn->name ?? $tour->name) }}" required>
                                </div>
                            </div>

                            <div class="row g-4 mb-1">
                                <div class="col-md-6">
                                    <label class="form-label">Short Description (TH)</label>
                                    <textarea name="short_description_th" class="form-control" rows="4">{{ old('short_description_th', $translationTh->short_description ?? $tour->short_description) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Short Description (EN)</label>
                                    <textarea name="short_description_en" class="form-control" rows="4">{{ old('short_description_en', $translationEn->short_description ?? $tour->short_description) }}</textarea>
                                </div>
                            </div>

                            <div class="row g-4 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Description (TH)</label>
                                    <textarea name="description_th" class="form-control" rows="6">{{ old('description_th', $translationTh->description ?? $tour->description) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Full Description (EN)</label>
                                    <textarea name="description_en" class="form-control" rows="6">{{ old('description_en', $translationEn->description ?? $tour->description) }}</textarea>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Tags</label>
                                <div class="row g-2">
                                    @php($selectedTagIds = old('tag_ids', $tour->tags->pluck('id')->all()))
                                    @forelse(($tags ?? []) as $tag)
                                        <div class="col-md-4">
                                            <label class="form-check form-check-custom form-check-sm">
                                                <input class="form-check-input" type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTagIds, true) ? 'checked' : '' }}>
                                                <span class="form-check-label ms-2">{{ $tag->name_th }} @if($tag->name_en)<small class="text-muted">({{ $tag->name_en }})</small>@endif</span>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="col-12 text-muted">No tags found. Please create tags first.</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ราคาเด็ก *</label>
                                    <input type="number" name="min_price" class="form-control" value="{{ $tour->min_price }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ราคาผู้ใหญ่ *</label>
                                    <input type="number" name="max_price" class="form-control" value="{{ $tour->max_price }}" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Current Thumbnail</label><br>
                                <img src="{{ $tour->thumbnail }}" width="150" class="rounded mb-3">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Upload New Thumbnail (optional)</label>
                                <input type="file" name="thumbnail" class="form-control js-tour-thumbnail" accept="image/*">
                                <div class="form-text js-thumbnail-help">ระบบจะย่อรูปให้อัตโนมัติก่อนอัปโหลด เพื่อลดปัญหาไฟล์จากมือถือใหญ่เกินไป</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Display Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" @selected($tour->is_active)>Active</option>
                                    <option value="0" @selected(!$tour->is_active)>Hidden</option>
                                </select>
                            </div>

                            <button class="btn btn-primary">Save Changes</button>
                            <a href="{{ route('admin.tours.index') }}" class="btn btn-light">Cancel</a>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.querySelector('.js-tour-thumbnail');
    const help = document.querySelector('.js-thumbnail-help');

    if (!input || typeof DataTransfer === 'undefined') {
        return;
    }

    function loadImage(file) {
        return new Promise(function (resolve, reject) {
            const reader = new FileReader();
            reader.onload = function (event) {
                const image = new Image();
                image.onload = function () { resolve(image); };
                image.onerror = reject;
                image.src = event.target.result;
            };
            reader.onerror = reject;
            reader.readAsDataURL(file);
        });
    }

    async function compressImage(file) {
        const image = await loadImage(file);
        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');
        const maxWidth = 2000;
        const maxHeight = 2000;
        let { width, height } = image;

        const ratio = Math.min(maxWidth / width, maxHeight / height, 1);
        width = Math.round(width * ratio);
        height = Math.round(height * ratio);

        canvas.width = width;
        canvas.height = height;
        context.drawImage(image, 0, 0, width, height);

        return new Promise(function (resolve) {
            canvas.toBlob(function (blob) {
                const baseName = file.name.replace(/\.[^.]+$/, '');
                resolve(new File([blob], baseName + '.jpg', { type: 'image/jpeg' }));
            }, 'image/jpeg', 0.82);
        });
    }

    input.addEventListener('change', async function () {
        const file = this.files && this.files[0];

        if (!file || !file.type.startsWith('image/')) {
            return;
        }

        if (help) {
            help.textContent = 'กำลังย่อรูปก่อนอัปโหลด...';
        }

        try {
            const resizedFile = await compressImage(file);
            const transfer = new DataTransfer();
            transfer.items.add(resizedFile);
            this.files = transfer.files;

            if (help) {
                help.textContent = 'ย่อรูปแล้ว พร้อมอัปโหลด';
            }
        } catch (error) {
            if (help) {
                help.textContent = 'ย่อรูปไม่สำเร็จ ใช้ไฟล์เดิมแทน';
            }
        }
    });
});
</script>
@endsection

