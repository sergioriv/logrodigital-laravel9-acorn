@php
$title = __('Create') . ' ' . __('Student');
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
    <script src="/js/forms/genericforms.js"></script>
    <script>
        jQuery("[logro='select2']").select2({
            minimumResultsForSearch: 30,
            placeholder: ''
        });

        jQuery("#document_type").change(function() {
            let foreigner = $("#document_type option:selected").attr('foreigner');
            if (1 == foreigner)
            {
                $("#birth_city").addClass('d-none');
                $("#country").removeClass('d-none');
            } else
            {
                $("#birth_city").removeClass('d-none');
                $("#country").addClass('d-none');
            }
        });

        jQuery("#saveAndMatriculate").click(function() {
            $("#matriculate").prop("checked", "checked");
        });

        jQuery(".filter").change(function() {
            studentParentFilter();
        });

        function studentParentFilter() {

            $.get("parents.filter", {
                headquarters: jQuery("#headquarters").val(),
                studyTime: jQuery("#studyTime").val(),
                studyYear: jQuery("#studyYear").val(),
            }, function(data) {
                if (0 != data) {
                    jQuery("#saveAndMatriculate").removeAttr('disabled');
                } else {
                    jQuery("#saveAndMatriculate").prop('disabled', 'disabled');
                }
            });
        }

        jQuery('#addInstitutionalEmail').click(function () {
            var email = $('#institutional_email');
            email.val( email.val().concat( $(this).data('value') ) );
        });
    </script>
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

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('students.store') }}" class="tooltip-label-end"
                        id="studentCreateForm" novalidate autocomplete="off">
                        @csrf

                        <!-- Validation Errors -->
                        {{-- <x-validation-errors class="mb-4" :errors="$errors" /> --}}

                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('first name') }} <span class="text-danger">*</span></x-label>
                                            <x-input :value="old('firstName')" name="firstName" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('second name') }}</x-label>
                                            <x-input :value="old('secondName')" name="secondName" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __("father's last name") }} <span class="text-danger">*</span>
                                            </x-label>
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
                                            <x-label>{{ __('document type') }} <span class="text-danger">*</span></x-label>
                                            <select name="document_type" id="document_type" logro="select2" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($documentType as $docType)
                                                    <option value="{{ $docType->code }}" foreigner="{{ $docType->foreigner }}" @selected(old('document_type') == $docType->code)>
                                                        {{ $docType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('document') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input :value="old('document')" name="document" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6
                                    @if(session('docType'))
                                        @if (session('docType')->foreigner === 1)
                                        d-none
                                        @endif
                                    @endif" id="birth_city">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('birth city') }}</x-label>
                                            <select name="birth_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected(old('birth_city') == $city->id)>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6
                                    @if(session('docType'))
                                        @if (session('docType')->foreigner !== 1)
                                        d-none
                                        @endif
                                    @else
                                        d-none
                                    @endif" id="country">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('home country') }}</x-label>
                                            <select name="country" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}" @selected(old('country') == $country->id)>
                                                        {{ __($country->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <x-input :value="old('birthdate')" logro="datePicker" name="birthdate" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('institutional email') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input :value="old('institutional_email')" name="institutional_email" id="institutional_email" required />

                                            @if ( \App\Http\Controllers\SchoolController::email() !== NULL)
                                            <div class="form-text cursor-pointer underline-link"
                                                id="addInstitutionalEmail" data-value="{{ \App\Http\Controllers\SchoolController::email() }}">
                                                {{ __("Add Institutional Email") }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('headquarters') }} <span class="text-danger">*</span></x-label>
                                            <select name="headquarters" id="headquarters" class="filter" logro="select2">
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
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('study time') }} <span class="text-danger">*</span></x-label>
                                            <select name="studyTime" id="studyTime" class="filter" logro="select2">
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
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('study year') }} <span class="text-danger">*</span></x-label>
                                            <select name="studyYear" id="studyYear" class="filter" logro="select2">
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

                        <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>

                        <input type="checkbox" name="matriculate" id="matriculate" class="d-none" value="1">
                        <button class="btn btn-outline-primary" id="saveAndMatriculate" type="submit"
                            @if (0 === $countGroups) disabled="disabled" @endif>
                            {{ __('Save and Matriculate') }}
                        </button>

                    </form>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
