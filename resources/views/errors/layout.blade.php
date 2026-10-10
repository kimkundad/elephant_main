{{--
  Error pages stand on their own: no site layout, no database, no build
  assets. Whatever broke, this page still has to render, so everything it
  needs is in the file.
--}}
@php
    $isThai = app()->getLocale() === 'th';
@endphp
<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('code') · Small Elephants</title>
    <style>
        :root{ color-scheme: light; }
        *{ box-sizing: border-box; }
        body{
            margin:0;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            padding:32px 20px;
            background:#f7f5f1;
            color:#2b2621;
            font-family:-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height:1.6;
        }
        .err{
            width:100%;
            max-width:520px;
            padding:40px 32px;
            background:#fff;
            border-radius:20px;
            box-shadow:0 24px 60px rgba(0,0,0,.08);
            text-align:center;
        }
        .err__brand{
            margin:0 0 18px;
            font-size:13px;
            font-weight:700;
            letter-spacing:.18em;
            text-transform:uppercase;
            color:#8a7f73;
        }
        .err__code{
            margin:0;
            font-size:64px;
            font-weight:800;
            line-height:1;
            letter-spacing:-.02em;
            color:#b5db2a;
        }
        .err__title{ margin:12px 0 8px; font-size:24px; font-weight:700; }
        .err__text{ margin:0 0 26px; color:#6b6156; }
        .err__actions{ display:flex; flex-wrap:wrap; gap:10px; justify-content:center; }
        .err__btn{
            display:inline-flex;
            align-items:center;
            min-height:46px;
            padding:0 22px;
            border-radius:999px;
            font-size:14px;
            font-weight:700;
            letter-spacing:.06em;
            text-transform:uppercase;
            text-decoration:none;
            background:#b5db2a;
            color:#fff;
        }
        .err__btn--plain{
            background:#fff;
            color:#2b2621;
            border:1px solid rgba(0,0,0,.14);
        }
        @media (max-width:420px){
            .err{ padding:32px 20px; }
            .err__code{ font-size:52px; }
            .err__title{ font-size:20px; }
        }
    </style>
</head>
<body>
    <main class="err">
        <p class="err__brand">Small Elephants</p>

        <p class="err__code">@yield('code')</p>
        <h1 class="err__title">@yield('title')</h1>
        <p class="err__text">@yield('message')</p>

        <div class="err__actions">
            <a class="err__btn" href="{{ url('/') }}">{{ $isThai ? 'กลับหน้าแรก' : 'Back to home' }}</a>
            <a class="err__btn err__btn--plain" href="{{ url('/programs') }}">{{ $isThai ? 'ดูโปรแกรมทัวร์' : 'See our tours' }}</a>
        </div>
    </main>
</body>
</html>
