<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    @stack('meta')
    
    @include('backend._partials._css')

    @stack('customCSS')
</head>
{{-- {!! render_conditional_class( isset($edit_mode) , render_conditional_class( isset($edit_mode),'onload="getDescription();"','') , '' ) !!} --}}
<body {!! render_conditional_class( Cache::has('_'. Auth::id(). '_sidebar_mini'), 'class='.Cache::get('_'. Auth::id(). '_sidebar_mini').' ', '' ) !!}>
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

    <script src="{{asset('dashboard/demo/demo.js')}}"></script>

    @if (session('errors'))
    @foreach (session('errors') as $error)
    <script type="text/javascript">
        $.notify({
            icon: "now-ui-icons ui-1_simple-remove",
            message: "{!! $error !!}",

        }, {
            type: 'danger',
            timer: 5000,
            allow_dismiss: false,
            placement: {
                from: 'top',
                align: 'right',
            },
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
        });
    </script>
    @endforeach
    @endif

    @if (session('success'))
    @foreach (session('success') as $success)
    <script type="text/javascript">
        $.notify({
            icon: "now-ui-icons ui-1_check",
            message: "{!! $success !!}",

        }, {
            type: 'success',
            timer: 3000,
            allow_dismiss: false,
            placement: {
                from: 'top',
                align: 'right',
            },
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
        });
    </script>
    @endforeach
    @endif
</body>

</html>