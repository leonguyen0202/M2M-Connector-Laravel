@extends('frontend.master')

@section('class')
blog-posts
@endsection

@push('meta')
<title>
    @lang('frontend.event_cover_title') - M2M Connector
</title>
@endpush

@section('content')
<div class="page-header page-header-small">
    <div class="page-header-image" data-parallax="true"
        style="background-image: url({{url(asset('images/cover/calendar_cover_background.jpeg'))}});">
    </div>
    <div class="content-center">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h2 class="title">
                    @lang('frontend.event_cover_title')
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
@if (!$special_events->isEmpty())
<div class="features-1">
    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
                <h2 class="title">
                    @lang('frontend.special_event')
                </h2>
            </div>
        </div>
        <br>
        <div class="row">
            @foreach ($special_events as $key => $item)
            <div class="col-md-6 {{ ($special_events->count() % 4 == 0) ? 'col-lg-3' : 'col-lg-4'}}">
                <div class="card card-blog card-plain">
                    <div class="card-image">
                        @if ( $item->{Cookie::get(strtolower(env('APP_NAME'))).'_slug' } != null )
                            <a href="{{route('event.detail', $item->{Cookie::get(strtolower(env('APP_NAME'))).'_slug' })}}">
                                <img class="img img-raised rounded"
                                    src="{{asset('images/events/'. $item->background_image)}}">
                            </a>
                        @else
                            <a href="{{route('event.detail', $item->{Config::get('app.fallback_locale').'_slug' })}}">
                                <img class="img img-raised rounded"
                                    src="{{asset('images/events/'. $item->background_image)}}">
                            </a>
                        @endif
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            @if ($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                                {{ split_sentence($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                            @else
                                {{split_sentence($item->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                            @endif
                            {{-- {{ split_sentence($item->title, 30, '...') }} --}}
                        </h5>
                        <p class="card-description">
                            @if ($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                                {{ split_sentence($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}, 50, '...') }}
                            @else
                                {{ __('frontend.no_language_detect') }}
                            @endif
                        </p>
                        <div class="card-footer">
                            @if ($item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                <a href="{{route('event.detail', $item->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} )}}" class="btn btn-primary">
                                    @lang('frontend.special_event_button')
                                </a>
                            @else
                                <a href="{{route('event.detail', $item->{Config::get('app.fallback_locale').'_slug' } )}}" class="btn btn-primary">
                                    @lang('frontend.special_event_button')
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@if (!$top_participated->isEmpty())

<div class="features-1" {!! render_conditional_class(!$special_events->isEmpty(), 'data-background-color="gray"', '')
    !!}>

    <div class="container">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto">
                <h2 class="title">
                    @lang('frontend.event_top_participated')
                </h2>
            </div>
        </div>
        <br>
        <div class="row">
            @foreach ($top_participated as $top)
            <div class="col-md-6">
                <div class="card card-background"
                    style="background-image: url( {{ $top->media_url('card') }} )">
                    <div class="card-body">
                        <div class="card-title text-left">
                            @if ($top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                <a href="{{route('event.detail', $top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} )}}" style="color:white">
                                    <h3 class="index_title">{{ split_sentence($top->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 35, '...') }}</h3>
                                </a>
                            @else
                                <a href="{{route('event.detail', $top->{Config::get('app.fallback_locale').'_slug' })}}" style="color:white">
                                    <h3 class="index_title">{{ split_sentence($top->{Config::get('app.fallback_locale').'_title' }, 35, '...') }}</h3>
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
@section('footer_class')
data-background-color="gray"
@endsection
<!-- Do not has event -->
@include('frontend._partials._features')
@endif

@if (!$events->isEmpty())
<div class="blogs-5" {!! render_conditional_class($special_events->isEmpty(), 'data-background-color="gray"', '') !!}>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 ml-auto mr-auto">
                <h2 class="title text-center">
                    @lang('frontend.event_recent')
                </h2>
                <br>
                <div class="row event-load-data">

                    @foreach ($events as $event)
                    <div class="col-md-4">
                        <div class="card card-blog">
                            <div class="card-image">
                                @if ($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                                    <a href="{{route('event.detail', $event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'})}}">
                                        <img class="img rounded" src="{{ $event->media_url('card') }}"
                                            alt="{{ $event->{Cookie::get(strtolower(env('APP_NAME')).'_language').'_title'} }}">
                                    </a>
                                @else
                                    <a href="{{route('event.detail', $event->{Config::get('app.fallback_locale').'_slug' })}}">
                                        <img class="img rounded" src="{{ $event->media_url('card') }}"
                                            alt="{{ $event->{Config::get('app.fallback_locale').'_title'} }}">
                                    </a>
                                @endif
                            </div>
                            <div class="card-body">
                                {!! render_category_class('h6', $event) !!}

                                <h5 class="card-title">
                                    @if ($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                                        {{ split_sentence($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                                    @else
                                        {{split_sentence($event->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                                    @endif
                                </h5>
                                <p class="card-description">
                                    @if ($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                                        {{ split_sentence( strip_tags($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}), 40, '...') }}
                                    @else
                                        {{ __('frontend.no_language_detect') }}
                                    @endif
                                </p>
                                <div class="card-footer">
                                    <div class="author">
                                        <img src="{{asset('images/avatars/'. $event->author->avatar)}}"
                                            alt="{{$event->author->avatar}}" class="avatar img-raised">
                                        <span>{{ $event->author->name }}</span>
                                    </div>
                                    <div class="stats stats-right">
                                        <i class="now-ui-icons tech_watch-time"></i>
                                        {{\Carbon\Carbon::parse($event->created_at)->diffForHumans()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="row event-load-button">
                    <div class="col-md-3 mr-auto ml-auto">
                        <a href="#"
                            class="btn btn-primary btn-round btn-block event-load-more">{{__('frontend.load_event_button')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if (!$contributors->isEmpty())
<div class="team-5" {!! render_conditional_class(!$special_events->isEmpty(), 'data-background-color="gray"', '') !!}>
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
@endif

@if ($special_events->isEmpty() || $contributors->isEmpty() || $events->isEmpty() || $top_participated->isEmpty())
@section('footer_class')
data-background-color="gray"
@endsection
@endif

@endsection