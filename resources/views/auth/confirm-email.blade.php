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
                <div class="logo-default img-uchuva"></div>
            </a>
        </div>
        <div class="mb-5">
            <h2 class="cta-1 text-primary">{{ __('Welcome') }}</h2>
        </div>
        <div class="mb-5">

            @if ($status == 'success')
            <p class="h6 mb-4">{{ __('Account verified successfully') }}</p>

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

            @else
            <p class="h6 mb-4">{{ __('Your account has already been verified') }}</p>
            <a href="/" class="btn btn-primary">{{ __('Go to Login') }}</a>
            @endif

        </div>
    </div>
</div>
@endsection
