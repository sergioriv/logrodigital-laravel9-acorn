@php
    $title = $teacher->names;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>

    <!-- DataTable -->
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>
    <script src="/js/forms/teacher-permit-create.js"></script>
@endsection

@section('content')
    <input type="hidden" id="teacher" value="{{ $teacher->uuid }}">
    <div class="container">
        <!-- Title Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ __('Teacher') . ' | ' . __($title) }}</h1>
                </div>
                <!-- Title End -->
            </div>
        </section>
        <!-- Title End -->

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
                                <x-avatar-profile :avatar="$teacher->user->avatar" class="mb-3" />
                                <!-- Avatar Form End -->

                                <div class="h5">{{ $teacher->getFullName() }}</div>
                                <div class="text-muted text-capitalize">{{ __($teacher->type_appointment) }}</div>
                                <div class="text-muted text-capitalize">{{ __($teacher->type_admin_act) }}</div>
                            </div>
                        </div>

                        <div class="nav flex-column mb-5" role="tablist">

                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#informationTab" role="tab">
                                <span class="align-middle">{{ __('Information') }}</span>
                            </a>
                            <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#subjectsTab" role="tab">
                                <span class="align-middle">{{ __('Subjects') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'permits') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#permitsTab" role="tab">
                                <span class="align-middle">{{ __('Permits') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'hierarchies') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#hierarchyTab" role="tab">
                                <span class="align-middle">{{ __('Hierarchy') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'degrees') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#degreeTab" role="tab">
                                <span class="align-middle">{{ __('Degrees') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'employments') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#employmentsTab" role="tab">
                                <span class="align-middle">{{ __('Employment history') }}</span>
                            </a>

                        </div>

                        <div class="d-flex flex-column">
                            <text class="text-muted text-small">{{ __('created at') }}:</text>
                            <text class="text-muted text-small">{{ $teacher->created_at }}</text>
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

                    <!-- Information Content Tab Start -->
                    <h2 class="small-title">{{ __('Information') }}</h2>
                    <section class="mb-5">

                        <div class="card mb-5">
                            <div class="card-body row g-3">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('names') }}</x-label>
                                        <text class="form-control">{{ $teacher->names }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('last names') }}</x-label>
                                        <text class="form-control">{{ $teacher->last_names }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('document number') }}</x-label>
                                        <text class="form-control">{{ $teacher->document }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('expedition city') }}</x-label>
                                        <text class="form-control">{{ $teacher->expeditionCity?->department->name .' | '. $teacher->expeditionCity?->name }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('birth city') }}</x-label>
                                        <text class="form-control">{{ $teacher->birthCity?->department->name .' | '. $teacher->birthCity?->name }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('birthdate') }}</x-label>
                                        <text class="form-control">{{ $teacher->birthdate }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('residence city') }}</x-label>
                                        <text class="form-control">{{ $teacher->residenceCity?->department->name .' | '. $teacher->residenceCity?->name }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('residence address') }}</x-label>
                                        <text class="form-control">{{ $teacher->address }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('telephone') }}</x-label>
                                        <text class="form-control">{{ $teacher->telephone }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('cellphone') }}</x-label>
                                        <text class="form-control">{{ $teacher->cellphone }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label required>{{ __('institutional email') }}</x-label>
                                        <text class="form-control">{{ $teacher->institutional_email }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('marital status') }}</x-label>
                                        <text class="form-control">{{ __($teacher->marital_status) }}</text>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Appointment, possession, transfer Start -->
                        <div class="card mb-5">
                            <div class="card-body row g-3">

                                @if ($teacher->file_appointment)
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <text class="form-control">{{ $teacher->appointment_number }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <text class="form-control">{{ $teacher->date_appointment }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end justify-content-center">
                                        <a href="{{ config('app.url') .'/'. $teacher->file_appointment }}"
                                            target="_blank" class="btn bt-sm btn-outline-primary icon-start">
                                            <i class="icon bi-box-arrow-up-right me-2"></i>
                                            {{ __('View document') }}
                                        </a>
                                    </div>
                                @endif

                                @if ($teacher->file_possession_certificate)
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <text class="form-control">{{ $teacher->possession_certificate }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <text class="form-control">{{ $teacher->date_possession_certificate }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end justify-content-center">
                                        <a href="{{ config('app.url') .'/'. $teacher->file_possession_certificate }}"
                                            target="_blank" class="btn bt-sm btn-outline-primary icon-start">
                                            <i class="icon bi-box-arrow-up-right me-2"></i>
                                            {{ __('View document') }}
                                        </a>
                                    </div>
                                @endif

                                @if ($teacher->file_transfer_resolution)
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <text class="form-control">{{ $teacher->transfer_resolution }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <text class="form-control">{{ $teacher->date_transfer_resolution }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end justify-content-center">
                                        <a href="{{ config('app.url') .'/'. $teacher->file_transfer_resolution }}"
                                            target="_blank" class="btn bt-sm btn-outline-primary icon-start">
                                            <i class="icon bi-box-arrow-up-right me-2"></i>
                                            {{ __('View document') }}
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- Appointment, possession, transfer End -->

                    </section>
                    <!-- Information Content Tab End -->

                </div>
                <!-- Information Tab End -->

                <!-- Subjects Tab Start -->
                <div class="tab-pane fade" id="subjectsTab"
                    role="tabpanel">

                    <!-- Subjects Content Tab Start -->
                    <h2 class="small-title">{{ __('Subjects') }}</h2>
                    <section class="mb-5">
                        @if ($schoolYear->count() === 0)
                            <h5 class="text-muted">{{ __('No Subjects') }}</h5>
                        @endif
                        <div class="mb-n2" id="accordionCardsSubjects">
                            @foreach ($schoolYear as $schy)
                                <div class="card d-flex mb-2 card-color-background">
                                    <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                        data-bs-target="#year-{{ $schy->name }}" aria-expanded="true"
                                        aria-controls="year-{{ $schy->name }}">
                                        <div class="card-body py-3 border-bottom">
                                            <div class="btn btn-link list-item-heading p-0">
                                                {{ $schy->name }}
                                            </div>
                                        </div>
                                    </div>
                                    <div id="year-{{ $schy->name }}"
                                        class="collapse @if ($loop->first) show @endif"
                                        data-bs-parent="#accordionCardsSubjects">
                                        <div class="card-body accordion-content">
                                            @if ($schy->teacherSubjectGroups->count() === 0)
                                                <h5 class="text-muted">{{ __('No Subjects') }}</h5>
                                            @endif
                                            <div class="row g-2 row-cols-3 row-cols-md-4">
                                                @foreach ($schy->teacherSubjectGroups as $teacherSubject)
                                                    <x-group.card :group="$teacherSubject->group">
                                                        <span class="mt-3 text-black btn-icon-start">
                                                            <i data-acorn-icon="notebook-1" class="icon"
                                                                data-acorn-size="15"></i>
                                                            {!! $teacherSubject->subject->resourceSubject->nameSpecialty() !!}
                                                        </span>
                                                    </x-group.card>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section> <!-- Subjects Content Tab End -->
                </div>
                <!-- Subjects Tab End -->

                <!-- Permits Tab Start -->
                <div class="tab-pane fade @if (session('tab') === 'permits') active show @endif" id="permitsTab"
                    role="tabpanel">

                    <!-- Permits Content Start -->
                    <h2 class="small-title">{{ __('Permits') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">

                            <!-- Permits Buttons Start -->
                            <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                                <a type="button" data-bs-toggle="modal" data-bs-target="#addPermitTeacherModal"
                                    class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                    <i data-acorn-icon="plus"></i>
                                    <span>{{ __('Add permit') }}</span>
                                </a>
                            </div>
                            <!-- Permits Buttons End -->

                            <!-- Table Start -->
                            <div class="">
                                <table logro='dataTableBoxed' data-order=""
                                    class="data-table responsive nowrap stripe dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('short description') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('date range') }}</th>
                                            <th class="empty p-0">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacher->permits as $permit)
                                            <tr>
                                                <td>{{ $permit->description }}</td>
                                                <td>{{ $permit->dateRange() }}</td>
                                                <td class="text-center">
                                                    <a target="_blank" title="{{ __('Download') }}"
                                                        href="{{ $permit->url }}"><i data-acorn-icon="download"
                                                            data-acorn-size="14"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </section>
                    <!-- Secretariat Content End -->

                </div>
                <!-- Permits Tab End -->

                <!-- Hierarchy Tab Start -->
                <div class="tab-pane fade show @if (session('tab') === 'hierarchies') active show @endempty" id="hierarchyTab"
                    role="tabpanel">

                    <!-- Hierarchy Content Tab Start -->
                    <h2 class="small-title">{{ __('Hierarchy') }}</h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" logro="dataTableBoxed"
                                    data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('number') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('Resolution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date') }}</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacher->hierarchies as $hierarchy)
                                            <tr>
                                                <td>{{ $hierarchy->number }}</td>
                                                <td>{{ $hierarchy->resolution }}</td>
                                                <td class="text-small">{{ $hierarchy->date }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') .'/'. $hierarchy->url }}" class="btn btn-sm btn-link text-capitalize" target="_blank">
                                                        <i class="icon bi-box-arrow-up-right me-1"></i>
                                                        {{ __('open') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                    <!-- Hierarchy Content Tab End -->

                </div>
                <!-- Hierarchy Tab End -->

                <!-- Degree Tab Start -->
                <div class="tab-pane fade show @if (session('tab') === 'degrees') active show @endempty" id="degreeTab"
                    role="tabpanel">

                    <!-- Degree Content Tab Start -->
                    <h2 class="small-title">{{ __('Degrees') }}</h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" logro="dataTableBoxed"
                                    data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('institution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('degree') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date') }}</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacher->degrees as $degree)
                                            <tr>
                                                <td>{{ $degree->institution }}</td>
                                                <td>{{ $degree->degree }}</td>
                                                <td class="text-small">{{ $degree->date }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') .'/'. $degree->url }}" class="btn btn-sm btn-link text-capitalize" target="_blank">
                                                        <i class="icon bi-box-arrow-up-right me-1"></i>
                                                        {{ __('open') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                    <!-- Degree Content Tab End -->

                </div>
                <!-- Degree Tab End -->

                <!-- Employment History Tab Start -->
                <div class="tab-pane fade show @if (session('tab') === 'employments') active show @endempty" id="employmentsTab"
                    role="tabpanel">

                    <!-- Employment History Content Tab Start -->
                    <h2 class="small-title">{{ __('Employment history') }}</h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" logro="dataTableBoxed"
                                    data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('institution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date of entry') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date of withdrawal') }}</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacher->employments as $employment)
                                            <tr>
                                                <td>{{ $employment->institution }}</td>
                                                <td class="text-small">{{ $employment->date_start }}</td>
                                                <td class="text-small">{{ $employment->date_end }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') .'/'. $employment->url }}" class="btn btn-sm btn-link text-capitalize" target="_blank">
                                                        <i class="icon bi-box-arrow-up-right me-1"></i>
                                                        {{ __('open') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                    <!-- Employment History Content Tab End -->

                </div>
                <!-- Employment History Tab End -->

            </div>
            <!-- Right Side End -->
        </section>
    </div>


    <!-- Modal Add Advice -->
    <div class="modal fade" id="addPermitTeacherModal" aria-labelledby="modalAddPermitTeacher" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddPermitTeacher">{{ __('Add permit') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @include('logro.teacher.permit.create')
            </div>
        </div>
    </div>
@endsection
