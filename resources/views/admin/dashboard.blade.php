@extends('partials.admin.template')

@section('content')
    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <div class="d-flex flex-column flex-column-fluid">


            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">ผู้ใช้งานทั้งหมด</h1>
                    </div>
                </div>
            </div>


            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxl">

                    {{-- กล่องข้อมูล QR --}}
                    <div class="card mb-7">
                        <div class="card-body">

                            @role('superAdmin')
                                <div class="alert alert-primary">
                                    คุณคือ Super Admin (เห็นข้อมูลทั้งหมด)
                                </div>
                            @endrole

                            @role('admin')
                                <div class="alert alert-info">
                                    คุณคือ Admin (จัดการ Tours / Bookings / Pages)
                                </div>
                            @endrole

                            {{-- ตัวอย่างจอการจองวันนี้ --}}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card card-flush">
                                        <div class="card-header">
                                            <h3 class="card-title">Bookings Today</h3>
                                        </div>
                                        <div class="card-body">
                                            {{ $bookingsToday ?? 0 }}
                                        </div>
                                    </div>
                                </div>
                                {{-- etc... --}}
                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection
