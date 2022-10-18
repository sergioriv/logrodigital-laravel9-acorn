@php
$title = __('Create Teacher');
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
    <script src="/js/vendor/imask.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/genericforms.js"></script>
    <script src="/js/forms/select2.js"></script>
    <script>
        IMask(document.querySelector('[name="document"]'), {
            mask: Number,
        });
        IMask(document.querySelector('[name="telephone"]'), {
            mask: Number,
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
                    <form method="post" action="{{ route('teacher.store') }}" class="tooltip-label-end" id="teacherForm"
                        novalidate>
                        @csrf

                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('names') }}</x-label>
                                            <x-input :value="old('names')" name="names" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('last names') }}</x-label>
                                            <x-input :value="old('lastNames')" name="lastNames" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
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
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <x-input :value="old('birthdate')" logro="datePickerBefore" name="birthdate" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('document number') }}</x-label>
                                            <x-input :value="old('document')" name="document" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <select name="expedition_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected(old('expedition_city') == $city->id)>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('residence city') }}</x-label>
                                            <select name="residence_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected(old('residence_city') == $city->id)>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('residence address') }}</x-label>
                                            <x-input :value="old('address')" name="address" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <x-input :value="old('telephone')" name="telephone" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('cellphone') }}</x-label>
                                            <x-input :value="old('cellphone')" mask="number" name="cellphone" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('email') }}</x-label>
                                            <x-input :value="old('email')" name="email" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('marital status') }}</x-label>
                                            <select name="marital_status" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($maritalStatus as $marital)
                                                    <option value="{{ $marital }}" @selected(old('marital_status') == $marital)>
                                                        {{ __($marital) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label required>{{ __('type of appointment') }}</x-label>
                                            <select name="type_appointment" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($typesAppointment as $typeAppointment)
                                                    <option value="{{ $typeAppointment }}" @selected(old('type_appointment') == $typeAppointment)>
                                                        {{ __($typeAppointment) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label required>{{ __('type of administrative act') }}</x-label>
                                            <select name="type_admin_act" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($typesAdministrativeAct as $typeAdminAct)
                                                    <option value="{{ $typeAdminAct }}" @selected(old('type_admin_act') == $typeAdminAct)>
                                                        {{ __($typeAdminAct) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <x-input :value="old('appointment_number')" mask="number" name="appointment_number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_appointment')" logro="datePickerBefore" name="date_appointment" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <x-input :value="old('appointment_number')" mask="number" name="appointment_number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_appointment')" logro="datePickerBefore" name="date_appointment" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <x-input :value="old('appointment_number')" mask="number" name="appointment_number" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_appointment')" logro="datePickerBefore" name="date_appointment" />
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
