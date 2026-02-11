<div id="kt_app_sidebar" class="app-sidebar flex-column bg-dark text-white" data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a target="_blank" href="{{ url('/') }}">


            <img src="{{ url('img/logo.webp') }}" alt="Logo" class="h-35px app-sidebar-logo-default" />

            <img src="{{ url('img/logo.webp') }}" alt="Logo" class="h-35px app-sidebar-logo-minimize" />


        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">

                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="{{ url('admin/dashboard') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                        d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                        d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                    <!--end:Menu link-->
                </div>


                {{-- เมนูโปรแกรมทัวร์ --}}
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                    <span class="menu-link">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3"
                                        d="M18 21H6C4.9 21 4 20.1 4 19V5C4 3.9 4.9 3 6 3H16L20 7V19C20 20.1 19.1 21 18 21Z"
                                        fill="currentColor" />
                                    <path d="M15 3V8H20" fill="currentColor" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">โปรแกรมทัวร์</span>
                        <span class="menu-arrow"></span>
                    </span>

                    {{-- เมนูย่อย --}}
                    <div class="menu-sub menu-sub-accordion">

                        {{-- รายการโปรแกรมทัวร์ --}}
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('admin.tours.index') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">รายการโปรแกรม</span>
                            </a>
                        </div>

                        {{-- Sessions ของโปรแกรมทั้งหมด --}}
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('admin.sessions.all') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">Sessions ของโปรแกรม</span>
                            </a>
                        </div>

                    </div>
                </div>


                <div class="menu-item">
                    <a class="menu-link" href="{{ url('admin/customers') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/communication/com013.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6.28548 15.0861C7.34369 13.1814 9.35142 12 11.5304 12H12.4696C14.6486 12 16.6563 13.1814 17.7145 15.0861L19.3493 18.0287C20.0899 19.3618 19.1259 21 17.601 21H6.39903C4.87406 21 3.91012 19.3618 4.65071 18.0287L6.28548 15.0861Z"
                                        fill="currentColor"></path>
                                    <rect opacity="0.3" x="8" y="3" width="8" height="8" rx="4"
                                        fill="currentColor"></rect>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">รายชื่อลูกค้า</span>
                    </a>
                </div>



                <div class="menu-item">
                    <a class="menu-link" href="{{ url('admin/users') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="8" r="4" fill="currentColor" />
                                    <rect x="6" y="16" width="12" height="2" rx="1"
                                        fill="currentColor" />
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">ผู้ใช้งานทั้งหมด</span>
                    </a>
                </div>


                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.bookings.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                {{-- ไอคอนจอง --}}
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <rect x="4" y="4" width="16" height="16" rx="2" fill="currentColor"/>
                                    <rect x="7" y="8" width="10" height="2" fill="white"/>
                                    <rect x="7" y="12" width="6" height="2" fill="white"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">รายการจอง (Bookings)</span>
                    </a>
                </div>


                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.pickup-locations.index') }}">
                        <span class="menu-icon">
												<!--begin::Svg Icon | path: icons/duotune/communication/com004.svg-->
												<span class="svg-icon svg-icon-2">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path opacity="0.3" d="M14 3V20H2V3C2 2.4 2.4 2 3 2H13C13.6 2 14 2.4 14 3ZM11 13V11C11 9.7 10.2 8.59995 9 8.19995V7C9 6.4 8.6 6 8 6C7.4 6 7 6.4 7 7V8.19995C5.8 8.59995 5 9.7 5 11V13C5 13.6 4.6 14 4 14V15C4 15.6 4.4 16 5 16H11C11.6 16 12 15.6 12 15V14C11.4 14 11 13.6 11 13Z" fill="currentColor"></path>
														<path d="M2 20H14V21C14 21.6 13.6 22 13 22H3C2.4 22 2 21.6 2 21V20ZM9 3V2H7V3C7 3.6 7.4 4 8 4C8.6 4 9 3.6 9 3ZM6.5 16C6.5 16.8 7.2 17.5 8 17.5C8.8 17.5 9.5 16.8 9.5 16H6.5ZM21.7 12C21.7 11.4 21.3 11 20.7 11H17.6C17 11 16.6 11.4 16.6 12C16.6 12.6 17 13 17.6 13H20.7C21.2 13 21.7 12.6 21.7 12ZM17 8C16.6 8 16.2 7.80002 16.1 7.40002C15.9 6.90002 16.1 6.29998 16.6 6.09998L19.1 5C19.6 4.8 20.2 5 20.4 5.5C20.6 6 20.4 6.60005 19.9 6.80005L17.4 7.90002C17.3 8.00002 17.1 8 17 8ZM19.5 19.1C19.4 19.1 19.2 19.1 19.1 19L16.6 17.9C16.1 17.7 15.9 17.1 16.1 16.6C16.3 16.1 16.9 15.9 17.4 16.1L19.9 17.2C20.4 17.4 20.6 18 20.4 18.5C20.2 18.9 19.9 19.1 19.5 19.1Z" fill="currentColor"></path>
													</svg>
												</span>
												<!--end::Svg Icon-->
											</span>
                        <span class="menu-title">จุดรับส่งรถ (Pick-up Locations)</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.settings.edit') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M12 8.5A3.5 3.5 0 1 1 8.5 12 3.5 3.5 0 0 1 12 8.5Z" fill="currentColor"/>
                                    <path opacity="0.3" d="M19.4 13.5a7.8 7.8 0 0 0 0-3l2-1.5-2-3.5-2.4 1a8.6 8.6 0 0 0-2.6-1.5L12 2H8l-.4 3A8.6 8.6 0 0 0 5 6.5l-2.4-1-2 3.5 2 1.5a7.8 7.8 0 0 0 0 3l-2 1.5 2 3.5 2.4-1a8.6 8.6 0 0 0 2.6 1.5L8 22h4l.4-3a8.6 8.6 0 0 0 2.6-1.5l2.4 1 2-3.5-2-1.5Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">ตั้งค่าเว็บไซต์</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.agents.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <circle cx="12" cy="8" r="4" fill="currentColor" />
                                    <path opacity="0.3" d="M4 20C4 16.7 7.1 14 12 14C16.9 14 20 16.7 20 20H4Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">พนักงานขาย</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.discount-codes.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M4 5C4 3.9 4.9 3 6 3H18C19.1 3 20 3.9 20 5V19C20 20.1 19.1 21 18 21H6C4.9 21 4 20.1 4 19V5Z" fill="currentColor"/>
                                    <path d="M8 7H16V9H8V7ZM8 11H16V13H8V11ZM8 15H13V17H8V15Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">โค้ดส่วนลด</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.reports.agents') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M4 4H20V20H4V4Z" fill="currentColor"/>
                                    <path d="M7 7H17V9H7V7ZM7 11H14V13H7V11ZM7 15H12V17H7V15Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">รายงานเอเจนต์</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link" href="{{ route('admin.elephants.index') }}">
                        <span class="menu-icon">
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path opacity="0.3" d="M3 7C3 5.3 4.3 4 6 4H16C18.8 4 21 6.2 21 9V13C21 15.2 19.2 17 17 17H13L10 20H8L9 17H7C4.8 17 3 15.2 3 13V7Z" fill="currentColor"/>
                                    <path d="M8 9.5C8 8.7 8.7 8 9.5 8H12.5C13.3 8 14 8.7 14 9.5V12.5C14 13.3 13.3 14 12.5 14H9.5C8.7 14 8 13.3 8 12.5V9.5Z" fill="currentColor"/>
                                </svg>
                            </span>
                        </span>
                        <span class="menu-title">ข้อมูลช้าง</span>
                    </a>
                </div>


                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="{{ url('admin/logout') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.3"
                                        d="M11.8 5.2L17.7 8.6V15.4L11.8 18.8L5.90001 15.4V8.6L11.8 5.2ZM11.8 2C11.5 2 11.2 2.1 11 2.2L3.8 6.4C3.3 6.7 3 7.3 3 7.9V16.2C3 16.8 3.3 17.4 3.8 17.7L11 21.9C11.3 22 11.5 22.1 11.8 22.1C12.1 22.1 12.4 22 12.6 21.9L19.8 17.7C20.3 17.4 20.6 16.8 20.6 16.2V7.9C20.6 7.3 20.3 6.7 19.8 6.4L12.6 2.2C12.4 2.1 12.1 2 11.8 2Z"
                                        fill="currentColor"></path>
                                    <path d="M11.8 8.69995L8.90001 10.3V13.7L11.8 15.3L14.7 13.7V10.3L11.8 8.69995Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">ออกจากระบบ</span>
                    </a>
                    <!--end:Menu link-->
                </div>



            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>
