<div class="sidebar" data-color="orange">
    <!--
    Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red | yellow"
    -->
    <div class="logo">
        <a href="{{route('home.index')}}" class="simple-text logo-mini">
            M2M
        </a>
        <a href="{{route('home.index')}}" class="simple-text logo-normal">
            Connector
        </a>
        <div class="navbar-minimize">
            <button id="minimizeSidebar" class="btn btn-simple btn-icon btn-default btn-round">
                <i class="now-ui-icons text_align-center visible-on-sidebar-regular"></i>
                <i class="now-ui-icons design_bullet-list-67 visible-on-sidebar-mini"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper" id="sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="{{asset('images/avatars/'.Auth::user()->avatar)}}" />
            </div>
            <div class="info">
                <a data-toggle="collapse" href="#collapseExample" class="collapsed">
                    <span>
                        {{Auth::user()->name }}
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="clearfix"></div>
                <div class="collapse" id="collapseExample">
                    <ul class="nav">
                        <li>
                            <a href="#">
                                <span class="sidebar-mini-icon">MP</span>
                                <span class="sidebar-normal">My Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="sidebar-mini-icon">EP</span>
                                <span class="sidebar-normal">Edit Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <span class="sidebar-mini-icon">S</span>
                                <span class="sidebar-normal">Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav">
            <li class="{{ set_request_class(['dashboard'],'active') }}">
                <a href="{{route('dashboard.index')}}">
                    <i class="now-ui-icons design_app"></i>
                    <p>{{__('backend.dashboard')}}</p>
                </a>
            </li>
            <li class="{{ set_request_class(['dashboard/blogs', 'dashboard/blog*'],'active') }}">
                <a href="{{route('blogs.index')}}">
                    <i class="now-ui-icons files_single-copy-04"></i>
                    <p>Blogs</p>
                </a>
            </li>
            <li class="{{ set_request_class(['dashboard/events', 'dashboard/event*'],'active') }}">
                <a href="{{route('events.index')}}">
                    <i class="now-ui-icons ui-1_calendar-60"></i>
                    <p>Events Calendar</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#pagesExamples">
                    <i class="now-ui-icons education_atom"></i>
                    <p>
                        Miscellaneous
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse " id="pagesExamples">
                    <ul class="nav">
                        <li>
                            <a href="../examples/pages/pricing.html">
                                <span class="sidebar-mini-icon">
                                    <i class="now-ui-icons education_agenda-bookmark"></i>
                                </span>
                                <span class="sidebar-normal"> 
                                    {{ __('backend.bookmark') }}
                                </span>
                            </a>
                        </li>
                        <li>
                            <a href="../examples/pages/rtl.html">
                                <span class="sidebar-mini-icon">
                                    <i class="now-ui-icons shopping_tag-content"></i>
                                </span>
                                <span class="sidebar-normal"> {{ __('backend.categories') }} </span>
                            </a>
                        </li>
                        <li>
                            <a href="../examples/pages/timeline.html">
                                <span class="sidebar-mini-icon">
                                    <i class="now-ui-icons users_single-02"></i>
                                </span>
                                <span class="sidebar-normal"> Follows </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @if (Auth::user()->hasRole('super-admin'))
            <li class="{{ set_request_class(['dashboard/settings'],'active') }}">
                <a href="#">
                    <i class="now-ui-icons loader_gear"></i>
                    <p>Settings</p>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>