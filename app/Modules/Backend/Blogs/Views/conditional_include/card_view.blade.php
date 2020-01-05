<div class="row">
    @if (!$posts->isEmpty())
    @foreach ($posts as $post)
    <div class="col-md-4">
        <div class="card" style="width: 20rem;">
            <img class="card-img-top" alt="Card image cap"
                src="{{ ( !file_exists($post->media_url('card')) ) ? $post->getFirstMediaUrl('blog-images') : $post->media_url('card') }}">
            <div class="card-body">
                <h4 class="card-title">
                    @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                    {{ split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                    @else
                    {{split_sentence($post->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                    @endif
                </h4>
                {{-- <p class="card-text">
                    @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                    {!! split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'},
                    30, '...') !!}
                    @else
                    {{ __('frontend.no_language_detect') }}
                @endif
                </p> --}}

                <div class="text-center parent">
                    @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
                    <input type="hidden" name="token" id="token" class="token"
                        value="{{ $post->{Cookie::get(strtolower(env('APP_NAME')).'_language' ).'_slug'} }}">
                    @else
                    <input type="hidden" name="token" id="token" class="token"
                        value="{{ $post->{Config::get('app.fallback_locale').'_slug' } }}">
                    @endif
                    <button type="button" class="btn btn-success btn-fab btn-icon btn-round blog-comments"
                        data-toggle="tooltip" data-placement="top" title="View Comments">
                        <i class="now-ui-icons ui-2_chat-round"></i>
                    </button>
                    &nbsp;
                    <button type="button" class="btn btn-info btn-fab btn-icon btn-round blog-view"
                        data-toggle="tooltip" data-placement="top" title="View Detail">
                        <i class="now-ui-icons ui-1_zoom-bold"></i>
                    </button>
                    &nbsp;
                    <button type="button" class="btn btn-primary btn-fab btn-icon btn-round blog-edit"
                        data-toggle="tooltip" data-placement="top" title="Edit Post">
                        <i class="now-ui-icons ui-2_settings-90"></i>
                    </button>
                    &nbsp;
                    <button type="button" class="btn btn-danger btn-fab btn-icon btn-round blog-delete"
                        data-toggle="tooltip" data-placement="top" title="Delete Post">
                        <i class="now-ui-icons ui-1_simple-remove"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="col-md-12 ">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title">No post have been found.</h4>
                <p class="card-text">Please take some time to create your personal post</p>
                <a href="{{route('blogs.create')}}" class="btn btn-success"><i class="now-ui-icons ui-1_simple-add"></i>&nbsp;New Blog</a>
            </div>
        </div>
    </div>
    @endif
</div>