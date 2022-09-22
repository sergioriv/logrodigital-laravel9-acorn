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
@endsection

@section('js_page')
<script src="/js/forms/select2.js"></script>
<script src="/js/forms/student-advices.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. __("advice") }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('students.advice.store', $student) }}" class="tooltip-center-bottom"
                        id="studentAdviceCreateForm" autocomplete="off">
                        @csrf

                        <div class="card mb-5">
                            <div class="card-body w-100">

                                <div class="row mb-3 position-relative">
                                    <label class="col-sm-3 col-form-label">
                                        {{ __('Attendance') }} <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-select name="attendance" id="attendance" logro="select2" required>
                                            <option value="done" @selected(old('attendance') == 'done')>{{ __("done") }}</option>
                                            <option value="not done" @selected(old('attendance') == 'not done')>{{ __("not done") }}</option>
                                        </x-select>
                                    </div>
                                </div>

                                <div id="attendance-content">
                                    <div class="row mb-3 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Type advice') }} <x-required />
                                        </label>
                                        <div class="col-sm-9">
                                            <x-select name="type_advice" logro="select2" >
                                                @foreach ($advice->enumTypeAdvice() as $typeAdvice)
                                                    <option value="{{ $typeAdvice }}"
                                                        @selected(old('type_advice') == $typeAdvice)>
                                                        {{ __($typeAdvice) }}
                                                    </option>
                                                @endforeach
                                            </x-select>
                                        </div>
                                    </div>
                                    <div class="row mb-5 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Evolución') }} <x-required />
                                        </label>
                                        <div class="col-sm-9">
                                            <textarea name="evolution" id="evolution" class="form-control" rows="5" required>{{ old('evolution') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3 position-relative pt-5 border-top">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Recomendación para los docentes') }}
                                        </label>
                                        <div class="col-sm-9">
                                            <textarea name="recommendations_teachers" id="recommendations_teachers"
                                                class="form-control" rows="3">{{ old('recommendations_teachers') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-5 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Alert due date') }}
                                        </label>
                                        <div class="col-sm-9">
                                            <x-input name="date_limite" id="date_limite" :value="old('date_limite')"
                                                logro="datePickerToday" disabled="true" />
                                        </div>
                                    </div>
                                    <div class="row mb-5 position-relative pt-5 border-top">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Recomendaciones para la familia') }}
                                        </label>
                                        <div class="col-sm-9">
                                            <textarea name="recommendations_family" class="form-control" rows="3">{{ old('recommendations_family') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3 position-relative pt-5 border-top">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Entidad a remitir') }}
                                        </label>
                                        <div class="col-sm-9">
                                            <x-select name="entity_remit" id="entity_remit" logro="select2">
                                                @foreach ($advice->enumEntityRemit() as $entityRemit)
                                                    <option value="{{ $entityRemit }}"
                                                        @selected(old('entity_remit') == $entityRemit)>
                                                        {{ __($entityRemit) }}
                                                    </option>
                                                @endforeach
                                            </x-select>
                                        </div>
                                    </div>
                                    <div class="row mb-3 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Observaciones para la entidad') }}
                                        </label>
                                        <div class="col-sm-9">
                                            <textarea name="observations_for_entity" id="observations_for_entity"
                                                class="form-control" rows="5" disabled="true">{{ old('observations_for_entity') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>

                    </form>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
