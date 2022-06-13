@php
$title = 'Create user';
@endphp
@extends('layout',['title'=>$title])

@section('css')
{{-- <link rel="stylesheet" href="/css/vendor/select2.min.css" /> --}}
{{-- <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" /> --}}
@endsection

@section('js_vendor')
{{-- <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
<script src="/js/vendor/jquery.validate/additional-methods.min.js"></script> --}}
{{-- <script src="/js/vendor/select2.full.min.js"></script> --}}
@endsection

@section('js_page')
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Title Start -->
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="card mb-5">
                    <div class="card-body">

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('support.users.store') }}" class="tooltip-end-bottom" novalidate>
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <x-label>{{ __('Name') }}</x-label>
                                <x-input id="name" name="name" :value="old('name')" required autofocus />
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <x-label>{{ __('Email') }}</x-label>
                                <x-input id="email" name="email" :value="old('email')" required />
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <x-label>{{ __('Password') }}</x-label>
                                <x-input id="password" name="password" type="password" required
                                    autocomplete="current-password" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <x-label>{{ __('Confirm Password') }}</x-label>
                                <x-input id="password_confirmation" name="password_confirmation" type="password"
                                    required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Role') }}</label>

                                @foreach ($roles as $role)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        {{ $role->name }}
                                        <input name="role" class="form-check-input" type="radio" value="{{ $role->id }}" />
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary">{{ __('Save user') }}</button>

                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
