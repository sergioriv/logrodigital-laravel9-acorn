@php
    $title = 'Login Page';
    $description = 'Login Page';
@endphp
@extends('layout_full', ['title' => $title, 'description' => $description])
@section('css')
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/pages/auth.login.js"></script>
@endsection

@section('content_right')
    <div
        class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
        <div class="sw-lg-50 px-5">
            <div class="sh-13 mb-7 d-flex justify-content-center">
                <x-auth.logo :badge="$SCHOOL_badge" />
            </div>
            <div class="mb-5 text-center">
                <h2 class="cta-1 mb-0 text-primary">{{ $SCHOOL_name }}</h2>
            </div>
            <div>
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" :errors="$errors" />

                <form id="loginForm" class="tooltip-end-top" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3 filled form-group">
                        <i data-acorn-icon="email"></i>
                        <x-input id="email" name="email" type="email" :placeholder="__('E-Mail Address')" :value="old('email')" required
                            autofocus />
                    </div>

                    <!-- Password -->
                    <div class="mb-3 filled form-group">
                        <i data-acorn-icon="lock-off"></i>
                        <x-input id="password" class="pe-7" type="password" name="password" :placeholder="__('Password')" required
                            autocomplete="current-password" />
                        <a class="text-small badge bg-pink position-absolute t-3 e-3"
                            href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
                    </div>

                    <!-- Remember Me -->
                    <input type="hidden" name="remember" value="0">

                    <x-button type="submit" class="btn-primary">
                        {{ __('Log in') }}
                    </x-button>

                    @if (config('services.azure.client_id'))
                    <a class="btn btn-icon btn-icon-start btn-outline-secondary" href="/microsoft">
                        <x-social microsoft />
                        Microsoft
                    </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
