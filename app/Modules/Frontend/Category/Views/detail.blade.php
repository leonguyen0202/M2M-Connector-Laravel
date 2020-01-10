@extends('frontend.master')

@section('class')
blog-post
@endsection

@push('meta')
<title>
    {{$category->title}} - {{$category->description}}
</title>
@endpush

@section('content')
<div class="page-header page-header-small">
    <div class="page-header-image" data-parallax="true"
        style="background-image: url({{ url(asset('images/categories/'. $category->background_image)) }});">
    </div>
    <div class="content-center">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h2 class="title">{{$category->title}}</h2>
                <h4>
                    {{$category->description}}
                </h4>
                <input type="hidden" name="_slug" id="_slug" value="{{$category->slug}}">
            </div>
        </div>
    </div>
</div>
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="button-container">
                    @guest
                    <a href="#" class="btn btn-primary btn-round btn-lg notifications-button">
                        <i class="far fa-bell"></i>&nbsp;Subscribe
                    </a>
                    @else
                    <a href="#pablo" class="btn {!! render_conditional_class($is_subscribed, 'btn-success', 'btn-primary') !!} btn-round btn-lg notifications-button">
                        <i class="{!! render_conditional_class($is_subscribed, 'fas', 'far') !!} fa-bell"></i> Register notification
                    </a>
                    <form id="notifications-form" action="{{ route('home.action') }}" method="POST" style="display: none;">
                        @csrf
                        <input type="hidden" name="email" id="email" value="{{Auth::user()->email}}">
                        <input type="hidden" name="type" id="type" value="categories">
                    </form>
                    @endguest
                </div>
            </div>
        </div>
    </div>
    @if ($top_posts != null || !$top_posts->isEmpty())
    <div class="features-1">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto">
                    <h2 class="title">
                        @lang('frontend.home_top_blog')
                    </h2>
                </div>
            </div>
            <div class="row">
                @foreach ($top_posts as $top)
                <div class="col-md-6">
                    <div class="card card-background"
                        style="background-image: url( {{ $top->media_url('card') }} )">
                        <div class="card-body">
                            <div class="card-title text-left">
                                @if ($top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                    <a href="{{route('blog.detail', $top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} )}}" style="color:white">
                                        <h3 style="color:white;">{{ split_sentence($top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 30, '...') }}</h3>
                                    </a>
                                @else
                                    <a href="{{route('blog.detail', $top->{Config::get('app.fallback_locale').'_slug' })}}" style="color:white">
                                        <h3 style="color:white;">{{ split_sentence($top->{Config::get('app.fallback_locale').'_title' }, 30, '...') }}</h3>
                                    </a>
                                @endif
                            </div>
                            <div class="card-footer text-left">
                                <div class="stats">
                                    <div class="author">
                                        <img src="{{asset('images/avatars/'. $top->author->avatar)}}"
                                            alt="{{$top->author->avatar}}" class="avatar img-raised">
                                        <span>{{ $top->author->name }}</span>
                                    </div>
                                </div>
                                <div class="stats stats-right justify-content-center">
                                    &#9;&#9;&#9;
                                    <i class="fas fa-eye"></i>
                                    {{number_format($top->visits)}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @else

    @section('footer_class')
    data-background-color="gray"
    @endsection
    @include('frontend._partials._features')

    @endif
</div>
@if ($posts != null || !$posts->isEmpty())
<div class="blogs-5" data-background-color="gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 ml-auto mr-auto">
                <h2 class="title text-center">
                    @lang('frontend.home_recent_blog')
                </h2>
                <div class="row category-load-data">

                    @foreach ($posts as $post)
                    <div class="col-md-4">
                        <div class="card card-blog">
                            <div class="card-image">
                                @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                    <a href="{{route('blog.detail', $post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} )}}">
                                        <img class="img rounded" src="{{ $post->media_url('card') }}"
                                            alt="{{$post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }}">
                                    </a>
                                @else
                                    <a href="{{route('blog.detail', $post->{Config::get('app.fallback_locale').'_slug' } )}}">
                                        <img class="img rounded" src="{{ $post->media_url('card') }}"
                                            alt="{{$post->{Config::get('app.fallback_locale').'_title' } }}">
                                    </a>
                                @endif
                            </div>
                            <div class="card-body">
                                {{-- {!! render_category_class('h6', $post) !!} --}}

                                <h5 class="card-title">
                                    @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} == null)
                                        {{split_sentence($post->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                                    @else
                                        {{ split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                                    @endif
                                </h5>
                                <p class="card-description">
                                    @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} == null)
                                        {{ __('frontend.no_language_detect') }}
                                    @else
                                        {{ split_sentence( strip_tags($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}) , 40, '...') }}
                                    @endif
                                </p>
                                <div class="card-footer">
                                    <div class="author">
                                        <img src="{{asset('images/avatars/'. $post->author->avatar)}}"
                                            alt="{{$post->author->avatar}}" class="avatar img-raised">
                                        <span>{{ $post->author->name }}</span>
                                    </div>
                                    <div class="stats stats-right">
                                        <i class="now-ui-icons tech_watch-time"></i>
                                        {{\Carbon\Carbon::parse($post->created_at)->diffForHumans()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row category-button">
                    <div class="col-md-3 mr-auto ml-auto preloader">
                        <a href="#" class="btn btn-primary btn-round btn-block category-load-more">
                            Load More Posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if (!$contributors->isEmpty())
@section('footer_class')
data-background-color="gray"
@endsection
<div class="section pt-0 pb-0">
    <div class="team-5">
        <div class="container">
            <div class="row">
                <div class="col-md-8 ml-auto mr-auto text-center">
                    <h2 class="title">
                        @lang('frontend.blog_contributor_title')
                    </h2>
                    <h4 class="description">
                        @lang('frontend.blog_contributor_description')
                    </h4>
                </div>
            </div>
            <div class="row">
                @foreach ($contributors as $contributor)
                <div class="col-md-4">
                    <div class="card card-profile">
                        <div class="card-avatar">
                            <a href="#pablo">
                                <img class="img img-raised"
                                    src="{{asset('images/avatars/'. $contributor->author->avatar)}}" />
                            </a>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">{{$contributor->author->name}}</h3>
                            {{-- <h6 class="category text-primary">Credit Analyst</h6> --}}
                            @if (isset($contributor->author->about))
                            <p class="card-description">
                                {{$contributor->author->about}}
                            </p>
                            @endif
                            <div class="card-footer">
                                <a href="#pablo" class="btn btn-icon btn-neutral btn-round"><i
                                        class="fab fa-linkedin"></i></a>
                                <a href="#pablo" class="btn btn-icon btn-neutral btn-round"><i
                                        class="fab fa-twitter"></i></a>
                                <a href="#pablo" class="btn btn-icon btn-neutral btn-round"><i
                                        class="fab fa-dribbble"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
@endsection


@push('customJS')

@guest
<script type="text/javascript">
    $(document).ready(function () {
        
        $('.notifications-button').on('click', function (e) {
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
        $('.notifications-button').on('click', function (e) {
            e.preventDefault();

            document.getElementById('notifications-form').submit();
        })
    })
</script>
@endguest
@endpush