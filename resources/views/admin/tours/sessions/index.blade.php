@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">

            {{-- Toolbar --}}
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">

                    {{-- TITLE --}}
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">
                            Sessions ของโปรแกรม: {{ $tour->name }}
                        </h1>
                    </div>

                    {{-- BUTTON --}}
                    <div class="d-flex align-items-center gap-2 gap-lg-3">
                        <a href="{{ route('admin.tours.sessions.create', $tour->id) }}" class="btn btn-primary">
                            + เพิ่ม Session
                        </a>
                    </div>
                </div>
            </div>

            {{-- CONTENT --}}
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">

                    <div class="card mb-7">
                        <div class="card-body">

                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Session Time</th>
                                        <th>Start - End</th>
                                        <th>Default Cap.</th>
                                        <th>Override Cap.</th>
                                        <th>Status</th>
                                        <th width="150">จัดการ</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($sessions as $s)
                                        <tr>
                                            <td>{{ $s->title }}</td>
                                            <td>{{ $s->session_time ?? '-' }}</td>

                                            <td>
                                                @if($s->start_time && $s->end_time)
                                                    {{ $s->start_time }} - {{ $s->end_time }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>{{ $s->default_capacity }}</td>
                                            <td>{{ $s->capacity ?? '-' }}</td>

                                            <td>
                                                @if ($s->is_active)
                                                    <span class="badge bg-success">เปิด</span>
                                                @else
                                                    <span class="badge bg-danger">ปิด</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a href="{{ route('admin.tours.sessions.edit', [$tour->id, $s->id]) }}"
                                                   class="btn btn-sm btn-warning">แก้ไข</a>

                                                <form action="{{ route('admin.tours.sessions.destroy', [$tour->id, $s->id]) }}"
                                                      method="POST"
                                                      style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                            onclick="return confirm('ยืนยันลบ Session นี้?')">
                                                        ลบ
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                ยังไม่มี Session สำหรับโปรแกรมนี้
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
