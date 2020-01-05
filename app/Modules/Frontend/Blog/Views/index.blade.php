@extends('frontend.master')

@section('class')
blog-posts
@endsection

@push('meta')
<title>
    @lang('frontend.blog_cover_title') - M2M Connector
</title>
@endpush

@section('content')
<div class="page-header page-header-small">
    <div class="page-header-image" data-parallax="true"
        style="background-image: url({{url(asset('images/cover/book_cover_background.jpg'))}});">
    </div>
    <div class="content-center">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h2 class="title">
                    @lang('frontend.blog_cover_title')
                </h2>
                <a href="#button" class="btn btn-primary btn-round  btn-icon">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="#button" class="btn btn-primary btn-round  btn-icon">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="main">
    @if (!$top_posts->isEmpty())
    <div class="container">
        <div class="section">
            <h3 class="title text-center">
                @lang('frontend.blog_top_title')
            </h3>
            <br />
            <div class="row">
                @foreach ($top_posts as $top)
                <div class="col-md-3">
                    <div class="card card-background"
                        style="background-image: url( {{url(asset('images/posts/'.$top->background_image))}} )">
                        <div class="card-body">
                            <div class="card-title text-left">
                                @if ( $top->{Cookie::get( strtolower(env('APP_NAME')) .'_language' ).'_slug' } != null )
                                    <a href="{{route('blog.detail', $top->{Cookie::get( strtolower(env('APP_NAME')) .'_language' ).'_slug' })}}">
                                        <h3 class="white">{{ split_sentence($top->{Cookie::get( strtolower(env('APP_NAME')) .'_language' ).'_title' }, 20, '...') }}</h3>
                                    </a>
                                @else
                                    <a href="{{route('blog.detail', $top->{Config::get('app.fallback_locale').'_slug' } )}}">
                                        <h3 class="white">{{ split_sentence($top->{Config::get('app.fallback_locale').'_title' }, 20, '...') }}</h3>
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
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @if (!$posts->isEmpty())
    <div class="container">
        <div class="section">
            <h3 class="title text-center">
                @lang('frontend.blog_interest_title')
            </h3>
            <br />
            <div class="row blog-data">
                @foreach ($posts as $post)
                <div class="col-md-4">
                    <div class="card card-blog">
                        <div class="card-image">
                            @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                <a href="{{route('blog.detail', $post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} )}}">
                                    <img class="img rounded" src="{{$post->getMedia('blog-images')->getFirstMediaUrl('frontend')}}"
                                        alt="{{$post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }}">
                                </a>
                            @else
                                <a href="{{route('blog.detail', $post->{Config::get('app.fallback_locale').'_slug' } )}}">
                                    <img class="img rounded" src="{{$post->getMedia('blog-images')->getFirstMediaUrl('frontend')}}"
                                        alt="{{$post->{Config::get('app.fallback_locale').'_title' } }}">
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            {!! render_category_class('h6',$post) !!}

                            <h5 class="card-title">
                                @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} == null)
                                    {{split_sentence($post->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                                @else
                                    {{ split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                                @endif
                            </h5>
                            
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
            <div class="row blog-button">
                <div class="col-md-3 mr-auto ml-auto">
                    <a href="#" class="btn btn-primary btn-round btn-block blog-load-more">Load More Posts</a>
                </div>
            </div>
        </div>
    </div>

    <div class="section pt-0 pb-0" data-background-color="gray">
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
    @else
    @section('footer_class')
        data-background-color="gray"
    @endsection
    @include('frontend._partials._features')
    @endif
</div>
@endsection