<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    @stack('meta')

    @include('backend._partials._css')

    @stack('customCSS')
</head>

<body class="sidebar-mini">
    <div class="wrapper ">
        @include('backend._partials._sidebar')
        <div class="main-panel" id="main-panel">
            <!--Top Navbar -->
            @include('backend._partials._topbar')
            <!-- End Top Navbar -->
            @yield('content')
            @include('backend._partials._footer')
        </div>
    </div>
    @include('backend._partials._javascript')
    @stack('customJS')
    <script>
        $(document).ready(function () {
            
        });
    </script>
</body>

</html>