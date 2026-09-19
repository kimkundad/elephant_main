<div id="kt_app_sidebar" class="app-sidebar flex-column bg-dark text-white" data-kt-drawer="true"
    data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
    data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a target="_blank" href="{{ url('/') }}">


            @if(!empty($siteSetting?->logo_header_url))
                <img src="{{ $siteSetting->logo_header_url }}" alt="Logo" class="h-35px app-sidebar-logo-default" />
                <img src="{{ $siteSetting->logo_header_url }}" alt="Logo" class="h-35px app-sidebar-logo-minimize" />
            @else
                <img src="{{ url('img/logo.webp') }}" alt="Logo" class="h-35px app-sidebar-logo-default" />
                <img src="{{ url('img/logo.webp') }}" alt="Logo" class="h-35px app-sidebar-logo-minimize" />
            @endif


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

    @php
        // Duotone icons, drawn inline so the sidebar needs no icon files.
        $menuIcons = [
            'grid' => '<path opacity="0.3" d="M3 3h8v8H3V3Zm10 0h8v5h-8V3Z" fill="currentColor"/><path d="M3 13h8v8H3v-8Zm10-3h8v11h-8V10Z" fill="currentColor"/>',
            'booking' => '<path opacity="0.3" d="M19 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2Z" fill="currentColor"/><path d="M7 8h10v2H7V8Zm0 4h10v2H7v-2Zm0 4h6v2H7v-2Z" fill="currentColor"/>',
            'customer' => '<path opacity="0.3" d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" fill="currentColor"/><path d="M12 14c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z" fill="currentColor"/>',
            'agent' => '<path opacity="0.3" d="M10 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" fill="currentColor"/><path d="M10 14c-4 0-7 2-7 4.5V20h14v-1.5C17 16 14 14 10 14Zm11.3-5.3-3.6 3.6-1.7-1.7-1.4 1.4 3.1 3.1 5-5-1.4-1.4Z" fill="currentColor"/>',
            'discount' => '<path opacity="0.3" d="m21 11.6-9.6-9.6H4a2 2 0 0 0-2 2v7.4l9.6 9.6a2 2 0 0 0 2.8 0l6.6-6.6a2 2 0 0 0 0-2.8Z" fill="currentColor"/><path d="M7 9a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" fill="currentColor"/>',
            'report' => '<path opacity="0.3" d="M4 4h2v16H4V4Zm14 6h2v10h-2V10Z" fill="currentColor"/><path d="M9 8h2v12H9V8Zm4.5 4h2v8h-2v-8Z" fill="currentColor"/>',
            'tour' => '<path opacity="0.3" d="m9 4 6 2 5-2v14l-5 2-6-2-5 2V6l5-2Z" fill="currentColor"/><path d="M9 4v14l6 2V6L9 4Z" fill="currentColor"/>',
            'session' => '<path opacity="0.3" d="M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20Z" fill="currentColor"/><path d="M13 7h-2v6l4.2 2.5 1-1.7-3.2-1.9V7Z" fill="currentColor"/>',
            'tag' => '<path opacity="0.3" d="M20 12.2 11.8 4H5a1 1 0 0 0-1 1v6.8l8.2 8.2a1 1 0 0 0 1.4 0l6.4-6.4a1 1 0 0 0 0-1.4Z" fill="currentColor"/><path d="M7.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" fill="currentColor"/>',
            'province' => '<path opacity="0.3" d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7Z" fill="currentColor"/><path d="M12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" fill="currentColor"/>',
            'pickup' => '<path opacity="0.3" d="M4 6a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v3h2.2a2 2 0 0 1 1.7 1l1.6 2.7c.3.4.5 1 .5 1.5V17a1 1 0 0 1-1 1H4a2 2 0 0 1-2-2V6Z" fill="currentColor"/><path d="M7 20a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm11 0a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z" fill="currentColor"/>',
            'elephant' => '<path opacity="0.3" d="M12 21s-7-4.4-7-9.6A4.4 4.4 0 0 1 12 8a4.4 4.4 0 0 1 7 3.4C19 16.6 12 21 12 21Z" fill="currentColor"/><path d="M12 8a4.4 4.4 0 0 0-7 3.4c0 1.5.6 2.9 1.5 4.1C6.2 14 6 12.8 6 11.6 6 9.6 7.6 8 9.6 8c.9 0 1.7.3 2.4.8V8Z" fill="currentColor"/>',
            'review' => '<path opacity="0.3" d="M12 3l2.6 5.27 5.82.85-4.21 4.11.99 5.79L12 16.29 6.8 19.02l.99-5.79-4.21-4.11 5.82-.85L12 3Z" fill="currentColor"/><path d="M12 5.84l1.45 2.93 3.23.47-2.34 2.28.55 3.21L12 13.21l-2.89 1.52.55-3.21-2.34-2.28 3.23-.47L12 5.84Z" fill="currentColor"/>',
            'text' => '<path opacity="0.3" d="M18 21H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h10l4 4v12a2 2 0 0 1-2 2Z" fill="currentColor"/><path d="M8 11h8v2H8v-2Zm0 4h8v2H8v-2Zm7-12v5h5l-5-5Z" fill="currentColor"/>',
            'media' => '<path opacity="0.3" d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Z" fill="currentColor"/><path d="M8.5 10a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM4 18l4.5-5 3 3.2L15 12l5 6H4Z" fill="currentColor"/>',
            'settings' => '<path opacity="0.3" d="m20.5 13.4-1.7-1a7.4 7.4 0 0 0 0-2l1.7-1a1 1 0 0 0 .4-1.3l-1.6-2.8a1 1 0 0 0-1.3-.4l-1.7 1a7.5 7.5 0 0 0-1.7-1V3a1 1 0 0 0-1-1h-3.2a1 1 0 0 0-1 1v2a7.5 7.5 0 0 0-1.7.9l-1.7-1a1 1 0 0 0-1.3.4L3.1 8.1a1 1 0 0 0 .4 1.3l1.7 1a7.4 7.4 0 0 0 0 2l-1.7 1a1 1 0 0 0-.4 1.3l1.6 2.8a1 1 0 0 0 1.3.4l1.7-1c.5.4 1.1.7 1.7 1v2a1 1 0 0 0 1 1h3.2a1 1 0 0 0 1-1v-2c.6-.3 1.2-.6 1.7-1l1.7 1a1 1 0 0 0 1.3-.4l1.6-2.8a1 1 0 0 0-.4-1.3Z" fill="currentColor"/><path d="M12 15.2a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4Z" fill="currentColor"/>',
            'users' => '<path opacity="0.3" d="M9 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Zm8 .5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" fill="currentColor"/><path d="M9 13c-3.9 0-7 1.9-7 4.2V19h14v-1.8c0-2.3-3.1-4.2-7-4.2Zm8 .5c-.7 0-1.4.1-2 .2 1.2.9 2 2.1 2 3.5V19h5v-1.6c0-2.1-2.2-3.9-5-3.9Z" fill="currentColor"/>',
            'logout' => '<path opacity="0.3" d="M13 3H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h7a1 1 0 1 0 0-2H6V5h7a1 1 0 1 0 0-2Z" fill="currentColor"/><path d="m17.6 8.6 2.7 2.7a1 1 0 0 1 0 1.4l-2.7 2.7-1.4-1.4 1-1H10v-2h7.2l-1-1 1.4-1.4Z" fill="currentColor"/>',
        ];

        // Grouped so related screens sit next to each other in the sidebar.
        $menuGroups = [
            [
                'label' => 'ภาพรวม',
                'items' => [
                    ['title' => 'Dashboard', 'url' => url('admin/dashboard'), 'icon' => 'grid'],
                ],
            ],
            [
                'label' => 'การขาย',
                'items' => [
                    ['title' => 'Bookings', 'url' => route('admin.bookings.index'), 'icon' => 'booking'],
                    ['title' => 'Customers', 'url' => url('admin/customers'), 'icon' => 'customer'],
                    ['title' => 'Sales Agents', 'url' => route('admin.agents.index'), 'icon' => 'agent'],
                    ['title' => 'Discount Codes', 'url' => route('admin.discount-codes.index'), 'icon' => 'discount'],
                    ['title' => 'Agent Reports', 'url' => route('admin.reports.agents'), 'icon' => 'report'],
                ],
            ],
            [
                'label' => 'ทัวร์',
                'items' => [
                    ['title' => 'Program List', 'url' => route('admin.tours.index'), 'icon' => 'tour'],
                    ['title' => 'Tour Sessions', 'url' => route('admin.sessions.all'), 'icon' => 'session'],
                    ['title' => 'Tour Tags', 'url' => route('admin.tour-tags.index'), 'icon' => 'tag'],
                    ['title' => 'Provinces / จังหวัด', 'url' => route('admin.provinces.index'), 'icon' => 'province'],
                    ['title' => 'Pick-up Locations', 'url' => route('admin.pickup-locations.index'), 'icon' => 'pickup'],
                ],
            ],
            [
                'label' => 'เนื้อหาเว็บ',
                'items' => [
                    ['title' => 'Elephants', 'url' => route('admin.elephants.index'), 'icon' => 'elephant'],
                    ['title' => 'Reviews', 'url' => route('admin.reviews.index'), 'icon' => 'review'],
                    ['title' => 'Site Texts (Home)', 'url' => route('admin.site-texts.home'), 'icon' => 'text'],
                    ['title' => 'Site Texts (About)', 'url' => route('admin.site-texts.about'), 'icon' => 'text'],
                    ['title' => 'Page Media', 'url' => route('admin.page-media.index'), 'icon' => 'media'],
                ],
            ],
            [
                'label' => 'ระบบ',
                'items' => [
                    ['title' => 'Site Settings', 'url' => route('admin.settings.edit'), 'icon' => 'settings'],
                    ['title' => 'Users', 'url' => url('admin/users'), 'icon' => 'users'],
                    ['title' => 'Logout', 'url' => url('admin/logout'), 'icon' => 'logout'],
                ],
            ],
        ];
    @endphp

    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">

                @foreach($menuGroups as $group)
                    <div class="menu-item pt-4">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-8 text-muted">{{ $group['label'] }}</span>
                        </div>
                    </div>

                    @foreach($group['items'] as $item)
                        @php($isActive = rtrim(request()->url(), '/') === rtrim($item['url'], '/'))
                        <div class="menu-item">
                            <a class="menu-link {{ $isActive ? 'active' : '' }}" href="{{ $item['url'] }}">
                                <span class="menu-icon">
                                    <span class="svg-icon svg-icon-2">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            {!! $menuIcons[$item['icon']] !!}
                                        </svg>
                                    </span>
                                </span>
                                <span class="menu-title">{{ $item['title'] }}</span>
                            </a>
                        </div>
                    @endforeach
                @endforeach

            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>
