@php
$title = __('Create Orientation User');
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
    <script src="/js/forms/teacher-create.js?d=1673974275586"></script>
    <script src="/js/forms/select2.js"></script>
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
                    <form method="post" action="{{ route('orientation.store') }}" class="tooltip-label-end"
                        id="teacherCreateForm" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('names') }}</x-label>
                                            <x-input :value="old('names')" name="names" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('last names') }}</x-label>
                                            <x-input :value="old('lastNames')" name="lastNames" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('institutional email') }}</x-label>
                                            <x-input :value="old('institutional_email')" name="institutional_email" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('date of entry into the institution') }}</x-label>
                                            <x-input :value="old('date_entry')" name="date_entry" logro="datePickerAll" required />
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
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
                                        <div class="w-100 position-relative form-group">
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
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <x-input :value="old('appointment_number')" name="appointment_number" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_appointment')" logro="datePickerBefore" name="date_appointment"
                                            data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('upload file') }}</x-label>
                                            <x-input type="file" accept=".pdf" name="file_appointment" class="d-block" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('possession certificate number') }}</x-label>
                                            <x-input :value="old('possession_certificate')" name="possession_certificate" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_possession_certificate')" logro="datePickerBefore" name="date_possession_certificate"
                                            data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('upload file') }}</x-label>
                                            <x-input type="file" accept=".pdf" name="file_possession_certificate" class="d-block" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('transfer resolution number') }}</x-label>
                                            <x-input :value="old('transfer_resolution')" name="transfer_resolution" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_transfer_resolution')" logro="datePickerBefore" name="date_transfer_resolution"
                                            data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('upload file') }}</x-label>
                                            <x-input type="file" accept=".pdf" name="file_transfer_resolution" class="d-block" />
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
