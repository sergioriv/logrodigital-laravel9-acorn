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

        jQuery("#country").change(function() {
            let national = $("option:selected", this).attr('national');
            if (1 == national) {
                $("#birth_city").prop('disabled', false);
            } else {
                $("#birth_city").prop('disabled', true);
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

        jQuery('#addInstitutionalEmail').click(function() {
            var email = $('#institutional_email');
            email.val(email.val().concat($(this).data('value'))).focus();
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

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('first name') }} <span class="text-danger">*</span></x-label>
                                            <x-input :value="old('firstName')" name="firstName" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('second name') }}</x-label>
                                            <x-input :value="old('secondName')" name="secondName" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('first last name') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input :value="old('firstLastName')" name="firstLastName" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('second last name') }}</x-label>
                                            <x-input :value="old('secondLastName')" name="secondLastName" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('document type') }} <span class="text-danger">*</span></x-label>
                                            <select name="document_type" id="document_type" logro="select2" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($documentType as $docType)
                                                    <option value="{{ $docType->code }}"
                                                        foreigner="{{ $docType->foreigner }}" @selected(old('document_type') == $docType->code)>
                                                        {{ $docType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('document') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input :value="old('document')" name="document" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('home country') }}
                                                <x-required />
                                            </x-label>
                                            <select name="country" id="country" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}"
                                                        national="{{ $country->national }}" @selected(old('country', $nationalCountry->id) == $country->id)>
                                                        {{ __($country->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('birth city') }}</x-label>
                                            <select name="birth_city" id="birth_city" logro="select2"
                                            @if (old('country', $nationalCountry->id) != $nationalCountry->id)
                                                disabled
                                            @endif>
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected(old('birth_city') == $city->id)>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <x-input :value="old('birthdate')" logro="datePickerBefore" name="birthdate"
                                            data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('Do you have siblings in the institution?') }}</x-label>
                                            <select name="siblings_in_institution" logro="select2">
                                                <option value="0" @selected(old('siblings_in_institution') == 0)>
                                                    {{ __('No') }}
                                                </option>
                                                <option value="1" @selected(old('siblings_in_institution') == 1)>
                                                    {{ __('Yes') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('institutional email') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input :value="old('institutional_email')" name="institutional_email" id="institutional_email"
                                                required />

                                            @if ($SCHOOL->institutional_email !== null)
                                                <div class="form-text cursor-pointer underline-link"
                                                    id="addInstitutionalEmail"
                                                    data-value="{{ $SCHOOL->institutional_email }}">
                                                    {{ __('Add Institutional Email') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
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

                        <div class="mb-5">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="repeat" name="repeat" value="1" />
                                <label class="form-check-label" for="repeat">{{ __('Is the student repeating?') }}</label>
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
