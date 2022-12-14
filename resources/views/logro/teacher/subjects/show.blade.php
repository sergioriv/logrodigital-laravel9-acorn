@php
    $title = $subject->subject->resourceSubject->public_name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
@endsection

@section('js_page')
    <script src="/js/pages/pasteGrades.js?d=1669929322185"></script>
    <script>
        jQuery("[absences='view']").click(function() {
            var attendance = $(this).attr('attendance-id');
            $.get(HOST + '/attendance/absences', {
                attendance: attendance
            }, function(data) {
                $('#modalViewAbsences').html(data.title);
                $('#modalContentViewAbsences').html(data.content);
                $('#viewAbsences').modal('show');
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
                                                <span class="align-middle">{{ $subject->group->studyYear->name }}</span>
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
                                        <a class="dropdown-item btn-icon btn-icon-start" href="{{ route('group.export.student-list', $subject->group) }}">
                                            <i data-acorn-icon="download"></i>
                                            <span>{{ __("Download student list") }}</span>
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
                                                            <th
                                                                class="text-center text-muted text-small text-uppercase p-0 pb-2">
                                                                {{ __('Definitive') }}</th>
                                                            <th
                                                                class="text-center text-muted text-small text-uppercase p-0 pb-2">
                                                                {{ __('Performance') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($studentsGroup as $studentG)
                                                            <tr>
                                                                <td scope="row">
                                                                    <a href="{{ route('students.view', $studentG) }}"
                                                                        class="list-item-heading body">
                                                                        {{ $studentG->getCompleteNames() }}
                                                                    </a>
                                                                    {!! $studentG->tag() !!}
                                                                </td>
                                                                <td class="text-center">
                                                                    {{ $studentG->attendance_student_count ?: null }}
                                                                </td>
                                                                <td class="text-center">
                                                                    @php $defStudent = \App\Http\Controllers\GradeController::forStudent($studentG->id, $subject) @endphp
                                                                    {{ $defStudent }}
                                                                </td>
                                                                <td class="text-center text-capitalize">
                                                                    {!! \App\Http\Controllers\GradeController::performance($subject->group->studyTimeSelectAll, $defStudent) !!}
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
                                                                        <div class="icon-14">{{ $period->workload }}%</div>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                    {{ __('Start date') }}<br /><b>{{ $period->start }}</b>
                                                                </div>
                                                                <div
                                                                    class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                    {{ __('Enabled as from') }}<br /><b>{{ $period->dateUploadingNotes() }}</b>
                                                                </div>
                                                                <div
                                                                    class="col-4 col-md-2 lh-base h6 m-0 text-center @if (!$isActive) text-light @endif">
                                                                    {{ __('Deadline date') }}<br /><b>{{ $period->end }}</b>
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
                                                            <div class="mb-3 d-flex justify-content-end">
                                                                <x-button type="button" class="btn-outline-primary btn-sm"
                                                                    id="clickPaste" data-period-id="{{ $period->id }}">
                                                                    {{ __('Paste values from Excel') }}
                                                                </x-button>
                                                            </div>

                                                            <form
                                                                action="{{ route('subject.qualify.students', $subject) }}"
                                                                method="POST" id="{{ $period->id }}"
                                                                class="qualify-period">
                                                                @csrf

                                                                <input type="hidden" name="period" value="{{ $period->id }}">
                                                        @endif

                                                        <table class="table table-striped mb-0">
                                                            <thead>
                                                                <tr class="text-small text-uppercase text-center">
                                                                    <th>&nbsp;</th>
                                                                    <th>{{ __('conceptual') }}<br />{{ $period->studyTime->conceptual }}%
                                                                    </th>
                                                                    <th>{{ __('procedural') }}<br />{{ $period->studyTime->procedural }}%
                                                                    </th>
                                                                    <th>{{ __('attitudinal') }}<br />{{ $period->studyTime->attitudinal }}%
                                                                    </th>
                                                                    <th>{{ __('final') }}<br />100%</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @php $gradeNumber = 1; @endphp
                                                                @foreach ($studentsGroup as $studentG)
                                                                    @php
                                                                        $GxPS = \App\Http\Controllers\GradeController::forPeriod($subject->id, $period->id, $studentG->id);
                                                                    @endphp

                                                                    <tr>
                                                                        <td scope="row">
                                                                            @can('students.info')
                                                                                <a href="{{ route('students.show', $studentG) }}"
                                                                                    class="list-item-heading body">
                                                                                    {{ $studentG->getCompleteNames() }}
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ route('students.view', $studentG) }}"
                                                                                    class="list-item-heading body">
                                                                                    {{ $studentG->getCompleteNames() }}
                                                                                </a>
                                                                            @endcan

                                                                            {!! $studentG->tag() !!}

                                                                        </td>
                                                                        <td scope="row" class="col-1">
                                                                            @if ($isActive)
                                                                                <x-input type="number"
                                                                                    id="{{ $period->id }}-grade-{{ $gradeNumber }}"
                                                                                    min="{{ $period->studyTime->minimum_grade }}"
                                                                                    max="{{ $period->studyTime->maximum_grade }}"
                                                                                    step="{{ $period->studyTime->step }}"
                                                                                    name="students[{{ $studentG->code }}][conceptual]"
                                                                                    value="{{ $GxPS->conceptual ?? null }}" />
                                                                            @else
                                                                                <div class="form-control bg-light">
                                                                                    {{ $GxPS->conceptual ?? null }}</div>
                                                                            @endif
                                                                        </td>
                                                                        <td scope="row" class="col-1">
                                                                            @if ($isActive)
                                                                                <x-input type="number"
                                                                                    id="{{ $period->id }}-grade-{{ $gradeNumber + 1 }}"
                                                                                    min="{{ $period->studyTime->minimum_grade }}"
                                                                                    max="{{ $period->studyTime->maximum_grade }}"
                                                                                    step="{{ $period->studyTime->step }}"
                                                                                    name="students[{{ $studentG->code }}][procedural]"
                                                                                    value="{{ $GxPS->procedural ?? null }}" />
                                                                            @else
                                                                                <div class="form-control bg-light">
                                                                                    {{ $GxPS->procedural ?? null }}</div>
                                                                            @endif
                                                                        </td>
                                                                        <td scope="row" class="col-1">
                                                                            @if ($isActive)
                                                                                <x-input type="number"
                                                                                    id="{{ $period->id }}-grade-{{ $gradeNumber + 2 }}"
                                                                                    min="{{ $period->studyTime->minimum_grade }}"
                                                                                    max="{{ $period->studyTime->maximum_grade }}"
                                                                                    step="{{ $period->studyTime->step }}"
                                                                                    name="students[{{ $studentG->code }}][attitudinal]"
                                                                                    value="{{ $GxPS->attitudinal ?? null }}" />
                                                                            @else
                                                                                <div class="form-control bg-light">
                                                                                    {{ $GxPS->attitudinal ?? null }}</div>
                                                                            @endif
                                                                        </td>

                                                                        <td scope="row" class="col-1">
                                                                            <div class="form-control bg-light">
                                                                                {{ $GxPS->final ?? null }}</div>
                                                                        </td>
                                                                    </tr>
                                                                    @if ($isActive)
                                                                        @php $gradeNumber = $gradeNumber + 3 @endphp
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
                                        <!-- Attendance Available Start -->
                                        <div class="col-12 col-md-6 h5 text-md-start text-center align-self-center">
                                            {{ __('Attendance for this week') . ': ' . $attendanceAvailable }}</div>
                                        <!-- Attendance Available End -->

                                        <div class="col-12 col-md-6 text-md-end text-center">

                                            <!-- Add Attendance Button Start -->
                                            <button type="button"
                                                @if ($attendanceAvailable) data-bs-toggle="modal" data-bs-target="#addAttendance"
                                                @else disabled @endif
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
                                                                <td scope="row" class="text-capitalize">{{ $attendance->created_at }}</td>
                                                                <td class="text-center">
                                                                    {{ $attendance->absences_count }}</td>
                                                                <td class="text-end">
                                                                    @if ($attendance->absences_count)
                                                                        <x-button class="btn-sm btn-outline-primary"
                                                                            absences='view'
                                                                            attendance-id="{{ $attendance->id }}">
                                                                            {{ __('see absences') }}</x-button>
                                                                    @endif
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

    @if ($attendanceAvailable)
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

                            <table class="table table-striped mb-0">
                                <tbody>
                                    @foreach ($studentsGroup as $studentG)
                                        <tr>
                                            <td>
                                                <label class="form-check custom-icon mb-0 unchecked-opacity-25">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="studentsAttendance[{{ $studentG->code }}]" value="1"
                                                        checked>
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
    @endif

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
@endsection
