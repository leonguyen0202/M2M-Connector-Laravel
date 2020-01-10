<div class="subscribe-line subscribe-line-image"
    style="background-image: url({{ url(asset('storage/kit/img/bg7.jpg')) }});">
    <div class="container">
        <div class="row">
            <div class="col-md-6 ml-auto mr-auto">
                <div class="text-center">
                    <h4 class="title">{{ __('frontend.subscribe_title') }}</h4>
                    <p class="description">
                        {{ __('frontend.subscribe_description_1') }} <br />
                        {{ __('frontend.subscribe_description_2') }}
                    </p>
                </div>
                @guest
                <div class="card card-raised card-form-horizontal">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="now-ui-icons ui-1_email-85"></i></span>
                                    </div>
                                    <input type="email" id="subscribe_email" class="form-control"
                                        placeholder="Your Email...">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-primary btn-round btn-block"
                                    id="subscribe-button">Subscribe</button>
                                <form action="" style="display:none;"></form>
                            </div>
                        </div>
                    </div>
                </div>
                @else

                @if (Auth::user()->subscribe_with_id || Auth::user()->has_subscribe)
                <div class="col-md-9 mr-auto ml-auto text-center">
                    <div class="alert alert-success" role="alert">
                        <div class="container">
                            <div class="alert-icon">
                                <i class="now-ui-icons ui-2_like"></i>
                            </div>
                            <strong>Well done!</strong> You successfully subscribe to receive important newsletter
                        </div>
                    </div>
                </div>
                @else
                <div class="col-md-8 mr-auto ml-auto">
                    <button type="button" class="btn btn-primary btn-round btn-block"
                        id="subscribe-button">Subscribe</button>
                </div>
                <form id="subscribe-form" action="{{ route('home.subscribe') }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="subscribe_email" id="subscribe_email" value="{{Auth::user()->email}}">
                </form>
                @endif

                @endguest
            </div>
        </div>
    </div>
</div>

<footer class="footer" data-background-color="black">
    <div class="container">
        <a class="footer-brand" style="text-decoration:none" href="{{route('home.index')}}">M2M Connector</a>
        <ul class="pull-center">
            <li>
                <a href="{{route('blog.index')}}">
                    {{ __('frontend.blog_menu') }}
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
                <a href="#pablo">
                    {{ __('frontend.about_us_menu') }}
                </a>
            </li>
            <li>
                <a href="#pablo">
                    {{ __('frontend.contact_us_menu') }}
                </a>
            </li>
        </ul>
        <ul class="social-buttons pull-right">
            <li>
                <a href="#" target="_blank" class="btn btn-icon btn-link btn-neutral">
                    <i class="fab fa-twitter"></i>
                </a>
            </li>
            <li>
                <a href="#" target="_blank" class="btn btn-icon btn-neutral btn-link">
                    <i class="fab fa-facebook-square"></i>
                </a>
            </li>
            <li>
                <a href="#" target="_blank" class="btn btn-icon btn-neutral btn-link">
                    <i class="fab fa-instagram"></i>
                </a>
            </li>
        </ul>
    </div>
</footer>

@push('customJS')
@guest
<script type="text/javascript">
    $(document).ready(function () {
        $('#subscribe-button').on('click', function (e) {
            e.preventDefault();

            var subscribe_email = $('#subscribe_email').val();

            if (subscribe_email == '') {
                sweetAlertError('Please enter email before subscribe!');
            } else {
                $.ajax({
                    url: '/subscribe',
                    method: "POST",
                    data: {
                        subscribe_email: subscribe_email,
                        '_token': $('input[name=_token]').val()
                    },
                    success: (data) => {
                        if (data.error) {
                            sweetAlertError(data.error);
                        } else {
                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                html: '<span class="text-success">' + data.success + '</span>',
                                showConfirmButton: false,
                            });
                            window.setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    },
                    error: (jqXHR, textStatus, errorThrown) => {
                        formatErrorMessage(jqXHR, errorThrown)
                    }
                })
            }
        })
    })
</script>
@else
<script type="text/javascript">
    $(document).ready(function () {
        $('#subscribe-button').on('click', function (e) {
            e.preventDefault();

            document.getElementById('subscribe-form').submit();
        })
    })
</script>
@endguest
@endpush