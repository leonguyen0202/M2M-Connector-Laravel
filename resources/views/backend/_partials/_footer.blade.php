<footer class="footer">
    <div class=" container-fluid ">
        <nav>
            <ul>
                <li>
                    <a href="{{route('home.index')}}">
                        {{ __('frontend.home_menu') }}
                    </a>
                </li>
                <li>
                    <a href="{{route('categories.index')}}">
                        {{ __('frontend.categories_menu') }}
                    </a>
                </li>
                <li>
                    <a href="{{route('event.index')}}">
                        {{ __('frontend.event_menu') }}
                    </a>
                </li>
                <li>
                    <a href="{{route('blog.index')}}">
                        {{ __('frontend.blog_menu') }}
                    </a>
                </li>
            </ul>
        </nav>
        <div class="copyright" id="copyright">
            &copy;
            <script>
                document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
            </script>, Registered by
            <a href="{{route('home.index')}}" target="_blank" class="m2m-text">M2M Connector</a>. All right reserved.
        </div>
    </div>
</footer>