@php
$title = __('Create') .' '. __('Student');
@endphp
@extends('layout',['title'=>$title])

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
<script src="/js/forms/genericforms.js"></script>
<script>
    jQuery("[logro='select2']").select2({minimumResultsForSearch: 30, placeholder: ''});
</script>
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
                <form method="post" action="{{ route('students.store') }}" class="tooltip-label-end" id="studentCreateForm">
                    @csrf

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" :errors="$errors" />

                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("first name") }} <span class="text-danger">*</span></x-label>
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
                                        <x-label>{{ __("father's last name") }} <span class="text-danger">*</span></x-label>
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
                                    <div class="mb-3 w-100 position-relative form-group">
                                        <x-label>{{ __("document type") }} <span
                                                class="text-danger">*</span></x-label>
                                        <select name="document_type" logro="select2" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($documentType as $docType)
                                            <option value="{{ $docType->code }}"
                                                @selected(old("document_type") === $docType->code)>
                                                {{ $docType->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("document") }} <span class="text-danger">*</span>
                                        </x-label>
                                        <x-input :value="old('document')" name="document" required/>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 w-100 position-relative form-group">
                                        <x-label>{{ __("birth city") }}</x-label>
                                        <select name="birth_city" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($cities as $city)
                                            <option value="{{ $city->id }}"
                                                @selected(old('birth_city') == $city->id)>
                                                {{ $city->department->name .' | '. $city->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("birthdate") }}</x-label>
                                        <x-input :value="old('birthdate')" logro="datePicker"
                                            name="birthdate" />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("institutional email") }} <span class="text-danger">*</span></x-label>
                                        <x-input :value="old('institutional_email')" name="institutional_email" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 w-100 position-relative form-group">
                                        <x-label>{{ __("headquarters") }} <span class="text-danger">*</span></x-label>
                                        <select name="headquarters" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($headquarters as $hq)
                                            <option value="{{ $hq->id }}" @selected(old('headquarters') == $hq->id)>
                                                {{ $hq->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 w-100 position-relative form-group">
                                        <x-label>{{ __("study time") }} <span class="text-danger">*</span></x-label>
                                        <select name="studyTime" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyTime as $st)
                                            <option value="{{ $st->id }}" @selected(old('studyTime') == $st->id)>
                                                {{ $st->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 w-100 position-relative form-group">
                                        <x-label>{{ __("study year") }} <span class="text-danger">*</span></x-label>
                                        <select name="studyYear" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyYear as $sy)
                                            <option value="{{ $sy->id }}" @selected(old('studyYear') == $sy->id)>
                                                {{ $sy->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <x-button class="btn-primary" type="submit">{{ __("Save") }}</x-button>

                </form>
            </section>
            <!-- Content End -->

        </div>
    </div>
</div>
@endsection
