@php
    $title = 'Confirm email';
    $description = '';
@endphp
@extends('layout_full', ['title' => $title, 'description' => $description])

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
            <div class="sh-13 mb-5 d-flex justify-content-center">
                <x-auth.logo :badge="$SCHOOL_badge" />
            </div>
            <div class="mb-5 text-center">
                <h2 class="cta-1 text-primary">
                    {{ $SCHOOL_name }}</h2>
            </div>
            <div class="mb-5">

                @if ($status == 'password')

                    <div class="h6 mb-3 text-center">
                        <i data-acorn-icon="shield-check" class="me-1 text-success"></i>
                        {{ __('Account verified successfully') }}
                    </div>
                    <div class="">
                        <h2 class="cta-1 mb-0">{{ __('Assign password') }}</h2>
                    </div>
                    <div class="mb-3 text-alternate">{{ auth()->user()->email }}</div>

                    <form method="POST" action="{{ route('support.users.password') }}">
                        @csrf
                        @method('PUT')

                        <!-- Password -->
                        <div class="mb-3">
                            <x-label>{{ __('New password') }}</x-label>
                            <x-input id="password" name="password" type="password" required />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-3">
                            <x-label>{{ __('Confirm Password') }}</x-label>
                            <x-input id="password_confirmation" name="password_confirmation" type="password" required />
                        </div>

                        <div class="text-end">
                            <x-button type="submit" class="btn-primary">
                                {{ __('Confirm') }}
                            </x-button>
                        </div>
                    </form>
                @else
                    <p class="h6 mb-4">{{ __('Your account has already been verified') }}</p>
                    <a href="/" class="btn btn-primary">{{ __('Go to Login') }}</a>
                @endif

            </div>
        </div>
    </div>
@endsection
