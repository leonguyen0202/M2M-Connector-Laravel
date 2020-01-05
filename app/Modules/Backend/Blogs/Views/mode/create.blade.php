@push('meta')
<title>
    New Blog - {{implode(" ", explode("_", env('APP_NAME')))}}
</title>
@endpush

@section('content')
<div class="panel-header" style="height:300px">
    <div class="header text-center">
        <h2 class="title">New Blog</h2>
        <p class="category">
            This section will let you create new blog<br>
        </p>
        <a href="{{route('blogs.create')}}" class="btn btn-success" id="blog-submit">
            <i class="now-ui-icons ui-1_check"></i>&nbsp;Save
        </a>
    </div>
</div>
<div class="content">
    
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="card">
                <div class="card-body">
                    <div id="accordion" role="tablist" aria-multiselectable="true" class="card-collapse">
                        <form action="{{route('blogs.store')}}" class="blog-form" id="blog-form"
                            enctype="multipart/form-data" method="POST">
                            @csrf
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
                                        <div class="form-group {{strtolower($section->locale_code)}}_tags_parent">
                                            <input type="text" name="{{strtolower($section->locale_code)}}_tags"
                                                value="Systems Administration,Security in Computing,Internet of Things,Code Igniter,Vuejs"
                                                class="tagsinput form-control {{strtolower($section->locale_code)}}_tags"
                                                data-role="tagsinput" data-color="info" disabled>
                                        </div>
                                        @endif
                                        <div class="form-group">
                                            <label for="{{strtolower($section->locale_code)}}_title">Title</label>
                                            <input type="text" class="form-control" autocomplete="off"
                                                name="{{strtolower($section->locale_code)}}_title"
                                                id="{{strtolower($section->locale_code)}}_title"
                                                placeholder="{{__('backend.new_title_input_placeholder')}}">
                                        </div>
                                        @if ($section->locale_code == Config::get('app.fallback_locale'))
                                        <div class="form-group">
                                            <label class="card-title"
                                                for="{{strtolower($section->locale_code)}}_background_image">Background
                                                Image</label>
                                            <div class="form-group form-file-upload form-file-simple">
                                                <input type="text"
                                                    name="background_image"
                                                    id="background_image"
                                                    class="form-control inputFileVisible" autocomplete="off"
                                                    placeholder="{{__('backend.new_background_image_input_placeholder')}}">
                                                <input type="file" accept="image/jpg, image/png, image/jpeg"
                                                    name="background_image_file"
                                                    class="inputFileHidden">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label
                                                for="{{strtolower($section->locale_code)}}_categories">Categories</label>
                                            <select
                                                class="selectpicker form-control {{strtolower($section->locale_code)}}_categories"
                                                data-live-search="true"
                                                name="categories[]" multiple
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
@endsection

@push('customJS')
<script src="{{ asset('js/tinymce/js/tinymce/tinymce.min.js') }}"></script>

@if (session('errors'))
    @foreach (session('errors') as $error)
        <script type="text/javascript">
            $.notify({
                icon: "now-ui-icons ui-1_simple-remove",
                message: "{!! $error !!}",

            }, {
                type: 'danger',
                timer: 5000,
                allow_dismiss: false,
                placement: {
                    from: 'top',
                    align: 'right',
                },
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });
        </script>
    @endforeach
@endif

<script type="text/javascript">
    $(document).ready(function () {
        $('#blog-submit').on('click', function (e) {
            e.preventDefault();
            document.getElementById('blog-form').submit();
        });

        $('#notification').on('click', function (e) {
            e.preventDefault();

            var align = ['left', 'center', 'right'];

            color = 'danger';

            $.notify({
                icon: "now-ui-icons ui-1_bell-53",
                message: "Welcome to <b>Now Ui Dashboard Pro</b> - a beautiful freebie for every web developer."

            }, {
                type: color,
                timer: 4000,
                placement: {
                    from: 'top',
                    align: align[Math.floor(Math.random() * align.length)],
                },
                animate: {
                    enter: 'animated fadeInDown',
                    exit: 'animated fadeOutUp'
                },
            });
        });
    })
</script>

<script type="text/javascript">
    $(document).ready(function () {
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


@endpush