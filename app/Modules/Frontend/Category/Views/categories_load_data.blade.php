@foreach ($categories as $item)
<div class="col-md-6 col-lg-4">
    <div class="card card-blog card-plain">
        <div class="card-image">
            <a href="{{route('category.detail', $item->slug)}}">
                <img class="img img-raised rounded" src="{{asset('images/categories/'. $item->background_image)}}">
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