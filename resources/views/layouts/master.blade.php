<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Trang chủ')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap + AdminLTE -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">

    <style>
        body {
            background-color: white;
        }
        .centered {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        h2 {
            color: gold;
        }
    </style>
</head>
<body>
    <div class="centered">
        @yield('content')
    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
