

<script>
var defaultThemeMode = "light";
var themeMode;
if ( document.documentElement )
{
    if ( document.documentElement.hasAttribute("data-theme-mode"))
    {
        themeMode = document.documentElement.getAttribute("data-theme-mode");
    } else {

        if ( localStorage.getItem("data-theme") !== null ) { themeMode = localStorage.getItem("data-theme");
        } else {
            themeMode = defaultThemeMode;
        }

    } if (themeMode === "system") {
        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
    }
    document.documentElement.setAttribute("data-theme", themeMode);
}
            </script>

<!--begin::Javascript-->
<script>var hostUrl = "{{ asset('build/admin') }}/";</script>
@vite('resources/admin/js/app.js')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    @if ($message = Session::get('add_success'))
    Swal.fire({
        text: "ระบบได้ทำการอัพเดทข้อมูลสำเร็จ!",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
    @endif

    @if ($message = Session::get('edit_success'))
    Swal.fire({
        text: "ระบบได้ทำการอัพเดทข้อมูลสำเร็จ!",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
    @endif

    @if ($message = Session::get('del_success'))
    Swal.fire({
        text: "ระบบได้ทำการลบข้อมูลสำเร็จ!",
        icon: "success",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
    @endif

    @if ($message = Session::get('edit_error'))
    Swal.fire({
        text: "ไม่สามารถลบรายการนี้ได้!",
        icon: "error",
        buttonsStyling: false,
        confirmButtonText: "Ok, got it!",
        customClass: {
            confirmButton: "btn btn-primary"
        }
    });
    @endif



</script>
<!--end::Custom Javascript-->
<!--end::Javascript-->
