@php
$title = $student->user->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    {{-- <link rel="stylesheet" href="/css/vendor/datatables.min.css" /> --}}
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    @if (null === $student->birthdate)
        <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
    @endif
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/singleimageupload.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    @if (null === $student->birthdate)
        <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
        <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
    @endif
@endsection

@section('js_page')
    <script src="/js/forms/genericforms.js"></script>
    <script src="/js/pages/student-profile.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                    <h1 class="mb-1 pb-0 display-4" id="title">
                        {{ __('Student') . ' | ' . $student->getNames() . ' ' . $student->getLastNames() }}</h1>
                </div>
                <!-- Title End -->

                @can('support.users')
                @if (NULL !== $Y->available)
                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Matriculate Button Start -->
                    <a class="btn btn-outline-info"
                        href="{{ route('students.matriculate', $student) }}">
                        @if (1 !== $student->enrolled)
                            {{ __('Matriculate') }}
                        @else
                            {{ __('Change group') }}
                        @endif
                    </a>
                    <!-- Matriculate Button End -->

                    <!-- Dropdown Button Start -->
                    {{-- <div class="ms-1">
                        <button
                                type="button"
                                class="btn btn-outline-info btn-icon btn-icon-only"
                                data-bs-offset="0,3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                                data-submenu
                        >
                            <i data-acorn-icon="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <button class="dropdown-item" type="button">Follow</button>
                            <button class="dropdown-item" type="button">Hire</button>
                            <button class="dropdown-item" type="button">Report</button>
                        </div>
                    </div> --}}
                    <!-- Dropdown Button End -->
                </div>
                <!-- Top Buttons End -->
                @endif
                @endcan

            </div>
        </section>
        <!-- Title and Top Buttons End -->

        <!-- Validation Errors -->
        <x-validation-errors class="mb-4" :errors="$errors" />

        <section class="row">
            <!-- Left Side Start -->
            <div class="col-12 col-xl-3">
                <!-- Biography Start -->
                <h2 class="small-title">{{ __('Profile') }}</h2>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-column">
                            <div class="mb-5 d-flex align-items-center flex-column">

                                <!-- Avatar Form Start -->
                                <form method="POST" id="formAvatar"
                                    action="{{ route('user.profile.avatar', $student->user) }}"
                                    enctype="multipart/form-data" class="tooltip-label-end" novalidate>
                                    @csrf
                                    @method('PUT')

                                    <x-avatar-profile-edit :avatar="$student->user->avatar" :inclusive="$student->inclusive" class="mb-3" />
                                </form>
                                <!-- Avatar Form End -->

                                <div class="h5">{{ $student->getFullName() }}</div>
                                @if (null !== $student->birthdate)
                                    <span class="mb-2 text-muted">{{ $student->age() . ' ' . __('years') }}</span>
                                @endif

                                <div class="text-muted">
                                    <i data-acorn-icon="email" class="me-1" data-acorn-size="17"></i>
                                    <span class="align-middle">{{ $student->institutional_email }}</span>
                                </div>
                                @if (null !== $student->telephone)
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

                                @if (NULL !== $student->group_id)
                                <div class="mt-2">
                                    <h5 class="text-primary font-weight-bold">{{ $student->group->name }}</h5>
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="nav flex-column" role="tablist">
                            <a class="nav-link active logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#informationTab" role="tab">
                                <span class="align-middle">{{ __('Information') }}</span>
                            </a>
                            <a class="nav-link logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                href="#personsChargeTab" role="tab">
                                <span class="align-middle">{{ __('Persons in Charge') }}</span>
                            </a>
                            <a class="nav-link logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                href="#documentsTab" role="tab">
                                <span class="align-middle">{{ __('Documents') }}</span>
                            </a>
                            @if (1 === $student->inclusive)
                                <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#piarTab" role="tab">
                                    <span class="align-middle">PIAR</span>
                                </a>
                            @endif
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

                    <form method="POST" action="{{ route('students.update', $student) }}" class="tooltip-label-end"
                        id="studentInfoForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section Start -->
                        <h2 class="small-title">{{ __('Basic information') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('first name') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input :value="$student->first_name" name="firstName" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('second name') }}</x-label>
                                            <x-input :value="$student->second_name" name="secondName" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __("father's last name") }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input :value="$student->father_last_name" name="fatherLastName" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __("mother's last name") }}</x-label>
                                            <x-input :value="$student->mother_last_name" name="motherLastName" />
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
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <x-input :value="$student->telephone" name="telephone" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('document type') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <select name="document_type" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($documentType as $docType)
                                                    <option value="{{ $docType->code }}"
                                                        @if ($student->document_type_code !== null) @selected($student->document_type_code === $docType->code) @endif>
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
                                            <x-input :value="$student->document" name="document" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <select name="expedition_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        @if ($student->expedition_city_id !== null) @selected($student->expedition_city_id === $city->id) @endif>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('number siblings') }}</x-label>
                                            <x-input type="number" :value="$student->number_siblings" name="number_siblings" />
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
                                                    <option value="{{ $city->id }}"
                                                        @if ($student->birth_city_id !== null) @selected($student->birth_city_id === $city->id) @endif>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            @if (null !== $student->birthdate)
                                                <span class="form-control text-muted">{{ $student->birthdate }}</span>
                                                <x-input type="hidden" :value="$student->birthdate" name="birthdate" />
                                            @else
                                                <x-input :value="$student->birthdate" logro="datePicker" name="birthdate" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('gender') }}</x-label>
                                            <select name="gender" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender->id }}"
                                                        @if ($student->gender_id !== null) @selected($student->gender_id === $gender->id) @endif>
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
                                                    <option value="{{ $rh->id }}"
                                                        @if ($student->rh_id !== null) @selected($student->rh_id === $rh->id) @endif>
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
                        <h2 class="small-title">{{ __('Domicile Place') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('zone') }}</x-label>
                                            <select name="zone" logro="select2">
                                                <option label="&nbsp;"></option>
                                                <option value="rural" @selected('rural' === $student->zone)>
                                                    {{ __('Rural') }}
                                                </option>
                                                <option value="urban" @selected('urban' === $student->zone)>
                                                    {{ __('Urban') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('residence city') }}</x-label>
                                            <select name="residence_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        @if ($student->residence_city_id !== null) @selected($student->residence_city_id === $city->id) @endif>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('address') }}</x-label>
                                            <x-input :value="$student->address" name="address" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('social stratum') }}</x-label>
                                            <select name="social_stratum" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @for ($stratum = 1; $stratum <= 6; $stratum++)
                                                    <option value="{{ $stratum }}"
                                                        @if ($student->social_stratum !== null) @selected($student->social_stratum === $stratum) @endif>
                                                        {{ $stratum }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('dwelling type') }}</x-label>
                                            <select name="dwelling_type" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($dwellingTypes as $dwellingType)
                                                    <option value="{{ $dwellingType->id }}"
                                                        @if ($student->dwelling_type_id !== null) @selected($student->dwelling_type_id === $dwellingType->id) @endif>
                                                        {{ __($dwellingType->name) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('neighborhood') }}</x-label>
                                            <x-input :value="$student->neighborhood" name="neighborhood" />
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
                                                    <input class="form-check-input" type="checkbox" name="natural_gas"
                                                        value="1" @checked($student->natural_gas)>
                                                    {{ __('natural gas') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="sewage_system"
                                                        value="1" @checked($student->sewage_system)>
                                                    {{ __('sewage system') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="aqueduct"
                                                        value="1" @checked($student->aqueduct)>
                                                    {{ __('aqueduct') }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="internet"
                                                        value="1" @checked($student->internet)>
                                                    internet
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="mb-3 position-relative form-group">
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
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('health manager') }}</x-label>
                                            <select name="health_manager" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($healthManager as $health)
                                                    <option value="{{ $health->id }}"
                                                        @if ($student->health_manager_id !== null) @selected($student->health_manager_id === $health->id) @endif>
                                                        {{ $health->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('school insurance') }}</x-label>
                                            <x-input :value="$student->school_insurance" name="school_insurance" />
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
                                                    <option value="{{ $sisben->id }}"
                                                        @if ($student->sisben_id !== null) @selected($student->sisben_id === $sisben->id) @endif>
                                                        {{ $sisben->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('disability') }}</x-label>
                                            <select name="disability" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($disabilities as $disability)
                                                    <option value="{{ $disability->id }}"
                                                        @if ($student->disability_id !== null) @selected($student->disability_id === $disability->id) @endif>
                                                        {{ $disability->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Social Safety Section End -->

                        @can('support.users')
                            <!-- Additional Information Section Start -->
                            <h2 class="small-title">{{ __('Additional Information') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('ethnic group') }}</x-label>
                                                <select name="ethnic_group" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($ethnicGroups as $ethnicGroup)
                                                        <option value="{{ $ethnicGroup->id }}"
                                                            @if ($student->ethnic_group_id !== null) @selected($student->ethnic_group_id === $ethnicGroup->id) @endif>
                                                            {{ __($ethnicGroup->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('conflict victim') }}</x-label>
                                                <select name="conflict_victim" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(0 === $student->conflict_victim)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(1 === $student->conflict_victim)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('origin school') }}</x-label>
                                                <select name="origin_school_id" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($originSchools as $originSchool)
                                                        <option value="{{ $originSchool->id }}"
                                                            @if ($student->origin_school_id !== null) @selected($student->origin_school_id === $originSchool->id) @endif>
                                                            {{ $originSchool->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('ICBF protection measure') }}</x-label>
                                                <select name="icbf_protection" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($icbfProtections as $icbfProtection)
                                                        <option value="{{ $icbfProtection->id }}"
                                                            @if ($student->ICBF_protection_measure_id !== null) @selected($student->ICBF_protection_measure_id === $icbfProtection->id) @endif>
                                                            {{ __($icbfProtection->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('foundation beneficiary') }}</x-label>
                                                <select name="foundation_beneficiary" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(0 === $student->foundation_beneficiary)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(1 === $student->foundation_beneficiary)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('linked to a process') }}</x-label>
                                                <select name="linked_process" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($linkageProcesses as $linkageProcess)
                                                        <option value="{{ $linkageProcess->id }}"
                                                            @if ($student->linked_to_process_id !== null) @selected($student->linked_to_process_id === $linkageProcess->id) @endif>
                                                            {{ __($linkageProcess->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('religion') }}</x-label>
                                                <select name="religion" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($religions as $religion)
                                                        <option value="{{ $religion->id }}"
                                                            @if ($student->religion_id !== null) @selected($student->religion_id === $religion->id) @endif>
                                                            {{ __($religion->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('economic dependence') }}</x-label>
                                                <select name="economic_dependence" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($economicDependences as $economicDependence)
                                                        <option value="{{ $economicDependence->id }}"
                                                            @if ($student->economic_dependence_id !== null) @selected($student->economic_dependence_id === $economicDependence->id) @endif>
                                                            {{ __($economicDependence->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Additional Information Section End -->

                            <!-- Psychosocial Information Section Start -->
                            <h2 class="small-title">{{ __('Psychosocial Information') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('plays sports') }}</x-label>
                                                <select name="plays_sports" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(0 === $student->plays_sports)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(1 === $student->plays_sports)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('freetime activity') }}</x-label>
                                                <x-input :value="$student->freetime_activity" name="freetime_activity" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('allergies that you suffer from') }}</x-label>
                                                <x-input :value="$student->allergies" name="allergies" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('medications you take') }}</x-label>
                                                <x-input :value="$student->medicines" name="medicines" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('favourite subjects?') }}</x-label>
                                                <x-input :value="$student->favorite_subjects" name="favorite_subjects" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('which subjects do you find most difficult?') }}
                                                </x-label>
                                                <x-input :value="$student->most_difficult_subjects" name="most_difficult_subjects" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="mb-3 position-relative form-group">
                                            {{-- <x-label class="d-block">{{ __('insomnia') }}</x-label> --}}
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="insomnia"
                                                        value="1" @checked($student->insomnia)>
                                                    {{ __('insomnia') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="colic"
                                                        value="1" @checked($student->colic)>
                                                    {{ __('colic') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="biting_nails"
                                                        value="1" @checked($student->biting_nails)>
                                                    {{ __('biting nails') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="sleep_talk"
                                                        value="1" @checked($student->sleep_talk)>
                                                    {{ __('sleep talk') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="nightmares"
                                                        value="1" @checked($student->nightmares)>
                                                    {{ __('nightmares') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="seizures"
                                                        value="1" @checked($student->seizures)>
                                                    {{ __('seizures') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="physical_abuse"
                                                        value="1" @checked($student->physical_abuse)>
                                                    {{ __('physical abuse') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="pee_at_night"
                                                        value="1" @checked($student->pee_at_night)>
                                                    {{ __('pee at night') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="hear_voices"
                                                        value="1" @checked($student->hear_voices)>
                                                    {{ __('hear voices') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="fever"
                                                        value="1" @checked($student->fever)>
                                                    {{ __('fever') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="fears_phobias"
                                                        value="1" @checked($student->fears_phobias)>
                                                    {{ __('fears or phobias') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="drug_consumption"
                                                        value="1" @checked($student->drug_consumption)>
                                                    {{ __('drug consumption') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="head_blows"
                                                        value="1" @checked($student->head_blows)>
                                                    {{ __('head blows') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="desire_to_die"
                                                        value="1" @checked($student->desire_to_die)>
                                                    {{ __('desire to die') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="see_strange_things"
                                                        value="1" @checked($student->see_strange_things)>
                                                    {{ __('see strange things') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="learning_problems"
                                                        value="1" @checked($student->learning_problems)>
                                                    {{ __('learning problems') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="dizziness_fainting"
                                                        value="1" @checked($student->dizziness_fainting)>
                                                    {{ __('dizziness or fainting') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="school_repetition"
                                                        value="1" @checked($student->school_repetition)>
                                                    {{ __('school repetition') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="accidents"
                                                        value="1" @checked($student->accidents)>
                                                    {{ __('accidents') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="asthma"
                                                        value="1" @checked($student->asthma)>
                                                    {{ __('asthma') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="suicide_attempts"
                                                        value="1" @checked($student->suicide_attempts)>
                                                    {{ __('suicide attempts') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="constipation"
                                                        value="1" @checked($student->constipation)>
                                                    {{ __('constipation') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="stammering"
                                                        value="1" @checked($student->stammering)>
                                                    {{ __('stammering') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="hands_sweating"
                                                        value="1" @checked($student->hands_sweating)>
                                                    {{ __('hands sweating') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="sleepwalking"
                                                        value="1" @checked($student->sleepwalking)>
                                                    {{ __('sleepwalking') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="nervous_tics"
                                                        value="1" @checked($student->nervous_tics)>
                                                    {{ __('nervous tics') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Psychosocial Information Section End -->

                            <!--  Psychosocial Assessment Section Start -->
                            <h2 class="small-title">{{ __('Psychosocial Evaluation') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label class="text-uppercase">SIMAT</x-label>
                                                <select name="simat" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(0 === $student->simat)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(1 === $student->simat)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('student inclusive') }}</x-label>
                                                <select name="inclusive" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(0 === $student->inclusive)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(1 === $student->inclusive)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('Psychosocial Evaluation') }}</x-label>
                                                <textarea name="psyc_evaluation" rows="10" class="form-control">{{ $student->psyc_evaluation }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('recommendations') }}</x-label>
                                                <textarea name="psyc_recommendations" rows="3" class="form-control">{{ $student->psyc_recommendations }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('student and family action plan') }}</x-label>
                                                <textarea name="psyc_student_family" rows="3" class="form-control">{{ $student->psyc_student_family }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!--  Psychosocial Assessment Section End -->
                        @endcan


                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                            <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>
                        </div>

                    </form>
                </div>
                <!-- Information Tab End -->

                <!-- Persons In Charge Tab Start -->
                <div class="tab-pane fade " id="personsChargeTab" role="tabpanel">

                    <form method="POST" action="{{ route('personsCharge', $student) }}" class="tooltip-label-end"
                        novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Mother Section Start -->
                        <h2 class="small-title">{{ __('Mother Information') }}</h2>
                        <input type="hidden" name="mother" value="{{ $student->mother->id ?? null }}">
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('name') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input value="{{ $student->mother->name ?? null }}" name="mother_name"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('email') }} <span class="text-danger">*</span>
                                            </x-label>
                                            @if (null === $student->mother)
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
                                            <x-label>{{ __('document') }}</x-label>
                                            <x-input value="{{ $student->mother->document ?? null }}"
                                                name="mother_document" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <select name="mother_expedition_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        @if ($student->mother->expedition_city_id ?? null !== null) @selected($student->mother->expedition_city_id ===
                                                            $city->id) @endif>
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
                                            <select name="mother_residence_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        @if ($student->mother->residence_city_id ?? null !== null) @selected($student->mother->residence_city_id === $city->id) @endif>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('address') }}</x-label>
                                            <x-input value="{{ $student->mother->address ?? null }}"
                                                name="mother_address" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <x-input value="{{ $student->mother->telephone ?? null }}"
                                                name="mother_telephone" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('cellphone') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input value="{{ $student->mother->cellphone ?? null }}"
                                                name="mother_cellphone" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <x-input value="{{ $student->mother->birthdate ?? null }}"
                                                logro="datePicker" name="mother_birthdate" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('occupation') }}</x-label>
                                            <x-input value="{{ $student->mother->occupation ?? null }}"
                                                name="mother_occupation" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Mother Section End -->

                        <!-- Father Section Start -->
                        <h2 class="small-title">{{ __('Father Information') }}</h2>
                        <input type="hidden" name="father" value="{{ $student->father->id ?? null }}">
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('name') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input value="{{ $student->father->name ?? null }}" name="father_name"
                                                required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('email') }} <span class="text-danger">*</span>
                                            </x-label>
                                            @if (null === $student->father)
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
                                            <x-label>{{ __('document') }}</x-label>
                                            <x-input value="{{ $student->father->document ?? null }}"
                                                name="father_document" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <select name="father_expedition_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        @if ($student->father->expedition_city_id ?? null !== null) @selected($student->father->expedition_city_id ===
                                                            $city->id) @endif>
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
                                            <select name="father_residence_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}"
                                                        @if ($student->father->residence_city_id ?? null !== null) @selected($student->father->residence_city_id === $city->id) @endif>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('address') }}</x-label>
                                            <x-input value="{{ $student->father->address ?? null }}"
                                                name="father_address" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <x-input value="{{ $student->father->telephone ?? null }}"
                                                name="father_telephone" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('cellphone') }} <span class="text-danger">*</span>
                                            </x-label>
                                            <x-input value="{{ $student->father->cellphone ?? null }}"
                                                name="father_cellphone" required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <x-input value="{{ $student->father->birthdate ?? null }}"
                                                logro="datePicker" name="father_birthdate" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 position-relative form-group">
                                            <x-label>{{ __('occupation') }}</x-label>
                                            <x-input value="{{ $student->father->occupation ?? null }}"
                                                name="father_occupation" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Father Section End -->

                        <!-- Tutor Section Start -->
                        <h2 class="small-title">{{ __('Tutor') }} <span class="text-danger">*</span></h2>
                        <section class="card mb-5">
                            <div class="card-body w-100">
                                <div class="w-100 position-relative form-group">
                                    <select name="person_charge" logro="select2" required>
                                        <option label="&nbsp;"></option>
                                        @foreach ($kinships as $kinship)
                                            <option value="{{ $kinship->id }}"
                                                @if ($student->person_charge ?? null !== null) @selected($student->person_charge === $kinship->id) @endif>
                                                {{ __($kinship->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </section>
                        <!-- Tutor Section End -->

                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                            <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>
                        </div>

                    </form>

                </div>
                <!-- Persons In Charge Tab End -->

                <!-- Documents Tab Start -->
                <div class="tab-pane fade " id="documentsTab" role="tabpanel">
                    <h2 class="small-title">{{ __('Documents') }}</h2>
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
                                                logro="select2" id="selectStudentDocument">
                                                <option label="&nbsp;"></option>
                                                @foreach ($studentFileTypes as $fileType)
                                                    @if ($fileType->studentFile === null)
                                                        <option value="{{ $fileType->id }}"
                                                            fileInfo="{{ $fileType->description }}"
                                                            @selected(old('file_type') == $fileType->id)>
                                                            {{ $fileType->name }}
                                                        </option>
                                                    @else
                                                        @if ($fileType->studentFile->checked !== 1)
                                                            <option value="{{ $fileType->id }}"
                                                                fileInfo="{{ $fileType->description }}"
                                                                @selected(old('file_type') == $fileType->id)>
                                                                {{ $fileType->name }}
                                                            </option>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <x-input type="file" name="file_upload"
                                                accept="image/jpg, image/jpeg, image/png, image/webp" class="d-block" />
                                        </div>
                                    </div>
                                    <div
                                        class="col-md-2 col-md-2 border-0 pt-0 d-flex justify-content-end align-items-start">
                                        <x-button class="btn-primary" type="submit">{{ __('Upload') }}
                                        </x-button>
                                    </div>
                                </div>
                                <div class="row mt-3 g-3 text-danger d-none" id="infoStudentDocument"></div>

                            </form>
                        </div>
                        <div class="card-body">

                            @can('support.users')
                                <form method="POST" action="{{ route('studentFile.checked', $student) }}"
                                    class="tooltip-label-end" novalidate>
                                    @csrf
                                    @method('PUT')
                                @endcan

                                <div class="row g-2 row-cols-4 row-cols-md-5">
                                    @foreach ($studentFileTypes as $studentFile)
                                        <div class="col small-gutter-col">
                                            <div class="h-100">
                                                <div class="text-center d-flex flex-column">
                                                    <span>

                                                        @if ($studentFile->studentFile ?? null !== null)
                                                            @if ($studentFile->studentFile->checked === 1)
                                                                <i class="icon bi-file-earmark-check-fill icon-70 text-muted cursor-pointer"
                                                                    logro="studentDocument"
                                                                    data-image="{{ $studentFile->studentFile->url }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalStudentDocuments"></i>
                                                            @elseif ($studentFile->studentFile->checked === 0)
                                                                <i class="icon bi-file-earmark-x-fill icon-70 text-danger cursor-pointer"
                                                                    logro="studentDocument"
                                                                    data-image="{{ $studentFile->studentFile->url }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalStudentDocuments"></i>
                                                            @else
                                                                <i class="icon bi-file-earmark-fill icon-70 text-info cursor-pointer"
                                                                    logro="studentDocument"
                                                                    data-image="{{ $studentFile->studentFile->url }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalStudentDocuments"></i>
                                                            @endif
                                                        @else
                                                            <i class="icon bi-file-earmark icon-70 text-muted"></i>
                                                        @endif

                                                    </span>
                                                    <span>{{ $studentFile->name }}</span>

                                                    @can('support.users')
                                                        @if ($studentFile->studentFile ?? null !== null)
                                                            @if ($studentFile->studentFile->checked !== 1)
                                                                <div class="form-switch">
                                                                    <input class="form-check-input" name="student_files[]"
                                                                        value="{{ $studentFile->studentFile->id }}"
                                                                        type="checkbox" />
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endcan

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @can('support.users')
                                    <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                        <x-button class="btn-primary" type="submit">{{ __('Save') }}
                                        </x-button>
                                    </div>
                                </form>
                            @endcan
                        </div>
                    </section>
                </div>
                <!-- Documents Tab End -->

                <!-- PIAR Tab Start -->
                @if (1 === $student->inclusive)
                    <div class="tab-pane fade " id="piarTab" role="tabpanel">
                        <section class="scroll-section">
                            <h2 class="small-title">PIAR</h2>
                            <div class="mb-n2" id="accordionCardsSubjects">
                                @foreach ($groupsStudent as $groupS)
                                    <div class="card d-flex mb-2 card-color-background">
                                        <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                            data-bs-target="#year-{{ $groupS->schoolYear->name }}" aria-expanded="true"
                                            aria-controls="year-{{ $groupS->schoolYear->name }}">
                                            <div class="card-body py-3 border-bottom">
                                                <div class="btn btn-link list-item-heading p-0">
                                                    {{ $groupS->schoolYear->name }} -
                                                    {{ '(' . $groupS->studyYear->name . ' - ' . $groupS->name . ')' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div id="year-{{ $groupS->schoolYear->name }}"
                                            class="collapse @if ($loop->first) show @endif"
                                            data-bs-parent="#accordionCardsSubjects">
                                            <div class="card mt-3 accordion-content">
                                                <div class="card-body pb-3">

                                                    @if ($YAvailable === $groupS->school_year_id)
                                                        <form
                                                            method="POST"action="{{ route('students.piar', $student) }}"
                                                            novalidate>
                                                            @csrf
                                                            @method('PUT')
                                                    @endif
                                                    @php $groupSubjects = '' @endphp

                                                    @foreach ($groupS->studyYear->studyYearSubject as $studyYearSubject)
                                                        @if ($groupS->school_year_id === $studyYearSubject->school_year_id)
                                                            <div class="mb-3">
                                                                <h2 class="small-title">
                                                                    {{ $studyYearSubject->subject->resourceSubject->name }}
                                                                    -

                                                                    @foreach ($studyYearSubject->subject->teacherSubjectGroups as $groupTSG)
                                                                        @if ($groupS->id === $groupTSG->group_id && $groupS->school_year_id === $groupTSG->school_year_id)
                                                                            {{ '(' . $groupTSG->teacher->getFullName() . ')' }}
                                                                        @endif
                                                                    @endforeach

                                                                </h2>
                                                                <div class="w-100 position-relative form-group">
                                                                    @if ($YAvailable === $studyYearSubject->subject->school_year_id)
                                                                        <textarea
                                                                            name="{{ $studyYearSubject->subject->piarOne->id ?? 'null' }}~{{ $studyYearSubject->subject->id }}~annotation"
                                                                            class="form-control" cols="2">{{ $studyYearSubject->subject->piarOne->annotation ?? null }}</textarea>
                                                                    @else
                                                                        <span
                                                                            class="form-control">{{ $studyYearSubject->subject->piarOne->annotation ?? null }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            @php
                                                                $groupSubjects .= $studyYearSubject->subject->id . '~';
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    @if ($YAvailable === $groupS->school_year_id)
                                                        <input type="hidden" name="groupSubjects"
                                                            value="{{ $groupSubjects }}">
                                                        <div
                                                            class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                                            <x-button class="btn-primary" type="submit">
                                                                {{ __('Save') }}</x-button>
                                                        </div>
                                                        </form>
                                                    @endif


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                    </div>
                @endif
                <!-- PIAR Tab End -->

            </div>
            <!-- Right Side End -->
        </section>

    </div>

    <!-- Modal Document Images -->
    <div class="modal fade modal-close-out" id="modalStudentDocuments" tabindex="-1" role="dialog"
        aria-labelledby="Imagen 1" aria-hidden="true">
        <div class="modal-dialog modal-semi-full modal-dialog-centered logro-modal-image">
            <img src="\img\other\none.png" alt="document">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
@endsection
