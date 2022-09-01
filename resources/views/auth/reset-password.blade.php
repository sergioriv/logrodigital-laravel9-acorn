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
<script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
@endsection

@section('js_page')
<script src="/js/pages/auth.resetpassword.js"></script>
@endsection

@section('content_right')
<div
    class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
    <div class="sw-lg-50 px-5">
        <div class="sh-13 mb-7 d-flex justify-content-center">
            <a href="/">
                <div class="logo-default img-logro"></div>
            </a>
        </div>
        <div class="mb-5 text-center">
            <h2 class="cta-1 text-primary">{{ \App\Http\Controllers\SchoolController::name() }}</h2>
        </div>
        <div class="mb-5">
            <p class="h6">{{ __("Enter new password for your account") }}</p>
        </div>
        <div>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form id="resetForm" method="POST" action="{{ route('password.update') }}" class="tooltip-end-top"
                novalidate>
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}" />

                <!-- Email Address -->
                <div class="mb-3 filled form-group">
                    <i data-acorn-icon="email"></i>
                    <x-input type="text" readonly name="email" value="{{ $request->email }}" required />
                </div>

                <!-- Password -->
                <div class="mb-3 filled form-group">
                    <i data-acorn-icon="lock-off"></i>
                    <x-input id="password" name="password" type="password" :placeholder="__('Password')" required />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3 filled form-group">
                    <i data-acorn-icon="lock-on"></i>
                    <x-input id="password_confirmation" name="password_confirmation" type="password" :placeholder="__('Confirm Password')" required />
                </div>

                <x-button type="submit" class="btn-primary">
                    {{ __('Reset Password') }}
                </x-button>

                <a href="/" class="btn btn-outline-primary">{{ __("Go Home") }}</a>
            </form>
        </div>
    </div>
</div>
@endsection
