@foreach ($posts as $post)
<div class="col-md-4">
    <div class="card" style="width: 20rem;">
        <img class="card-img-top" src="{{asset('images/posts/'.$post->background_image)}}" alt="Card image cap">
        <div class="card-body">
            <h4 class="card-title">
                @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                {{ split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                @else
                {{split_sentence($post->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                @endif
            </h4>
            <p class="card-text">
                @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                {!! split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'},
                30, '...') !!}
                @else
                {{ __('frontend.no_language_detect') }}
                @endif
            </p>

            <div class="text-center">
                <button type="button" class="btn btn-success btn-fab btn-icon btn-round view" data-toggle="tooltip"
                    data-placement="top" title="View">
                    <i class="now-ui-icons ui-2_chat-round"></i>
                </button>
                &nbsp;
                <button type="button" class="btn btn-info btn-fab btn-icon btn-round view" data-toggle="tooltip"
                    data-placement="top" title="View">
                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                </button>
                &nbsp;
                <button type="button" class="btn btn-primary btn-fab btn-icon btn-round edit" data-toggle="tooltip"
                    data-placement="top" title="Edit">
                    <i class="now-ui-icons ui-2_settings-90"></i>
                </button>
                &nbsp;
                <button type="button" class="btn btn-danger btn-fab btn-icon btn-round delete" data-toggle="tooltip"
                    data-placement="top" title="Delete">
                    <i class="now-ui-icons ui-1_simple-remove"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach