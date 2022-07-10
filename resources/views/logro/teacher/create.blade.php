@php
$title = __('Create Teacher');
@endphp
@extends('layout',['title'=>$title])

@section('css')
@endsection

@section('js_vendor')
<script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
<script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
<script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
@endsection

@section('js_page')
<script src="/js/forms/genericforms.js"></script>
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

            <!-- Content Start -->
            <section class="scroll-section">
                <form method="post" action="{{ route('teacher.store') }}" class="tooltip-label-end" id="teacherForm" novalidate>
                    @csrf

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" :errors="$errors" />

                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("first name") }}</x-label>
                                        <x-input :value="old('firstName')" name="firstName" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("second name") }}</x-label>
                                        <x-input :value="old('secondName')" name="secondName" />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("father's last name") }}</x-label>
                                        <x-input :value="old('fatherLastName')" name="fatherLastName" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("mother's last name") }}</x-label>
                                        <x-input :value="old('motherLastName')" name="motherLastName" />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("telephone") }}</x-label>
                                        <x-input :value="old('phone')" name="phone" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("email") }}</x-label>
                                        <x-input :value="old('email')" name="email" required />
                                    </div>
                                </div>
                            </div>

                            <x-button class="btn-primary" type="submit">{{ __("Save") }}</x-button>

                        </div>
                    </div>

                </form>
            </section>
            <!-- Content End -->
        </div>
    </div>
</div>
@endsection
