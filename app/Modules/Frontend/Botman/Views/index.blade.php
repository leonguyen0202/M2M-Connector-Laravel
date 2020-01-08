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
    <div class="container">
        <div class="section">
            @if (!$results->isEmpty())
            <h3 class="title text-center">Here is the answer you are looking for</h3>
            <br />
            <div class="row">
                @foreach ($results as $result)
                <div class="col-md-4">
                    <div class="card card-plain card-blog">
                        <div class="card-image">
                            <a href="#pablo">
                                <img class="img rounded img-raised" src="{{$result->media_url('card')}}" />
                            </a>
                        </div>
                        <div class="card-body">
                            {!! render_category_class('h6',$result) !!}
                            <h4 class="card-title">
                                @if ($result->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                                {{ split_sentence($result->{ Cookie::get(strtolower(env('APP_NAME')).'_language').'_title'} ,50 ,'...') }}
                                @else
                                {{ $result->{Config::get('app.fallback_locale').'_title'} }}
                                @endif
                            </h4>
                            <p class="card-description">
                                @if ($result->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} !=
                                null)
                                {!! split_sentence( strip_tags($result->{Cookie::get(
                                strtolower(env('APP_NAME')).'_language' ).'_description'}) , 150, '...') !!}
                                @else
                                {{ __('frontend.no_language_detect') }}
                                @endif
                                <a href="#pablo"> Read More </a>
                                <div class="author">
                                    <img src="{{asset('images/avatars/'. $result->author->avatar)}}"
                                        alt="{{$result->author->avatar}}" class="avatar img-raised">
                                    <span>{{ $result->author->name }}</span>
                                </div>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <h3 class="title text-center">
                Sorry! I cannot find the answer you are looking for.
            </h3>
            @endif
        </div>
    </div>
</div>
@endsection