@php
$title = 'Inactive';
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
            <x-auth.logo :badge="$SCHOOL_badge" />
        </div>
        <div class="mb-5 text-center">
            <h2 class="cta-1 text-primary">{{ $SCHOOL_name }}</h2>
        </div>
        <div class="mb-5">
            <h2 class="cta-1 text-danger lh-1">
                <i data-acorn-icon="shield-warning"></i>
                {{ __('Account deactivated') }}</h2>
        </div>
        <div class="mb-5">
            <p class="h6">
                {{ __("To activate your account and log in to the platform, you must go to the institution's offices.") }}
            </p>
        </div>
        <div>
            <a href="./" class="btn btn-lg btn-light">
            {{ __('Go Home') }}
            </a>
        </div>
    </div>
</div>
@endsection
