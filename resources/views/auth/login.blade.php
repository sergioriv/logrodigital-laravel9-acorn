@php
$title = 'Login Page';
$description = 'Login Page'
@endphp
@extends('layout_full',['title'=>$title, 'description'=>$description])
@section('css')
@endsection

@section('js_vendor')
<script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
<script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
@endsection

@section('js_page')
{{-- <script src="/js/pages/auth.login.js"></script> --}}
@endsection

@section('content_right')
<div
    class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
    <div class="sw-lg-50 px-5">
        <div class="sh-13 mb-7 d-flex justify-content-center">
            <a href="/">
                <div class="logo-default img-uchuva"></div>
            </a>
        </div>
        <div class="mb-5">
            <h2 class="cta-1 mb-0 text-primary">{{ __('Welcome') }},</h2>
            <h2 class="cta-1 text-primary">{{ __("let's get started!") }}</h2>
        </div>
        <div>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form id="loginForm" class="tooltip-end-bottom" method="POST" action="{{ route('login') }}" novalidate>
                @csrf

                <!-- Email Address -->
                <div class="mb-3 filled form-group tooltip-end-top">
                    <i data-acorn-icon="email"></i>
                    <x-input id="email" name="email" type="email" :placeholder="__('E-Mail Address')" :value="old('email')" required
                        autofocus />
                </div>

                <!-- Password -->
                <div class="mb-3 filled form-group tooltip-end-top">
                    <i data-acorn-icon="lock-off"></i>
                    <x-input id="password" class="pe-7" type="password" name="password" :placeholder="__('Password')"
                        required autocomplete="current-password" />
                    <a class="text-small position-absolute t-3 e-3" href="{{ route('password.request') }}">{{ __('Forgot') }}?</a>
                </div>

                <!-- Remember Me -->
                <div class="mb-3 form-group">
                    <div class="form-check">
                        <input name="remember" class="form-check-input" type="checkbox" id="remember_me" />
                        <label class="form-check-label" for="remember_me">{{ __('Remember Me') }}</label>
                    </div>
                </div>

                <x-button type="submit" class="btn-primary">
                    {{ __('Log in') }}
                </x-button>
            </form>
        </div>
    </div>
</div>
@endsection
