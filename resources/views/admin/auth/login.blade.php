<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>
    @include('partials.admin.inc-style')
</head>

<body class="app-blank">

<div class="d-flex flex-column flex-root" id="kt_app_root">
    <div class="d-flex flex-column flex-center flex-column-fluid p-10">

        <div class="w-lg-500px p-10 bg-white rounded shadow-sm">

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="text-center mb-10">
                    <h1 class="text-dark fw-bolder mb-3">Admin Login</h1>
                    <div class="text-gray-500 fw-semibold fs-6">เข้าสู่ระบบจัดการหลังบ้าน</div>
                </div>

                {{-- Email --}}
                <div class="fv-row mb-10">
                    <label class="form-label fw-bolder text-dark">Email</label>
                    <input class="form-control form-control-lg" type="email" name="email" required autofocus />

                    @error('email')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="fv-row mb-10">
                    <label class="form-label fw-bolder text-dark">Password</label>
                    <input class="form-control form-control-lg" type="password" name="password" required />
                </div>

                {{-- Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                {{-- Submit --}}
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">
                        เข้าสู่ระบบ
                    </button>
                </div>
            </form>

        </div>

    </div>
</div>

@include('partials.admin.inc-script')

</body>
</html>
