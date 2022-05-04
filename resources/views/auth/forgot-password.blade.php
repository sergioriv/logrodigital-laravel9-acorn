@php
$title = 'Forgot Password Page';
$description = 'Forgot Password Page'
@endphp
@extends('layout_full',['title'=>$title, 'description'=>$description])
@section('css')
@endsection

@section('js_vendor')
<script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
<script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection

@section('js_page')
<script src="/js/pages/auth.forgotpassword.js"></script>
@endsection

@section('content_right')
<div
    class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
    <div class="sw-lg-50 px-5">
        <div class="sh-11">
            <a href="/">
                <div class="logo-default"></div>
            </a>
        </div>
        <div class="mb-5">
            <h2 class="cta-1 mb-0 text-primary">Password is gone?</h2>
            <h2 class="cta-1 text-primary">Let's reset it!</h2>
        </div>
        <div class="mb-5">
            <p class="h6">Please enter your email to receive a link to reset your password.</p>
            <p class="h6">
                If you are a member, please
                <a href="/">login</a>
                .
            </p>
        </div>
        <div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form id="forgotPasswordForm" class="tooltip-end-bottom" method="POST" action="{{ route('password.email') }}" novalidate>
                @csrf

                <!-- Email -->
                <div class="mb-3 filled form-group tooltip-end-top">
                    <i data-acorn-icon="email"></i>
                    <x-input id="email" name="email" :placeholder="__('Email')" :value="old('email')" required autofocus />
                </div>

                <x-button type="submit" class="btn-primary">
                    {{ __('Send Reset Email') }}
                </x-button>
            </form>
        </div>
    </div>
</div>
@endsection
