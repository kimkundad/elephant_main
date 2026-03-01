@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">Tour Tags</h1>
        </div>
        <div>
          <a href="{{ route('admin.tour-tags.create') }}" class="btn btn-primary">Create Tag</a>
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
          <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
              <thead>
                <tr class="fw-bold text-muted">
                  <th>ID</th>
                  <th>Thai</th>
                  <th>English</th>
                  <th>Slug</th>
                  <th>Sort</th>
                  <th>Status</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($tags as $tag)
                  <tr>
                    <td>{{ $tag->id }}</td>
                    <td>{{ $tag->name_th }}</td>
                    <td>{{ $tag->name_en }}</td>
                    <td><code>{{ $tag->slug }}</code></td>
                    <td>{{ $tag->sort_order }}</td>
                    <td>
                      @if($tag->is_active)
                        <span class="badge badge-light-success">Active</span>
                      @else
                        <span class="badge badge-light-secondary">Hidden</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <a href="{{ route('admin.tour-tags.edit', $tag) }}" class="btn btn-sm btn-light-primary">Edit</a>
                      <form action="{{ route('admin.tour-tags.destroy', $tag) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this tag?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light-danger">Delete</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="7" class="text-center text-muted">No tags found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

            <div class="mt-4">
              {{ $tags->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

