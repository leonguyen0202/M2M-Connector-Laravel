<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    @stack('meta')
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no'
        name='viewport' />
    @include('frontend._partials._css')
    @stack('css')
</head>

<body class="sidebar-collapse @yield('class')">
    @include('frontend._partials._navbar')
    <div class="wrapper">
        @yield('content')
        @include('frontend._partials._footer')
    </div>

    <!--   Core JS Files   -->
    @include('frontend._partials._javascript')
    @stack('customJS')

    {{-- @auth
        {!!
            "<script type='text/javascript'>
            ".JavaScript::put(['email' => Auth::user()->email])."    
            </script>"
        !!}
    @endauth --}}

    <script>
        $(document).ready(function() {
            console.clear();
            $(".error-alert").fadeTo(2000, 700).slideUp(700, function(){
                $(".error-alert").slideUp(700);
            });
        });

        var botmanWidget = {
            title: 'M2M Connector Chat Bot',
            aboutText: 'M2M Connector Website',
            bubbleAvatarUrl: '{{ asset("images/cover/botman-default.png") }}',
            mainColor: '#ea7659',
            introMessage: "✋ Hi! I'm the awesome automated chat bot.",
        };
        
    </script>
</body>

</html>