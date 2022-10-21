@php
    $title = $teacher->names;
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
        IMask(document.querySelector('[name="cellphone"]'), {
            mask: Number,
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <section class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7">
                            <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                        </div>
                        <!-- Title End -->

                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">

                            <!-- Add New Button Start -->
                            <a href="{{ route('profile.auth.avatar.edit') }}"
                                class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                <i data-acorn-icon="edit-square"></i>
                                <span>{{ __('Edit avatar') }}</span>
                            </a>
                            <!-- Add New Button End -->


                        </div>
                        <!-- Top Buttons End -->
                    </div>
                </section>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('user.profile.update') }}" class="tooltip-label-end"
                        id="teacherProfileForm" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="card mb-5">
                            <div class="card-body row g-3">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('names') }}</x-label>
                                        <x-input :value="old('names', $teacher)" name="names" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('last names') }}</x-label>
                                        <x-input :value="old('lastNames', $teacher->last_names)" name="lastNames" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('document number') }}</x-label>
                                        <x-input :value="old('document', $teacher)" name="document" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('expedition city') }}</x-label>
                                        <select name="expedition_city" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" @selected(old('expedition_city', $teacher) == $city->id)>
                                                    {{ $city->department->name . ' | ' . $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('birth city') }}</x-label>
                                        <select name="birth_city" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" @selected(old('birth_city', $teacher) == $city->id)>
                                                    {{ $city->department->name . ' | ' . $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('birthdate') }}</x-label>
                                        <x-input :value="old('birthdate', $teacher)" logro="datePickerBefore" name="birthdate" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('residence city') }}</x-label>
                                        <select name="residence_city" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->id }}" @selected(old('residence_city', $teacher) == $city->id)>
                                                    {{ $city->department->name . ' | ' . $city->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('residence address') }}</x-label>
                                        <x-input :value="old('address', $teacher)" name="address" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('telephone') }}</x-label>
                                        <x-input :value="old('telephone', $teacher)" name="telephone" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('cellphone') }}</x-label>
                                        <x-input :value="old('cellphone', $teacher)" mask="number" name="cellphone" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('institutional email') }}</x-label>
                                        <x-input :value="old('institutional_email', $teacher)" name="institutional_email" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('marital status') }}</x-label>
                                        <select name="marital_status" logro="select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($maritalStatus as $marital)
                                                <option value="{{ $marital }}" @selected(old('marital_status', $teacher) == $marital)>
                                                    {{ __($marital) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card mb-5">
                            <div class="card-body row g-3">
                                <div class="col-md-6">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('appointment number') }}</x-label>
                                        <x-input :value="old('appointment_number', $teacher)" name="appointment_number" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('date') }}</x-label>
                                        <x-input :value="old('date_appointment', $teacher)" logro="datePickerBefore" name="date_appointment" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('possession certificate number') }}</x-label>
                                        <x-input :value="old('possession_certificate', $teacher)" name="possession_certificate" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('date') }}</x-label>
                                        <x-input :value="old('date_possession_certificate', $teacher)" logro="datePickerBefore"
                                            name="date_possession_certificate" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('transfer resolution number') }}</x-label>
                                        <x-input :value="old('transfer_resolution', $teacher)" name="transfer_resolution" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('date') }}</x-label>
                                        <x-input :value="old('date_transfer_resolution', $teacher)" logro="datePickerBefore"
                                            name="date_transfer_resolution" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-5">
                            <div class="card-body row g-3">
                                <div class="col-md-4">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('hierarchy grade') }}</x-label>
                                        <x-input :value="old('hierarchy_grade', $teacher)" name="hierarchy_grade" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('resolution number') }}</x-label>
                                        <x-input :value="old('resolution_hierarchy', $teacher)" name="resolution_hierarchy" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('date') }}</x-label>
                                        <x-input :value="old('date_resolution_hierarchy', $teacher)" logro="datePickerBefore"
                                            name="date_resolution_hierarchy" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-5">
                            <div class="card-body row g-3">
                                <div class="col-md-4">
                                    <div class="w-100 position-relative form-group">
                                        <x-label>{{ __('last diploma earned') }}</x-label>
                                        <x-input :value="old('last_diploma', $teacher)" name="last_diploma" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('institution') }}</x-label>
                                        <x-input :value="old('institution_last_diploma', $teacher)" name="institution_last_diploma" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('date') }}</x-label>
                                        <x-input :value="old('date_last_diploma', $teacher)" logro="datePickerBefore" name="date_last_diploma" />
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
