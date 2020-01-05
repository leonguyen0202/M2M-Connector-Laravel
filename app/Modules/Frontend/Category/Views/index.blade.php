@extends('frontend.master')

@section('class')
blog-posts
@endsection

@push('meta')
<title>
    @lang('frontend.category_cover_title') - M2M Connector
</title>
@endpush

@section('content')

@section('footer_class')
data-background-color="gray"
@endsection

<div class="page-header page-header-small">
    <div class="page-header-image" data-parallax="true"
        style="background-image: url({{url(asset('images/cover/category_cover_background.jpg'))}});">
    </div>
    <div class="content-center">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h2 class="title">
                    @lang('frontend.category_cover_title')
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

<div class="section section-plain-cards">
    <div class="container">
        <!--     *********    PLAIN BLOG CARDS      *********      -->
        <div class="row categories-load-data">
            @foreach ($categories as $item)
            <div class="col-md-6 col-lg-4">
                <div class="card card-blog card-plain">
                    <div class="card-image">
                        <a href="{{route('category.detail', $item->slug)}}">
                            <img class="img img-raised rounded"
                                src="{{asset('images/categories/'. $item->background_image)}}">
                        </a>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">
                            {{$item->title}}
                        </h5>
                        <p class="card-description">
                            {{ split_sentence($item->description, 50, '...') }}
                        </p>
                        <div class="card-footer">
                            <a href="{{route('category.detail', $item->slug)}}" class="btn btn-primary">View
                                category</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if (isset($categories))
        <div class="row categories-button">
            <div class="col-md-3 mr-auto ml-auto">
                <a href="#" class="btn btn-primary btn-round btn-block categories-load-more">Load More Categories</a>
            </div>
        </div>
        @endif
    </div>
    <!--     *********    END PLAIN BLOG CARDS      *********      -->
</div>
@endsection