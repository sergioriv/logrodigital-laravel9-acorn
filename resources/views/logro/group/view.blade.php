@php
    $title = $group->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/progressbar.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>
    <script src="/js/forms/select2.js"></script>
    <script src="/js/plugins/progressbars.js"></script>
    <script>

        jQuery('#openModelGenerateGradeReport').click(function() {
            $('button#btn-generateGradeReport').prop('disabled', false);
        });

        jQuery('#openModelConsolidateGradeReport').click(function() {
            $('button#btn-consolidateGradeReport').prop('disabled', false);
        });

        let attendanceFileModal = $('#addAttendanceFile');
        function attendanceFile(attendanceId, studentId) {
            $('#attendance-file-id').val(attendanceId);
            $('#attendance-file-student').val(studentId);
            attendanceFileModal.modal('show');
        }

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

                            <!-- Top Buttons Start -->
                            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">

                                <!-- Dropdown Button Start -->
                                <div class="ms-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                        data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" data-submenu>
                                        <i data-acorn-icon="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item btn-sm btn-icon btn-icon-start"
                                            href="{{ route('group.export.student-list', $group) }}">
                                            <i data-acorn-icon="download" class="me-1"></i>
                                            <span>{{ __('Student list') }}</span>
                                        </a>
                                        <a class="dropdown-item btn-sm btn-icon btn-icon-start"
                                            href="{{ route('group.export.information-student-list', $group) }}">
                                            <i data-acorn-icon="download" class="me-1"></i>
                                            <span>{{ __('Information general from student list') }}</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        @hasanyrole('SUPPORT|COORDINATOR|SECRETARY|TEACHER')
                                            @if (!$group->specialty)
                                                <a class="dropdown-item btn-sm btn-icon btn-icon-start" href="#"
                                                    id="openModelGenerateGradeReport" data-bs-toggle="modal"
                                                    data-bs-target="#generateGradeReport">
                                                    <i data-acorn-icon="file-text" class="me-1"></i>
                                                    <span>{{ __('Grade report') }}</span>
                                                </a>
                                            @endif
                                            @if ($group->studyYear->useGrades() && !$group->specialty)
                                                <a class="dropdown-item btn-sm btn-icon btn-icon-start" href="#"
                                                    id="openModelConsolidateGradeReport" data-bs-toggle="modal"
                                                    data-bs-target="#consolidateGradeReport">
                                                    <i data-acorn-icon="file-chart" class="me-1"></i>
                                                    <span>{{ __('Consolidation grades') }}</span>
                                                </a>
                                            @endif
                                        @endhasanyrole
                                        @hasanyrole('SUPPORT') {{-- |COORDINATOR --}}
                                        <div class="dropdown-divider"></div>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#openModelSendMailTutors"
                                        class="dropdown-item btn-sm btn-icon btn-icon-start">
                                            <i data-acorn-icon="email" class="me-1"></i>
                                            <span>Enviar correo a acudientes</span>
                                        </a>
                                        @endhasanyrole
                                    </div>
                                </div>
                                <!-- Dropdown Button End -->
                            </div>
                            <!-- Top Buttons End -->
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
                                            aria-selected="true">{{ __('Students') }} ({{ $studentsGroup->count() }})</a>
                                    </li>
                                @endcan
                                @can('groups.teachers')
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#subjectsTab" role="tab"
                                            aria-selected="true">{{ __('Subjects') . ' & ' . __('Teachers') }}</a>
                                    </li>
                                @endcan
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-capitalize" data-bs-toggle="tab" href="#summaryTab"
                                        role="tab" aria-selected="true">{{ __('summary') }}</a>
                                </li>
                            </ul>
                            <!-- Title Tabs End -->

                            <div class="tab-content">

                                <!-- Students Tab Start -->
                                <div class="tab-pane fade active show" id="studentsTab" role="tabpanel">

                                    <!-- Students Content Tab Start -->
                                    <section class="scroll-section">

                                        <div class="card">
                                            <div class="card-body">
                                                <table class="table table-striped mb-0">
                                                    <tbody>
                                                        @foreach ($studentsGroup as $studentG)
                                                            <tr>
                                                                <td scope="row">
                                                                    <a href="{{ route('students.show', $studentG) }}"
                                                                        class="list-item-heading body">
                                                                        {{ $studentG->getCompleteNames() }}
                                                                    </a>

                                                                    {!! $studentG->tag() !!}
                                                                </td>
                                                                <td>
                                                                    @if (is_null($group->specialty) && !is_null($studentG->groupOfSpecialty))
                                                                        {{ $studentG->groupOfSpecialty->groupSpecialty->name }}
                                                                    @elseif (!is_null($group->specialty) && !is_null($studentG->groupOfPrimary))
                                                                        {{ $studentG->groupOfPrimary->groupPrimary->name }}
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        @can('groups.create')
                                            @if ($studentsGroup->isEmpty())
                                                <div class="text-start mt-3">
                                                    <x-button class="btn-outline-danger" type="button"
                                                        data-bs-toggle="modal" data-bs-target="#deleteGroupModal">
                                                        {{ __('Delete group') }}</x-button>
                                                </div>

                                                <!-- Modal Delete Group -->
                                                <div class="modal fade" id="deleteGroupModal"
                                                    aria-labelledby="modalDeleteGroup" data-bs-backdrop="static"
                                                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalDeleteGroup">
                                                                    {{ __('Delete group') }}</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form action="{{ route('groups.delete', $group) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')

                                                                <div class="modal-body">
                                                                    <p>
                                                                        {{ __('Are you sure you want to delete the group? Please note that the group will be permanently deleted.') }}
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-primary"
                                                                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                                    <button type="submit" id="btn-confirmDelete"
                                                                        class="btn btn-danger">{{ __('Confirm deletion') }}</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endcan

                                    </section>
                                    <!-- Students Content Tab End -->
                                </div>
                                <!-- Students Tab End -->

                                @can('groups.teachers')
                                    <!-- Subjects & Teachers Tab Start -->
                                    <div class="tab-pane fade" id="subjectsTab" role="tabpanel">

                                        <!-- Groups Content Tab Start -->
                                        <section class="scroll-section">
                                            @foreach ($areas as $area)
                                                <div class="card d-flex mb-2">
                                                    <div class="card-body">
                                                        <h2 class="small-title">{{ $area->name }}</h2>
                                                        <table class="table table-striped mb-0">
                                                            <tbody>

                                                                @foreach ($area->subjects as $subject)
                                                                    <tr>
                                                                        <td scope="row">
                                                                            {!! $subject->resourceSubject->nameSpecialty() !!}
                                                                        </td>
                                                                        <td>
                                                                            @unless(is_null($subject->teacherSubject))
                                                                                {{ $subject->teacherSubject?->teacher?->getFullName() }}
                                                                            @endunless
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
                                    <!-- Subjects & Teachers Tab End -->
                                @endcan


                                <!-- Summary Tab Start -->
                                <div class="tab-pane fade" id="summaryTab" role="tabpanel">

                                    <div class="row g-2">

                                        <!-- Absences Start -->
                                        <div class="col-12 mt-3 mb-5">
                                            <h2 class="small-title">{{ __('Absences') }}</h2>
                                            <div class="card">
                                                <div class="card-body">

                                                    <table logro='dataTableBoxed' data-order='[]'
                                                        class="table responsive stripe">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="text-muted text-small text-uppercase p-0 pb-2">
                                                                    {{ __('date') }}</th>
                                                                <th
                                                                    class="text-muted text-small text-uppercase p-0 pb-2">
                                                                    {{ __('subject') }}</th>
                                                                <th
                                                                    class="text-muted text-small text-uppercase p-0 pb-2">
                                                                    {{ __('student') }}</th>
                                                                <th
                                                                    class="text-muted text-small text-uppercase p-0 pb-2">
                                                                    {{ __('type') }}</th>
                                                                <th class="sw-5 empty">&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($absences as $absence)

                                                                <tr>
                                                                    <td>{{ $absence->attendance->dateLabel() }}</td>
                                                                    <td>{{ $absence->attendance->teacherSubjectGroup->subject->resourceSubject->name }}</td>
                                                                    <td>
                                                                        <a href="{{ route('students.show', $absence->student->id) }}"
                                                                            class="list-item-heading body">
                                                                            {{ $absence->student->getCompleteNames() }}
                                                                        </a>

                                                                        {!! $absence->student->tag() !!}
                                                                    </td>
                                                                    <td>{{ $absence->attend->getLabelText() }}</td>
                                                                    <td class="text-end">
                                                                        @if (in_array($absence->attend->value, ['J','L']))
                                                                        <!-- Dropdown Button Start -->
                                                                        <div class="">
                                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                                                data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                                                aria-expanded="false" data-submenu>
                                                                                <i data-acorn-icon="more-vertical"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                                @if (is_null($absence->file_support))
                                                                                <button class="dropdown-item btn-icon btn-icon-start" onclick="attendanceFile('{{ $absence->attendance_id }}','{{ $absence->student_id }}')">
                                                                                    <i data-acorn-icon="upload"></i>
                                                                                    <span>Cargar documento soporte</span>
                                                                                </button>
                                                                                @else
                                                                                <a class="dropdown-item"
                                                                                    href="{{ config('app.url') .'/'. $absence->file_support }}"
                                                                                    target="_blank">Ver archivo soporte</a>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <!-- Dropdown Button End -->
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- Absences End -->

                                        <!-- Grade Point Average Start -->
                                        <div class="col-12 mb-5">
                                            <div class="card">
                                                <div
                                                    class="h-100 d-flex flex-column justify-content-between card-body align-items-center">
                                                    <div class="sw-13">
                                                        <div logro="progress" role="progressbar"
                                                            class="progress-bar-circle position-relative text-muted text-sm"
                                                            data-trail-color=""
                                                            aria-valuemax="{{ \App\Http\Controllers\GradeController::numberFormat($group->studyTime, $group->studyTime->maximum_grade) }}"
                                                            aria-valuenow="{{ \App\Http\Controllers\GradeController::numberFormat($group->studyTime, $avgGrade) }}"
                                                            data-hide-all-text="false" data-stroke-width="3"
                                                            data-trail-width="1" data-duration="0"></div>
                                                    </div>
                                                    <div
                                                        class="heading text-center mb-0 sh-8 d-flex align-items-center lh-1-25">
                                                        {{ __('Grade point average') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Grade Point Average End -->
                                    </div>

                                </div>
                                <!-- Summary Tab End -->

                            </div>
                        </div>
                        <!-- Right Side End -->
                    </div>
                </section>
            </div>
        </div>
    </div>


    @hasanyrole('SUPPORT|COORDINATOR|SECRETARY|TEACHER')
        @if (!$group->specialty)
            <!-- Modal Grades Report Start -->
            <div class="modal fade" id="generateGradeReport" aria-labelledby="modalGenerateGradeReport"
                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalGenerateGradeReport">
                                {{ __('Generate grade report') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('group.reportGrade', $group) }}" method="POST">
                            @csrf

                            <div class="modal-body">

                                <div class="form-group">
                                    <x-label>{{ __('select period') }}</x-label>
                                    <select logro='select2' name="periodGradeReport">
                                        <option value="FINAL">FINAL</option>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" id="btn-generateGradeReport"
                                    class="btn btn-primary">{{ __('Generate') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Grades Report End -->
        @endif
        @if ($group->studyYear->useGrades() && !$group->specialty)
            <!-- Modal Consolidate Report Start -->
            <div class="modal fade" id="consolidateGradeReport" aria-labelledby="modalConsolidateGradeReport"
                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalConsolidateGradeReport">
                                {{ __('Consolidation grades') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('group.consolidate-grades', $group) }}" method="POST">
                            @csrf

                            <div class="modal-body">

                                <div class="form-group">
                                    <x-label>{{ __('select period') }}</x-label>
                                    <select logro='select2' name="periodConsolidateGrades">
                                        <option value="FINAL">FINAL</option>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-danger"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" id="btn-consolidateGradeReport"
                                    class="btn btn-primary">{{ __('Generate') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Consolidate Report End -->
        @endif
    @endhasanyrole

    <!-- Modal Attendance File Start -->
    <div class="modal fade" id="addAttendanceFile" aria-labelledby="modalAttendanceFile" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAttendanceFile">
                        Cargar documento soporte
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('attendance.upload_file') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="attendance-file-id" name="attendance-file-id" value="">
                    <input type="hidden" id="attendance-file-student" name="attendance-file-student" value="">

                    <div class="modal-body">

                        <div class="form-group position-relative">
                            <x-label>{{ __('support document') }}<x-required /></x-label>
                            <input type="file" required class="d-block form-control" name="file_attendance" accept="image/jpg, image/jpeg, image/png, image/webp, application/pdf">
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
    <!-- Modal Attendance File End -->

@endsection
