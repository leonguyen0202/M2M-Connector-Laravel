<div class="row">
    @if (!$posts->isEmpty())
    @foreach ($posts as $post)
    <div class="col-md-4">
        <div class="card" style="width: 20rem;">
            @if ($post instanceof \App\Modules\Backend\Blogs\Models\Blog)
            <img class="card-img-top" alt="Card image cap"
            src="{{ ( !file_exists($post->media_url('card')) ) ? $post->getFirstMediaUrl('blog-images') : $post->media_url('card') }}">
            @else
            <img class="card-img-top" alt="Card image cap"
                src="{{ asset('storage/images/upload/'.$post->background_image) }}">
            @endif
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
                    {!! split_sentence( strip_tags($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}) ,
                    30, '...') !!}
                    @else
                    {{ __('frontend.no_language_detect') }}
                @endif
                </p>

                <div class="text-center parent">
                    <?php 
                        if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null) {
                            $slug = $post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'};
                        } else {
                            $slug = $post->{Config::get('app.fallback_locale').'_slug' };
                        }
                        echo "<a href='#' class='btn btn-success btn-fab btn-icon btn-round blog-comments' data-slug='".$slug."' data-toggle='tooltip' data-placement='top' title='View Comments'>
                                <i class='now-ui-icons ui-2_chat-round'></i>
                            </a>";
                        echo "&nbsp;&nbsp;&nbsp;";
                        echo "<a href='#' class='btn btn-info btn-fab btn-icon btn-round blog-view' data-slug='".$slug."' data-toggle='tooltip' data-placement='top' title='View Detail'>
                                <i class='now-ui-icons ui-1_zoom-bold'></i>
                            </a>";
                        echo "&nbsp;&nbsp;&nbsp;";
                        echo "<a href='".route('blogs.edit', $slug)."' class='btn btn-primary btn-fab btn-icon btn-round blog-edit' data-toggle='tooltip' data-placement='top' title='Edit Post'>
                                    <i class='now-ui-icons ui-2_settings-90'></i>
                                </a>";
                        echo "&nbsp;&nbsp;&nbsp;";
                        echo "<a href='#' class='btn btn-danger btn-fab btn-icon btn-round blog-delete' data-slug='".$slug."' data-toggle='tooltip' data-placement='top' title='Delete Post'>
                                <i class='now-ui-icons ui-1_simple-remove'></i>
                            </a>";
                    ?>
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