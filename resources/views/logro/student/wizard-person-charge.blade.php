@php
$title = __('Persons in Charge');
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
    <script src="/js/forms/person-charge.js"></script>
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

                <x-validation-errors class="mb-4" :errors="$errors" />

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
                                    <a class="nav-link text-center active" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Persons in Charge') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Personal Information') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item d-none" role="presentation">
                                    <a class="nav-link text-center" role="tab"></a>
                                </li>
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('student.wizard.person-charge') }}" id="studentPersonChargeForm">
                            @csrf
                            @method('PUT')

                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" role="tabpanel">

                                        <!-- Mother Section Start -->
                                        <h2 class="small-title">{{ __('Mother Information') }}</h2>
                                        <input type="hidden" name="mother" value="{{ $student->mother->id ?? null }}">
                                        <section class="mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('full name') }}
                                                            </x-label>
                                                            <x-input-error
                                                                value="{{ old('mother_name', $student->mother->name ?? null) }}"
                                                                name="mother_name" :hasError="'mother_name'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('email') }}
                                                            </x-label>
                                                            @if (null === $student->mother)
                                                                <x-input-error
                                                                    value="{{ old('mother_email', $student->mother->email ?? null) }}"
                                                                    name="mother_email" :hasError="'mother_email'" />
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
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('document') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('mother_document', $student->mother->document ?? null) }}"
                                                                name="mother_document" :hasError="'mother_document'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('expedition city') }}</x-label>
                                                            <x-select name="mother_expedition_city" logro="select2"
                                                                :hasError="'mother_expedition_city'">
                                                                <option label="&nbsp;"></option>
                                                                @foreach ($cities as $city)
                                                                    <option value="{{ $city->id }}"
                                                                        @if ($student->mother->expedition_city_id ?? null !== null) @selected($student->mother->expedition_city_id === $city->id) @endif>
                                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                                    </option>
                                                                @endforeach
                                                            </x-select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('residence city') }}</x-label>
                                                            <x-select name="mother_residence_city" logro="select2"
                                                                :hasError="'mother_residence_city'">
                                                                <option label="&nbsp;"></option>
                                                                @foreach ($cities as $city)
                                                                    <option value="{{ $city->id }}"
                                                                        @if ($student->mother->residence_city_id ?? null !== null) @selected($student->mother->residence_city_id === $city->id) @endif>
                                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                                    </option>
                                                                @endforeach
                                                            </x-select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('address') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('mother_address', $student->mother->address ?? null) }}"
                                                                name="mother_address" :hasError="'mother_address'" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('telephone') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('mother_telephone', $student->mother->telephone ?? null) }}"
                                                                name="mother_telephone" :hasError="'mother_telephone'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('cellphone') }}
                                                            </x-label>
                                                            <x-input-error
                                                                value="{{ old('mother_cellphone', $student->mother->cellphone ?? null) }}"
                                                                name="mother_cellphone" :hasError="'mother_cellphone'" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('birthdate') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('mother_birthdate', $student->mother->birthdate ?? null) }}"
                                                                logro="datePicker" name="mother_birthdate"
                                                                :hasError="'mother_birthdate'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('occupation') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('mother_occupation', $student->mother->occupation ?? null) }}"
                                                                name="mother_occupation" :hasError="'mother_occupation'" />
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- </div> --}}
                                        </section>
                                        <!-- Mother Section End -->

                                        <!-- Father Section Start -->
                                        <h2 class="small-title">{{ __('Father Information') }}</h2>
                                        <input type="hidden" name="father" value="{{ $student->father->id ?? null }}">
                                        <section class="mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('full name') }}
                                                            </x-label>
                                                            <x-input-error
                                                                value="{{ old('father_name', $student->father->name ?? null) }}"
                                                                name="father_name" :hasError="'father_name'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('email') }}
                                                            </x-label>
                                                            @if (null === $student->father)
                                                                <x-input-error
                                                                    value="{{ old('father_email', $student->father->email ?? null) }}"
                                                                    name="father_email" :hasError="'father_email'" />
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
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('document') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('father_document', $student->father->document ?? null) }}"
                                                                name="father_document" :hasError="'father_document'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('expedition city') }}</x-label>
                                                            <x-select name="father_expedition_city" logro="select2"
                                                                :hasError="'father_expedition_city'">
                                                                <option label="&nbsp;"></option>
                                                                @foreach ($cities as $city)
                                                                    <option value="{{ $city->id }}"
                                                                        @if ($student->father->expedition_city_id ?? null !== null) @selected($student->father->expedition_city_id === $city->id) @endif>
                                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                                    </option>
                                                                @endforeach
                                                            </x-select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div
                                                            class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('residence city') }}</x-label>
                                                            <x-select name="father_residence_city" logro="select2"
                                                                :hasError="'father_residence_city'">
                                                                <option label="&nbsp;"></option>
                                                                @foreach ($cities as $city)
                                                                    <option value="{{ $city->id }}"
                                                                        @if ($student->father->residence_city_id ?? null !== null) @selected($student->father->residence_city_id === $city->id) @endif>
                                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                                    </option>
                                                                @endforeach
                                                            </x-select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('address') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('father_address', $student->father->address ?? null) }}"
                                                                name="father_address" :hasError="'father_address'" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('telephone') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('father_telephone', $student->father->telephone ?? null) }}"
                                                                name="father_telephone" :hasError="'father_telephone'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('cellphone') }}
                                                            </x-label>
                                                            <x-input-error
                                                                value="{{ old('father_cellphone', $student->father->cellphone ?? null) }}"
                                                                name="father_cellphone" :hasError="'father_cellphone'" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('birthdate') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('father_birthdate', $student->father->birthdate ?? null) }}"
                                                                logro="datePicker" name="father_birthdate"
                                                                :hasError="'father_birthdate'" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="tooltip-label-end position-relative form-group">
                                                            <x-label>{{ __('occupation') }}</x-label>
                                                            <x-input-error
                                                                value="{{ old('father_occupation', $student->father->occupation ?? null) }}"
                                                                name="father_occupation" :hasError="'father_occupation'" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <!-- Father Section End -->

                                        <!-- Tutor Student Section Start -->
                                        <h3>@if (old('person_charge') == 2) person charge @endif</h3>
                                        <section class="card-body mb-3">
                                            <div class="tooltip-label-end position-relative form-group">
                                                <x-label class="small-title">{{ __('Tutor') }}
                                                    <span class="text-danger">*</span>
                                                </x-label>
                                                <div class="w-100">
                                                    <select name="person_charge" logro="select2" id="person_charge"
                                                        required>
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
                                        <!-- Tutor Student Section End -->

                                        <!-- Tutor Section Start -->
                                        <div class="mt-5 @if (old('person_charge') < 3) d-none @endif" id="section-tutor">
                                            <h2 class="small-title">{{ __('Tutor Information') }}</h2>
                                            <input type="hidden" name="tutor"
                                                value="{{ $student->tutor->id ?? null }}">
                                            <section>
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('full name') }}
                                                                </x-label>
                                                                <x-input-error
                                                                    value="{{ old('tutor_name', $student->tutor->name ?? null) }}"
                                                                    name="tutor_name" :hasError="'tutor_name'" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('email') }}
                                                                </x-label>
                                                                @if (null === $student->tutor)
                                                                    <x-input-error
                                                                        value="{{ old('tutor_email', $student->tutor->email ?? null) }}"
                                                                        name="tutor_email" :hasError="'tutor_email'" />
                                                                @else
                                                                    <span class="form-control text-muted">
                                                                        {{ $student->tutor->email }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('document') }}</x-label>
                                                                <x-input-error
                                                                    value="{{ old('tutor_document', $student->tutor->document ?? null) }}"
                                                                    name="tutor_document" :hasError="'tutor_document'" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('expedition city') }}</x-label>
                                                                <x-select name="tutor_expedition_city" logro="select2"
                                                                    :hasError="'tutor_expedition_city'">
                                                                    <option label="&nbsp;"></option>
                                                                    @foreach ($cities as $city)
                                                                        <option value="{{ $city->id }}"
                                                                            @if ($student->tutor->expedition_city_id ?? null !== null) @selected($student->tutor->expedition_city_id === $city->id) @endif>
                                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </x-select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('residence city') }}</x-label>
                                                                <x-select name="tutor_residence_city" logro="select2"
                                                                    :hasError="'tutor_residence_city'">
                                                                    <option label="&nbsp;"></option>
                                                                    @foreach ($cities as $city)
                                                                        <option value="{{ $city->id }}"
                                                                            @if ($student->tutor->residence_city_id ?? null !== null) @selected($student->tutor->residence_city_id === $city->id) @endif>
                                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </x-select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('address') }}</x-label>
                                                                <x-input-error
                                                                    value="{{ old('tutor_address', $student->tutor->address ?? null) }}"
                                                                    name="tutor_address" :hasError="'tutor_address'" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('telephone') }}</x-label>
                                                                <x-input-error
                                                                    value="{{ old('tutor_telephone', $student->tutor->telephone ?? null) }}"
                                                                    name="tutor_telephone" :hasError="'tutor_telephone'" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div
                                                                class="mb-3 tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('cellphone') }}
                                                                </x-label>
                                                                <x-input-error
                                                                    value="{{ old('tutor_cellphone', $student->tutor->cellphone ?? null) }}"
                                                                    name="tutor_cellphone" :hasError="'tutor_cellphone'" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('birthdate') }}</x-label>
                                                                <x-input-error
                                                                    value="{{ old('tutor_birthdate', $student->tutor->birthdate ?? null) }}"
                                                                    logro="datePicker" name="tutor_birthdate"
                                                                    :hasError="'tutor_birthdate'" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="tooltip-label-end position-relative form-group">
                                                                <x-label>{{ __('occupation') }}</x-label>
                                                                <x-input-error
                                                                    value="{{ old('tutor_occupation', $student->tutor->occupation ?? null) }}"
                                                                    name="tutor_occupation" :hasError="'tutor_occupation'" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                        <!-- Tutor Section End -->

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-icon btn-icon-end btn-outline-primary btn-next" type="submit">
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
