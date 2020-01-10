<nav class="navbar navbar-expand-lg navbar-transparent  bg-primary  navbar-absolute">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-toggle">
                <button type="button" class="navbar-toggler">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </button>
            </div>
            {!! render_dashboard_breadcrumb() !!}
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation"
            aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <form>
                <div class="input-group no-border">
                    <input type="text" value="" class="form-control" placeholder="Search...">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="now-ui-icons ui-1_zoom-bold"></i>
                        </div>
                    </div>
                </div>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="event.preventDefault();
                            location.reload();" id="refresh-button">
                        <i class="now-ui-icons loader_refresh"></i>
                        <p>
                            <span class="d-lg-none d-md-block">Refresh</span>
                        </p>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="now-ui-icons ui-1_bell-53"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{__('frontend.language_menu')}}</span>
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-header">{{__('backend.notification')}}</a>
                        <a class="dropdown-item notification-item" href="#">Notification</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="now-ui-icons business_globe"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{__('frontend.language_menu')}}</span>
                        </p>
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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}" class="logout-button">
                        <i class="now-ui-icons media-1_button-power"></i>
                        <p>
                            <span class="d-lg-none d-md-block">{{ __('frontend.logout') }}</span>
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

@auth
@push('customJS')
<script type="text/javascript">
    $(document).ready(function () {
            $('.logout-button').on('click', function (e) {
                e.preventDefault();
                document.getElementById('logout-form').submit();
            })
        })
</script>
@endpush
@endauth