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
        <div class="sh-11">
            <a href="/">
                <div class="logo-default"></div>
            </a>
        </div>
        <div class="mb-5">
            <h2 class="cta-1 text-primary">{{ __('Welcome') }}</h2>
        </div>
        <div class="mb-5">

            @if ($status == 'success')
                <p class="h6 mb-4">{{ __('Account verified successfully') }}</p>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('Continue') }}</a>
            @else
                <p class="h6 mb-4">{{ __('Your account has already been verified') }}</p>
                <a href="/" class="btn btn-primary">{{ __('Go to Login') }}</a>
            @endif

        </div>
    </div>
</div>
@endsection
