@php
$title = __('Dashboard');
@endphp
@extends('layout',['title'=>$title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
<script>
    $( document ).ready(function() {
        const permission = await navigator.permissions.query({ name: 'clipboard-read' });
        alert(permission.state);
    });
</script>
@endsection



@section('content')
<div class="container">
    <!-- Title and Top Buttons Start -->
    <div class="page-title-container">
        <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7">
                <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
            </div>
            <!-- Title End -->
        </div>
    </div>
    <!-- Title and Top Buttons End -->

    <!-- Content Start -->
    <div class="card mb-3">
        <div class="card-body h-100">Bienvenido a {{ config('app.name') }}</div>
    </div>
    @hasrole('PARENT')
    <div class="alert alert-info" role="alert">Próximamente, en esta plataforma tendrá información sobre los estudiantes que usted tenga a cargo</div>
    @endhasrole
    <!-- Content End -->
</div>
@endsection
