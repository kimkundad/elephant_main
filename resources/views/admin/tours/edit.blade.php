@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">Edit Tour Program</h1>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <style>
                    .tour-gallery-upload-card { border: 1px solid #e9edf3; border-radius: 12px; padding: 24px; background: #fff; }
                    .tour-gallery-upload-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
                    .tour-gallery-upload-input { display: none; }
                    .tour-gallery-file-list { display: flex; flex-direction: column; gap: 10px; }
                    .tour-gallery-file-item, .tour-gallery-current-item { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 16px; border-radius: 10px; background: #f5f8fc; border: 1px solid #eef2f7; }
                    .tour-gallery-file-meta { min-width: 0; }
                    .tour-gallery-file-name { font-weight: 600; color: #2b3445; word-break: break-word; }
                    .tour-gallery-file-size { color: #7e8299; font-size: 12px; }
                    .tour-gallery-file-remove { border: 0; background: transparent; color: #7e8299; font-size: 20px; line-height: 1; }
                    .tour-gallery-empty { color: #7e8299; font-size: 13px; padding: 14px 0 4px; }
                    .tour-gallery-current-thumb { width: 76px; height: 56px; object-fit: cover; border-radius: 8px; border: 1px solid #e9edf3; }
                </style>

                <div class="card mb-7">
                    <div class="card-body">
                        <form action="{{ route('admin.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data" class="js-tour-form">
                            @csrf
                            @method('PUT')

                            <div class="row g-4 mb-1">
                                <div class="col-md-12"><label class="form-label">Program Name (TH) *</label><input type="text" name="name_th" class="form-control" value="{{ old('name_th', $translationTh->name ?? $tour->name) }}" required></div>
                                <div class="col-md-12"><label class="form-label">Program Name (EN) *</label><input type="text" name="name_en" class="form-control" value="{{ old('name_en', $translationEn->name ?? $tour->name) }}" required></div>
                            </div>

                            <div class="row g-4 mb-1">
                                <div class="col-md-12"><label class="form-label">Short Description (TH)</label><textarea name="short_description_th" class="form-control" rows="4">{{ old('short_description_th', $translationTh->short_description ?? $tour->short_description) }}</textarea></div>
                                <div class="col-md-12"><label class="form-label">Short Description (EN)</label><textarea name="short_description_en" class="form-control" rows="4">{{ old('short_description_en', $translationEn->short_description ?? $tour->short_description) }}</textarea></div>
                            </div>

                            <div class="row g-4 mb-3">
                                <div class="col-md-12"><label class="form-label">Full Description (TH)</label><textarea name="description_th" class="form-control" rows="6">{{ old('description_th', $translationTh->description ?? $tour->description) }}</textarea></div>
                                <div class="col-md-12"><label class="form-label">Full Description (EN)</label><textarea name="description_en" class="form-control" rows="6">{{ old('description_en', $translationEn->description ?? $tour->description) }}</textarea></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-block">Tags</label>
                                <div class="row g-2">
                                    @php($selectedTagIds = old('tag_ids', $tour->tags->pluck('id')->all()))
                                    @forelse(($tags ?? []) as $tag)
                                        <div class="col-md-4"><label class="form-check form-check-custom form-check-sm"><input class="form-check-input" type="checkbox" name="tag_ids[]" value="{{ $tag->id }}" {{ in_array($tag->id, $selectedTagIds, true) ? 'checked' : '' }}><span class="form-check-label ms-2">{{ $tag->name_th }} @if($tag->name_en)<small class="text-muted">({{ $tag->name_en }})</small>@endif</span></label></div>
                                    @empty
                                        <div class="col-12 text-muted">No tags found. Please create tags first.</div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3"><label class="form-label">Child Price *</label><input type="number" name="min_price" class="form-control" value="{{ $tour->min_price }}" required></div>
                                <div class="col-md-6 mb-3"><label class="form-label">Adult Price *</label><input type="number" name="max_price" class="form-control" value="{{ $tour->max_price }}" required></div>
                            </div>

                            <div class="mb-3"><label class="form-label">Current Thumbnail</label><br><img src="{{ $tour->thumbnail }}" width="150" class="rounded mb-3"></div>
                            <div class="mb-3"><label class="form-label">Upload New Thumbnail (optional)</label><input type="file" name="thumbnail" class="form-control js-tour-thumbnail" accept="image/*"><div class="form-text js-thumbnail-help">The system will optimize the image before upload.</div></div>

                            <div class="mb-4">
                                <label class="form-label d-block">Tour Gallery Images</label>
                                <div class="tour-gallery-upload-card">
                                    <div class="row align-items-start g-4">
                                        <div class="col-md-2"><div class="pt-2 fw-semibold">Upload Files:</div></div>
                                        <div class="col-md-10">
                                            <div class="tour-gallery-upload-actions"><button type="button" class="btn btn-primary js-gallery-attach">Attach files</button><button type="button" class="btn btn-light-info js-gallery-upload-all">Upload All</button><button type="button" class="btn btn-light js-gallery-remove-all">Remove All</button></div>
                                            <input type="file" name="gallery_images[]" class="tour-gallery-upload-input js-tour-gallery-input" accept="image/*" multiple>
                                            <div class="tour-gallery-file-list js-tour-gallery-file-list"></div>
                                            <div class="tour-gallery-empty js-tour-gallery-empty">No new gallery files selected.</div>
                                            <div class="form-text mt-3">Max file size is 10MB and max number of files is 10.</div>

                                            @if(!empty($tour->gallery_images))
                                                <div class="mt-5">
                                                    <div class="fw-semibold mb-3">Current Gallery</div>
                                                    <div class="tour-gallery-file-list">
                                                        @foreach($tour->gallery_images as $galleryImage)
                                                            <div class="tour-gallery-current-item">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <img src="{{ $galleryImage }}" alt="Gallery image" class="tour-gallery-current-thumb">
                                                                    <div class="tour-gallery-file-meta">
                                                                        <div class="tour-gallery-file-name">{{ basename(parse_url($galleryImage, PHP_URL_PATH)) }}</div>
                                                                        <div class="tour-gallery-file-size">Keep this image unless checked for removal.</div>
                                                                    </div>
                                                                </div>
                                                                <label class="form-check form-check-custom form-check-sm mb-0">
                                                                    <input class="form-check-input" type="checkbox" name="remove_gallery_images[]" value="{{ $galleryImage }}">
                                                                    <span class="form-check-label ms-2">Remove</span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3"><label class="form-label">Display Status</label><select name="is_active" class="form-select"><option value="1" @selected($tour->is_active)>Active</option><option value="0" @selected(!$tour->is_active)>Hidden</option></select></div>
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
    const galleryInput = document.querySelector('.js-tour-gallery-input');
    const galleryList = document.querySelector('.js-tour-gallery-file-list');
    const galleryEmpty = document.querySelector('.js-tour-gallery-empty');
    const attachButton = document.querySelector('.js-gallery-attach');
    const uploadAllButton = document.querySelector('.js-gallery-upload-all');
    const removeAllButton = document.querySelector('.js-gallery-remove-all');
    const form = document.querySelector('.js-tour-form');
    let galleryFiles = [];
    if (!input || typeof DataTransfer === 'undefined') return;
    function loadImage(file) { return new Promise(function (resolve, reject) { const reader = new FileReader(); reader.onload = function (event) { const image = new Image(); image.onload = function () { resolve(image); }; image.onerror = reject; image.src = event.target.result; }; reader.onerror = reject; reader.readAsDataURL(file); }); }
    async function compressImage(file) { const image = await loadImage(file); const canvas = document.createElement('canvas'); const context = canvas.getContext('2d'); const ratio = Math.min(2000 / image.width, 2000 / image.height, 1); const width = Math.round(image.width * ratio); const height = Math.round(image.height * ratio); canvas.width = width; canvas.height = height; context.drawImage(image, 0, 0, width, height); return new Promise(function (resolve) { canvas.toBlob(function (blob) { const baseName = file.name.replace(/\.[^.]+$/, ''); resolve(new File([blob], baseName + '.jpg', { type: 'image/jpeg' })); }, 'image/jpeg', 0.82); }); }
    function formatFileSize(file) { return (file.size / (1024 * 1024)).toFixed(1) + ' MB'; }
    function syncGalleryInput() { if (!galleryInput) return; const transfer = new DataTransfer(); galleryFiles.forEach(function (file) { transfer.items.add(file); }); galleryInput.files = transfer.files; }
    function renderGalleryFiles() { if (!galleryList || !galleryEmpty) return; galleryList.innerHTML = ''; if (!galleryFiles.length) { galleryEmpty.style.display = ''; return; } galleryEmpty.style.display = 'none'; galleryFiles.forEach(function (file, index) { const item = document.createElement('div'); item.className = 'tour-gallery-file-item'; item.innerHTML = `<div class="tour-gallery-file-meta"><div class="tour-gallery-file-name">${file.name}</div><div class="tour-gallery-file-size">(${formatFileSize(file)})</div></div><button type="button" class="tour-gallery-file-remove" data-index="${index}">&times;</button>`; galleryList.appendChild(item); }); }
    input.addEventListener('change', async function () { const file = this.files && this.files[0]; if (!file || !file.type.startsWith('image/')) return; help && (help.textContent = 'Optimizing image before upload...'); try { const resizedFile = await compressImage(file); const transfer = new DataTransfer(); transfer.items.add(resizedFile); this.files = transfer.files; help && (help.textContent = 'Image optimized and ready to upload.'); } catch (error) { help && (help.textContent = 'Could not optimize image. Using original file instead.'); } });
    attachButton?.addEventListener('click', function () {
        const picker = document.createElement('input');
        picker.type = 'file';
        picker.accept = 'image/*';
        picker.multiple = true;
        picker.addEventListener('change', async function () {
            const removeChecked = document.querySelectorAll('input[name="remove_gallery_images[]"]:checked').length;
            const currentCount = document.querySelectorAll('input[name="remove_gallery_images[]"]').length;
            const availableSlots = Math.max(0, 10 - (currentCount - removeChecked) - galleryFiles.length);
            const selectedFiles = Array.from(picker.files || []).filter(function (file) { return file.type.startsWith('image/'); }).slice(0, availableSlots);
            const compressedFiles = [];
            for (const file of selectedFiles) {
                try { compressedFiles.push(await compressImage(file)); } catch (error) { compressedFiles.push(file); }
            }
            galleryFiles = galleryFiles.concat(compressedFiles).slice(0, 10);
            syncGalleryInput();
            renderGalleryFiles();
        });
        picker.click();
    });
    uploadAllButton?.addEventListener('click', function () {
        const hasRemoveSelection = document.querySelectorAll('input[name="remove_gallery_images[]"]:checked').length > 0;

        if (!galleryFiles.length && !hasRemoveSelection) {
            alert('Please select gallery images first.');
            galleryInput?.click();
            return;
        }

        form?.submit();
    });
    removeAllButton?.addEventListener('click', function () { galleryFiles = []; syncGalleryInput(); renderGalleryFiles(); });
    galleryList?.addEventListener('click', function (event) { const removeButton = event.target.closest('.tour-gallery-file-remove'); if (!removeButton) return; const index = Number(removeButton.getAttribute('data-index')); galleryFiles = galleryFiles.filter(function (_, fileIndex) { return fileIndex !== index; }); syncGalleryInput(); renderGalleryFiles(); });
    renderGalleryFiles();
});
</script>
@endsection
