@php
$title = 'Edit user';
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
                    <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="card mb-5">
                    <div class="card-body">

                        <!-- Validation Errors -->
                        {{-- <x-validation-errors class="mb-4" :errors="$errors" /> --}}

                        <form id="registerForm" method="POST" action="{{ route('support.users.update', $user) }}" class="tooltip-end-bottom" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="mb-3">
                                <x-label>{{ __('Name') }}</x-label>
                                <x-input id="name" name="name" value="{{ $user->name }}" disabled required />
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <x-label>{{ __('Email') }}</x-label>
                                <x-input id="email" name="email" value="{{ $user->email }}" disabled required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Role') }}</label>

                                @foreach ($roles as $role)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        {{ $role->name }}
                                        <input name="role" class="form-check-input" type="radio" value="{{ $role->id }}"
                                        {{ ($user->getRoleNames()[0] ?? null) === $role->name ? 'checked' : '' }} />
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn btn-lg btn-primary">{{ __('Update user') }}</button>

                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
