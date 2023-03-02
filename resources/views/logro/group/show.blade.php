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
    <script src="/js/vendor/progressbar.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
@endsection

@section('js_page')
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

        jQuery('#openModelGenerateGradeReport').click(function () {
            $('button#btn-generateGradeReport').prop('disabled', false);
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
                                                        <i data-acorn-icon="destination"></i>
                                                        <span>{{ __('Transfer students') }}</span>
                                                    </a>
                                                @endif
                                            @endunless
                                        @endcan
                                        <a class="dropdown-item btn-sm btn-icon btn-icon-start"
                                            href="{{ route('group.export.student-list', $group) }}">
                                            <i data-acorn-icon="download"></i>
                                            <span>{{ __('Student list') }}</span>
                                        </a>
                                        <a class="dropdown-item btn-sm btn-icon btn-icon-start"
                                            href="{{ route('group.export.information-student-list', $group) }}">
                                            <i data-acorn-icon="download"></i>
                                            <span>{{ __('Information general from student list') }}</span>
                                        </a>
                                        @hasrole('SUPPORT')
                                        @if (!$group->specialty && !$periods->isEmpty())
                                        <a class="dropdown-item btn-sm btn-icon btn-icon-start" href="#"
                                            id="openModelGenerateGradeReport"
                                            data-bs-toggle="modal" data-bs-target="#generateGradeReport">
                                            <i data-acorn-icon="file-text"></i>
                                            <span>{{ __('Grade report') }}</span>
                                        </a>
                                        @endif
                                        @endhasrole
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
                                    <a class="nav-link text-capitalize" data-bs-toggle="tab" href="#summaryTab" role="tab"
                                        aria-selected="true">{{ __('summary') }}</a>
                                </li>
                            </ul>
                            <!-- Title Tabs End -->

                            <div class="tab-content">

                                <!-- Students Tab Start -->
                                <div class="tab-pane fade active show" id="studentsTab" role="tabpanel">

                                    @can('groups.students.matriculate')
                                        <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                                            @if (null !== $Y->available)
                                                @if ((is_null($group->specialty) && $count_studentsNoEnrolled > 0) ||
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
                                                                            @unless (is_null($subject->teacherSubject))
                                                                                {{ $subject->teacherSubject->teacher->getFullName() ?? null }}
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
                                                                            @canany(['groups.teachers.edit', 'group.subject.period.active'])
                                                                            @if ($subject?->teacherSubject)
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
                                                                                        @can('group.subject.period.active')
                                                                                            <x-dropdown-item type="button"
                                                                                                modal-period-permit
                                                                                                data-subject-id="{{ $subject->teacherSubject->id }}">
                                                                                                <span>{{ __('Activate note upload') }}</span>
                                                                                            </x-dropdown-item>
                                                                                        @endcan
                                                                                        @can('groups.teachers.edit')
                                                                                            <x-dropdown-item type="button"
                                                                                                :link="route('group.export.student-list-guide', $subject->teacherSubject)">
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
                                                                    </div><div
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
                                        <div class="col-12 mb-5">
                                            <div class="card">
                                                <div class="h-100 d-flex flex-column justify-content-between card-body align-items-center">
                                                    <div class="sw-13">
                                                        <div
                                                                logro="progress"
                                                                role="progressbar"
                                                                class="progress-bar-circle position-relative text-muted text-sm"
                                                                data-trail-color=""
                                                                aria-valuemax="{{ \App\Http\Controllers\GradeController::numberFormat($group->studyTimeSelectAll, $group->studyTimeSelectAll->maximum_grade) }}"
                                                                aria-valuenow="{{ \App\Http\Controllers\GradeController::numberFormat($group->studyTimeSelectAll, $avgGrade) }}"
                                                                data-hide-all-text="false"
                                                                data-stroke-width="3"
                                                                data-trail-width="1"
                                                                data-duration="0"
                                                        ></div>
                                                    </div>
                                                    <div class="heading text-center mb-0 sh-8 d-flex align-items-center lh-1-25">{{ __('Grade point average') }}</div>
                                                </div>
                                            </div>
                                        </div>
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
    @endcan

    @hasrole('SUPPORT')
    @if (!$group->specialty && !$periods->isEmpty())
        <!-- Modal Delete Group -->
        <div class="modal fade" id="generateGradeReport"
            aria-labelledby="modalGenerateGradeReport" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalGenerateGradeReport">
                            {{ __('Generate grade report') }}</h5>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('group.reportGrade', $group) }}"
                        method="POST">
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
                            <button type="submit" id="btn-generateGradeReport" class="btn btn-primary">{{ __('Generate') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    @endhasrole
@endsection
