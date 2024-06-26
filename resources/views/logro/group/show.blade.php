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
        jQuery('[modal-period-permit]').click(function() {
            let subjectId = $(this).data('subject-id');

            if (subjectId) {
                $('#subject-permit-id').val(subjectId);
                $('#addPeriodPermit').modal('show');
            }
        });

        jQuery('#openModelGenerateGradeReport').click(function() {
            $('button#btn-generateGradeReport').prop('disabled', false);
        });

        jQuery('#openModelConsolidateGradeReport').click(function() {
            $('button#btn-consolidateGradeReport').prop('disabled', false);
        });

        jQuery('[grades-view]').on('click', function () {
            let studentId = $(this).attr('grades-view');
            let modalStudentGradesString = "#modalStudentGrades";

            $.get(HOST + '/group/{{ $group->id }}/student/grades/view', {
                studentId: studentId
            }, function (data) {
                $(modalStudentGradesString + 'Label').html(data.title);
                $(modalStudentGradesString + 'Content').html(data.content);
                $(modalStudentGradesString).modal('show');
            })
        });


        let attendanceFileModal = $('#addAttendanceFile');
        function attendanceFile(attendanceId, studentId) {
            $('#attendance-file-id').val(attendanceId);
            $('#attendance-file-student').val(studentId);
            attendanceFileModal.modal('show');
        }

        let absenceChangeTypeModal = $('#absenceChangeTypeModal');
        function absenceChangeType(attendanceId, currentType) {
            $('#attendance-change-id').val(attendanceId);
            currentType === 'N' && $('#abcenseNewType_no').prop('checked', true);
            currentType === 'L' && $('#abcenseNewType_lateArrival').prop('checked', true);
            currentType === 'J' && $('#abcenseNewType_justified').prop('checked', true);
            absenceChangeTypeModal.modal('show');
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
                                @can('groups.create')
                                    <!-- Edit Name Button Start -->
                                    <a href="{{ route('group.edit', $group) }}"
                                        class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                        <i data-acorn-icon="edit-square"></i>
                                        <span>{{ __('Edit') }}</span>
                                    </a>
                                    <!-- Edit Name Button End -->
                                @endcan

                                <!-- Dropdown Button Start -->
                                <div class="ms-1">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                        data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" data-submenu>
                                        <i data-acorn-icon="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        @can('groups.students.matriculate')
                                            @unless($studentsGroup->isEmpty())
                                                @if (!$group->specialty)
                                                    <a class="dropdown-item btn-sm btn-icon btn-icon-start"
                                                        href="{{ route('group.transfer-students', $group) }}">
                                                        <i data-acorn-icon="destination" class="me-1"></i>
                                                        <span>{{ __('Transfer students') }}</span>
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                @endif
                                            @endunless
                                        @endcan
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
                                        <a class="dropdown-item btn-sm btn-icon btn-icon-start"
                                            href="{{ route('group.pdf.template-observations', $group) }}">
                                            <i data-acorn-icon="download" class="me-1"></i>
                                            <span>Plantilla de observadores</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item btn-sm btn-icon btn-icon-start"
                                            href="{{ route('group.export.attendance-control', $group) }}">
                                            <i data-acorn-icon="download" class="me-1"></i>
                                            <span>{{ __('Planilla auxiliar control de asistencia') }}</span>
                                        </a>
                                        @hasanyrole('SUPPORT|COORDINATOR|SECRETARY|TEACHER')
                                            @if (!$group->specialty && !$periods->isEmpty())
                                                <div class="dropdown-divider"></div>
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
                                        @if ($finishStudyTime && $periods->count())
                                        @hasanyrole('SUPPORT|COORDINATOR|SECRETARY')
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('group.finish', $group->id) }}"
                                        class="dropdown-item btn-sm btn-icon btn-icon-start">
                                            <i data-acorn-icon="flag" class="me-1"></i>
                                            <span>Cerrar grupo</span>
                                        </a>
                                        @endif
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
                                @hasrole('TEACHER')
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#remarksTab" role="tab"
                                            aria-selected="true">{{ __('Remarks') }}</a>
                                    </li>
                                @endhasrole
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-capitalize" data-bs-toggle="tab" href="#summaryTab"
                                        role="tab" aria-selected="true">{{ __('summary') }}</a>
                                </li>
                            </ul>
                            <!-- Title Tabs End -->

                            <div class="tab-content">

                                <!-- Students Tab Start -->
                                <div class="tab-pane fade active show" id="studentsTab" role="tabpanel">

                                    @can('groups.students.matriculate')
                                        <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                                            @if (null !== $Y->available)
                                                @if (
                                                    (is_null($group->specialty) && $count_studentsNoEnrolled > 0) ||
                                                        ($group->specialty && $count_studentsMatriculateInStudyYear > 0))
                                                    <!-- Groups Buttons Start -->
                                                    <div class="col-12 d-flex align-items-start justify-content-end">
                                                        <!-- Matriculate Students Button Start -->
                                                        <a href="{{ route('group.matriculate', $group) }}"
                                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
                                                                @hasanyrole('SUPPORT|COORDINATOR')
                                                                <td align="right">
                                                                    <!-- Dropdown Button Start -->
                                                                    <div class="ms-1 dropstart">
                                                                        <button type="button" class="btn btn-sm btn-icon btn-icon-only text-primary"
                                                                            data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                                            aria-expanded="false" data-submenu>
                                                                            <i data-acorn-icon="more-vertical"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu dropdown-menu-end">
                                                                            <div class="dropdown-item btn-sm btn-icon btn-icon-start cursor-pointer"
                                                                                grades-view="{{ $studentG->id }}"
                                                                            >
                                                                                <i data-acorn-icon="file-text"></i>
                                                                                <span>{{ __('Grades') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Dropdown Button End -->
                                                                </td>
                                                                @endhasanyrole
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

                                        @if (null !== $Y->available)
                                            <!-- Groups Buttons Start -->
                                            <div class="col-12 mb-2 d-flex align-items-start justify-content-end">

                                                @can('groups.teachers.edit')
                                                    @if ($areas->count() !== 0)
                                                        <!-- Add New Button Start -->
                                                        <a href="{{ route('group.teachers.edit', $group) }}"
                                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                                            <i data-acorn-icon="edit-square"></i>
                                                            <span>{{ __('Edit') . ' ' . __('Teachers') }}</span>
                                                        </a>
                                                        <!-- Add New Button End -->
                                                    @elseif ($group->specialty === true && $group->specialty_area_id === null)
                                                        <!-- Assing Area Specialty Button Start -->
                                                        <a href="{{ route('group.specialty', $group) }}"
                                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                                            <i data-acorn-icon="edit-square"></i>
                                                            <span>{{ __('Assign specialty area') }}</span>
                                                        </a>
                                                        <!-- Assing Area Specialty Button End -->
                                                    @else
                                                        <!-- Assing Teachers Button Start -->
                                                        <a href="{{ route('studyYear.subject.show', $group->studyYear) }}"
                                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                                            <i data-acorn-icon="edit-square"></i>
                                                            <span>{{ __('Assign') . ' ' . __('Subjects') . ' ' . $group->studyYear->name }}</span>
                                                        </a>
                                                        <!-- Assing Teachers Button End -->
                                                    @endif
                                                @endcan

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
                                                                        <td class="col-1 text-end">
                                                                            @canany(['groups.teachers.edit',
                                                                                'group.subject.period.active'])
                                                                                @if ($subject?->teacherSubject?->teacher)
                                                                                    <!-- Dropdown Button Start -->
                                                                                    <div class="ms-1">
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                                                            data-bs-offset="0,3"
                                                                                            data-bs-toggle="dropdown"
                                                                                            aria-haspopup="true"
                                                                                            aria-expanded="false" data-submenu>
                                                                                            <i data-acorn-icon="more-vertical"></i>
                                                                                        </button>
                                                                                        <div
                                                                                            class="dropdown-menu dropdown-menu-end">
                                                                                            @can('group.subject.period.active')
                                                                                                <x-dropdown-item type="button"
                                                                                                    modal-period-permit
                                                                                                    data-subject-id="{{ $subject->teacherSubject->id }}">
                                                                                                    <span>{{ __('Activate note upload') }}</span>
                                                                                                </x-dropdown-item>
                                                                                            @endcan
                                                                                            @can('groups.teachers.edit')
                                                                                                <x-dropdown-item type="button"
                                                                                                    :link="route(
                                                                                                        'group.export.student-list-guide',
                                                                                                        $subject->teacherSubject,
                                                                                                    )">
                                                                                                    <span>{{ __('Download auxiliary template') }}</span>
                                                                                                </x-dropdown-item>
                                                                                            @endcan
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- Dropdown Button End -->
                                                                                @endif
                                                                            @endcanany
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


                                @hasrole('TEACHER')
                                    <!-- Periods Tab Start -->
                                    <!-- Pera que el director de grupo pueda agregar las observaciones -->
                                    <div class="tab-pane fade" id="remarksTab" role="tabpanel">

                                        <section class="scroll-section mb-n2" id="remarksCard">

                                            @foreach ($periods as $period)
                                                @php $isActive = $period->active() || $period->permit ? TRUE : FALSE @endphp

                                                <div class="card d-flex mt-2">
                                                    <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                                        data-bs-target="#period-{{ $period->id }}" aria-expanded="true"
                                                        aria-controls="period-{{ $period->id }}">
                                                        <div class="card-body py-4">
                                                            <div class="list-item-heading p-0">
                                                                <div class="row g-2">
                                                                    <div class="col-md-6 text-md-start text-center">
                                                                        <div
                                                                            class="font-weight-bold h3 m-0 @if (!$isActive) text-light @endif">
                                                                            {{ $period->name }}
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                        {{ __('Start date') }}<br /><b>{{ $period->start }}</b>
                                                                    </div>
                                                                    <div
                                                                        class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                        {{ __('End date') }}<br /><b>{{ $period->end }}</b>
                                                                    </div>
                                                                    <div
                                                                        class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                        {{ __('Grades upload') }}<br /><b>{{ $period->dateUploadingNotes() }}</b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="period-{{ $period->id }}"
                                                        class="collapse @if ($isActive) show @endif"
                                                        data-bs-parent="#remarksCard">
                                                        <div class="card-body accordion-content pt-0">

                                                            @if ($isActive)
                                                                <form action="{{ route('remark.store', $group) }}"
                                                                    method="POST">
                                                                    @csrf

                                                                    <input type="hidden" name="period"
                                                                        value="{{ $period->id }}">
                                                            @endif

                                                            <table class="table table-striped mb-0">
                                                                <thead>
                                                                    <tr class="text-small text-uppercase text-center">
                                                                        <th>&nbsp;</th>
                                                                        <th>&nbsp;</th>
                                                                    </tr>
                                                                </thead>
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
                                                                            <td scope="row" class="col-1">
                                                                                <div class="form-control bg-light cursor-pointer"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#modalRemark-P{{ $period->id }}-STUDENT{{ $studentG->id }}">
                                                                                    {{ __('Remark') }}</div>

                                                                                <!-- Modal Period Remark Start -->
                                                                                <div class="modal fade"
                                                                                    id="modalRemark-P{{ $period->id }}-STUDENT{{ $studentG->id }}"
                                                                                    aria-labelledby="modalTitleRemark-P{{ $period->id }}-STUDENT{{ $studentG->id }}"
                                                                                    data-bs-backdrop="static"
                                                                                    data-bs-keyboard="false" tabindex="-1"
                                                                                    aria-hidden="true">
                                                                                    <div
                                                                                        class="modal-dialog modal-dialog-centered">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title"
                                                                                                    id="modalTitleRemark-P{{ $period->id }}-STUDENT{{ $studentG->id }}">
                                                                                                    {{ __('Remark') }} -
                                                                                                    {{ $studentG->getCompleteNames() }}
                                                                                                </h5>
                                                                                                <button type="button"
                                                                                                    class="btn-close"
                                                                                                    data-bs-dismiss="modal"
                                                                                                    aria-label="Close"></button>
                                                                                            </div>
                                                                                            <div class="modal-body">

                                                                                                @php
                                                                                                    $remarkStudent =
                                                                                                        $period->remarks
                                                                                                            ->filter(function ($remark) use ($studentG) {
                                                                                                                return $remark->student_id == $studentG->id;
                                                                                                            })
                                                                                                            ->first()->remark ?? null;
                                                                                                @endphp
                                                                                                @if ($isActive)
                                                                                                    <textarea name="remark[{{ $studentG->code }}]" class="form-control"
                                                                                                        placeholder="{{ __('Write your remark here') }}" rows="3">{{ $remarkStudent }}</textarea>
                                                                                                @else
                                                                                                    {{ $remarkStudent }}
                                                                                                @endif

                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button type="button"
                                                                                                    class="btn btn-outline-danger"
                                                                                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                                                                                                @if ($isActive)
                                                                                                    <button type="button"
                                                                                                        class="btn btn-primary"
                                                                                                        data-bs-dismiss="modal">
                                                                                                        {{ __('Save') }}</button>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Modal Period Remark End -->

                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>

                                                            @if ($isActive)
                                                                <div class="mt-4 d-flex justify-content-end">
                                                                    <x-button type="submit" class="btn-primary">
                                                                        {{ __('Save') }}</x-button>
                                                                </div>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </section>

                                    </div>
                                    <!-- Periods Tab End -->
                                @endhasrole

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
                                                                @hasanyrole('SUPPORT|COORDINATOR|TEACHER')
                                                                <th class="sw-5 empty">&nbsp;</th>
                                                                @endhasanyrole
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
                                                                    @hasanyrole('SUPPORT|COORDINATOR|TEACHER')
                                                                    <td class="text-end">
                                                                        @if (auth()->user()->hasRole('COORDINATOR')
                                                                            || auth()->user()->hasRole('SUPPORT')
                                                                            || (auth()->user()->hasRole('TEACHER') && $absence->attendance->teacherSubjectGroup->teacher_id === auth()->id())
                                                                        )
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
                                                                                <button class="dropdown-item btn-icon btn-icon-start" type="button" onclick="absenceChangeType('{{$absence->id}}','{{$absence->attend->value}}')">
                                                                                    <i data-acorn-icon="edit-square"></i>
                                                                                    <span>Cambiar de tipo</span>
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
                                                                    @endhasanyrole
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

    @can('group.subject.period.active')

        <!-- Modal Period Permit Start -->
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
        <!-- Modal Period Permit End -->

    @endcan

    @hasanyrole('SUPPORT|COORDINATOR|SECRETARY|TEACHER')
        @if (!$group->specialty && !$periods->isEmpty())
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
                                        <option label="&nbsp;"></option>
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}">
                                                {{ $period->name }}
                                            </option>
                                        @endforeach

                                        {{-- Para generar el reporte final --}}
                                        @if ($periods->count() === $countPeriods)
                                            <option value="FINAL">FINAL</option>
                                        @endif
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
                                        @foreach ($periods as $period)
                                            <option value="{{ $period->id }}" @selected($loop->first)>
                                                {{ $period->name }}
                                            </option>
                                        @endforeach

                                        {{-- Para generar el reporte final --}}
                                        @if ($periods->count() === $countPeriods)
                                            <option value="FINAL">FINAL</option>
                                        @endif
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

    @hasrole('SUPPORT|COORDINATOR')

        <!-- Modal Student Grades Start -->
        <div class="modal fade modal-close-out" id="modalStudentGrades" aria-labelledby="modalStudentGradesLabel" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalStudentGradesLabel">{{ __('Grades') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div id="modalStudentGradesContent"></div>
                </div>
            </div>
        </div>
        <!-- Modal Student Grades End -->

        <!-- Modal Send Email Tutors Start -->
        <div class="modal fade modal-close-out" id="openModelSendMailTutors" aria-labelledby="modalSendMailTutors" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalSendMailTutors">
                            {{ __('Activate note upload') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('send-mail.group', $group) }}" id="sendMailTutorsForm" method="POST">
                        @csrf

                        <div class="modal-body">

                            <div class="row g-2">

                                <!-- Subject Mail -->
                                <div class="col-12">
                                    <div class="form-group position-relative">
                                        <x-label required>{{ __('Email subject') }}</x-label>
                                        <x-input type="text" name="email_subject" maxlength="191" value="" required />
                                    </div>
                                </div>

                                <!-- Message Mail -->
                                <div class="col-12">
                                    <div class="form-group position-relative">
                                        <x-label required>{{ __('Email message') }}</x-label>
                                        <textarea rows="5" maxlength="3000" name="email_message" class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">
                                {{ __('Send') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal Send Email Tutors End -->

    @endhasrole

    @hasanyrole('SUPPORT|COORDINATOR|TEACHER')
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

    <!-- Modal Absence Change Type Start -->
    <div class="modal fade" id="absenceChangeTypeModal" aria-labelledby="modalAbsenceChangeType" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAbsenceChangeType">
                        Cambiar de tipo
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('attendance.student.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <input type="hidden" id="attendance-change-id" name="attendance-change-id" value="">

                    <div class="modal-body">

                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="attendance-new-type" id="abcenseNewType_yes" value="yes">
                                <label class="form-check-label" for="abcenseNewType_yes">Asistió</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="attendance-new-type" id="abcenseNewType_no" value="no">
                                <label class="form-check-label" for="abcenseNewType_no">No justificada</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="attendance-new-type" id="abcenseNewType_lateArrival" value="late-arrival">
                                <label class="form-check-label" for="abcenseNewType_lateArrival">Llegada tarde</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="attendance-new-type" id="abcenseNewType_justified" value="justified">
                                <label class="form-check-label" for="abcenseNewType_justified">Justificada</label>
                            </div>
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
    <!-- Modal Absence Change Type End -->
    @endhasanyrole
@endsection
