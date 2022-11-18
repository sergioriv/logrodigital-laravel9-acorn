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
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
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

                @hasrole('TEACHER')
                    @if ($orientation)
                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                            <!-- Download Matriculate Button -->
                            <x-button type="button" class="btn-outline-info" data-bs-toggle="modal"
                                data-bs-target="#addRemitToOrientation">{{ __('Remit to Orientation') }}</x-button>
                        </div>
                    @endif
                @endhasrole

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

                            </div>
                        </div>

                        <div class="nav flex-column mb-5" role="tablist">
                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#informationTab" role="tab">
                                <span class="align-middle">{{ __('Information') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'personsCharge') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#personsChargeTab" role="tab">
                                <span class="align-middle">{{ __('Persons in Charge') }}</span>
                            </a>
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
                <div class="tab-pane fade active show" id="informationTab" role="tabpanel">

                    <!-- Basic Information Section Start -->
                    <h2 class="small-title">{{ __('Basic information') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body row g-3">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('first name') }}</x-label>
                                    <div class="form-control">{{ $student->first_name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('second name') }}</x-label>
                                    <div class="form-control">{{ $student->second_name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('first last name') }}</x-label>
                                    <div class="form-control">{{ $student->first_last_name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('second last name') }}</x-label>
                                    <div class="form-control">{{ $student->second_last_name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('institutional email') }}</x-label>
                                    <div class="form-control">{{ $student->institutional_email }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('telephone') }}</x-label>
                                    <div class="form-control">{{ $student->telephone }}</div>
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
                                    <div class="form-control">{{ $student->document }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('expedition city') }}</x-label>
                                    <div class="form-control">{{ $student->expeditionCity->name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('number siblings') }}</x-label>
                                    <div class="form-control">{{ $student->number_siblings }}</div>
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
                                    <div class="form-control">{{ $student->birthCity->name ?? null }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label>{{ __('birthdate') }}</x-label>
                                    <div class="form-control">{{ $student->birthdate }}</div>
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

            </div>
            <!-- Right Side End -->
        </section>

    </div>


    @hasrole('TEACHER')
        <!-- Modal Report To Orientation -->
        <div class="modal fade" id="addRemitToOrientation" aria-labelledby="modalAddRemitToOrientation"
            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAddRemitToOrientation">{{ __('Report to Orientation') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    @include('logro.teacher.report.student_to_orientation')
                </div>
            </div>
        </div>
    @endhasrole
@endsection
