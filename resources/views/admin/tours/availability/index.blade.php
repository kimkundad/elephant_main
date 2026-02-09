@extends('partials.admin.template')

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Header --}}
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">

                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading fs-3 fw-bold">Availability รายวัน - {{ $tour->name }}</h1>
                </div>

                <form method="GET">
                    <input type="date" name="date" value="{{ $date }}" class="form-control">
                </form>

            </div>
        </div>

        {{-- Content --}}
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <form method="POST" action="{{ route('admin.tours.availability.store', $tour->id) }}">
                    @csrf

                    <input type="hidden" name="date" value="{{ $date }}">

                    <div class="card">
                        <div class="card-body">

                            <h4 class="mb-4">วันที่: {{ $date }}</h4>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Session</th>
                                        <th>เวลา</th>
                                        <th>เปิด?</th>
                                        <th width="200">Capacity Override</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($sessions as $session)

                                        @php
                                            $av = $availabilities[$session->id] ?? null;
                                        @endphp

                                        <tr>
                                            <td>{{ $session->title ?? $session->name }}</td>
                                            <td>{{ $session->start_time }} - {{ $session->end_time }}</td>

                                            <td>
                                                <input type="hidden" name="sessions[{{ $session->id }}][is_open]" value="1">
                                                    <label>
                                                    <input type="checkbox"
                                                            name="sessions[{{ $session->id }}][is_open]"
                                                            value="0"
                                                            {{ (isset($availabilities[$session->id]) && $availabilities[$session->id]->is_open == 0) ? 'checked' : '' }}>
                                                    ปิดรอบนี้ (เฉพาะวันนี้)
                                                    </label>
                                            </td>

                                            <td>
                                                <input type="number"
                                                       name="sessions[{{ $session->id }}][capacity_override]"
                                                       class="form-control"
                                                       value="{{ $av?->capacity_override }}">
                                            </td>
                                        </tr>

                                    @endforeach
                                </tbody>

                            </table>

                            <button class="btn btn-primary mt-4">บันทึกข้อมูล</button>

                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
document.querySelector("input[name=date]").addEventListener("change", function () {
    this.form.submit();
});
</script>


@endsection
