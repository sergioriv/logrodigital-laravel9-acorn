@php
$title = $student->getFullName();
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
    <script src="/js/vendor/timepicker.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/select2.js"></script>
    <script src="/js/forms/student-advices.js"></script>
    <script>
        new TimePicker(document.querySelector('#timeAdvice'));
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. __('new advice') }}</h1>
                    </div>
                    <a href="{{ URL::previous() }}" class="muted-link pb-3 d-inline-block lh-1">
                        <i class="me-1" data-acorn-icon="chevron-left" data-acorn-size="13"></i>
                        <span class="text-small align-middle">{{ __("Go back") }}</span>
                    </a>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('students.advice.store', $student) }}" class="tooltip-start-top"
                        id="studentAdviceCreateForm" autocomplete="off">
                        @csrf

                        <div class="card mb-3">
                            <div class="card-body w-100">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input name="date" :value="old('date')" logro="datePickerToday" />
                                            <div class="form-text">Min: {{ date('Y-m-d') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="time-picker-container">
                                            <div class="position-relative form-group">
                                                <x-label>{{ __('hour') }}</x-label>
                                                <input class="form-control time-picker" name="time" data-format="12"
                                                    data-minutes="0,10,20,30,40,50" id="timeAdvice" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <x-button class="btn-primary" type="submit">{{ __('Create') }}</x-button>

                    </form>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
