@extends('frontend.master')

@section('class')
blog-post
@endsection

@push('css')
<style>
    .categories-label {
        color: red !important;
    }
</style>
@endpush

@push('meta')
{{-- <meta name="description" content="{{ split_sentence($blog->description, 250, '...') }}"> --}}
<title>
    @if ( $blog->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null )
        {{$blog->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }} - M2M Connector
    @else
        {{ __('frontend.no_language_detect') }}
    @endif
</title>
@endpush

@section('content')
<div class="page-header page-header-small">
    <div class="page-header-image" data-parallax="true"
        style="background-image: url({{url(asset('images/posts/'. $blog->background_image))}});">
    </div>
    <div class="content-center">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h2 class="title">
                    @if ($blog->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} == null)
                        {{ $blog->{ Config::get('app.fallback_locale').'_title' } }}
                    @else
                        {{ $blog->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }}
                    @endif
                </h2>
                <h4>
                    {{\Carbon\Carbon::parse($blog->created_at)->isoFormat("MMMM Do YYYY")}}
                </h4>
            </div>
        </div>
    </div>
</div>


<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="button-container">
                    {{-- @csrf --}}
                    {{-- <a href="#" class="btn btn-primary btn-round btn-lg bookmark-button" id="bookmark-button">
                        <i class="far fa-bookmark"></i> Bookmark Post
                    </a> --}}
                    @guest
                    <a href="#" class="btn btn-primary btn-round btn-lg bookmark-button" id="bookmark-button">
                        <i class="far fa-bookmark"></i> Bookmark Post
                    </a>
                    @else
                    @if (Auth::id() != $blog->author_id)
                    <a href="#pablo" class="btn {!! render_conditional_class($is_bookmarked, 'btn-success', 'btn-primary') !!} btn-round btn-lg bookmark-button" id="bookmark-button">
                        <i class="{!! render_conditional_class($is_bookmarked, 'fas', 'far') !!} fa-bookmark"></i> Bookmark Post
                    </a>
                    @endif

                    <form id="bookmark-form" action="{{ route('home.action') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="email" id="email" value="{{Auth::user()->email}}">
                        <input type="hidden" name="type" id="type" value="blogs">
                    </form>
                    @endguest

                    <a href="#pablo" class="btn btn-icon btn-lg btn-twitter btn-round">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#pablo" class="btn btn-icon btn-lg btn-facebook btn-round">
                        <i class="fab fa-facebook-square"></i>
                    </a>
                    <a href="#pablo" class="btn btn-icon btn-lg btn-google btn-round">
                        <i class="fab fa-google"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto {!! render_conditional_class($blog->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} == null, 'text-center', '') !!}">
                    <br>
                    @if ($blog->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} == null)
                        <h3>{{ __('frontend.no_language_detect') }}</h3>
                        <br>
                        <a href="#" class="btn btn-primary request-language">{{__('frontend.language_request')}}</a>
                    @else
                    {!! $blog->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} !!}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section section-blog-info">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="blog-tags">
                                Categories:
                                @foreach (($blog->categories) as $key => $value)
                                    @foreach ($value as $item)
                                        <span class="label label-primary">
                                            <a href="{{route('category.detail', render_all_categories('slug', $item))}}"
                                                style="text-decoration: none; color:red" onMouseOut="this.style.color='red'"
                                                onMouseOver="this.style.color='{{ render_all_categories('color', $item) }}'">{{render_all_categories('title', $item)}}</a>
                                        </span>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="card card-profile card-plain">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="card-avatar">
                                    <a href="#pablo">
                                        <img class="img img-raised"
                                            src="{{asset('images/avatars/'. $blog->author->avatar)}}">
                                    </a>
                                    <div class="ripple-container"></div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h4 class="card-title">{{$blog->author->name}}</h4>
                                <p class="description">{{$blog->author->about}}</p>
                            </div>
                            @guest
                                <div class="col-md-2">
                                    <button type="button"
                                        class="btn btn-default pull-right btn-round follow-button">Follow</button>
                                </div>
                            @else
                                @if (Auth::id() != $blog->author_id)
                                    <div class="col-md-2">
                                        <button type="button"
                                            class="btn {!! render_conditional_class($is_followed, 'btn-success', 'btn-default') !!} pull-right btn-round follow-button">Follow</button>
                                    </div>
                                    <form id="follow-form" action="{{ route('home.action') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="email" id="email" value="{{Auth::user()->email}}">
                                        <input type="hidden" name="type" id="type" value="users">
                                    </form>
                                @endif
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section" data-background-color="gray">
    <div class="container">
        <div class="col-md-12">
            <h2 class="title text-center">Similar Stories</h2>
            <br />
            <div class="blogs-1" id="blogs-1">
                <div class="row">
                    <div class="col-md-10 ml-auto mr-auto">
                        @foreach ($similar_stories as $key => $value)
                        <div class="card card-plain card-blog">
                            <div class="row">
                                @if ($key == 0)
                                <div class="col-md-5">
                                    <div class="card-image">
                                        <img class="img img-raised rounded"
                                            src="{{asset('images/posts/'. $value->background_image)}}">
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    {!! render_category_class('h6', $value) !!}

                                    <h3 class="card-title">
                                        @if ($value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} == null)
                                            <a href="{{route('blog.detail', $value->{ Config::get('app.fallback_locale').'_slug' } )}}">
                                                {{ $value->{ Config::get('app.fallback_locale').'_title' } }}
                                            </a>
                                        @else
                                            <a href="{{route('blog.detail', $value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} )}}">
                                                {{ $value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }}
                                            </a>
                                        @endif
                                    </h3>
                                    <p class="card-description">
                                        @if ($value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} == null)
                                            {{ __('frontend.no_language_detect') }}
                                        @else
                                            {{ split_sentence($value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}, 170, '...') }}
                                        @endif
                                    </p>
                                    <p class="author">
                                        by
                                        <a href="#pablo">
                                            <b>{{$value->author->name}}</b>
                                        </a>,
                                        {{\Carbon\Carbon::parse($value->created_at)->diffForHumans()}}
                                    </p>
                                </div>
                                @else
                                <div class="col-md-7">
                                    {!! render_category_class( 'h6',$value) !!}
                                    <h3 class="card-title">
                                        @if ($value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} == null)
                                            <a href="{{route('blog.detail', $value->{ Config::get('app.fallback_locale').'_slug' } )}}">
                                                {{ $value->{ Config::get('app.fallback_locale').'_title' } }}
                                            </a>
                                        @else
                                            <a href="{{route('blog.detail', $value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} )}}">
                                                {{ $value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }}
                                            </a>
                                        @endif
                                    </h3>
                                    <p class="card-description">
                                        @if ($value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} == null)
                                            {{ __('frontend.no_language_detect') }}
                                        @else
                                            {{ split_sentence($value->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}, 170, '...') }}
                                        @endif
                                    </p>
                                    <p class="author">
                                        by
                                        <a href="#pablo">
                                            <b>{{$value->author->name}}</b>
                                        </a>,
                                        {{\Carbon\Carbon::parse($value->created_at)->diffForHumans()}}
                                    </p>
                                </div>
                                <div class="col-md-5">
                                    <div class="card-image">
                                        <img class="img img-raised rounded
                                              " src="{{asset('images/posts/'. $value->background_image)}}">
                                    </div>
                                </div>
                                @endif

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('customJS')
@guest
<script type="text/javascript">
    $(document).ready(function () {
        
        $('.bookmark-button').on('click', function (e) {
            e.preventDefault();

            $('#login_email').val("");
            $('#login_password').val("");

            $('#loginModal').modal('show');
        })

        $('.request-language').on('click', function (e) {
            e.preventDefault();

            $('#login_email').val("");
            $('#login_password').val("");

            $('#loginModal').modal('show');
        })

        $('.follow-button').on('click', function (e) {
            e.preventDefault();

            $('#login_email').val("");
            $('#login_password').val("");

            $('#loginModal').modal('show');
        })
    });
</script>
@else
<script type="text/javascript">
    $(document).ready(function () {
        $('.bookmark-button').on('click', function (e) {
            e.preventDefault();

            document.getElementById('bookmark-form').submit();
        });

        $('.follow-button').on('click', function (e) {
            e.preventDefault();

            document.getElementById('follow-form').submit();
        })
    })
</script>
@endguest
@endpush