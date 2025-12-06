@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Toolbar --}}
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container"
                 class="app-container container-xxl d-flex flex-stack">

                {{-- TITLE --}}
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">
                        แก้ไข Session: {{ $session->title ?? 'ไม่มีชื่อ' }}
                    </h1>
                    <div class="text-muted fs-7">
                        โปรแกรม: {{ $tour->name }}
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('admin.tours.sessions.index', $tour->id) }}"
                       class="btn btn-light">
                        ย้อนกลับ
                    </a>
                </div>

            </div>
        </div>

        {{-- Content --}}
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="card mb-7">
                    <div class="card-body">

                        <form action="{{ route('admin.tours.sessions.update', [$tour->id, $session->id]) }}"
                              method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Title --}}
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text"
                                       name="title"
                                       class="form-control"
                                       value="{{ old('title', $session->title) }}">
                            </div>

                            {{-- Internal Name --}}
                            <div class="mb-3">
                                <label class="form-label">Internal Name (name)</label>
                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ old('name', $session->name) }}">
                            </div>

                            {{-- Times --}}
                            <div class="row mb-4">


                                <div class="col-md-6">
                                    <label class="form-label">Start Time</label>
                                    <input type="time"
                                           name="start_time"
                                           class="form-control"
                                           value="{{ old('start_time', $session->start_time) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">End Time</label>
                                    <input type="time"
                                           name="end_time"
                                           class="form-control"
                                           value="{{ old('end_time', $session->end_time) }}">
                                </div>
                            </div>

                            {{-- Capacity --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Default Capacity *</label>
                                    <input type="number"
                                           name="default_capacity"
                                           class="form-control"
                                           value="{{ old('default_capacity', $session->default_capacity) }}"
                                           required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Capacity Override</label>
                                    <input type="number"
                                           name="capacity"
                                           class="form-control"
                                           value="{{ old('capacity', $session->capacity) }}">
                                </div>
                            </div>

                            {{-- Active --}}
                            <div class="mb-4">
                                <label class="form-label">สถานะ</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" @selected($session->is_active == 1)>เปิด</option>
                                    <option value="0" @selected($session->is_active == 0)>ปิด</option>
                                </select>
                            </div>

                            {{-- Submit --}}
                            <button class="btn btn-primary">บันทึกการแก้ไข</button>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
