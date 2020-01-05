<div class="col-md-4">
    <div class="card card-background card-raised" data-background-color
        style="background-image: url({{ url(asset('images/posts/'. $top->background_image)) }})">
        <div class="info">
            <div class="icon icon-white">
                {{-- <i class="now-ui-icons business_bulb-63"></i> --}}
            </div>
            <div class="description">
                <h4 class="info-title">{{split_sentence($top->title, 20, '...')}}</h4>
                <p>{{split_sentence($top->description, 65, '...')}}</p>
                <a href="#pablo" class="ml-3">Read more...</a>
            </div>
        </div>
    </div>
</div>