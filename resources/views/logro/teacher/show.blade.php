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
                                <div class="text-muted">{{ __($teacher->type_appointment) }}</div>
                                <div class="text-muted">{{ __($teacher->type_admin_act) }}</div>
                            </div>
                        </div>

                        <div class="nav flex-column mb-5" role="tablist">

                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#subjectsTab" role="tab">
                                <span class="align-middle">{{ __('Subjects') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'permits') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#permitsTab" role="tab">
                                <span class="align-middle">{{ __('Permits') }}</span>
                            </a>

                        </div>

                        <div class="mb-5">
                            <p class="text-small text-uppercase text-muted mb-2">{{ __('contact') }}</p>
                            @if ($teacher->telephone)
                                <div class="d-block mb-1">
                                    <i data-acorn-icon="phone" class="me-2" data-acorn-size="17"></i>
                                    <span class="align-middle">{{ $teacher->telephone }}</span>
                                </div>
                            @endif
                            @if ($teacher->cellphone)
                                <div class="d-block mb-1">
                                    <i data-acorn-icon="phone" class="me-2" data-acorn-size="17"></i>
                                    <span class="align-middle">{{ $teacher->cellphone }}</span>
                                </div>
                            @endif
                            <div class="d-block">
                                <i data-acorn-icon="email" class="me-2" data-acorn-size="17"></i>
                                <span class="align-middle">{{ $teacher->institutional_email }}</span>
                            </div>
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

                <!-- Subjects Tab Start -->
                <div class="tab-pane fade @empty(session('tab')) active show @endempty" id="subjectsTab"
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
                                            @if ($teacher->teacherSubjectGroups->count() === 0)
                                                <h5 class="text-muted">{{ __('No Subjects') }}</h5>
                                            @endif
                                            <div class="row g-2 row-cols-3 row-cols-md-4">
                                                @foreach ($teacher->teacherSubjectGroups as $teacherSubject)
                                                    @if ($teacherSubject->school_year_id === $schy->id)
                                                        <x-group.card :group="$teacherSubject->group">
                                                            <span class="mt-3 text-black btn-icon-start">
                                                                <i data-acorn-icon="notebook-1" class="icon"
                                                                    data-acorn-size="15"></i>
                                                                {!! $teacherSubject->subject->resourceSubject->name !!}
                                                            </span>
                                                        </x-group.card>
                                                    @endif
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
                {{-- <div class="tab-pane fade @if (session('tab') === 'permits') active show @endif" id="permitsTab"
                    role="tabpanel">
                    <!-- Teachers Content Start -->
                    <h2 class="small-title">{{ __('Teachers') }}</h2>

                    <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                        <!-- Permits Buttons Start -->
                        <a type="button" data-bs-toggle="modal" data-bs-target="#addPermitTeacherModal"
                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                            <i data-acorn-icon="plus"></i>
                            <span>{{ __('Add permit') }}</span>
                        </a>
                        <!-- Permits Buttons End -->
                    </div>

                    <!-- Permits Table Start -->
                    <section class="card mb-5">
                        <div class="card-body">
                            <table class="data-table dataTable responsive stripe no-footer dtr-inline"
                                data-order='[[ 0, "asc" ]]' logro="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-muted text-small text-uppercase p-0 pb-2">
                                            {{ __('short description') }}</th>
                                        <th class="text-muted text-small text-uppercase p-0 pb-2">
                                            {{ __('date range') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teacher->permits as $permit)
                                        <tr>
                                            <td>{{ $permit->description }}</td>
                                            <td>{{ $permit->dateRange() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <!-- Permits Table End -->
                </div> --}}
                <!-- Permits Tab End -->

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
                                                    <a target="_blank" title="{{ __('Download') }}" href="{{ $permit->url }}"><i data-acorn-icon="download" data-acorn-size="14"></i></a>
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
