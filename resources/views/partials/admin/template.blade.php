<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<!--begin::Head-->
	<head>

		@yield('title')
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		@include('partials.admin.inc-style')
    	@yield('stylesheet')

	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body
    id="kt_app_body"
    data-kt-app-layout="dark-sidebar"
    data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="true"
    data-kt-app-sidebar-fixed="true"
    data-kt-app-sidebar-hoverable="true"
    data-kt-app-sidebar-push-header="true"
    data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true"
    data-kt-app-toolbar-enabled="true"
    class="app-default"
    >
		<!--begin::Theme mode setup on page load-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">

				@include('partials.admin.inc-header')

				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

					@include('partials.admin.inc-slidebar')

					@yield('content')

				</div>
			</div>
		</div>



		@include('partials.admin.inc-script')
    @yield('scripts')



	</body>
</html>
