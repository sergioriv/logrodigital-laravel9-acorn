@php
    $title = $student->getFullName();
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>
    <script src="/js/forms/select2.js"></script>
    @if (count($student->observer))
        <script>
            jQuery("[data-observer]").click(function() {
                let _observer = $(this).data('observer');

                if (_observer) {
                    $("#observerDisclaimers").val(_observer);
                    $("#addDisclaimers").modal('show');
                }
            });
        </script>
    @endif
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7 mb-2 mb-md-0">
                    <h1 class="mb-1 pb-0 display-4" id="title">
                        {{ __('Student') . ' | ' . $student->getCompleteNames() }}</h1>
                    <div aria-label="breadcrumb">
                        <div class="breadcrumb">
                            <span class="breadcrumb-item text-muted">
                                <div class="text-muted d-inline-block">
                                    <i data-acorn-icon="building-large" class="me-1" data-acorn-size="12"></i>
                                    <span class="align-middle">{{ $student->headquarters->name }}</span>
                                </div>
                            </span>
                            <span class="breadcrumb-item text-muted">
                                <div class="text-muted d-inline-block">
                                    <i data-acorn-icon="clock" class="me-1" data-acorn-size="12"></i>
                                    <span class="align-middle">{{ $student->studyTime->name }}</span>
                                </div>
                            </span>
                            <span class="breadcrumb-item text-muted">
                                <div class="text-muted d-inline-block">
                                    <i data-acorn-icon="calendar" class="me-1" data-acorn-size="12"></i>
                                    <span class="align-middle">{{ $student->studyYear->name }}</span>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    @hasrole('TEACHER|COORDINATOR')
                        @if ($existOrientation && $student->enrolled)
                            <!-- Remit to Orientation Button -->
                            <x-button type="button" class="btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#addRemitToOrientation">{{ __('Remit to Orientation') }}</x-button>
                        @endif
                    @endhasrole

                    <!-- Dropdown Button Start -->
                    <div class="ms-1">
                        <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only" data-bs-offset="0,3"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-submenu>
                            <i data-acorn-icon="more-horizontal"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">

                            @if ($student->enrolled)
                            <x-dropdown-item type="button" :link="route('students.pdf.matriculate', $student)">
                                <i data-acorn-icon="download"></i>
                                <span>{{ __('Download enrollment sheet') }}</span>
                            </x-dropdown-item>
                            <x-dropdown-item type="button" :link="route('students.pdf.observations', $student)">
                                <i data-acorn-icon="download"></i>
                                <span>{{ __('Download observer') }}</span>
                            </x-dropdown-item>
                            <div class="dropdown-divider"></div>
                            @endif
                            <x-dropdown-item type="button" :link="route('students.pdf.report_grades', $student)">
                                <i data-acorn-icon="download"></i>
                                <span>{{ __('Grade report') }}</span>
                            </x-dropdown-item>

                        </div>
                    </div>
                    <!-- Dropdown Button End -->

                </div>
                <!-- Top Buttons End -->

            </div>
        </section>
        <!-- Title and Top Buttons End -->

        <section class="row">
            <!-- Left Side Start -->
            <div class="col-12 col-xl-3">
                <!-- Biography Start -->
                <h2 class="small-title">{{ __('Profile') }}</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-column">
                            <div class="mb-5 d-flex align-items-center flex-column">

                                <!-- Avatar Form Start -->
                                <x-avatar-profile :avatar="$student->user->avatar" :inclusive="$student->inclusive" class="mb-3" />
                                <!-- Avatar Form End -->

                                <div class="h5">{{ $student->getFullName() }}</div>
                                @if (null !== $student->birthdate)
                                    <span class="mb-2 text-muted">{{ $student->age() . ' ' . __('years') }}</span>
                                @endif

                                @if (null !== $student->group_id)
                                    <div class="mt-2 text-center">
                                        <h5 class="text-primary font-weight-bold mb-0">{{ $student->group->name }}</h5>
                                        <text class="text-primary text-small">{{ $student->enrolled_date }}</text>
                                    </div>
                                @endif

                                @if ($student->fallas->count())
                                    <div class="mt-2">
                                        <a href="{{ route('attendances.student.download', $student) }}">{{ $student->fallas->count() }}
                                            Fallas</a>
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div class="nav flex-column mb-5" role="tablist">
                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#informationTab" role="tab">
                                <span class="align-middle">{{ __('Information') }}</span>
                            </a>
                            <a class="nav-link logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                href="#personsChargeTab" role="tab">
                                <span class="align-middle">{{ __('Persons in Charge') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'observer') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#observerTab" role="tab">
                                <span class="align-middle">{{ __('Observer') }}</span>
                            </a>
                            @if ($absences)
                                <a class="nav-link @if (session('tab') === 'attendance') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#attendanceTab" role="tab">
                                    <span class="align-middle">{{ __('Absences') }}</span>
                                </a>
                            @endif
                            @if ($student->isInclusive())
                                <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#psychosocialTab" role="tab">
                                    <span class="align-middle">{{ __('Psychosocial Information') }}</span>
                                </a>
                            @endif
                            @if ($areasWithGrades)
                                <a class="nav-link @if (session('tab') === 'grades') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#gradesTab" role="tab">
                                    <span class="align-middle">{{ __('Grades') }}</span>
                                </a>
                            @endif
                        </div>

                        <div class="d-flex flex-column">
                            <text class="text-muted text-small">{{ __('created at') }}:</text>
                            <text class="text-muted text-small">{{ $student->created_at }}</text>
                        </div>


                    </div>
                </div>
                <!-- Biography End -->
            </div>
            <!-- Left Side End -->

            <!-- Right Side Start -->
            <div class="col-12 col-xl-9 mb-5 tab-content">

                <!-- Information Tab Start -->
                <div class="tab-pane fade @empty(session('tab')) active show @endempty" id="informationTab"
                    role="tabpanel">

                    <!-- Basic Information Section Start -->
                    <h2 class="small-title">{{ __('Basic information') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('first name') }}</x-label>
                                    <div class="form-control">{{ $student->first_name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('second name') }}</x-label>
                                    <div class="form-control">{{ $student->second_name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('first last name') }}</x-label>
                                    <div class="form-control">{{ $student->first_last_name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('second last name') }}</x-label>
                                    <div class="form-control">{{ $student->second_last_name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('institutional email') }}</x-label>
                                    <div class="form-control">{{ $student->institutional_email ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('telephone') }}</x-label>
                                    <div class="form-control">{{ $student->telephone ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('document type') }}</x-label>
                                    <div class="form-control">{{ $student->documentTypeCode->name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('document number') }}</x-label>
                                    <div class="form-control">{{ $student->document ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('expedition city') }}</x-label>
                                    <div class="form-control">
                                        {{ $student->expeditionCity->department->name ?? null }} |
                                        {{ $student->expeditionCity->name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('number siblings') }}</x-label>
                                    <div class="form-control">{{ $student->number_siblings ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('home country') }}</x-label>
                                    <div class="form-control">{{ $student->country->name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('birth city') }}</x-label>
                                    <div class="form-control">{{ $student->birthCity->department->name ?? null }} |
                                        {{ $student->birthCity->name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('birthdate') }}</x-label>
                                    <div class="form-control">{{ $student->birthdate ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('Do you have siblings in the institution?') }}</x-label>
                                    <div class="form-control">
                                        @if ($student->siblings_in_institution)
                                            {{ __('Yes') }}
                                        @else
                                            {{ __('No') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('gender') }}</x-label>
                                    <div class="form-control">{{ $student->gender->name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>RH</x-label>
                                    <div class="form-control">{{ $student->rh->name ?? null }}</div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Basic Information Section End -->

                </div>
                <!-- Information Tab End -->

                <!-- Persons In Charge Tab Start -->
                <div class="tab-pane fade" id="personsChargeTab" role="tabpanel">

                    <!-- Mother Section Start -->
                    @if ($student->mother)
                        <h2 class="small-title">
                            {{ __('Mother Information') }}
                            @if ($student->person_charge === $student->mother->id)
                                ({{ __('Tutor') }})
                            @endif
                        </h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('full name') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->mother->name ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('email') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->mother->email ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('document') }}</x-label>
                                            <div class="form-control">{{ $student->mother->document ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->mother->expeditionCity->department->name ?? null }} |
                                                {{ $student->mother->expeditionCity->name ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('residence city') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->mother->residenceCity->department->name ?? null }} |
                                                {{ $student->mother->residenceCity->name ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('address') }}</x-label>
                                            <div class="form-control">{{ $student->mother->address ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <div class="form-control">{{ $student->mother->telephone ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('cellphone') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->mother->cellphone ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <div class="form-control">{{ $student->mother->birthdate ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('occupation') }}</x-label>
                                            <div class="form-control">{{ $student->mother->occupation ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                    <!-- Mother Section End -->

                    <!-- Father Section Start -->
                    @if ($student->father)
                        <h2 class="small-title">
                            {{ __('Father Information') }}
                            @if ($student->person_charge === $student->father->id)
                                ({{ __('Tutor') }})
                            @endif
                        </h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('full name') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->father->name ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('email') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->father->email ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('document') }}</x-label>
                                            <div class="form-control">{{ $student->father->document ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->father->expeditionCity->department->name ?? null }} |
                                                {{ $student->father->expeditionCity->name ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('residence city') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->father->residenceCity->department->name ?? null }} |
                                                {{ $student->father->residenceCity->name ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('address') }}</x-label>
                                            <div class="form-control">{{ $student->father->address ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <div class="form-control">{{ $student->father->telephone ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('cellphone') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->father->cellphone ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <div class="form-control">{{ $student->father->birthdate ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('occupation') }}</x-label>
                                            <div class="form-control">{{ $student->father->occupation ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                    <!-- Father Section End -->

                    <!-- Tutor Section Start -->
                    @if ($student->tutor)
                        <h2 class="small-title">{{ __('Tutor Information') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('full name') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->tutor->name ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('email') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->tutor->email ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('document') }}</x-label>
                                            <div class="form-control">{{ $student->tutor->document ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->tutor->expeditionCity->department->name ?? null }} |
                                                {{ $student->tutor->expeditionCity->name ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('residence city') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->tutor->residenceCity->department->name ?? null }} |
                                                {{ $student->tutor->residenceCity->name ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('address') }}</x-label>
                                            <div class="form-control">{{ $student->tutor->address ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <div class="form-control">{{ $student->tutor->telephone ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('cellphone') }}
                                            </x-label>
                                            <div class="form-control">{{ $student->tutor->cellphone ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <div class="form-control">{{ $student->tutor->birthdate ?? null }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3 tooltip-label-end position-relative form-group">
                                            <x-label>{{ __('occupation') }}</x-label>
                                            <div class="form-control">{{ $student->tutor->occupation ?? null }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                    <!-- Tutor Section End -->

                </div>
                <!-- Persons In Charge Tab End -->

                <!-- Observer Tab Start -->
                <div class="tab-pane fade @if (session('tab') === 'observer') active show @endif" id="observerTab"
                    role="tabpanel">

                    <!-- Observer Section Start -->
                    <h2 class="small-title">{{ __('Observer') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">
                            @include('logro.student.observer.content-tab')
                        </div>
                    </section>
                    <!-- Observer Section End -->

                </div>
                <!-- Observer Tab End -->

                @if ($absences)
                    <!-- Attendances Tab Start -->
                    <div class="tab-pane fade @if (session('tab') === 'attendance') active show @endif" id="attendanceTab"
                        role="tabpanel">

                        <!-- Attendance Section Start -->
                        <h2 class="small-title">{{ __('Absences') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                @include('logro.student.attendance.attendance-tab')
                            </div>
                        </section>
                        <!-- Attendance Section End -->

                    </div>
                    <!-- Attendances Tab End -->
                @endif

                @if ($student->isInclusive())
                    <!-- Psychosocial Information Tab Start -->
                    <div class="tab-pane fade" id="psychosocialTab" role="tabpanel">

                        <!-- Psychosocial Information Section Start -->
                        <h2 class="small-title">{{ __('Psychosocial Information') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('medical diagnosis') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->medical_diagnosis }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('medical prediagnosis') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->medical_prediagnosis }}</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('risks or vulnerabilities') }}</x-label>
                                            <div class="form-control">
                                                {{ $student->risks_vulnerabilities }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </section>
                        <!-- Psychosocial Information Section End -->

                    </div>
                    <!-- Psychosocial Information Tab End -->
                @endif

                @if ($areasWithGrades)
                    <!-- Grades Tab Start -->
                    <div class="tab-pane fade @if (session('tab') === 'grades') active show @endif" id="gradesTab"
                        role="tabpanel">

                        <h2 class="small-title">{{ __('Grades') }}</h2>
                        <div class="card">
                            <div class="card-body">
                                @include('logro.student.grades.report-tab')
                            </div>
                        </div>
                    </div>
                    <!-- Grades Tab End -->
                @endif

            </div>
            <!-- Right Side End -->
        </section>

    </div>


    @hasrole('TEACHER|COORDINATOR')
        @if ($existOrientation && $student->enrolled)
            <!-- Modal Remit to Orientation -->
            <div class="modal fade modal-close-out" id="addRemitToOrientation" aria-labelledby="modalAddRemitToOrientation"
                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAddRemitToOrientation">{{ __('Remit to Orientation') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        @include('logro.teacher.report.student_to_orientation')
                    </div>
                </div>
            </div>
        @endif
    @endhasrole
@endsection
