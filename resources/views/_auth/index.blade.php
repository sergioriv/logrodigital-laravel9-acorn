@php
    $html_tag_data = [];
    $title = 'Authentication';
    $description = 'User verification, registration and recovery pages.';
    $breadcrumbs = ["/"=>"Home", "/Pages"=>"Pages"]
@endphp
@extends('layout',['html_tag_data'=>$html_tag_data, 'title'=>$title, 'description'=>$description])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                    <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    @include('_layout.breadcrumb',['breadcrumbs'=>$breadcrumbs])
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

        <!-- List Items Start -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-2">
            <div class="col">
                <div class="card h-100">
                    <div class="card-body row gx-4">
                        <div class="col-auto">
                            <i data-acorn-icon="login" class="text-primary"></i>
                        </div>
                        <div class="col">
                            <a href="/Pages/Authentication/Login" class="heading stretched-link d-block">Login</a>
                            <div class="text-muted">Login form with email and password.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body row gx-4">
                        <div class="col-auto">
                            <i data-acorn-icon="content" class="text-primary"></i>
                        </div>
                        <div class="col">
                            <a href="/Pages/Authentication/Register" class="heading stretched-link d-block">Register</a>
                            <div class="text-muted">Register form with email, password and password confirmation.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body row gx-4">
                        <div class="col-auto">
                            <i data-acorn-icon="key" class="text-primary"></i>
                        </div>
                        <div class="col">
                            <a href="/Pages/Authentication/ForgotPassword" class="heading stretched-link d-block">Forgot Password</a>
                            <div class="text-muted">Password recovery form with an email field.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <div class="card-body row gx-4">
                        <div class="col-auto">
                            <i data-acorn-icon="shield" class="text-primary"></i>
                        </div>
                        <div class="col">
                            <a href="/Pages/Authentication/ResetPassword" class="heading stretched-link d-block">Reset Password</a>
                            <div class="text-muted">Password reset form with password and password confirmation.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- List Items End -->
    </div>
@endsection
