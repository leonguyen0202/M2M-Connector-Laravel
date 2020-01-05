@extends('frontend.master')

@section('class')
blog-post
@endsection

@push('meta')
<title>
    @if ( $event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null )
        {{$event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }} - M2M Connector
    @else
        {{ __('frontend.no_language_detect') }}
    @endif
</title>
@endpush

@section('footer_class')
data-background-color="gray"
@endsection

@section('content')
<div class="page-header page-header-small">
    <div class="page-header-image" data-parallax="true"
        style="background-image: url({{url(asset('images/events/'. $event->background_image))}});">
    </div>
    <div class="content-center">
        <div class="row">
            <div class="col-md-8 ml-auto mr-auto text-center">
                <h2 class="title">
                    @if ($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                        {{ $event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} }}
                    @else
                        {{ $event->{ Config::get('app.fallback_locale').'_title' } }}
                    @endif
                </h2>
                <h4>
                    {{\Carbon\Carbon::parse($event->created_at)->isoFormat("MMMM Do YYYY")}}
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
                    @if (isset($event->qr_code))
                    <a href="#pablo" class="btn btn-primary btn-round btn-lg" data-toggle="modal"
                        data-target="#qrCodeModal">
                        <i class="now-ui-icons text_align-left"></i> View QR Code
                    </a>
                    @endif

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
                <div class="col-md-8 ml-auto mr-auto {!! render_conditional_class($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} == null, 'text-center', '') !!}">
                    @if ($event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                        {!! $event->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} !!}
                    @else
                        <h3>{{ __('frontend.no_language_detect') }}</h3>
                        <br>
                        <a href="#" class="btn btn-primary request-language">{{__('frontend.language_request')}}</a>
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
                                @foreach (($event->categories) as $key => $value)
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
                                            src="{{asset('images/avatars/'. $event->author->avatar)}}">
                                    </a>
                                    <div class="ripple-container"></div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <h4 class="card-title">{{$event->author->name}}</h4>
                                <p class="description">{{$event->author->about}}</p>
                            </div>
                            @guest
                                <div class="col-md-3">
                                    <button type="button"
                                        class="btn btn-default pull-right btn-round follow-button">Follow</button>
                                </div>
                            @else
                                @if (Auth::id() != $event->author_id)
                                    <div class="col-md-3">
                                        <button type="button"
                                            class="btn {!! render_conditional_class($is_followed, 'btn-success', 'btn-default') !!} pull-right btn-round follow-button">
                                            <i class="{!! render_conditional_class($is_followed, 'fas', 'far') !!} fa-heart"></i>&nbsp;&nbsp;Follow
                                        </button>
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
@if (isset($event->qr_code))
<div class="modal fade modal-primary" id="qrCodeModal" tabindex="-1" role="dialog" aria-labelledby="qrCodeModal"
    aria-hidden="true">
    <div class="modal-dialog modal-login">
        <div class="modal-content">
            <div class="card card-login card-plain">
                <div class="modal-header justify-content-center">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>

                    <div class="header header-primary text-center">
                        <div class="logo-container">
                            <img src="{{asset('kit/img/now-logo.png')}}" alt="">
                        </div>
                    </div>
                </div>

                <div class="modal-body text-center" data-background-color>
                    {{-- {!!
                    QrCode::size(200)->generate('https://docs.google.com/forms/d/18tZIufI47jcTDEMBS8tSY_Km0dzxzt_ND6oSvDn7hRI/edit?usp=sharing')
                    !!} --}}
                    {!! QrCode::size(200)->generate($event->qr_code) !!}
                </div>
                <div class="modal-footer text-center justify-content-center">
                    {{-- <a href="#pablo" class="btn btn-neutral btn-round btn-lg btn-block">Get Started</a> --}}
                </div>
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

        $('.follow-button').on('click', function (e) {
            e.preventDefault();

            document.getElementById('follow-form').submit();
        })
    })
</script>
@endguest
@endpush