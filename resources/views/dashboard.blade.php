@php
$html_tag_data = [
'override' => '{ "attributes" : { "placement" : "vertical", "layout":"fluid" }, "storagePrefix" :
"starter-project", "showSettings" : false }',
];
$title = 'Dashboard';
$description = 'An empty page with a fluid vertical layout.';
$breadcrumbs = [];
@endphp
@extends('layout',['html_tag_data'=>$html_tag_data, 'title'=>$title, 'description'=>$description])

@section('css')
@endsection

@section('js_vendor')
<script src="/js/vendor/bootstrap-notify.min.js"></script>
@endsection

@section('js_page')
@if (Session::has('message'))
<script>
    callNotify( "{{ Session::get('message') }}" )
</script>
@endif
@endsection



@section('content')
<div class="container">
    <!-- Title and Top Buttons Start -->
    <div class="page-title-container">
        <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                @include('_layout.breadcrumb', ['breadcrumbs' => $breadcrumbs])
            </div>
            <!-- Title End -->
        </div>
    </div>
    <!-- Title and Top Buttons End -->

    <!-- Content Start -->
    <div class="card mb-2">
        <div class="card-body h-100">{{ $description }}</div>
    </div>
    <!-- Content End -->
</div>
@endsection
