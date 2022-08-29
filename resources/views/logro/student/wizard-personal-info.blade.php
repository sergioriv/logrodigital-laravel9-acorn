@php
$title = __('Personal Information');
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
    <script src="/js/forms/student-profile.js"></script>
    <script src="/js/forms/signature.js"></script>
    <script>
        jQuery("#confirm_save").click(function () {
            if ( $(this).is(':checked') ) {
                $("#save_personal_info").prop('disabled', false);
            } else {
                $("#save_personal_info").prop('disabled', true);
            }
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

                {{-- @error('custom')
                    <x-validation-errors class="mb-4" :errors="$errors" />
                @else
                    @error('disability_certificate')
                        <x-validation-errors class="mb-4" :message="$message" />
                    @else
                        <x-validation-errors-empty class="mb-4" />
                    @enderror
                @enderror --}}

                <section class="scroll-section">
                    <div class="card mb-5 wizard">
                        <div class="card-header border-0 pb-0">
                            <ul class="nav nav-tabs justify-content-center disabled" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Documents') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Persons in Charge') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center active" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Personal Information') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item d-none" role="presentation">
                                    <a class="nav-link text-center" role="tab"></a>
                                </li>
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('student.wizard.personal-info') }}"
                            id="studentProfileInfoForm" class="tooltip-label-end" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="tab-content">

                                    <!-- Basic Information Section Start -->
                                    <h2 class="small-title">{{ __('Basic information') }}</h2>
                                    <section class="mb-5 border-bottom">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('first name') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-input-error :value="$student->first_name" name="firstName"
                                                            :hasError="'firstName'" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('second name') }}</x-label>
                                                        <x-input-error :value="$student->second_name" name="secondName"
                                                            :hasError="'secondName'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __("father's last name") }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-input-error :value="$student->father_last_name" name="fatherLastName"
                                                            :hasError="'fatherLastName'" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __("mother's last name") }}</x-label>
                                                        <x-input-error :value="$student->mother_last_name" name="motherLastName"
                                                            :hasError="'motherLastName'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('institutional email') }}</x-label>
                                                        <span class="form-control text-muted">
                                                            {{ $student->institutional_email }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('telephone') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-input-error :value="$student->telephone" name="telephone"
                                                            :hasError="'telephone'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('document type') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="document_type" id="document_type" logro="select2"
                                                            :hasError="'document_type'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($documentType as $docType)
                                                                <option value="{{ $docType->code }}"
                                                                    foreigner="{{ $docType->foreigner }}"
                                                                    @if ($student->document_type_code !== null) @selected($student->document_type_code === $docType->code) @endif>
                                                                    {{ $docType->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('document') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-input-error :value="$student->document" name="document"
                                                            :hasError="'document'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('expedition city') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="expedition_city" id="expedition_city"
                                                            logro="select2" :hasError="'expedition_city'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}"
                                                                    @if ($student->expedition_city_id !== null) @selected($student->expedition_city_id === $city->id) @endif>
                                                                    {{ $city->department->name . ' | ' . $city->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('number siblings') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-input-error type="number" :value="$student->number_siblings"
                                                            name="number_siblings" max="200" min="0"
                                                            :hasError="'number_siblings'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6 @if ($student->country_id !== null) d-none @endif"
                                                    id="birth_city">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('birth city') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-select name="birth_city" logro="select2" :hasError="'birth_city'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}"
                                                                    @if ($student->birth_city_id !== null) @selected($student->birth_city_id === $city->id) @endif>
                                                                    {{ $city->department->name . ' | ' . $city->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 @if ($student->country_id === null) d-none @endif"
                                                    id="country">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('home country') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="country" logro="select2" :hasError="'country'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->id }}"
                                                                    @if ($student->country_id !== null) @selected($student->country_id === $country->id) @endif>
                                                                    {{ __($country->name) }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('birthdate') }}</x-label>
                                                        @if (null !== $student->birthdate)
                                                            <span
                                                                class="form-control text-muted">{{ $student->birthdate }}</span>
                                                            <x-input-error type="hidden" :value="$student->birthdate"
                                                                name="birthdate" :hasError="'birthdate'" />
                                                        @else
                                                            <x-input-error :value="$student->birthdate" logro="datePicker"
                                                                name="birthdate" :hasError="'birthdate'" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <div class="w-100 position-relative form-group">
                                                        <x-label>{{ __('gender') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-select name="gender" logro="select2" :hasError="'gender'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($genders as $gender)
                                                                <option value="{{ $gender->id }}"
                                                                    @if ($student->gender_id !== null) @selected($student->gender_id === $gender->id) @endif>
                                                                    {{ $gender->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="w-100 position-relative form-group">
                                                        <x-label class="text-uppercase">RH <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="rh" logro="select2" :hasError="'rh'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($rhs as $rh)
                                                                <option value="{{ $rh->id }}"
                                                                    @if ($student->rh_id !== null) @selected($student->rh_id === $rh->id) @endif>
                                                                    {{ $rh->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Basic Information Section End -->

                                    <!-- Localization Section Start -->
                                    <h2 class="small-title">{{ __('Domicile Place') }}</h2>
                                    <section class="mb-5 border-bottom">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('zone') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-select name="zone" logro="select2" :hasError="'zone'">
                                                            <option label="&nbsp;"></option>
                                                            <option value="rural" @selected('rural' === $student->zone)>
                                                                {{ __('Rural') }}
                                                            </option>
                                                            <option value="urban" @selected('urban' === $student->zone)>
                                                                {{ __('Urban') }}
                                                            </option>
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('residence city') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="residence_city" logro="select2"
                                                            :hasError="'residence_city'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($cities as $city)
                                                                <option value="{{ $city->id }}"
                                                                    @if ($student->residence_city_id !== null) @selected($student->residence_city_id === $city->id) @endif>
                                                                    {{ $city->department->name . ' | ' . $city->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('address') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-input-error :value="$student->address" name="address"
                                                            :hasError="'address'" />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('social stratum') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="social_stratum" logro="select2"
                                                            :hasError="'social_stratum'">
                                                            <option label="&nbsp;"></option>
                                                            @for ($stratum = 1; $stratum <= 6; $stratum++)
                                                                <option value="{{ $stratum }}"
                                                                    @if ($student->social_stratum !== null) @selected($student->social_stratum === $stratum) @endif>
                                                                    {{ $stratum }}
                                                                </option>
                                                            @endfor
                                                        </x-select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('dwelling type') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="dwelling_type" logro="select2" :hasError="'dwelling_type'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($dwellingTypes as $dwellingType)
                                                                <option value="{{ $dwellingType->id }}"
                                                                    @if ($student->dwelling_type_id !== null) @selected($student->dwelling_type_id === $dwellingType->id) @endif>
                                                                    {{ __($dwellingType->name) }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('neighborhood') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-input-error :value="$student->neighborhood" name="neighborhood"
                                                            :hasError="'neighborhood'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label class="d-block">{{ __('housing services') }}</x-label>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="electrical_energy" value="1"
                                                                    @checked($student->electrical_energy)>
                                                                {{ __('electrical energy') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="natural_gas" value="1"
                                                                    @checked($student->natural_gas)>
                                                                {{ __('natural gas') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="sewage_system" value="1"
                                                                    @checked($student->sewage_system)>
                                                                {{ __('sewage system') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="aqueduct" value="1"
                                                                    @checked($student->aqueduct)>
                                                                {{ __('aqueduct') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="internet" value="1"
                                                                    @checked($student->internet)>
                                                                internet
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <x-label class="d-block">{{ __('who lives with you at home') }}
                                                        </x-label>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="lives_with_father" value="1"
                                                                    @checked($student->lives_with_father)>
                                                                {{ __('lives with father') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="lives_with_mother" value="1"
                                                                    @checked($student->lives_with_mother)>
                                                                {{ __('lives with mother') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="lives_with_siblings" value="1"
                                                                    @checked($student->lives_with_siblings)>
                                                                {{ __('lives with siblings') }}
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <label class="form-check-label logro-label">
                                                                <input class="form-check-input" type="checkbox"
                                                                    name="lives_with_other_relatives" value="1"
                                                                    @checked($student->lives_with_other_relatives)>
                                                                {{ __('lives with other relatives') }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Localization Section End -->

                                    <!-- Social Safety Section Start -->
                                    <h2 class="small-title">{{ __('Social Safety') }}</h2>
                                    <section class="border-bottom">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('health manager') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-select name="health_manager" logro="select2"
                                                            :hasError="'health_manager'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($healthManager as $health)
                                                                <option value="{{ $health->id }}"
                                                                    @if ($student->health_manager_id !== null) @selected($student->health_manager_id === $health->id) @endif>
                                                                    {{ $health->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('school insurance') }} <span
                                                                class="text-danger">*</span></x-label>
                                                        <x-input-error :value="$student->school_insurance" name="school_insurance"
                                                            :hasError="'school_insurance'" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>sisben <span class="text-danger">*</span></x-label>
                                                        <x-select name="sisben" logro="select2" :hasError="'sisben'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($sisbenes as $sisben)
                                                                <option value="{{ $sisben->id }}"
                                                                    @if ($student->sisben_id !== null) @selected($student->sisben_id === $sisben->id) @endif>
                                                                    {{ $sisben->name }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3 w-100 position-relative form-group">
                                                        <x-label>{{ __('disability') }} <span class="text-danger">*</span>
                                                        </x-label>
                                                        <x-select name="disability" id="disability" logro="select2"
                                                            :hasError="'disability'">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($disabilities as $disability)
                                                                <option value="{{ $disability->id }}"
                                                                    @if ($student->disability_id !== null) @selected($student->disability_id === $disability->id) @endif>
                                                                    {{ __($disability->name) }}
                                                                </option>
                                                            @endforeach
                                                        </x-select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row g-3 d-none" id="content-disability">
                                                <div class="col-md-12">
                                                    <div class="mb-3 position-relative form-group">
                                                        <x-label>{{ __('Disability certificate') }}</x-label>
                                                        <x-input type="file" class="d-block"
                                                            name="disability_certificate"
                                                            accept="image/jpg, image/jpeg, image/png, image/webp" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Social Safety Section End -->

                                    <!-- Data Treatment Policy Section Start -->
                                    <section class="mb-5 border-bottom">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-12 mb-3">
                                                    {{ __("By continuing, you accept") }}
                                                    <span class="text-primary cursor-pointer" data-bs-toggle="modal"
                                                        data-bs-target="#modalDataTreatmentPolicy">
                                                        {{ __('data treatment policy') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div class="form-check d-inline-block w-100">
                                                        <input class="form-check-input" type="checkbox" name="data_treatment"
                                                            value="1" @checked($student->data_treatment)>
                                                        <label class="form-check-label logro-label">
                                                            {{ __('I authorize the institution the permissions of') }}
                                                            <span class="text-primary cursor-pointer" data-bs-toggle="modal"
                                                                data-bs-target="#modalDataTreatmentImage">
                                                                {{ __('image use') }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($handbook !== NULL)
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-12">
                                                    <div>
                                                        <a class="btn btn-link p-0 mt-3" target="_blank" href="{{ $handbook }}">
                                                            <i data-acorn-icon="book" data-acorn-size="16"></i>
                                                            {{ __("Handbook of coexistence") }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Modal Data Treatment Policy Start -->
                                            <div class="modal fade scroll-out" id="modalDataTreatmentPolicy"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="modalCloseDataTreatmentPolicy" aria-hidden="true">
                                                <div
                                                    class="modal-dialog modal-xl modal-dialog-scrollable short modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title logro-label"
                                                                id="modalCloseDataTreatmentPolicy">
                                                                {{ __('data treatment policy') }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="scroll-track-visible">
                                                                <x-data-treatment-policy />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal Data Treatment Policy End -->

                                            <!-- Modal Data Treatment Imagen Rights Start -->
                                            <div class="modal fade scroll-out" id="modalDataTreatmentImage"
                                                tabindex="-1" role="dialog"
                                                aria-labelledby="modalCloseDataTreatmentImage" aria-hidden="true">
                                                <div
                                                    class="modal-dialog modal-xl modal-dialog-scrollable short modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title logro-label"
                                                                id="modalCloseDataTreatmentImage">
                                                                {{ __('image use') }}
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="scroll-track-visible">
                                                                <x-data-treatment-image-rights />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal Data Treatment Imagen Rights End -->

                                        </div>
                                    </section>
                                    <!-- Data Treatment Policy Section End -->

                                    <!-- Signatures Start -->
                                    <h2 class="small-title">{{ __('Signatures') }}</h2>
                                    <section class="">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <x-label>{{ __('signature tutor') }}</x-label>
                                                    @if (null === $student->signature_tutor)
                                                        <div class="mb-1">
                                                            <button type="button" id="openSigTutor"
                                                                class="btn btn-outline-secondary mb-1"
                                                                data-bs-toggle="modal" data-bs-target="#modalSigTutor">
                                                                {{ __('Make signature') }}
                                                            </button>
                                                        </div>
                                                        <input type="hidden" id="sig-dataUrl-tutor"
                                                            name="signature_tutor" class="form-control">
                                                        <div class="d-none mb-3 mb-md-0">
                                                            <img id="sig-image-tutor" src=""
                                                                class="w-100 border rounded-md" alt="signature">
                                                        </div>
                                                    @else
                                                        <img src="{{ env('APP_URL') . '/' . $student->signature_tutor }}"
                                                            class="w-100 mb-3 mb-md-0 border rounded-md" alt="signature">
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <x-label>{{ __('signature student') }}</x-label>
                                                    @if (null === $student->signature_student)
                                                        <div>
                                                            <button type="button" id="openSigStudent"
                                                                class="btn btn-outline-secondary mb-1"
                                                                data-bs-toggle="modal" data-bs-target="#modalSigStudent">
                                                                {{ __('Make signature') }}
                                                            </button>
                                                        </div>
                                                        <input type="hidden" id="sig-dataUrl-student"
                                                            name="signature_student" class="form-control">
                                                        <div class="d-none">
                                                            <img id="sig-image-student" src=""
                                                                class="w-100 border rounded-md" alt="signature">
                                                        </div>
                                                    @else
                                                        <img src="{{ env('APP_URL') . '/' . $student->signature_student }}"
                                                            class="w-100 border rounded-md" alt="signature">
                                                    @endif
                                                </div>
                                            </div>

                                            @if (null === $student->signature_tutor)
                                                <!-- Signature Tutor modal-->
                                                <div class="modal fade" id="modalSigTutor" tabindex="-1" role="dialog"
                                                    aria-labelledby="SigTutorLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title logro-label" id="SigTutorLabel">
                                                                    {{ __('signature tutor') }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <canvas id="sig-canvas-tutor"
                                                                        class="sig-canvas form-signature">
                                                                    </canvas>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" id="sig-clearBtn-tutor"
                                                                    class="btn btn-outline-danger">{{ __('Clear signature') }}</button>
                                                                <button type="button" id="sig-submitBtn-tutor"
                                                                    data-bs-dismiss="modal"
                                                                    class="btn btn-primary">{{ __('Confirm signature') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if (null === $student->signature_student)
                                                <!-- Signature Student modal-->
                                                <div class="modal fade" id="modalSigStudent" tabindex="-1"
                                                    role="dialog" aria-labelledby="SigStudentLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title logro-label" id="SigStudentLabel">
                                                                    {{ __('signature student') }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center">
                                                                    <canvas id="sig-canvas-student"
                                                                        class="sig-canvas form-signature">
                                                                    </canvas>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" id="sig-clearBtn-student"
                                                                    class="btn btn-outline-danger">{{ __('Clear signature') }}</button>
                                                                <button type="button" id="sig-submitBtn-student"
                                                                    data-bs-dismiss="modal"
                                                                    class="btn btn-primary">{{ __('Confirm signature') }}</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                    </section>
                                    <!-- Signatures End -->

                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <div class="form-check mb-3">
                                    <input class="form-check-input float-none me-1" type="checkbox" id="confirm_save" />
                                    <label class="form-check-label" for="confirm_save">{{ __("I confirm that the information that I am registering on this form is reliable.") }}</label>
                                </div>
                                <button disabled id="save_personal_info" class="btn btn-icon btn-icon-end btn-outline-primary btn-next" type="submit">
                                    <span>{{ __('Continue') }}</span>
                                    <i data-acorn-icon="chevron-right" class="icon" data-acorn-size="18"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection
