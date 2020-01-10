@extends('frontend.master')

@push('meta')
<title>
    M2M Connector - Home Page
</title>
@endpush

@push('carousel')
@if ($sliders != null)
<div id="carouselExampleIndicators" class="carousel slide">
    <ol class="carousel-indicators">
        @foreach ($sliders as $key => $value)
        <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}"
            class="{{slider_active_class($key, 'active')}}"></li>
        @endforeach
    </ol>
    <div class="carousel-inner" role="listbox">
        @foreach ($sliders as $key => $item)
        <div class="carousel-item {{slider_active_class($key, 'active')}}">
            <div class="page-header header-filter">

                @if ($item instanceof \App\Modules\Backend\Blogs\Models\Blog)
                <div class="page-header-image"
                    style="background-image: url({{ $item->media_url('slider') }});">
                </div>
                @elseif($item instanceof \App\Modules\Backend\Events\Models\Event)
                <div class="page-header-image"
                    style="background-image: url({{ url(asset('images/events/'.$item->background_image)) }});">
                </div>
                @endif

                <div class="content-center">
                    <div class="container">
                        <div class="content-center">
                            <div class="row">
                                <div class="col-md-8 ml-auto mr-auto text-center">
                                    <h1 class="title">
                                        @if ($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                                            {{ split_sentence($item->{ Cookie::get(strtolower(env('APP_NAME')).'_language').'_title'} ,50 ,'...') }}
                                        @else
                                            {{ $item->{Config::get('app.fallback_locale').'_title'} }}
                                        @endif
                                    </h1>
                                    <h4 class="description ">
                                        @if ($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                                            {!! split_sentence( strip_tags($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}) , 150, '...') !!}
                                        @else
                                            {{ __('frontend.no_language_detect') }}
                                        @endif
                                    </h4>
                                    <br />
                                    <div class="buttons">
                                        @if ($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                            @if ($item instanceof \App\Modules\Backend\Blogs\Models\Blog)
                                                <a href="{{route('blog.detail', $item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'})}}" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-book-open"></i> @lang('frontend.home_view_post_button')
                                                </a>
                                            @else
                                                <a href="{{route('event.detail', $item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'})}}" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-book-open"></i> @lang('frontend.home_view_event_button')
                                                </a>
                                            @endif
                                        @else
                                            @if ($item instanceof \App\Modules\Backend\Blogs\Models\Blog)
                                                <a href="{{route('blog.detail', $item->{Config::get('app.fallback_locale').'_slug'} )}}" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-book-open"></i> @lang('frontend.home_view_post_button')
                                                </a>
                                            @else
                                                <a href="{{route('event.detail', $item->{Config::get('app.fallback_locale').'_slug'})}}" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-book-open"></i> @lang('frontend.home_view_event_button')
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <i class="now-ui-icons arrows-1_minimal-left"></i>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <i class="now-ui-icons arrows-1_minimal-right"></i>
    </a>
</div>
@else
@section('class')
sections-page
@endsection
<div class="page-header header-filter">
    <div class="page-header-image" style="background-image: url({{url(asset('images/posts/bg13.jpg'))}});"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h1 class="title"> You should be here!</h1>
                <h5 class="description">5,000 capacity venue, holding some of the latest technology lighting with a 24
                    colour laser system Amnesia is one of the islands most legendary clubs.</h4>
            </div>
            <div class="col-md-10 ml-auto mr-auto">
                <div class="card card-raised card-form-horizontal card-plain" data-background-color>
                    <div class="card-body">
                        <form method="" action="">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="text" value="" autocomplete="off" placeholder="Full Name"
                                            class="form-control" autocomplete="family-name" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input type="email" autocomplete="off" placeholder="Your Email"
                                            class="form-control" autocomplete="email" />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="selectpicker "
                                            data-style="select-with-transition btn-success btn-round"
                                            title="Who you are?" data-size="7">
                                            {{-- <option disabled>Who you are?</option> --}}
                                            <option value="2">I'm a writer</option>
                                            <option value="3">I'm event creator</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary btn-round btn-block">Join Us</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endpush

@section('content')
@if (!$top_posts->isEmpty())
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
                        <div class="card-title text-left title">
                            @if ($top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                <a href="{{route('blog.detail', $top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'}) }}">
                                    <h3 class="index_title">{{ split_sentence($top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 35, '...') }}</h3>
                                </a>
                            @else
                                <a href="{{route('blog.detail', $top->{Config::get('app.fallback_locale').'_slug'} ) }}">
                                    <h3 class="index_title">{{ split_sentence($top->{Config::get('app.fallback_locale').'_title'}, 35, '...') }}</h3>
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
                                &nbsp;
                                <div class="stats stats-right justify-content-center">
                                    &#9;&#9;&#9;
                                    <i class="now-ui-icons tech_watch-time"></i>
                                    {{\Carbon\Carbon::parse($top->created_at)->diffForHumans()}}
                                </div>
                            </div>
                            {!! render_category_class('div', $top) !!}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@else

@section('class')
sections-page
@endsection

@section('footer_class')
    data-background-color="gray"
@endsection

@include('frontend._partials._features')

@endif
@if (!$posts->isEmpty())
<div class="blogs-5" data-background-color="gray">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 ml-auto mr-auto">
                <h2 class="title text-center">
                    @lang('frontend.home_recent_blog')
                </h2>
                <div class="row index-load-data">

                    @foreach ($posts as $post)
                    <div class="col-md-4">
                        <div class="card card-blog">
                            <div class="card-image">
                                @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                    <a href="{{route('blog.detail', $post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'})}}">
                                        <img class="img rounded" src="{{ $post->media_url('card') }}"
                                            alt="{{ $post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} }}">
                                    </a>
                                @else
                                    <a href="{{route('blog.detail', $post->{Config::get('app.fallback_locale').'_slug'})}}">
                                        <img class="img rounded" src="{{ $post->media_url('card') }}"
                                            alt="{{ $post->{Config::get('app.fallback_locale').'_slug'} }}">
                                    </a>
                                @endif
                            </div>
                            <div class="card-body">
                                {!! render_category_class('h6', $post) !!}

                                <h5 class="card-title">
                                    @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                                        {{ split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                                    @else
                                        {{split_sentence($post->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                                    @endif
                                </h5>
                                <p class="card-description">
                                        @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                                        {{ split_sentence( strip_tags($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}) , 40, '...') }}
                                    @else
                                        {{ __('frontend.no_language_detect') }}
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
                <div class="row index-load-button">
                    <div class="col-md-3 mr-auto ml-auto">
                        <a href="#" class="btn btn-primary btn-round btn-block index-load-more">Load More Posts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection