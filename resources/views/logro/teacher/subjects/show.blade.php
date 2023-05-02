@php
    $title = $subject->subject->resourceSubject->public_name;

    $periodActive = $periods->filter(function ($p) {
        return $p->active();
    });
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
    @if ($periodActive->count() && $studyYear->useGrades())
    <script src="/js/pages/pasteGrades.js?d=1669929322185"></script>
    @endif
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>

    <script>
        jQuery("[absences='view']").click(function() {
            let attendance = $(this).attr('attendance-id');
            $.get(HOST + '/attendance/absences', {
                attendance: attendance
            }, function(data) {
                $('#modalViewAbsences').html(data.title);
                $('#modalContentViewAbsences').html(data.content);
                $('#viewAbsences').modal('show');
            })
        });

        jQuery("[absences='edit']").click(function() {
            let attendance = $(this).attr('attendance-id');
            $.get(HOST + '/attendance/' + attendance + '/edit', null, function(content) {
                $('#editAttendance .modal-content').html(content);
                $('#editAttendance').modal('show');
            })
        });

        jQuery("[attendanceStudent].form-check-input").click(function() {

            var studentCode = $(this).data('code');

            $("#dropdown" + studentCode + " input").prop('checked', false);

            if ($(this).prop('checked')) {
                $('#dropdown' + studentCode).addClass('d-none');
            } else {
                $('#dropdown' + studentCode).removeClass('d-none');
            }
        });

        jQuery("#attendance-date").on('change', function() {
            $.get(HOST + '/mysubjects/{{ $subject->id }}/attendance-limit-week', {
                date: $(this).val()
            }, function(res) {
                if ( ! res.data.active ) {
                    $("form#addAttendanceForm button[type='submit']").prop("disabled", true);
                } else {
                    $("form#addAttendanceForm button[type='submit']").prop("disabled", false);
                }
                $("#alert-attendance").html(res.data.content);
            })
        });

        $(document).ready(function () {
            $.get(HOST + '/mysubjects/{{ $subject->id }}/attendance-limit-week', {
                date: $(this).val()
            }, function(res) {
                if ( ! res.data.active ) {
                    $("form#addAttendanceForm button[type='submit']").prop("disabled", true);
                } else {
                    $("form#addAttendanceForm button[type='submit']").prop("disabled", false);
                }
                $("#alert-attendance").html(res.data.content);
            })
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
                            <div class="col-12 col-md-7 mb-2 mb-md-0">
                                <h1 class="mb-1 pb-0 display-4">{{ __('Group') . ' | ' . $subject->group->name }}</h1>
                                <div aria-label="breadcrumb">
                                    <div class="breadcrumb">
                                        <span class="breadcrumb-item text-muted">
                                            <div class="text-muted d-inline-block">
                                                <i data-acorn-icon="building-large" class="me-1" data-acorn-size="12"></i>
                                                <span class="align-middle">{{ $subject->group->headquarters->name }}</span>
                                            </div>
                                        </span>
                                        <span class="breadcrumb-item text-muted">
                                            <div class="text-muted d-inline-block">
                                                <i data-acorn-icon="clock" class="me-1" data-acorn-size="12"></i>
                                                <span class="align-middle">{{ $subject->group->studyTime->name }}</span>
                                            </div>
                                        </span>
                                        <span class="breadcrumb-item text-muted">
                                            <div class="text-muted d-inline-block">
                                                <i data-acorn-icon="calendar" class="me-1" data-acorn-size="12"></i>
                                                <span class="align-middle">{{ $studyYear->name }}</span>
                                            </div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Title End -->

                            <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">

                                <span class="display-4 fw-bold">{{ $title }}</span>

                                <!-- Dropdown Button Start -->
                                <div class="ms-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                        data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false" data-submenu>
                                        <i data-acorn-icon="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item btn-icon btn-icon-start"
                                            href="{{ route('group.export.student-list', $subject->group) }}">
                                            <i data-acorn-icon="download"></i>
                                            <span>{{ __('Download student list') }}</span>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item"
                                            href="{{ route('teacher.subject.descriptors', [$subject->subject->resourceSubject->id, $studyYear->id]) }}">
                                            <span>{{ __('Descriptors') }}</span>
                                        </a>
                                    </div>
                                </div>
                                <!-- Dropdown Button End -->
                            </div>

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
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#studentsTab" role="tab"
                                        aria-selected="true">{{ __('Students') }}
                                        ({{ $studentsGroup->count() }})</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#periodsTab" role="tab"
                                        aria-selected="false">{{ __('Periods') }}</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#attendanceTab" role="tab"
                                        aria-selected="false">{{ __('Attendance') }}</a>
                                </li>
                            </ul>
                            <!-- Title Tabs End -->

                            <div class="tab-content">

                                <!-- Students Tab Start -->
                                <div class="tab-pane fade active show" id="studentsTab" role="tabpanel">

                                    <!-- Students Content Tab Start -->
                                    <section class="scroll-section">
                                        <div class="card">
                                            <div class="card-body pt-2">
                                                <table class="table table-striped mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th>&nbsp;</th>
                                                            <th
                                                                class="text-center text-muted text-small text-uppercase p-0 pb-2">
                                                                {{ __('absences') }}</th>

                                                            @if ($studyYear->useGrades())
                                                            @foreach ($periods as $period)
                                                                <th
                                                                    class="text-center text-muted text-small text-uppercase p-0 pb-2">
                                                                    {{ 'P' . $period->ordering }}
                                                                </th>
                                                            @endforeach
                                                            <th
                                                                class="text-center text-muted text-small text-uppercase p-0 pb-2">
                                                                {{ __('Definitive') }}</th>
                                                            <th
                                                                class="text-center text-muted text-small text-uppercase p-0 pb-2">
                                                                {{ __('Performance') }}</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($studentsGroup as $studentG)
                                                            <tr>
                                                                <!-- Student Name -->
                                                                <td scope="row">
                                                                    <a href="{{ route('students.show', $studentG) }}"
                                                                        class="list-item-heading body">
                                                                        {{ $studentG->getCompleteNames() }}
                                                                    </a>
                                                                    {!! $studentG->tag() !!}
                                                                </td>

                                                                <!-- Absences Student -->
                                                                <td class="text-center text-small">
                                                                    {{ $studentG->attendance_student_count ?: null }}
                                                                </td>

                                                                @if ($studyYear->useGrades())

                                                                <!-- Grade periods -->
                                                                @foreach ($periods as $period)
                                                                @php
                                                                    $studentGradePeriod = $studentG->grades->filter(function ($Sgrade) use ($period) {
                                                                        return $Sgrade->period->id == $period->id;
                                                                    })->first();
                                                                @endphp
                                                                    <td class="text-center text-small">
                                                                        {{ $studentGradePeriod->final ?? null }}
                                                                    </td>
                                                                @endforeach
                                                                <!-- Definitive Grade -->
                                                                <td class="text-center text-small">
                                                                    {{ $studentG?->finalGrade['definitive'] ?: null }}
                                                                </td>

                                                                <!-- Performance Definitive Grade -->
                                                                <td class="text-center text-small text-capitalize">
                                                                    @if ($studentG?->finalGrade['definitive'])
                                                                    {!! $studentG?->finalGrade['performance'] !!}
                                                                    @endif
                                                                </td>
                                                                @endif
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

                                <!-- Periods Tab Start -->
                                <div class="tab-pane fade" id="periodsTab" role="tabpanel">

                                    <section class="scroll-section mb-n2" id="periodsCard">

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
                                                                        <div class="icon-14">{{ $period->workload }}%
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                    {{ __('Start date') }}<br /><b>{{ $period->startLabel() }}</b>
                                                                </div>
                                                                <div
                                                                    class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                    {{ __('End date') }}<br /><b>{{ $period->endLabel() }}</b>
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
                                                    data-bs-parent="#periodsCard">
                                                    <div class="card-body accordion-content pt-0">

                                                        @if ($isActive)
                                                            @if ($studyYear->useGrades())
                                                                <div class="mb-3 d-flex justify-content-end">
                                                                    <x-button type="button"
                                                                        class="btn-outline-primary btn-sm" id="clickPaste"
                                                                        data-period-id="{{ $period->id }}">
                                                                        {{ __('Paste values from Excel') }}
                                                                    </x-button>
                                                                    <!-- Dropdown Button Start -->
                                                                    <div class="ms-1">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                                            data-bs-offset="0,3"
                                                                            data-bs-toggle="dropdown"
                                                                            aria-haspopup="true"
                                                                            aria-expanded="false" data-submenu>
                                                                            <i
                                                                                data-acorn-icon="more-vertical"></i>
                                                                        </button>
                                                                        <div
                                                                            class="dropdown-menu dropdown-menu-end">
                                                                            <a
                                                                                class="dropdown-item btn-icon btn-icon-start"
                                                                                href="{{ route('group.export.grades-instructive', $subject) }}"
                                                                            >
                                                                                <i data-acorn-icon="download"></i>
                                                                                <span class="ms-1">Descargar plantilla</span>
                                                                            </a>
                                                                            <x-dropdown-item type="button"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#importGrades-P{{ $period->id }}"
                                                                            >
                                                                                <i data-acorn-icon="upload"></i>
                                                                                <span class="ms-1">Cargar plantilla con notas</span>
                                                                            </x-dropdown-item>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Dropdown Button End -->

                                                                    <!-- Modal Edit Attendance -->
                                                                    <div class="modal fade modal-close-out" id="importGrades-P{{ $period->id }}" aria-labelledby="importGrades-P{{ $period->id }}Label" data-bs-backdrop="static"
                                                                        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="importGrades-P{{ $period->id }}Label">
                                                                                        {{ $period->name }} | Cargar plantilla con notas
                                                                                    </h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="card-body border-danger">
                                                                                        <div class="row g-3">
                                                                                            <div class="h3 text-danger text-center">Importante!</div>
                                                                                            <div>Las columnas requeridas son (<strong>codigo</strong>, <strong>nota</strong>)</div>
                                                                                            <div class="alert alert-light ps-0">
                                                                                                <ul class="mb-0">
                                                                                                    <li>
                                                                                                        <article class="font-weight-bold mb-2">
                                                                                                            Dentro del instructivo, encontrarán códigos únicos asignados a cada estudiante; estos códigos no deben ser reemplazados ni modificados para una correcta carga de notas.
                                                                                                        </article>
                                                                                                    </li>
                                                                                                    <li>
                                                                                                        <article class="font-weight-bold">
                                                                                                            En caso de tener notas con decimales, se recomienda utilizar comas (<span class="icon-24" style="line-height: 0.1;">,</span>).
                                                                                                        </article>
                                                                                                    </li>
                                                                                                </ul>
                                                                                            </div>
                                                                                            <div class="text-center">
                                                                                                <a
                                                                                                    class="btn btn-background hover-outline"
                                                                                                    href="{{ route('group.export.grades-instructive', $subject) }}">Descargar plantilla</a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <form method="POST" action="{{ route('group.import.subject-grades', [$subject, $period]) }}"
                                                                                        enctype="multipart/form-data"
                                                                                    >
                                                                                        @csrf
                                                                                        @method('PATCH')

                                                                                        <div class="d-flex align-items-end content-container gap-2">
                                                                                                <x-input type="file" class="d-block" name="grades_file" />
                                                                                                <x-button type="submit"
                                                                                                    class="btn-primary">
                                                                                                    Cargar plantilla con notas
                                                                                                </x-button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <form
                                                                action="{{ route('subject.qualify.students', $subject) }}"
                                                                method="POST" id="{{ $period->id }}"
                                                                class="qualify-period">
                                                                @csrf

                                                                <input type="hidden" name="period"
                                                                    value="{{ $period->id }}">
                                                        @endif

                                                        <table class="table table-striped mb-0">
                                                            <thead>
                                                                <tr class="text-small text-uppercase text-center">
                                                                    <th>&nbsp;</th>

                                                                    @if ($studyYear->useGrades())
                                                                        @if ($studyYear->useComponents())
                                                                        <th>{{ __('conceptual') }}<br />{{ $period->studyTime->conceptual }}%</th>
                                                                        <th>{{ __('procedural') }}<br />{{ $period->studyTime->procedural }}%</th>
                                                                        <th>{{ __('attitudinal') }}<br />{{ $period->studyTime->attitudinal }}%</th>
                                                                        @endif
                                                                    <th>{{ __('final') }}</th>
                                                                    @endif

                                                                    @if ($studyYear->useDescriptors())
                                                                    <th>&nbsp;</th>
                                                                    @endif
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $gradeNumber = 1; @endphp
                                                                @foreach ($studentsGroup as $studentG)
                                                                    @php
                                                                        if ($studyYear->useGrades()) {
                                                                            $studentGradeXPeriod = $studentG->grades->filter(function ($Sgrade) use ($period) {
                                                                                return $Sgrade->period->id == $period->id;
                                                                            })->first();
                                                                        }
                                                                    @endphp
                                                                    <tr>
                                                                        <!-- Student Names -->
                                                                        <td scope="row">
                                                                            <a href="{{ route('students.show', $studentG) }}"
                                                                                class="list-item-heading body">
                                                                                {{ $studentG->getCompleteNames() }}
                                                                            </a>
                                                                            {!! $studentG->tag() !!}
                                                                        </td>

                                                                        @if ($studyYear->useGrades())
                                                                            @if ($studyYear->useComponents())
                                                                            <!-- Conceptual grade -->
                                                                            <td scope="row" class="col-1">
                                                                                @if ($isActive)
                                                                                    <x-input type="number"
                                                                                        id="{{ $period->id }}-grade-{{ $gradeNumber }}"
                                                                                        min="{{ $period->studyTime->minimum_grade }}"
                                                                                        max="{{ $period->studyTime->maximum_grade }}"
                                                                                        step="{{ $period->studyTime->step }}"
                                                                                        name="students[{{ $studentG->code }}][conceptual]"
                                                                                        value="{{ $studentGradeXPeriod->conceptual ?? null }}" />
                                                                                @else
                                                                                    <div class="form-control bg-light">
                                                                                        {{ $studentGradeXPeriod->conceptual ?? null }}</div>
                                                                                @endif
                                                                            </td>

                                                                            <!-- Procedural grade -->
                                                                            <td scope="row" class="col-1">
                                                                                @if ($isActive)
                                                                                    <x-input type="number"
                                                                                        id="{{ $period->id }}-grade-{{ $gradeNumber + 1 }}"
                                                                                        min="{{ $period->studyTime->minimum_grade }}"
                                                                                        max="{{ $period->studyTime->maximum_grade }}"
                                                                                        step="{{ $period->studyTime->step }}"
                                                                                        name="students[{{ $studentG->code }}][procedural]"
                                                                                        value="{{ $studentGradeXPeriod->procedural ?? null }}" />
                                                                                @else
                                                                                    <div class="form-control bg-light">
                                                                                        {{ $studentGradeXPeriod->procedural ?? null }}</div>
                                                                                @endif
                                                                            </td>

                                                                            <!-- Attitudinal grade -->
                                                                            <td scope="row" class="col-1">
                                                                                @if ($isActive)
                                                                                    <x-input type="number"
                                                                                        id="{{ $period->id }}-grade-{{ $gradeNumber + 2 }}"
                                                                                        min="{{ $period->studyTime->minimum_grade }}"
                                                                                        max="{{ $period->studyTime->maximum_grade }}"
                                                                                        step="{{ $period->studyTime->step }}"
                                                                                        name="students[{{ $studentG->code }}][attitudinal]"
                                                                                        value="{{ $studentGradeXPeriod->attitudinal ?? null }}" />
                                                                                @else
                                                                                    <div class="form-control bg-light">
                                                                                        {{ $studentGradeXPeriod->attitudinal ?? null }}</div>
                                                                                @endif
                                                                            </td>
                                                                            @endif

                                                                        <!-- Final grade -->
                                                                        <td scope="row" class="col-1">
                                                                            @if ($isActive && ! $studyYear->useComponents())
                                                                                <x-input type="number"
                                                                                    id="{{ $period->id }}-grade-{{ $gradeNumber }}"
                                                                                    min="{{ $period->studyTime->minimum_grade }}"
                                                                                    max="{{ $period->studyTime->maximum_grade }}"
                                                                                    step="{{ $period->studyTime->step }}"
                                                                                    name="students[{{ $studentG->code }}][final]"
                                                                                    value="{{ $studentGradeXPeriod->final ?? null }}" />
                                                                            @else
                                                                                <div class="form-control bg-light">
                                                                                    {{ $studentGradeXPeriod->final ?? null }}</div>
                                                                            @endif
                                                                        </td>
                                                                        @endif

                                                                        @if ($studyYear->useDescriptors())
                                                                        <!-- Descriptors -->
                                                                        <td class="text-end">

                                                                            @php
                                                                                if (is_null($studentG->inclusive)) {
                                                                                    $descriptorsFor = $descriptors->where('period', $period->ordering);
                                                                                } else {
                                                                                    $descriptorsFor = $descriptorsInclusive->where('period', $period->ordering);
                                                                                }
                                                                            @endphp

                                                                            @if ($isActive)
                                                                                @if ( ! count($descriptorsFor) )
                                                                                    <div
                                                                                        class="btn btn-sm text-light border-light btn-icon btn-icon-only cursor-default">
                                                                                        <i data-acorn-icon="more-vertical"></i>
                                                                                    </div>
                                                                                @else
                                                                                    <!-- Dropdown Button Start -->
                                                                                    <div>
                                                                                        <button type="button"
                                                                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                                                            data-bs-offset="0,3"
                                                                                            data-bs-toggle="dropdown"
                                                                                            aria-haspopup="true"
                                                                                            aria-expanded="false" data-submenu>
                                                                                            <i
                                                                                                data-acorn-icon="more-vertical"></i>
                                                                                        </button>
                                                                                        <div
                                                                                            class="dropdown-menu dropdown-menu-end">
                                                                                            <x-dropdown-item type="button"
                                                                                                data-bs-toggle="modal"
                                                                                                data-bs-target="#modalDescriptors-P{{ $period->id }}-STUDENT{{ $studentG->id }}">
                                                                                                <span>{{ __('Add descriptors') }}</span>
                                                                                            </x-dropdown-item>
                                                                                        </div>
                                                                                    </div>
                                                                                    <!-- Dropdown Button End -->

                                                                                    <!-- Modal Period Remark Start -->
                                                                                    <div class="modal fade modal-close-out"
                                                                                        id="modalDescriptors-P{{ $period->id }}-STUDENT{{ $studentG->id }}"
                                                                                        aria-labelledby="modalTitleDescriptors-P{{ $period->id }}-STUDENT{{ $studentG->id }}"
                                                                                        data-bs-backdrop="static"
                                                                                        data-bs-keyboard="false"
                                                                                        tabindex="-1" aria-hidden="true">
                                                                                        <div
                                                                                            class="modal-dialog modal-dialog-centered modal-lg">
                                                                                            <div class="modal-content">
                                                                                                <div
                                                                                                    class="modal-header text-start">
                                                                                                    <h5 class="modal-title"
                                                                                                        id="modalTitleDescriptors-P{{ $period->id }}-STUDENT{{ $studentG->id }}">
                                                                                                        {{ __('Descriptors') }}
                                                                                                        -
                                                                                                        {{ $studentG->getCompleteNames() }}
                                                                                                    </h5>
                                                                                                    <button type="button"
                                                                                                        class="btn-close"
                                                                                                        data-bs-dismiss="modal"
                                                                                                        aria-label="Close"></button>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <table
                                                                                                        {{-- logro="dataTableBoxed" --}}
                                                                                                        {{-- class="data-table responsive stripe dataTable no-footer dtr-inline" --}}
                                                                                                        class="table table-striped mb-0">
                                                                                                        <tbody>
                                                                                                            @foreach ($descriptorsFor as $descriptor)
                                                                                                                @php
                                                                                                                    $descriptorChecked =
                                                                                                                        $studentG->studentDescriptors
                                                                                                                            ->filter(function ($filter) use ($descriptor) {
                                                                                                                                return $filter->descriptor_id == $descriptor->id;
                                                                                                                            })
                                                                                                                            ->first() ?? false;
                                                                                                                @endphp

                                                                                                                <tr>
                                                                                                                    <td
                                                                                                                        class="text-alternate col-1">
                                                                                                                        <div
                                                                                                                            class="form-check ms-2 mb-0">
                                                                                                                            <input
                                                                                                                                class="form-check-input"
                                                                                                                                logro="studentCheck"
                                                                                                                                type="checkbox"
                                                                                                                                name="students[{{ $studentG->code }}][descriptors][]"
                                                                                                                                id="P{{ $period->id }}-student{{ $studentG->id }}-descriptor{{ $descriptor->id }}"
                                                                                                                                value="{{ $descriptor->id }}"
                                                                                                                                @checked($descriptorChecked)>
                                                                                                                        </div>
                                                                                                                    </td>
                                                                                                                    <td
                                                                                                                        class="text-alternate text-start">
                                                                                                                        <label
                                                                                                                            for="P{{ $period->id }}-student{{ $studentG->id }}-descriptor{{ $descriptor->id }}">{{ $descriptor->content }}</label>
                                                                                                                    </td>
                                                                                                                </tr>
                                                                                                            @endforeach
                                                                                                        </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endif
                                                                        </td>
                                                                        @endif
                                                                    </tr>
                                                                    @if ($isActive)
                                                                        @php
                                                                            $gradeNumber = $studyYear->useComponents() ? $gradeNumber + 3 : $gradeNumber + 1;
                                                                        @endphp
                                                                    @endif
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

                                <!-- Attendance Tab Start -->
                                <div class="tab-pane fade" id="attendanceTab" role="tabpanel">

                                    <!-- Groups Buttons Start -->
                                    <div class="row d-flex align-items-start justify-content-between">
                                        <div class="col-12 text-md-end text-center">

                                            <!-- Add Attendance Button Start -->
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#addAttendance"
                                                class="btn btn-primary">
                                                <span>{{ __('Take attendance') }}</span>
                                            </button>
                                            <!-- Add Attendance Button End -->
                                        </div>
                                    </div>
                                    <!-- Groups Buttons End -->

                                    <section class="scroll-section mt-2">
                                        <div class="card">
                                            @if (!$attendances->isEmpty())
                                                <div class="card-body pt-2">
                                                    <table class="table table-striped mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                                    {{ __('date') }}</th>
                                                                <th
                                                                    class="text-center text-muted text-small text-uppercase p-0 pb-2">
                                                                    {{ __('absences') }}</th>
                                                                <th class="empty ps-spacing-sm pe-0">&nbsp;</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($attendances as $attendance)
                                                                <tr>
                                                                    <td scope="row" class="text-capitalize">
                                                                        {{ $attendance->dateLabel() }}</td>
                                                                    <td class="text-center">
                                                                        {{ $attendance->absences_count }}</td>
                                                                    <td class="text-end">
                                                                        <div
                                                                            class="d-flex align-items-start justify-content-end">
                                                                            @if ($attendance->absences_count)
                                                                                <x-button
                                                                                    class="btn-sm btn-outline-primary"
                                                                                    absences='view'
                                                                                    attendance-id="{{ $attendance->id }}">
                                                                                    {{ __('see absences') }}</x-button>
                                                                            @endif

                                                                            <!-- Dropdown Button Start -->
                                                                            <div class="ms-2">
                                                                                <button type="button"
                                                                                    class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                                                    data-bs-offset="0,3"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-haspopup="true"
                                                                                    aria-expanded="false" data-submenu>
                                                                                    <i
                                                                                        data-acorn-icon="more-horizontal"></i>
                                                                                </button>
                                                                                <div
                                                                                    class="dropdown-menu dropdown-menu-end">
                                                                                    <div class="dropdown-item">
                                                                                        <div absences='edit'
                                                                                            attendance-id="{{ $attendance->id }}">
                                                                                            {{ __('Edit') }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Dropdown Button End -->
                                                                            </div>

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                        </div>
                                    </section>

                                </div>
                                <!-- Attendance Tab End -->

                            </div>
                        </div>
                        <!-- Right Side End -->
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- Modal Add Attendance -->
    <div class="modal fade" id="addAttendance" aria-labelledby="modalAddAttendance" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddAttendance">
                        {{ __('Take attendance') . ': ' . $subject->group->name . ' | ' . $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('attendance.subject', $subject) }}" id="addAttendanceForm" method="POST">
                    @csrf

                    <div class="modal-body">

                        <div id="alert-attendance"></div>

                        <div class="row mb-3 position-relative">
                            <x-label for="date"
                                class="col-sm-3 col-form-label text-sm-start text-center font-weight-bold text-danger"
                                required>{{ __('Choose date') }}</x-label>
                            <div class="col-sm-9">
                                <x-input :value="old('date', now()->format('Y-m-d'))" id="attendance-date" logro="datePickerBefore" name="date"
                                    data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" class="text-center"
                                    required />
                            </div>
                        </div>

                        <table class="table table-striped mb-0">
                            <tbody>
                                @foreach ($studentsGroup as $studentG)
                                    <tr>
                                        <td>
                                            <label class="form-check custom-icon mb-0 unchecked-opacity-25">
                                                <input type="checkbox" class="form-check-input"
                                                    name="studentsAttendance[{{ $studentG->code }}]" value="1"
                                                    attendanceStudent data-code="{{ $studentG->code }}" checked>
                                                <span class="form-check-label">
                                                    <span class="content">
                                                        <span class="heading mb-1 d-block lh-1-25">
                                                            {{ $studentG->getCompleteNames() }}
                                                            <x-tag-student :student="$studentG" />
                                                        </span>
                                                    </span>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <!-- Dropdown Button Start -->
                                            <div id="dropdown{{ $studentG->code }}" class="d-none">
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                    data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" data-bs-auto-close="inside">
                                                    <i class="icon bi-three-dots-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <div class="dropdown-item">
                                                        <label class="form-label cursor-pointer">
                                                            <input type="radio"
                                                                name="studentsAttendance[{{ $studentG->code }}][type]"
                                                                value="late-arrival" />
                                                            {{ __('Late arrival') }}
                                                        </label>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <label class="form-label cursor-pointer">
                                                            <input type="radio"
                                                                name="studentsAttendance[{{ $studentG->code }}][type]"
                                                                value="justified" />
                                                            {{ __('Justified') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Dropdown Button End -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

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

    <!-- Modal View Absences -->
    <div class="modal fade" id="viewAbsences" aria-labelledby="modalViewAbsences" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalViewAbsences"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalContentViewAbsences"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Attendance -->
    <div class="modal fade" id="editAttendance" aria-labelledby="modalEditAbsences" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection
