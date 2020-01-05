@extends('backend.master')

@push('meta')
<title>
    New Blog - {{implode(" ", explode("_", env('APP_NAME')))}}
</title>
@endpush

@section('content')

@endsection

@push('customJS')
<script src="{{ asset('js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
@if ($is_table_view)
<script>
    $(document).ready(function() {
        $('#blog-table-view').DataTable({
            "order": [
                [ 0, "asc" ]
            ],
            "pagingType": "full_numbers",
            "lengthMenu": [
                [50, 100, 150, -1],
                [50, 100, 150, "All"]
            ],
            iDisplayLength: 50,
            dateFormat: 'yyyy-mm-dd',
            processing: true,
            serverSide: true,
            
            ajax: {
                url: '/dashboard/blog/table/view',
                type: 'POST',
                headers:{
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            },
            columns: [{
                    data: 'background',
                    name: 'background'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'categories',
                    name: 'categories'
                },
                {
                    data: 'comments',
                    name: 'comments'
                },
                {
                    data: 'action',
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
            ],
            language: {
                search: "_INPUT_",
                sSearchPlaceholder: "{!! __('backend.blog_sSearchPlaceholder') !!}",
                sInfoEmpty: "{!! __('backend.blog_sInfoEmpty') !!}",
                sInfoFiltered: "{!! __('backend.blog_sInfoFiltered') !!}",
                sZeroRecords: "{!! __('backend.blog_sZeroRecords') !!}",
                sLoadingRecords: "{!! __('backend.blog_sLoadingRecords') !!}",
                sProcessing: "{!! __('backend.blog_sProcessing') !!}",
                sZeroRecords: "{!! __('backend.blog_sZeroRecords') !!}",
                sLengthMenu: "{!! __('backend.blog_sLengthMenu') !!}",
                sInfo: "{!! __('backend.blog_sInfo') !!}",
                oPaginate: {
                    sFirst: "{!! __('backend.blog_sFirst') !!}",
                    sLast: "{!! __('backend.blog_sLast') !!}",
                    sNext: "{!! __('backend.blog_sNext') !!}",
                    sPrevious: "{!! __('backend.blog_sPrevious') !!}"
                },
            }
        });
    });
</script>
@endif

@endpush