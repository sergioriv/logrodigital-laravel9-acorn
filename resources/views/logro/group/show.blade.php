@php
$title = $group->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
@endsection

@section('js_page')
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
                                <!-- Edit Name Button Start -->
                                <a href="{{ route('group.edit', $group) }}"
                                    class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                    <i data-acorn-icon="edit-square"></i>
                                    <span>{{ __('Edit') }}</span>
                                </a>
                                <!-- Edit Name Button End -->
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
                                            aria-selected="true">{{ __('Students') }} ({{ $group->student_quantity }})</a>
                                    </li>
                                @endcan
                                @can('groups.teachers')
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#subjectsTab" role="tab"
                                            aria-selected="true">{{ __('Subjects') . ' & ' . __('Teachers') }}</a>
                                    </li>
                                @endcan
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#otherTab" role="tab"
                                        aria-selected="false">{{ __('Other') }}</a>
                                </li>
                            </ul>
                            <!-- Title Tabs End -->

                            <div class="tab-content">

                                <!-- Students Tab Start -->
                                <div class="tab-pane fade active show" id="studentsTab" role="tabpanel">

                                    <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                                        @can('groups.students.matriculate')
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
                                        @endcan
                                    </div>

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
                                                                        {{ $studentG->getLastNames() . ' ' . $studentG->getNames() }}
                                                                    </a>
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

                                <!-- Groups Tab Start -->
                                <div class="tab-pane fade" id="subjectsTab" role="tabpanel">

                                    @can('groups.teachers.edit')
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
                                    @endcan

                                    <!-- Groups Content Tab Start -->
                                    <section class="scroll-section">
                                        @foreach ($areas as $area)
                                            <div class="card d-flex mb-2">
                                                <div class="card-body">
                                                    <h2 class="small-title">{{ $area->name }}</h2>
                                                    <table class="table table-striped">
                                                        <tbody>
                                                            @foreach ($area->subjects as $subject)
                                                                <tr>
                                                                    <td scope="row" class="col-4">
                                                                        {{ $subject->resourceSubject->name }}
                                                                    </td>
                                                                    <td class="col-6">
                                                                        @foreach ($subject->teacherSubjectGroups as $teacher_subject)
                                                                            @if ($loop->first)
                                                                                {{ $teacher_subject->teacher->fullName() }}
                                                                            @endif
                                                                        @endforeach
                                                                    </td>
                                                                    <td class="col-1 text-center">
                                                                        {{ $subject->studyYearSubject->hours_week }}
                                                                        @if (1 === $subject->studyYearSubject->hours_week)
                                                                            {{ __('hour') }}
                                                                        @else
                                                                            {{ __('hours') }}
                                                                        @endif
                                                                    </td>
                                                                    <td class="col-1 text-center">
                                                                        {{ $subject->studyYearSubject->course_load }}%</td>
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

                                <!-- Branches Tab Start -->
                                <div class="tab-pane fade" id="otherTab" role="tabpanel">
                                    other
                                </div>
                                <!-- Branches Tab End -->
                            </div>
                        </div>
                        <!-- Right Side End -->
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
