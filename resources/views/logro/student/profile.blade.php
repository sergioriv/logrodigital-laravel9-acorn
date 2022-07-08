@php
$title = $student->user->name;
@endphp
@extends('layout',['title'=>$title])

@section('css')
{{-- <link rel="stylesheet" href="/css/vendor/datatables.min.css" /> --}}
<link rel="stylesheet" href="/css/vendor/select2.min.css" />
<link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
<link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css"/>
@endsection

@section('js_vendor')
<script src="/js/cs/responsivetab.js"></script>
<script src="/js/vendor/singleimageupload.js"></script>
{{-- <script src="/js/vendor/bootstrap-submenu.js"></script> --}}
{{-- <script src="/js/vendor/datatables.min.js"></script> --}}
{{-- <script src="/js/vendor/mousetrap.min.js"></script> --}}
<script src="/js/vendor/select2.full.min.js"></script>
<script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
{{-- <script src="/js/cs/datatable.extend.js"></script> --}}
{{-- <script src="/js/plugins/datatable/datatable_standard.ajax.js"></script> --}}
<script>
    jQuery("[logro='select2']").select2({minimumResultsForSearch: 30, placeholder: ''});
    // jQuery("[logro='select2']").select2({placeholder: ''});
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

                                        <x-avatar-profile-edit :avatar="$student->user->avatar" class="mb-3" />
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
                                    <a class="nav-link active logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab" href="#informationTab" role="tab">
                                        <span class="align-middle">{{ __("Information") }}</span>
                                    </a>
                                    <a class="nav-link logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab" href="#documentsTab" role="tab">
                                        <span class="align-middle">{{ __("Documents") }}</span>
                                    </a>
                                </div>

                            </div>
                        </div>
                        <!-- Biography End -->
                    </div>
                    <!-- Left Side End -->

                    <!-- Right Side Start -->
                    <div class="col-12 col-xl-8 col-xxl-9 mb-5 tab-content">

                            <!-- Information Tab Start -->
                            <div class="tab-pane fade active show" id="informationTab" role="tabpanel">

                                <form method="POST" action="{{ route('students.update', $student) }}" class="tooltip-label-end" novalidate>
                                    @csrf
                                    @method('PUT')

                                <!-- Basic Information Section Start -->
                                <h2 class="small-title">{{ __("Basic information") }}</h2>
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 position-relative form-group">
                                                    <x-label>{{ __("first name") }} <span class="text-danger">*</span></x-label>
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
                                                    <x-label>{{ __("father's last name") }} <span class="text-danger">*</span></x-label>
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
                                                    <x-label>{{ __("document type") }} <span class="text-danger">*</span></x-label>
                                                    <select name="document_type" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($documentType as $docType)
                                                        <option value="{{ $docType->code }}"
                                                            @if ($student->document_type_code !== NULL)
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
                                                    <x-label>{{ __("document") }} <span class="text-danger">*</span></x-label>
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
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->expedition_city_id !== NULL)
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
                                                    <x-input type="number" :value="$student->number_siblings" name="number_siblings" />
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
                                                            @if ($student->birth_city_id !== NULL)
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
                                                    <x-input :value="$student->birthdate" logro="datePicker" name="birthdate" />
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
                                                        <option value="{{ $gender->id }}"
                                                            @if ($student->gender_id !== NULL)
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
                                                        <option value="{{ $rh->id }}"
                                                            @if ($student->rh_id !== NULL)
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
                                                        <option value="rural"
                                                        @selected("rural" === $student->zone)>{{ __("Rural") }}</option>
                                                        <option value="urban"
                                                        @selected("urban" === $student->zone)>{{ __("Urban") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("residence city") }}</x-label>
                                                    <select name="residence_city" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->residence_city_id !== NULL)
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
                                                        @for ($stratum = 1; $stratum <= 6; $stratum++)
                                                        <option value="{{ $stratum }}"
                                                            @if ($student->social_stratum !== NULL)
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
                                                        <option value="{{ $health->id }}"
                                                            @if ($student->health_manager_id !== NULL)
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
                                                            @if ($student->sisben_id !== NULL)
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
                                                        <option value="{{ $ethnicGroup->id }}"
                                                            @if ($student->ethnic_group_id !== NULL)
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
                                                        <option value="0"
                                                        @selected(0 === $student->conflict_victim)>{{ __("No") }}</option>
                                                        <option value="1"
                                                        @selected(1 === $student->conflict_victim)>{{ __("Yes") }}</option>
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
                                                        <option value="0"
                                                        @selected(0 === $student->lunch)>{{ __("No") }}</option>
                                                        <option value="1"
                                                        @selected(1 === $student->lunch)>{{ __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("refreshment") }}</x-label>
                                                    <select name="refreshment" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        <option value="0"
                                                        @selected(0 === $student->refreshment)>{{ __("No") }}</option>
                                                        <option value="1"
                                                        @selected(1 === $student->refreshment)>{{ __("Yes") }}</option>
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
                                                        <option value="0"
                                                        @selected(0 === $student->transport)>{{ __("No") }}</option>
                                                        <option value="1"
                                                        @selected(1 === $student->transport)>{{ __("Yes") }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 position-relative form-group">
                                                    <x-label>{{ __("origin school") }}</x-label>
                                                    <select name="origin_school_id" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($originSchools as $originSchool)
                                                        <option value="{{ $originSchool->id }}"
                                                            @if ($student->origin_school_id !== NULL)
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

                            <!-- Branches Tab Start -->
                            <div class="tab-pane fade" id="documentsTab" role="tabpanel">
                                documentos
                            </div>
                            <!-- Branches Tab End -->


                    </div>
                    <!-- Right Side End -->
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
