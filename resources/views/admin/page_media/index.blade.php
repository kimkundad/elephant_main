@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">Page Media</h1>
        </div>
        <div>
          <a href="{{ route('admin.page-media.create') }}" class="btn btn-primary">Add Media</a>
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

        <div class="card mb-6">
          <div class="card-body">
            <form method="GET" action="{{ route('admin.page-media.index') }}" class="row g-3">
              <div class="col-md-6">
                <input class="form-control" name="q" placeholder="Search key/title/path" value="{{ $filters['q'] ?? '' }}">
              </div>
              <div class="col-md-2">
                <select class="form-select" name="locale">
                  <option value="all" @selected(($filters['locale'] ?? 'all') === 'all')>All Locales</option>
                  <option value="" @selected(($filters['locale'] ?? 'all') === '')>Global</option>
                  <option value="th" @selected(($filters['locale'] ?? '') === 'th')>Thai</option>
                  <option value="en" @selected(($filters['locale'] ?? '') === 'en')>English</option>
                </select>
              </div>
              <div class="col-md-2">
                <select class="form-select" name="type">
                  <option value="" @selected(($filters['type'] ?? '') === '')>All Types</option>
                  <option value="image" @selected(($filters['type'] ?? '') === 'image')>Image</option>
                  <option value="video" @selected(($filters['type'] ?? '') === 'video')>Video</option>
                  <option value="file" @selected(($filters['type'] ?? '') === 'file')>File</option>
                </select>
              </div>
              <div class="col-md-2">
                <button class="btn btn-light-primary w-100" type="submit">Filter</button>
              </div>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-body table-responsive">
            <table class="table table-row-dashed align-middle">
              <thead>
                <tr>
                  <th>Key</th>
                  <th>Locale</th>
                  <th>Type</th>
                  <th>Status</th>
                  <th>File</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($mediaItems as $item)
                  <tr>
                    <td>
                      <div class="fw-bold">{{ $item->key }}</div>
                      @if(!empty($keyDescriptions[$item->key] ?? null))
                        <div class="text-muted fs-7">{{ $keyDescriptions[$item->key] }}</div>
                      @endif
                      @if($item->title)
                        <div class="text-muted fs-7">{{ $item->title }}</div>
                      @endif
                    </td>
                    <td>{{ $item->locale === '' ? 'Global' : strtoupper($item->locale) }}</td>
                    <td>{{ ucfirst($item->type) }}</td>
                    <td>
                      @if($item->is_active)
                        <span class="badge badge-light-success">Active</span>
                      @else
                        <span class="badge badge-light-secondary">Inactive</span>
                      @endif
                    </td>
                    <td>
                      @if($item->path)
                        <a href="{{ \Illuminate\Support\Facades\Storage::disk($item->disk ?: 'spaces')->url($item->path) }}" target="_blank">
                          View
                        </a>
                      @else
                        <span class="text-muted">N/A</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <a href="{{ route('admin.page-media.edit', $item) }}" class="btn btn-sm btn-light-primary">Edit</a>
                      <form action="{{ route('admin.page-media.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this media?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-light-danger" type="submit">Delete</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center text-muted py-8">No media found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

            <div class="mt-4">
              {{ $mediaItems->links('pagination::bootstrap-5') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
