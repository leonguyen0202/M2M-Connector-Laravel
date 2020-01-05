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
@endpush