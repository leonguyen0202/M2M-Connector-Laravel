@extends('backend.master')

@push('meta')
<title>
    @if ($edit_mode)
    Edit Blog
    @else
    New Blog
    @endif
    - {{implode(" ", explode("_", env('APP_NAME')))}}
</title>
@endpush

@section('content')
<div class="panel-header" style="height:320px;">
    <div class="header text-center">
        @if ($edit_mode)
        <h2 class="title">Edit Blog</h2>
        <p class="category">
            Image and categories will be kept the same if there is no new change<br>
        </p>
        @else
        <h2 class="title">New Blog</h2>
        <p class="category">
            This section will let you create new blog<br>
        </p>
        @endif
        <br>
        <button type="button" class="btn btn-success btn-round" id="blog-submit">
            <i class="now-ui-icons ui-1_check"></i>&nbsp;Save
        </button>
        @if ($edit_mode)
        &nbsp;
        <button type="button" class="btn btn-primary btn-round" data-toggle="modal" data-target="#originalImageModal">
            <i class="now-ui-icons media-1_album"></i> Original Image
        </button>
        @endif
    </div>
</div>
<div class="content">

    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div id="accordion" role="tablist" aria-multiselectable="true" class="card-collapse">
                        <form action="{{ ($edit_mode) ? route('blogs.update', $slug) : route('blogs.store') }}"
                            class="blog-form" id="blog-form" enctype="multipart/form-data" method="POST">
                            @csrf
                            @if ($edit_mode)
                            @method('PUT')
                            <input type="hidden" name="_slug" id="slug" class="slug" value="{{$slug}}">
                            @endif
                            @foreach ($languages as $key => $section)
                            <div class="card card-plain">
                                <div class="card-header" role="tab" id="{{$section->locale_name}}">
                                    <a class="collapsed m2m-text" data-toggle="collapse" data-parent="#accordion"
                                        href="#{{strtolower($section->locale_name)}}"
                                        aria-expanded="{!! render_conditional_class($section->locale_code == 'en', 'true', 'false') !!}"
                                        aria-controls="{{strtolower($section->locale_name)}}">
                                        {{ucfirst($section->locale_name)}} Section
                                        @if (strtolower($section->locale_code) == Config::get('app.fallback_locale'))
                                        <sup>
                                            <span class="text-danger">* required</span>
                                        </sup>
                                        @else
                                        <sup>
                                            <span class="text-info">* optional</span>
                                        </sup>
                                        @endif
                                        <i class="now-ui-icons arrows-1_minimal-down"></i>
                                    </a>
                                </div>
                                <div id="{{strtolower($section->locale_name)}}"
                                    class="collapse {!! render_conditional_class(($section->locale_code == Config::get('app.fallback_locale')), 'show', '') !!}"
                                    role="tabpanel" aria-labelledby="{{strtolower($section->locale_name)}}">
                                    <div class="card-body">
                                        @if ($section->locale_code == Config::get('app.fallback_locale'))
                                        <div class="form-group tags-parent">
                                            <input type="text" name="tags" {!! ($edit_mode) ? " value=' " .
                                                form_tags($post->categories) . " ' "
                                            : " value='' " !!} class="tagsinput form-control tags"
                                            data-role="tagsinput" data-color="info" disabled>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="{{strtolower($section->locale_code)}}_title">Title</label>
                                            <input type="text" class="form-control" autocomplete="off"
                                                name="{{strtolower($section->locale_code)}}_title"
                                                id="{{strtolower($section->locale_code)}}_title"
                                                placeholder="{{__('backend.new_title_input_placeholder')}}" {!!
                                                ($edit_mode) ? "value='" .$post->{
                                            strtolower($section->locale_code).'_title' }."'" : '' !!}>
                                        </div>
                                        @if ($section->locale_code == Config::get('app.fallback_locale'))
                                        <div class="form-group">
                                            <label class="card-title"
                                                for="{{strtolower($section->locale_code)}}_background_image">Background
                                                Image</label>
                                            <div class="form-group form-file-upload form-file-simple">
                                                <input type="text" name="background_image" id="background_image"
                                                    class="form-control inputFileVisible" autocomplete="off"
                                                    placeholder="{{__('backend.new_background_image_input_placeholder')}}">
                                                <input type="file" accept="image/jpg, image/png, image/jpeg"
                                                    name="background_image_file" class="inputFileHidden">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="{{strtolower($section->locale_code)}}_categories">Categories</label>
                                            <select
                                                class="selectpicker form-control {{strtolower($section->locale_code)}}_categories"
                                                data-live-search="true" name="categories[]" multiple
                                                data-max-options="5" data-style="select-with-transition"
                                                title="{{__('backend.new_categories_input_placeholder')}}"
                                                data-size="10" id="{{strtolower($section->locale_code)}}_categories">
                                                <option disabled>{{__('backend.new_categories_input_placeholder')}}
                                                </option>
                                                @foreach ($categories as $category)
                                                <option value="{{$category->slug}}">{{$category->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label
                                                for="{{strtolower($section->locale_code)}}_description">Description</label>
                                            <textarea name="{{strtolower($section->locale_code)}}_description"
                                                class="{{strtolower($section->locale_code)}}_description"
                                                id="{{strtolower($section->locale_code)}}_description"
                                                style="min-width: 100%"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@if ($edit_mode)
<div class="modal fade" id="originalImageModal" tabindex="-1" role="dialog" aria-labelledby="originalImageModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="origianlImageModalLabel">Original Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row text-center">
                    <div class="col">
                        <img src="{{ $post->media_url('slider') }}" alt="original-image">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary ml-auto" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('customJS')
<script src="{{ asset('js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#blog-submit').on('click', function (e) {
            e.preventDefault();
            document.getElementById('blog-form').submit();
        });

        @foreach ($languages as $tiny_selector)
        var {!! $tiny_selector->locale_code . '_description' !!} = '{!! $tiny_selector->locale_code !!}_description';
        tinymce.init({
            mode: "textareas",
            selector: 'textarea.{!! $tiny_selector->locale_code.'_description' !!}',
            height: 500,
            plugins: "fullscreen",
            branding: false
        });
        @endforeach
    })
</script>

@if ($edit_mode)
<script type="text/javascript">
    var slug = $('input[name=_slug]').val();
    $.ajax({
        url: '/dashboard/blog/tinymce/description',
        method: "POST",
        dataType: 'json',
        data: {
            slug:slug,
            '_token': $('input[name=_token]').val()
        },
        success: (data) => {
            if (data.error) {
                Swal.fire({
                    type: 'error',
                    title: data.error,
                    showConfirmButton: false,
                    timer: 1000,
                });
            } else {
                $.each(data.data, function (k,v) {
                    if (v != null) {
                        tinymce.get(k).setContent(v);
                    };
                });
            };
        },
        error: (jqXHR, textStatus, errorThrown) => {
            formatErrorMessage(jqXHR, errorThrown);
        },
    });

    function sweetAlertError(message) {
        Swal.fire({
            type: 'error',
            title: message,
            showConfirmButton: false,
            timer: 1000
        });
    };

    function formatErrorMessage(jqXHR, exception) {
        if (jqXHR.status === 0) {
            sweetAlertError('Not connected.\nPlease verify your network connection.');
        } else if (jqXHR.status == 404) {
            sweetAlertError('The request not found.');
        } else if (jqXHR.status == 401) {
            Swal.fire({
                type: 'error',
                title: 'Sorry!! You session has expired. Please login to continue access.',
                showConfirmButton: false,
                timer: 1500
            });
        } else if (jqXHR.status == 500) {
            sweetAlertError('Internal Server Error.');
        } else if (exception === 'parsererror') {
            sweetAlertError('Requested JSON parse failed.');
        } else if (exception === 'timeout') {
            sweetAlertError('Time out error.');
        } else if (exception === 'abort') {
            sweetAlertError('Ajax request aborted.');
        } else {
            sweetAlertError('Unknown error occured. Please try again.');
        };
    };
</script>
@endif



@endpush