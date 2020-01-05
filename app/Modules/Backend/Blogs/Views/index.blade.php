@extends('backend.master')

@push('meta')
<title>
    Blogs - {{implode(" ", explode("_", env('APP_NAME')))}}
</title>
@endpush

@section('content')
<div class="panel-header" style="height:350px">
    <div class="header text-center">
        <h2 class="title">Blogs</h2>
        <p class="category">
            This section will display your created blogs.<br>
            You can switch between card and table view to suit your taste.
        </p>
        <div class="btn-group">
            <button type="button" class="btn btn-primary">
                @if (Cache::has('_'.Auth::id().'_blog_view'))
                {{ ucfirst(\Cache::get('_'.Auth::id().'_blog_view')) }}&nbsp;View
                @else
                Select View
                @endif
            </button>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="sr-only">
                    @if (Cache::has('_'.Auth::id().'_blog_view'))
                    {{ ucfirst(\Cache::get('_'.Auth::id().'_blog_view')) }}&nbsp;View
                    @else
                    Select view
                    @endif
                </span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="{{route('blogs.view', 'card')}}">Card view</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('blogs.view', 'table')}}">Table view</a>
            </div>
        </div>
        &nbsp;
        <a href="{{route('blogs.create')}}" class="btn btn-success">
            <i class="now-ui-icons ui-1_simple-add"></i>&nbsp;New blog
        </a>
    </div>
</div>
<div class="content">
    <!-- ajax: '/dashboard/permission/api/list', -->
    @if ($is_table_view)
    @include('Blogs::conditional_include.table_view')
    @else
    @include('Blogs::conditional_include.card_view')
    @endif
</div>
@endsection

@push('customJS')
<script src="{{asset('js/backend.js')}}"></script>
@if (session('success'))
    @foreach (session('success') as $success)
        <script type="text/javascript">
            $.notify({
                icon: "now-ui-icons ui-1_check",
                message: "{!! $success !!}",

            }, {
                type: 'success',
                timer: 3000,
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

@endpush