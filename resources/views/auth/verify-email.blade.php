@php
$title = 'Verify email';
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
            <p class="h6">
                {{ __('Before we begin, could you please verify your email address by clicking the link we sent to your
                email address? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
            <p>{{ __('A new verification link has been sent to your email address (' . Auth::user()->email . ')') }}
            </p>
            @endif
        </div>
        <div>

            @unless (session('status') == 'verification-link-sent')
            <form method="POST" action="{{ route('verification.send') }}" class="tooltip-end-bottom">
                @csrf

                <x-button type="submit" class="btn-primary">
                    {{ __('Resend Verification Email') }}
                </x-button>
            </form>
            @endunless

            <form method="POST" action="{{ route('logout') }}" class="tooltip-end-bottom mt-4">
                @csrf

                <x-button type="submit" class="btn-light">
                    {{ __('Log Out') }}
                </x-button>
            </form>

        </div>
    </div>
</div>
@endsection
