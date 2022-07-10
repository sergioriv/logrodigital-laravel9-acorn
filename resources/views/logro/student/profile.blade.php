@php
$title = $student->user->name;
@endphp
@extends('layout',['title'=>$title])

@section('css')
{{--
<link rel="stylesheet" href="/css/vendor/datatables.min.css" /> --}}
<link rel="stylesheet" href="/css/vendor/select2.min.css" />
<link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
<link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
<script src="/js/cs/responsivetab.js"></script>
<script src="/js/vendor/singleimageupload.js"></script>
<script src="/js/vendor/select2.full.min.js"></script>
<script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
<script>
    jQuery("[logro='select2']").select2({minimumResultsForSearch: 30, placeholder: ''});

    $('#avatar').on("change", function(){ $( "#formAvatar" ).submit(); });
</script>
@endsection

@section('content')
{{-- <input type="hidden" id="restaurant" value="{{ $student->id }}"> --}}
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Title Start -->
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ __('Student') .' | '. $student->getNames() .' '.
                        $student->getLastNames() }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <section class="scroll-section">
                <div class="row gx-4 gy-5">
                    <!-- Left Side Start -->
                    <div class="col-12 col-xl-3">
                        <!-- Biography Start -->
                        <h2 class="small-title">{{ __("Profile") }}</h2>
                        <div class="card">
                            <div class="card-body mb-n5">
                                <div class="d-flex align-items-center flex-column">
                                    <div class="mb-5 d-flex align-items-center flex-column">

                                        <!-- Avatar Form Start -->
                                        <form method="POST" id="formAvatar"
                                            action="{{ route('user.profile.avatar', $student->user) }}"
                                            enctype="multipart/form-data" class="tooltip-label-end" novalidate>
                                            @csrf
                                            @method('PUT')

                                            <x-avatar-profile-edit :avatar="$student->user->avatar" class="mb-3" />
                                        </form>
                                        <!-- Avatar Form End -->

                                        <div class="h5 mb-2">{{ $student->user->name }}</div>

                                        <div class="text-muted">
                                            <i data-acorn-icon="email" class="me-1" data-acorn-size="17"></i>
                                            <span class="align-middle">{{ $student->institutional_email }}</span>
                                        </div>
                                        @if (NULL !== $student->telephone)
                                        <div class="text-muted">
                                            <i data-acorn-icon="phone" class="me-1" data-acorn-size="17"></i>
                                            <span class="align-middle">{{ $student->telephone }}</span>
                                        </div>
                                        @endif
                                        <div class="text-muted">
                                            <i data-acorn-icon="building-large" class="me-1" data-acorn-size="17"></i>
                                            <span class="align-middle">{{ $student->headquarters->name }}</span>
                                        </div>
                                        <div class="text-muted">
                                            <i data-acorn-icon="clock" class="me-1" data-acorn-size="17"></i>
                                            <span class="align-middle">{{ $student->studyTime->name }}</span>
                                        </div>
                                        <div class="text-muted">
                                            <i data-acorn-icon="calendar" class="me-1" data-acorn-size="17"></i>
                                            <span class="align-middle">{{ $student->studyYear->name }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="nav flex-column mb-5" role="tablist">
                                    <a class="nav-link active logro-toggle px-0 border-bottom border-separator-light"
                                        data-bs-toggle="tab" href="#informationTab" role="tab">
                                        <span class="align-middle">{{ __("Information") }}</span>
                                    </a>
                                    <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                        data-bs-toggle="tab" href="#personsChargeTab" role="tab">
                                        <span class="align-middle">{{ __("Persons in Charge") }}</span>
                                    </a>
                                    <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                        data-bs-toggle="tab" href="#documentsTab" role="tab">
                                        <span class="align-middle">{{ __("Documents") }}</span>
                                    </a>
                                    <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                        data-bs-toggle="tab" href="#documentsTab" role="tab">
                                        <span class="align-middle">{{ __("Study Year") }}</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <!-- Biography End -->
                    </div>
                    <!-- Left Side End -->

                    <!-- Right Side Start -->
                    <div class="col-12 col-xl-9 mb-5 tab-content">

                        <!-- Information Tab Start -->
                        <div class="tab-pane fade active show" id="informationTab" role="tabpanel">

                            <form method="POST" action="{{ route('students.update', $student) }}"
                                class="tooltip-label-end" novalidate>
                                @csrf
                                @method('PUT')

                                <!-- Basic Information Section Start -->
                                <h2 class="small-title">{{ __("Basic information") }}</h2>
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("first name") }} <span class="text-danger">*</span>
                                                    </x-label>
                                                    <x-input :value="$student->first_name" name="firstName" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("second name") }}</x-label>
                                                    <x-input :value="$student->second_name" name="secondName" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("father's last name") }} <span
                                                            class="text-danger">*</span></x-label>
                                                    <x-input :value="$student->father_last_name" name="fatherLastName"
                                                        required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("mother's last name") }}</x-label>
                                                    <x-input :value="$student->mother_last_name"
                                                        name="motherLastName" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("institutional email") }}</x-label>
                                                    <span class="form-control text-muted">
                                                        {{ $student->institutional_email }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("telephone") }}</x-label>
                                                    <x-input :value="$student->telephone" name="telephone" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("document type") }} <span
                                                            class="text-danger">*</span></x-label>
                                                    <select name="document_type" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($documentType as $docType)
                                                        <option value="{{ $docType->code }}" @if ($student->
                                                            document_type_code !== NULL)
                                                            @selected($student->document_type_code === $docType->code)
                                                            @endif >
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
                                                    <x-input :value="$student->document" name="document" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("expedition city") }}</x-label>
                                                    <select name="expedition_city" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" @if ($student->
                                                            expedition_city_id !== NULL)
                                                            @selected($student->expedition_city_id === $city->id)
                                                            @endif >
                                                            {{ $city->department->name .' | '. $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("number siblings") }}</x-label>
                                                    <x-input type="number" :value="$student->number_siblings"
                                                        name="number_siblings" />
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
                                                        <option value="{{ $city->id }}" @if ($student->birth_city_id !==
                                                            NULL)
                                                            @selected($student->birth_city_id === $city->id)
                                                            @endif >
                                                            {{ $city->department->name .' | '. $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("birthdate") }}</x-label>
                                                    <x-input :value="$student->birthdate" logro="datePicker"
                                                        name="birthdate" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("gender") }}</x-label>
                                                    <select name="gender" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($genders as $gender)
                                                        <option value="{{ $gender->id }}" @if ($student->gender_id !==
                                                            NULL)
                                                            @selected($student->gender_id === $gender->id)
                                                            @endif >
                                                            {{ $gender->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label class="text-uppercase">RH</x-label>
                                                    <select name="rh" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($rhs as $rh)
                                                        <option value="{{ $rh->id }}" @if ($student->rh_id !== NULL)
                                                            @selected($student->rh_id === $rh->id)
                                                            @endif >
                                                            {{ $rh->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Basic Information Section End -->

                                <!-- Localization Section Start -->
                                <h2 class="small-title">{{ __("Domicile Place") }}</h2>
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("zone") }}</x-label>
                                                    <select name="zone" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        <option value="rural" @selected("rural"===$student->zone)>{{
                                                            __("Rural") }}</option>
                                                        <option value="urban" @selected("urban"===$student->zone)>{{
                                                            __("Urban") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("residence city") }}</x-label>
                                                    <select name="residence_city" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" @if ($student->residence_city_id
                                                            !== NULL)
                                                            @selected($student->residence_city_id === $city->id)
                                                            @endif >
                                                            {{ $city->department->name .' | '. $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("address") }}</x-label>
                                                    <x-input :value="$student->address" name="address" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("social stratum") }}</x-label>
                                                    <select name="social_stratum" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @for ($stratum = 1; $stratum <= 6; $stratum++) <option
                                                            value="{{ $stratum }}" @if ($student->social_stratum !==
                                                            NULL)
                                                            @selected($student->social_stratum === $stratum)
                                                            @endif >
                                                            {{ $stratum }}
                                                            </option>
                                                            @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Localization Section End -->

                                <!-- Social Safety Section Start -->
                                <h2 class="small-title">{{ __("Social Safety") }}</h2>
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("health manager") }}</x-label>
                                                    <select name="health_manager" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($healthManager as $health)
                                                        <option value="{{ $health->id }}" @if ($student->
                                                            health_manager_id !== NULL)
                                                            @selected($student->health_manager_id === $health->id)
                                                            @endif >
                                                            {{ $health->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("school insurance") }}</x-label>
                                                    <x-input :value="$student->school_insurance"
                                                        name="school_insurance" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>sisben</x-label>
                                                    <select name="sisben" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($sisbenes as $sisben)
                                                        <option value="{{ $sisben->id }}" @if ($student->sisben_id !==
                                                            NULL)
                                                            @selected($student->sisben_id === $sisben->id)
                                                            @endif >
                                                            {{ $sisben->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("disability") }}</x-label>
                                                    <x-input :value="$student->disability" name="disability" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Social Safety Section End -->

                                <!-- Additional Information Section Start -->
                                <h2 class="small-title">{{ __("Additional Information") }}</h2>
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("ethnic group") }}</x-label>
                                                    <select name="ethnic_group" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($ethnicGroups as $ethnicGroup)
                                                        <option value="{{ $ethnicGroup->id }}" @if ($student->
                                                            ethnic_group_id !== NULL)
                                                            @selected($student->ethnic_group_id === $ethnicGroup->id)
                                                            @endif >
                                                            {{ __($ethnicGroup->name) }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("conflict victim") }}</x-label>
                                                    <select name="conflict_victim" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        <option value="0" @selected(0===$student->conflict_victim)>{{
                                                            __("No") }}</option>
                                                        <option value="1" @selected(1===$student->conflict_victim)>{{
                                                            __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("lunch") }}</x-label>
                                                    <select name="lunch" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        <option value="0" @selected(0===$student->lunch)>{{ __("No") }}
                                                        </option>
                                                        <option value="1" @selected(1===$student->lunch)>{{ __("Yes") }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("refreshment") }}</x-label>
                                                    <select name="refreshment" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        <option value="0" @selected(0===$student->refreshment)>{{
                                                            __("No") }}</option>
                                                        <option value="1" @selected(1===$student->refreshment)>{{
                                                            __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("transport") }}</x-label>
                                                    <select name="transport" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        <option value="0" @selected(0===$student->transport)>{{ __("No")
                                                            }}</option>
                                                        <option value="1" @selected(1===$student->transport)>{{
                                                            __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("origin school") }}</x-label>
                                                    <select name="origin_school_id" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($originSchools as $originSchool)
                                                        <option value="{{ $originSchool->id }}" @if ($student->
                                                            origin_school_id !== NULL)
                                                            @selected($student->origin_school_id === $originSchool->id)
                                                            @endif >
                                                            {{ $originSchool->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Additional Information Section End -->

                                <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                    <x-button class="btn-primary" type="submit">{{ __("Save") }}</x-button>
                                </div>

                            </form>
                        </div>
                        <!-- Information Tab End -->

                        <!-- Persons In Charge Tab Start -->
                        <div class="tab-pane fade " id="personsChargeTab" role="tabpanel">

                            <form method="POST" action="{{ route('personsCharge', $student) }}"
                                class="tooltip-label-end" novalidate>
                                @csrf
                                @method('PUT')

                                <!-- Mother Section Start -->
                                <h2 class="small-title">{{ __("Mother Information") }}</h2>
                                <input type="hidden" name="mother" value="{{ $student->mother->id ?? null }}">
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("name") }} <span class="text-danger">*</span>
                                                    </x-label>
                                                    <x-input value="{{ $student->mother->name ?? null }}"
                                                        name="mother_name" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("email") }} <span class="text-danger">*</span>
                                                    </x-label>
                                                    @if (NULL === $student->mother)
                                                    <x-input value="{{ $student->mother->email ?? null }}"
                                                        name="mother_email" required />
                                                    @else
                                                    <span class="form-control text-muted">
                                                        {{ $student->mother->email }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("document") }}</x-label>
                                                    <x-input value="{{ $student->mother->document ?? null }}"
                                                        name="mother_document" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("expedition city") }}</x-label>
                                                    <select name="mother_expedition_city" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" @if ($student->
                                                            mother->expedition_city_id ?? null !== NULL)
                                                            @selected($student->mother->expedition_city_id ===
                                                            $city->id)
                                                            @endif >
                                                            {{ $city->department->name .' | '. $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("residence city") }}</x-label>
                                                    <select name="mother_residence_city" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" @if ($student->
                                                            mother->residence_city_id ?? null !== NULL)
                                                            @selected($student->mother->residence_city_id === $city->id)
                                                            @endif >
                                                            {{ $city->department->name .' | '. $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("address") }}</x-label>
                                                    <x-input value="{{ $student->mother->address ?? null }}"
                                                        name="mother_address" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("telephone") }}</x-label>
                                                    <x-input value="{{ $student->mother->telephone ?? null }}"
                                                        name="mother_telephone" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("cellphone") }} <span class="text-danger">*</span>
                                                    </x-label>
                                                    <x-input value="{{ $student->mother->cellphone ?? null }}"
                                                        name="mother_cellphone" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("birthdate") }}</x-label>
                                                    <x-input value="{{ $student->mother->birthdate ?? null }}"
                                                        logro="datePicker" name="mother_birthdate" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("occupation") }}</x-label>
                                                    <x-input value="{{ $student->mother->occupation ?? null }}"
                                                        name="mother_occupation" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Mother Section End -->

                                <!-- Father Section Start -->
                                <h2 class="small-title">{{ __("Father Information") }}</h2>
                                <input type="hidden" name="father" value="{{ $student->father->id ?? null }}">
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("name") }} <span class="text-danger">*</span>
                                                    </x-label>
                                                    <x-input value="{{ $student->father->name ?? null }}"
                                                        name="father_name" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("email") }} <span class="text-danger">*</span>
                                                    </x-label>
                                                    @if (NULL === $student->father)
                                                    <x-input value="{{ $student->father->email ?? null }}"
                                                        name="father_email" required />
                                                    @else
                                                    <span class="form-control text-muted">
                                                        {{ $student->father->email }}
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("document") }}</x-label>
                                                    <x-input value="{{ $student->father->document ?? null }}"
                                                        name="father_document" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("expedition city") }}</x-label>
                                                    <select name="father_expedition_city" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" @if ($student->
                                                            father->expedition_city_id ?? null !== NULL)
                                                            @selected($student->father->expedition_city_id ===
                                                            $city->id)
                                                            @endif >
                                                            {{ $city->department->name .' | '. $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("residence city") }}</x-label>
                                                    <select name="father_residence_city" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" @if ($student->
                                                            father->residence_city_id ?? null !== NULL)
                                                            @selected($student->father->residence_city_id === $city->id)
                                                            @endif >
                                                            {{ $city->department->name .' | '. $city->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("address") }}</x-label>
                                                    <x-input value="{{ $student->father->address ?? null }}"
                                                        name="father_address" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("telephone") }}</x-label>
                                                    <x-input value="{{ $student->father->telephone ?? null }}"
                                                        name="father_telephone" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("cellphone") }} <span class="text-danger">*</span>
                                                    </x-label>
                                                    <x-input value="{{ $student->father->cellphone ?? null }}"
                                                        name="father_cellphone" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("birthdate") }}</x-label>
                                                    <x-input value="{{ $student->father->birthdate ?? null }}"
                                                        logro="datePicker" name="father_birthdate" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("occupation") }}</x-label>
                                                    <x-input value="{{ $student->father->occupation ?? null }}"
                                                        name="father_occupation" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Father Section End -->

                                <!-- Tutor Section Start -->
                                <h2 class="small-title">{{ __("Tutor") }} <span class="text-danger">*</span></h2>
                                <section class="card mb-5">
                                    <div class="card-body w-100">
                                        <div class="w-100 position-relative form-group">
                                            <select name="person_charge" logro="select2" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($kinships as $kinship)
                                                <option value="{{ $kinship->id }}" @if ($student->person_charge ?? null
                                                    !== NULL)
                                                    @selected($student->person_charge === $kinship->id)
                                                    @endif >
                                                    {{ __($kinship->name) }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </section>
                                <!-- Tutor Section End -->

                                <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                    <x-button class="btn-primary" type="submit">{{ __("Save") }}</x-button>
                                </div>

                            </form>

                        </div>
                        <!-- Persons In Charge Tab End -->

                        <!-- Documents Tab Start -->
                        <div class="tab-pane fade " id="documentsTab" role="tabpanel">

                            <!-- Mother Section Start -->
                            <h2 class="small-title">{{ __("Documents") }}</h2>
                            <section class="card mb-5">
                                <div class="card-header">
                                    <form method="POST" action="{{ route('studentFile', $student) }}"
                                        enctype="multipart/form-data" class="tooltip-label-end" novalidate>
                                        @csrf
                                        @method('PUT')

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="w-100 position-relative form-group">
                                                    <select data-placeholder="Seleccione documento" name="file_type"
                                                        logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($studentFileTypes as $fileType)
                                                        <option value="{{ $fileType->id }}"
                                                            @selected(old("file_type")==$fileType->id)>
                                                            {{ $fileType->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group">
                                                    <x-input type="file" name="file_upload"
                                                        accept="image/jpg, image/jpeg, image/png, image/webp"
                                                        class="d-block" />
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-2 col-md-2 border-0 pt-0 d-flex justify-content-end align-items-start">
                                                <x-button class="btn-primary" type="submit">{{ __("Upload") }}
                                                </x-button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="card-body">

                                    <form method="POST" action="{{ route('studentFile.checked', $student) }}"
                                        class="tooltip-label-end" novalidate>
                                        @csrf
                                        @method('PUT')

                                    <div class="row g-2 row-cols-4 row-cols-md-5">
                                        @foreach ($studentFileTypes as $studentFile)
                                        <div class="col small-gutter-col">
                                            <div class="h-100">
                                                <div class="text-center d-flex flex-column">
                                                    <span>

                                                        @if ($studentFile->studentFile ?? null !== NULL)

                                                        @if ($studentFile->studentFile->checked === 1)
                                                        <i
                                                            class="icon bi-file-earmark-check-fill icon-70 text-success"></i>
                                                        @elseif ($studentFile->studentFile->checked === 0)
                                                        <i class="icon bi-file-earmark-x-fill icon-70 text-danger"></i>
                                                        @else
                                                        <i class="icon bi-file-earmark-fill icon-70 text-info"></i>
                                                        @endif

                                                        @else
                                                        <i class="icon bi-file-earmark icon-70 text-muted"></i>
                                                        @endif

                                                    </span>
                                                    <span>{{ $studentFile->name }}</span>

                                                    @can('support.users')
                                                    @if ($studentFile->studentFile ?? null !== NULL)
                                                    <div class="form-switch">
                                                        @if ($studentFile->studentFile->checked === 1)
                                                        <input class="form-check-input" name="student_files[]"
                                                            value="{{ $studentFile->studentFile->id }}" type="checkbox"
                                                            checked />
                                                        @else
                                                        <input class="form-check-input" name="student_files[]"
                                                            value="{{ $studentFile->studentFile->id }}"
                                                            type="checkbox" />
                                                        @endif
                                                    </div>
                                                    @endif
                                                    @endcan

                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    @can('support.users')
                                    <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                        <x-button class="btn-primary" type="submit">{{ __("Save") }}</x-button>
                                    </div>
                                    @endcan
                                </div>
                            </section>
                        </div>
                        <!-- Documents Tab End -->

                    </div>
                    <!-- Right Side End -->
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
