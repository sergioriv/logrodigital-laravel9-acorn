@php
$title = 'Confirm email';
$description = '';
@endphp
@extends('layout_full',['title'=>$title, 'description'=>$description])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
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
            <h2 class="cta-1 text-primary">
                {{ \App\Http\Controllers\SchoolController::name() }}</h2>
        </div>
        <div class="mb-5">

            @if ($status == 'password')
            <p class="h6 mb-4">
                <i data-acorn-icon="shield-check" class="me-1 text-success"></i>
                {{ __('Account verified successfully') }}
            </p>

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('support.users.password') }}">
                @csrf
                @method('PUT')

                <!-- Password -->
                <div class="mb-3">
                    <x-label>{{ __('Password') }}</x-label>
                    <x-input id="password" name="password" type="password" required />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <x-label>{{ __('Confirm Password') }}</x-label>
                    <x-input id="password_confirmation" name="password_confirmation" type="password" required />
                </div>

                <x-button type="submit" class="btn-primary">{{ __('Confirm') }}</x-button>
            </form>

            {{-- @elseif ($status == 'provider')
            <p class="h6 mb-4">{{ __('Account verified successfully') }}</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Continue') }}</a> --}}
            @else
            <p class="h6 mb-4">{{ __('Your account has already been verified') }}</p>
            <a href="/" class="btn btn-primary">{{ __('Go to Login') }}</a>
            @endif

        </div>
    </div>
</div>
@endsection
