<div class="header-3">
    <nav class="navbar navbar-expand-lg fixed-top navbar-transparent bg-primary navbar-absolute">
        <div class="container">
            <div class="navbar-translate">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#master-navbar"
                    aria-controls="#master-navbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
                <a class="navbar-brand" href="{{route('home.index')}}">
                    <strong>M2M Connector</strong>
                </a>
            </div>
            <div class="collapse navbar-collapse" id="master-navbar">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item {{ set_request_class(['/'],'active') }}">
                        <a class="nav-link" href="{{route('home.index')}}">
                            {!! set_strong_navigation_active(['/'], __('frontend.home_menu')) !!}
                        </a>
                    </li>
                    <li class="nav-item {{ set_request_class(['categories', 'category*'],'active') }}">
                        <a class="nav-link" href="{{route('categories.index')}}">
                            {!! set_strong_navigation_active(['categories', 'category*'], __('frontend.categories_menu')
                            ) !!}
                        </a>
                    </li>
                    <li class="nav-item {{ set_request_class(['blogs', 'blog*'],'active') }}">
                        <a class="nav-link" href="{{route('blog.index')}}">
                            {!! set_strong_navigation_active(['blogs', 'blog*'], __('frontend.blog_menu')) !!}
                        </a>
                    </li>
                    <li class="nav-item {{ set_request_class(['events', 'event*'],'active') }}">
                        <a class="nav-link" href="{{route('event.index')}}">
                            {!! set_strong_navigation_active(['events', 'event*'], __('frontend.event_menu')) !!}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pablo">
                            {!! set_strong_navigation_active(['about-us'], __('frontend.about_us_menu')) !!}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pablo">
                            {!! set_strong_navigation_active(['contact-us'], __('frontend.contact_us_menu')) !!}
                        </a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown">
                            <i class="now-ui-icons business_globe" aria-hidden="true"></i>&nbsp;&nbsp;{{__('frontend.language_menu')}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-header">{{__('frontend.language_available')}}</a>
                            <?php
                                use Illuminate\Support\Facades\DB;
                                $locale = DB::table('localization')->get();
                                foreach ($locale as $key => $value) {
                                    echo "<a class='dropdown-item' href='".route('locale', $value->locale_code)."'>
                                        <img src='".asset('images/cover/'. $value->locale_icon)."' style='width:25px;height:25px' class='rounded-circle' alt='".asset('images/cover/'.$value->locale_icon)."'>
                                        &nbsp;&nbsp;".$value->locale_name."</a>";
                                }
                            ?>
                        </div>
                    </li>
                    @guest
                    <li class="nav-item">
                        <a class="nav-link login-button">
                            {{ __('frontend.login') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link register-button">
                            {{ __('frontend.register') }}
                        </a>
                    </li>
                    @else
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown">
                            <i class="now-ui-icons users_single-02" aria-hidden="true"></i>&nbsp;&nbsp;{{Auth::user()->name}}
                            {{-- <img src="{{asset('images/avatars/'.Auth::user()->avatar)}}" class="img-rounded" style="width:25px;height:25px" alt="">&nbsp;&nbsp;{{Auth::user()->name}} --}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-header">{{Auth::user()->name}}</a>
                            <a class="dropdown-item" href="{{route('dashboard.index')}}">
                                <i class="fas fa-tachometer-alt"></i>&nbsp;&nbsp;{{ __('frontend.dashboard') }}
                            </a>
                            <div class="divider"></div>
                            <a class="dropdown-item" href="{{ route('logout') }}" id="logout-button" style="color:red">
                                <i class="fas fa-power-off"></i>&nbsp;&nbsp;{{ __('frontend.logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    @stack('carousel')
</div>

@guest

<div class="modal fade modal-primary" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-login card-plain">
                <div class="modal-header justify-content-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>

                    <div class="header header-primary text-center" style="width:20%">
                        <div class="logo-container">
                            <img src="{{asset('kit/img/now-logo.png')}}" alt="">
                        </div>
                    </div>
                </div>

                <div class="modal-body" data-background-color>
                    
                    @csrf
                    <div class="card-body">
                        <div class="input-group form-group-no-border input-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                placeholder="Username...">
                        </div>

                        <div class="input-group form-group-no-border input-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input id="email" type="email" placeholder="Email Address..."
                                class="form-control @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email">
                        </div>

                        <div class="input-group form-group-no-border input-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="now-ui-icons ui-1_lock-circle-open"></i>
                                </span>
                            </div>
                            <input id="password" type="password" placeholder="Password..."
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">
                        </div>

                        <div class="input-group form-group-no-border input-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="now-ui-icons ui-1_lock-circle-open"></i>
                                </span>
                            </div>
                            <input id="password-confirm" type="password" class="form-control"
                                placeholder="Password Confirmation..." name="password_confirmation" required
                                autocomplete="new-password">
                        </div>
                        <br>
                        <a href="#" class="btn btn-neutral btn-round btn-lg btn-block register-submit">
                            {{ __('frontend.register') }}
                        </a>
                    </div>
                    
                </div>
                
                <div class="modal-footer" style="color:black">
                    <a class="btn btn-neutral btn-round btn-lg btn-block forgot-password-button">
                        {{ __('frontend.forgot_password') }}
                    </a>
                    <br>
                    <a class="btn btn-neutral btn-round btn-lg btn-block login-button">
                        {{ __('frontend.has_account') }}
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-primary" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-login card-plain">
                <div class="modal-header justify-content-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>

                    <div class="header header-primary text-center" style="width:20%">
                        <div class="logo-container">
                            <img src="{{asset('kit/img/now-logo.png')}}" alt="">
                        </div>
                    </div>
                </div>

                <div class="modal-body" data-background-color>
                    {{-- <form method="POST" action="{{ route('login') }}" autocomplete="off"> --}}
                    @csrf
                    <div class="card-body">
                        <div class="input-group form-group-no-border input-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                            <input id="login_email" type="email" placeholder="Email Address..."
                                class="form-control @error('email') is-invalid @enderror" name="login_email" required
                                autocomplete="off" autofocus>
                        </div>

                        <div class="input-group form-group-no-border input-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="now-ui-icons ui-1_lock-circle-open"></i>
                                </span>
                            </div>
                            <input id="login_password" type="password" placeholder="Password..."
                                class="form-control @error('password') is-invalid @enderror" name="login_password"
                                required autocomplete="off">
                        </div>
                        <br>
                        <a href="#" class="btn btn-neutral btn-round btn-lg btn-block login-submit">
                            {{ __('frontend.login') }}
                        </a>
                    </div>
                    {{-- </form> --}}
                </div>
                {{-- @if (Route::has('password.request')) --}}
                <div class="modal-footer" style="color:black">
                    <a class="btn btn-neutral btn-round btn-lg btn-block forgot-password-button">
                        {{ __('frontend.forgot_password') }}
                    </a>
                    <br>
                    <a class="btn btn-neutral btn-round btn-lg btn-block register-button">
                        {{ __('frontend.dont_has_account') }}
                    </a>
                </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-primary" id="forgotPasswordModal" tabindex="-1" role="dialog"
    aria-labelledby="forgotPasswordModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-login card-plain">
                <div class="modal-header justify-content-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>

                    <div class="header header-primary text-center" style="width:20%">
                        <div class="logo-container">
                            <img src="{{asset('kit/img/now-logo.png')}}" alt="">
                        </div>
                    </div>
                </div>

                <div class="modal-body" data-background-color>
                    <form method="POST" action="{{ route('password.email') }}" autocomplete="off">
                        @csrf
                        <div class="card-body">
                            <div class="input-group form-group-no-border input-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                <input id="forgot_email" type="email" placeholder="Email Address..."
                                    class="form-control @error('forgot_email') is-invalid @enderror" name="forgot_email"
                                    required>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-neutral btn-round btn-lg btn-block">
                                {{ __('frontend.send_reset_link') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer text-center justify-content-center" style="color:black">
                    <a class="btn btn-neutral btn-round btn-lg btn-block login-button">
                        {{ __('frontend.has_account') }}
                    </a>
                    <br>
                    <a class="btn btn-neutral btn-round btn-lg btn-block register-button">
                        {{ __('frontend.dont_has_account') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endguest

@push('customJS')
@guest
<script type="text/javascript">
    $(document).ready(function () {
        $('.forgot-password-button').on('click', function (e) {
            e.preventDefault();

            $('#loginModal').modal('hide');
            $('#registerModal').modal('hide');

            $('#forgot_email').val("");

            $('#forgotPasswordModal').modal('show');
        });

        $('.register-button').on('click', function (e) {
            e.preventDefault();

            $('#forgotPasswordModal').modal('hide');
            $('#loginModal').modal('hide');

            $('#name').val("");
            $('#email').val("");
            $('#password').val("");
            $('#password-confirm').val("");

            $('#registerModal').modal('show');
        });
        
        $('.login-button').on('click', function (e) {
            e.preventDefault();

            $('#forgotPasswordModal').modal('hide');
            $('#registerModal').modal('hide');

            $('#login_email').val("");
            $('#login_password').val("");

            $('#loginModal').modal('show');
        });

        $('.register-submit').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url: '/register',
                method: "POST",
                data: {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    password_confirmation: $('#password-confirm').val(),
                    '_token': $('input[name=_token]').val()
                },
                beforeSend: () => {
                    Swal.fire({
                        title: 'Sending....',
                        html: '<span class="text-success">Waiting for data to be sent</span>',
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        },
                    })
                },
                success: (data) => {
                    console.clear();
                    
                    Swal.disableLoading();

                    Swal.close();

                    Swal.fire({
                        type: 'success',
                        title: 'Success!',
                        html: '<span class="text-success">Please confirm your email before login</span>',
                        showConfirmButton: false,
                    });

                    window.setTimeout(() => {
                        location.reload();
                    }, 5000);
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.clear();
                    
                    Swal.disableLoading();

                    Swal.close();

                    if (jqXHR.responseJSON.errors.name) {
                        sweetAlertError(jqXHR.responseJSON.errors.name[0]);
                    }else if (jqXHR.responseJSON.errors.email) {
                        sweetAlertError(jqXHR.responseJSON.errors.email[0]);
                    } else if (jqXHR.responseJSON.errors.password) {
                        sweetAlertError(jqXHR.responseJSON.errors.password[0]);
                    } else {
                        sweetAlertError(jqXHR.responseJSON.message + " or empty!");
                    }
                }
            })
        });

        $('.login-submit').on('click', function (e) {
            e.preventDefault();

            $.ajax({
                url: '/login',
                method: "POST",
                data: {
                    login_email: $('#login_email').val(),
                    login_password: $('#login_password').val(),
                    '_token': $('input[name=_token]').val()
                },
                beforeSend: () => {
                    Swal.fire({
                        title: 'Sending....',
                        html: '<span class="text-success">Waiting for data to be sent</span>',
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        },
                    })
                },
                success: (data) => {
                    console.clear();
                    
                    Swal.disableLoading();

                    Swal.close();

                    location.reload();
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.clear();

                    Swal.disableLoading();

                    Swal.close();

                    sweetAlertError(jqXHR.responseJSON.message);
                }
            })
        });

    })
</script>
@else
<script type="text/javascript">
    $(document).ready(function () {
        $('#logout-button').on('click', function (e) {
            e.preventDefault();
            document.getElementById('logout-form').submit();
        })
    })
</script>
@endguest
@endpush