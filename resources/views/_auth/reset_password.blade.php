@php
    $title = 'Reset Password Page';
    $description = 'Reset Password Page'
@endphp
@extends('layout_full',['title'=>$title, 'description'=>$description])
@section('css')
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/pages/auth.resetpassword.js"></script>
@endsection

@section('content_left')
    <div class="min-h-100 d-flex align-items-center">
        <div class="w-100 w-lg-75 w-xxl-50">
            <div>
                <div class="mb-5">
                    <h1 class="display-3 text-white">Multiple Niches</h1>
                    <h1 class="display-3 text-white">Ready for Your Project</h1>
                </div>
                <p class="h6 text-white lh-1-5 mb-5">
                    Dynamically target high-payoff intellectual capital for customized technologies. Objectively integrate emerging core competencies before
                    process-centric communities...
                </p>
                <div class="mb-5">
                    <a class="btn btn-lg btn-outline-white" href="/">Learn More</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content_right')
    <div class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
        <div class="sw-lg-50 px-5">
            <div class="sh-11">
                <a href="/">
                    <div class="logo-default"></div>
                </a>
            </div>
            <div class="mb-5">
                <h2 class="cta-1 mb-0 text-primary">Password trouble?</h2>
                <h2 class="cta-1 text-primary">Renew it here!</h2>
            </div>
            <div class="mb-5">
                <p class="h6">Please use below form to reset your password.</p>
                <p class="h6">
                    If you are a member, please
                    <a href="/Pages/Authentication/Login">login</a>
                    .
                </p>
            </div>
            <div>
                <form id="resetForm" class="tooltip-end-bottom" novalidate>
                    <div class="mb-3 filled">
                        <i data-acorn-icon="lock-off"></i>
                        <input class="form-control" id="password" name="password" type="password" placeholder="Password" />
                    </div>
                    <div class="mb-3 filled">
                        <i data-acorn-icon="lock-on"></i>
                        <input class="form-control" name="verifyPassword" type="password" placeholder="Verify Password" />
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
@endsection
