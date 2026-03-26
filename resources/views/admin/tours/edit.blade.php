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
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
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

