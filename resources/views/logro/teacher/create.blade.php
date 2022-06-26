@php
$title = 'Create Teacher';
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
                <form method="post" action="{{ route('teacher.store') }}" class="tooltip-start-top" id="teacherForm" novalidate>
                    @csrf

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" :errors="$errors" />

                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="mb-3 top-label">
                                        <input class="form-control" name="firstName" />
                                        <span>{{ __("FIRST NAME") }}</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-3 top-label">
                                        <input class="form-control" name="secondName" />
                                        <span>{{ __("SECOND NAME") }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="mb-3 top-label">
                                        <input class="form-control" name="fatherLastName" />
                                        <span>{{ __("FATHER'S LAST NAME") }}</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-3 top-label">
                                        <input class="form-control" name="motherLastName" />
                                        <span>{{ __("MOTHER'S LAST NAME") }}</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="mb-3 top-label">
                                        <input class="form-control" name="phone" />
                                        <span>{{ __("PHONE NUMBER") }}</span>
                                    </label>
                                </div>
                                <div class="col-md-6">
                                    <label class="mb-3 top-label">
                                        <input class="form-control" name="email" />
                                        <span>{{ __("EMAIL") }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer border-0 pt-0 d-flex justify-content-end align-items-center">
                            <div>
                                <x-button class="btn-primary" type="submit">
                                    <span>{{ __("Save") }}</span>
                                </x-button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <!-- Content End -->
        </div>
    </div>
</div>
@endsection
