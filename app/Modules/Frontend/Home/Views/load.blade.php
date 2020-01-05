@foreach ($posts as $post)
<div class="col-md-4">
    <div class="card card-blog">
        <div class="card-image">
            @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} != null)
            <a href="{{route('blog.detail', $post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'})}}">
                <img class="img rounded" src="{{asset('images/posts/'. $post->background_image)}}"
                    alt="{{ $post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_slug'} }}">
            </a>
            @else
            <a href="{{route('blog.detail', $post->{Config::get('app.fallback_locale').'_slug'})}}">
                <img class="img rounded" src="{{asset('images/posts/'. $post->background_image)}}"
                    alt="{{ $post->{Config::get('app.fallback_locale').'_slug'} }}">
            </a>
            @endif
        </div>
        <div class="card-body">
            {!! render_category_class('h6', $post) !!}

            <h5 class="card-title">
                @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'} != null)
                {{ split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_title'}, 20, '...') }}
                @else
                {{split_sentence($post->{Config::get('app.fallback_locale').'_title' }, 20, '...')}}
                @endif
            </h5>
            <p class="card-description">
                @if ($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'} != null)
                {{ split_sentence($post->{Cookie::get( strtolower(env('APP_NAME')).'_language' ).'_description'}, 40, '...') }}
                @else
                {{ __('frontend.no_language_detect') }}
                @endif
            </p>
            <div class="card-footer">
                <div class="author">
                    <img src="{{asset('images/avatars/'. $post->author->avatar)}}" alt="{{$post->author->avatar}}"
                        class="avatar img-raised">
                    <span>{{ $post->author->name }}</span>
                </div>
                <div class="stats stats-right">
                    <i class="now-ui-icons tech_watch-time"></i>
                    {{\Carbon\Carbon::parse($post->created_at)->diffForHumans()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach