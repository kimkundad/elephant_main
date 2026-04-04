@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">Reviews</h1>
        </div>
        <div>
          <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary">Add Review</a>
        </div>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
          <div class="card-body table-responsive">
            <table class="table table-row-bordered align-middle">
              <thead>
                <tr class="fw-bold text-muted">
                  <th>ID</th>
                  <th>Reviewer</th>
                  <th>Source</th>
                  <th>Tour</th>
                  <th>Rating</th>
                  <th>Review</th>
                  <th>Date</th>
                  <th>Status</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                @forelse($reviews as $review)
                  <tr>
                    <td>{{ $review->id }}</td>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <div style="width:40px;height:40px;border-radius:999px;background:{{ $review->avatar_color ?: '#A678A6' }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;">
                          {{ strtoupper(\Illuminate\Support\Str::substr($review->author_name, 0, 1)) }}
                        </div>
                        <div>
                          <div class="fw-bold">{{ $review->author_name }}</div>
                          <div class="text-muted fs-7">{{ $review->author_email ?: ucfirst($review->avatar_variant ?: 'classic') }}</div>
                        </div>
                      </div>
                    </td>
                    <td>
                      @if($review->source === \App\Models\Review::SOURCE_CUSTOMER)
                        <span class="badge badge-light-warning">Customer</span>
                      @else
                        <span class="badge badge-light-primary">Admin</span>
                      @endif
                    </td>
                    <td>
                      @if($review->tour)
                        <div class="fw-semibold">{{ $review->tour->name }}</div>
                      @else
                        <span class="text-muted">All tours / general</span>
                      @endif
                    </td>
                    <td style="color:#f97316;">{{ str_repeat('★', $review->rating) }}</td>
                    <td><div class="text-gray-700">{{ \Illuminate\Support\Str::limit($review->review_text, 110) }}</div></td>
                    <td>{{ optional($review->reviewed_at)->format('d M Y H:i') ?: '-' }}</td>
                    <td>
                      @if($review->is_active)
                        <span class="badge badge-light-success">Approved</span>
                      @else
                        <span class="badge badge-light-secondary">Pending / Hidden</span>
                      @endif
                    </td>
                    <td class="text-end">
                      <form action="{{ route('admin.reviews.toggle-status', $review) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $review->is_active ? 'btn-light-warning' : 'btn-light-success' }}">
                          {{ $review->is_active ? 'Hide' : 'Approve' }}
                        </button>
                      </form>
                      <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-light-primary">Edit</a>
                      <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-light-danger">Delete</button>
                      </form>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="9" class="text-center text-muted py-8">No reviews found.</td>
                  </tr>
                @endforelse
              </tbody>
            </table>

            <div class="mt-4">
              {{ $reviews->links('pagination::bootstrap-5') }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
