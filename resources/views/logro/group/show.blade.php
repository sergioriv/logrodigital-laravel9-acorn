@php
    $title = $group->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/select2.js"></script>
    <script>
        jQuery('[modal-period-permit]').click(function() {
            let subjectId = $(this).data('subject-id');

            if (subjectId) {
                $('#subject-permit-id').val(subjectId);
                $('#addPeriodPermit').modal('show');
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
                        <div class="row">

                            <!-- Title Start -->
                            <div class="col-12 col-md-7">
                                <h1 class="mb-1 pb-0 display-4">{{ __('Group') . ' | ' . $title }}</h1>
                                <div aria-label="breadcrumb">
                                    <div class="breadcrumb">
                                        <span class="breadcrumb-item text-muted">
                                            <div class="text-muted d-inline-block">
                                                <i data-acorn-icon="building-large" class="me-1" data-acorn-size="12"></i>
                                                <span class="align-middle">{{ $group->headquarters->name }}</span>
                                            </div>
                                        </span>
                                        <span class="breadcrumb-item text-muted">
                                            <div class="text-muted d-inline-block">
                                                <i data-acorn-icon="clock" class="me-1" data-acorn-size="12"></i>
                                                <span class="align-middle">{{ $group->studyTime->name }}</span>
                                            </div>
                                        </span>
                                        <span class="breadcrumb-item text-muted">
                                            <div class="text-muted d-inline-block">
                                                <i data-acorn-icon="calendar" class="me-1" data-acorn-size="12"></i>
                                                <span class="align-middle">{{ $group->studyYear->name }}</span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Title End -->

                            @can('groups.create')
                                <!-- Top Buttons Start -->
                                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                                    <!-- Edit Name Button Start -->
                                    <a href="{{ route('group.edit', $group) }}"
                                        class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                        <i data-acorn-icon="edit-square"></i>
                                        <span>{{ __('Edit') }}</span>
                                    </a>
                                    <!-- Edit Name Button End -->
                                </div>
                                <!-- Top Buttons End -->
                            @endcan
                        </div>

                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">
                    <div class="row">

                        <!-- Right Side Start -->
                        <div class="col-12">
                            <!-- Title Tabs Start -->
                            <ul class="nav nav-tabs nav-tabs-title nav-tabs-line-title responsive-tabs" role="tablist">
                                @can('groups.students')
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#studentsTab" role="tab"
                                            aria-selected="true">{{ __('Students') }} ({{ $group->student_quantity }})</a>
                                    </li>
                                @endcan
                                @can('groups.teachers')
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#subjectsTab" role="tab"
                                            aria-selected="true">{{ __('Subjects') . ' & ' . __('Teachers') }}</a>
                                    </li>
                                @endcan
                            </ul>
                            <!-- Title Tabs End -->

                            <div class="tab-content">

                                <!-- Students Tab Start -->
                                <div class="tab-pane fade active show" id="studentsTab" role="tabpanel">

                                    @can('groups.students.matriculate')
                                        <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                                            @if (null !== $Y->available)
                                                @if ($count_studentsNoEnrolled > 0)
                                                    <!-- Groups Buttons Start -->
                                                    <div class="col-12 d-flex align-items-start justify-content-end">
                                                        <!-- Matriculate Students Button Start -->
                                                        <a href="{{ route('group.matriculate', $group) }}"
                                                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                                            <i data-acorn-icon="edit-square"></i>
                                                            <span>{{ __('Matriculate students') }}</span>
                                                        </a>
                                                        <!-- Matriculate Students Button End -->
                                                    </div>
                                                    <!-- Groups Buttons End -->
                                                @endif
                                            @endif
                                        </div>
                                    @endcan

                                    <!-- Students Content Tab Start -->
                                    <section class="scroll-section">
                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table table-striped mb-0">
                                                    <tbody>
                                                        @foreach ($studentsGroup as $studentG)
                                                            <tr>
                                                                <td scope="row">
                                                                    @can('students.info')
                                                                        <a href="{{ route('students.show', $studentG) }}"
                                                                            class="list-item-heading body">
                                                                            {{ $studentG->getLastNames() . ' ' . $studentG->getNames() }}
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ route('students.view', $studentG) }}"
                                                                            class="list-item-heading body">
                                                                            {{ $studentG->getLastNames() . ' ' . $studentG->getNames() }}
                                                                        </a>
                                                                    @endcan

                                                                    @if (1 === $studentG->inclusive)
                                                                        <span
                                                                            class="badge bg-outline-warning">{{ __('inclusive') }}</span>
                                                                    @endif
                                                                    @if ('new' === $studentG->status)
                                                                        <span
                                                                            class="badge bg-outline-primary">{{ __($studentG->status) }}</span>
                                                                    @elseif ('repeat' === $studentG->status)
                                                                        <span
                                                                            class="badge bg-outline-danger">{{ __($studentG->status) }}</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </section>
                                    <!-- Students Content Tab End -->
                                </div>
                                <!-- Students Tab End -->

                                @can('groups.teachers.edit')
                                    <!-- Groups Tab Start -->
                                    <div class="tab-pane fade" id="subjectsTab" role="tabpanel">

                                        @if (null !== $Y->available)
                                            <!-- Groups Buttons Start -->
                                            <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                                                @if ($areas->count() !== 0)
                                                    <!-- Add New Button Start -->
                                                    <a href="{{ route('group.teachers.edit', $group) }}"
                                                        class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                                        <i data-acorn-icon="edit-square"></i>
                                                        <span>{{ __('Edit') . ' ' . __('Teachers') }}</span>
                                                    </a>
                                                    <!-- Add New Button End -->
                                                @else
                                                    <!-- Assing Teachers Button Start -->
                                                    <a href="{{ route('studyYear.subject.show', $group->studyYear) }}"
                                                        class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                                        <i data-acorn-icon="edit-square"></i>
                                                        <span>{{ __('Assign') . ' ' . __('Subjects') . ' ' . $group->studyYear->name }}</span>
                                                    </a>
                                                    <!-- Assing Teachers Button End -->
                                                @endif

                                            </div>
                                            <!-- Groups Buttons End -->
                                        @endif

                                        <!-- Groups Content Tab Start -->
                                        <section class="scroll-section">
                                            @foreach ($areas as $area)
                                                <div class="card d-flex mb-2">
                                                    <div class="card-body">
                                                        <h2 class="small-title">{{ $area->name }}</h2>
                                                        <table class="table table-striped mb-0">
                                                            <tbody>
                                                                @foreach ($area->subjects as $subject)
                                                                    @php $TSG = \App\Http\Controllers\TeacherSubjectGroupController::forSubject($group->id, $subject->id) @endphp
                                                                    <tr>
                                                                        <td scope="row">
                                                                            {!! $subject->resourceSubject->name !!}
                                                                        </td>
                                                                        <td>
                                                                            @if ($TSG)
                                                                                {{ $TSG->teacher->getFullName() ?? null }}
                                                                            @endif
                                                                        </td>
                                                                        <td class="col-1 text-center">
                                                                            {{ $subject->academicWorkload->hours_week }}
                                                                            @if (1 === $subject->academicWorkload->hours_week)
                                                                                {{ __('hour') }}
                                                                            @else
                                                                                {{ __('hours') }}
                                                                            @endif
                                                                        </td>
                                                                        <td class="col-1 text-center">
                                                                            {{ $subject->academicWorkload->course_load }}%
                                                                        </td>
                                                                        @hasrole('COORDINATOR')
                                                                            <td class="col-1 text-end">
                                                                                <!-- Dropdown Button Start -->
                                                                                <div class="ms-1">
                                                                                    <button type="button"
                                                                                        class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                                                        data-bs-offset="0,3"
                                                                                        data-bs-toggle="dropdown"
                                                                                        aria-haspopup="true" aria-expanded="false"
                                                                                        data-submenu>
                                                                                        <i data-acorn-icon="more-vertical"></i>
                                                                                    </button>
                                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                                        <x-dropdown-item type="button"
                                                                                            modal-period-permit
                                                                                            data-subject-id="{{ $TSG->id }}">
                                                                                            {{-- <i data-acorn-icon="download"></i> --}}
                                                                                            <span>{{ __('Activate note upload') }}</span>
                                                                                        </x-dropdown-item>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Dropdown Button End -->
                                                                            </td>
                                                                        @endhasrole
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </section>
                                        <!-- Groups Content Tab End -->
                                    </div>
                                    <!-- Groups Tab End -->
                                @endcan

                            </div>
                        </div>
                        <!-- Right Side End -->
                    </div>
                </section>
            </div>
        </div>
    </div>

    @hasrole('COORDINATOR')
        <!-- Modal Period Permit -->
        <div class="modal fade" id="addPeriodPermit" aria-labelledby="modalPeriodPermit" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPeriodPermit">
                            {{ __('Activate note upload') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('period.permit') }}" id="addPeriodPermitForm" method="POST">
                        @csrf

                        <input type="hidden" id="subject-permit-id" name="subject-permit-id" value="">

                        <div class="modal-body">

                            <div class="alert alert-info">
                                <i data-acorn-icon="notification"></i>
                                {{ __('Extemporaneous uploading of grades for the assigned teacher') }}
                            </div>

                            <div class="form-group">

                                <x-label>{{ __('Period') }}</x-label>
                                <select logro='select2' name="period-permit">
                                    <option label="&nbsp;"></option>
                                    @foreach ($periods as $period)
                                    <option value="{{ $period->id }}">
                                        {{ $period->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endhasrole
@endsection
