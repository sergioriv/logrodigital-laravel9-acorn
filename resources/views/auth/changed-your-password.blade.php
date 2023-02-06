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
<script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
@endsection

@section('js_page')
<script src="/js/pages/auth.forgotpassword.js"></script>
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
        <div class="mb-3">
            <h2 class="cta-1">Actualizar contraseña</h2>
        </div>
        <div class="mb-3 text-alternate">{{ auth()->user()->email }}</div>
        <div class="mb-3 text-alternate">
            Por su seguridad, es necesario actualizar la contraseña porque fué generada por el sistema.
        </div>
        <div>
            <form action="{{ route('user.change-password.verified') }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Current Password -->
                <div class="mb-3 filled form-group">
                    <i data-acorn-icon="lock-off"></i>
                    <x-input
                        type="password"
                        name="current_password"
                        placeholder="{{ __('Current password') }}"
                        required />
                </div>

                <!-- Password -->
                <div class="mb-3 filled form-group">
                    <i data-acorn-icon="lock-on"></i>
                    <x-input
                        type="password"
                        name="password"
                        placeholder="{{ __('Password') }}"
                        required />
                </div>

                <!-- Confirm Password -->
                <div class="mb-3 filled form-group">
                    <i data-acorn-icon="lock-on"></i>
                    <x-input
                        type="password"
                        name="password_confirmation"
                        placeholder="{{ __('Confirm Password') }}"
                        required />
                </div>

                <div class="text-end">
                    <x-button type="submit" class="btn-primary">
                        {{ __('Log In') }}
                    </x-button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
