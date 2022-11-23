@php
    $title = $subject->subject->resourceSubject->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
@endsection

@section('js_page')
    <script>
        let data = "";

        function pasteValues() {

            var rows = data.split("\n");

            let i = 1;
            for (var y in rows) {

                var cells = rows[y].split("\t");

                for (var x in cells) {

                    if (!isNaN(cells[x])) {
                        $('#grade-' + i).val(cells[x]);
                    }

                    i++;

                }
            }

        }

        async function clickPaste() {
            navigator.clipboard
                .readText()
                .then((value) => (initPasteValues(value)));
        }

        function initPasteValues(values) {
            document.getElementById("qualify-period").reset();

            data = values
                .replaceAll(",", ".")
                .replaceAll("\r", "");

            pasteValues();
        };

        $('.qualify-period').bind("paste", function(e) {
            document.getElementById("qualify-period").reset();

            data = e.originalEvent.clipboardData.getData('text')
                .replaceAll(",", ".")
                .replaceAll("\r", "");

            pasteValues();
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
                            <div class="col-12 col-md-6">
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
                            <div class="col-12 col-md-6 text-end display-4 fw-bold">{{ $title }}</div>
                            <!-- Title End -->

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
                                        aria-selected="true">{{ __('Students') }} ({{ $subject->group->student_quantity }})</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-bs-toggle="tab" href="#periodsTab" role="tab"
                                        aria-selected="false">{{ __('Periods') }}</a>
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
                                                                    <a href="{{ route('students.view', $studentG) }}"
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

                                <!-- Periods Tab Start -->
                                <div class="tab-pane fade" id="periodsTab" role="tabpanel">

                                    <div class="mb-n2" id="periodsCard">

                                        @foreach ($periods as $period)
                                            <div class="card d-flex mt-2 text-muted">
                                                <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                                    data-bs-target="#period-{{ $period->id }}" aria-expanded="true"
                                                    aria-controls="period-{{ $period->id }}">
                                                    <div class="card-body py-4">
                                                        <div class="list-item-heading p-0">
                                                            <div class="row g-2">
                                                                <div class="col-6 d-inline-flex">
                                                                    <div
                                                                        class="font-weight-bold h3 m-0 align-self-center @if ($period->active()) text-base @else text-muted @endif">
                                                                        {{ $period->name }}</div>
                                                                </div>
                                                                <div class="col-2 lh-lg m-0 text-center">
                                                                    {{ __('Start date') }}<br /><b>{{ $period->start }}</b>
                                                                </div>
                                                                <div class="col-2 lh-lg m-0 text-center">
                                                                    {{ __('Enabled as from') }}<br /><b>{{ $period->dateUploadingNotes() }}</b>
                                                                </div>
                                                                <div class="col-2 lh-lg m-0 text-center">
                                                                    {{ __('Deadline date') }}<br /><b>{{ $period->end }}</b>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="period-{{ $period->id }}"
                                                    class="collapse @if ($period->active()) show @endif"
                                                    data-bs-parent="#periodsCard">
                                                    <div class="card-body accordion-content pt-0">

                                                        @if ($period->active())
                                                            <div class="mb-3 d-flex justify-content-end">
                                                                <x-button type="button" class="btn-outline-primary btn-sm"
                                                                    onclick="clickPaste()"><i data-acorn-icon="clipboard"
                                                                        data-acorn-size="16"
                                                                        class="me-2 align-self-center"></i>{{ __('Paste values') }}
                                                                </x-button>
                                                            </div>

                                                            <form action="{{ route('subject.qualify.students', $subject) }}"
                                                                method="POST" id="qualify-period" class="qualify-period">
                                                                @csrf
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
                                                                    $gradesStudent = \App\Http\Controllers\GradeController::gradesStudent($subject->id, $period->id, $studentG->id)
                                                                    @endphp

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
                                                                        <td scope="row" class="col-1">
                                                                            @if ($period->active())
                                                                                <x-input type="number"
                                                                                    id="grade-{{ $gradeNumber }}"
                                                                                    min="{{ $period->studyTime->minimum_grade }}"
                                                                                    max="{{ $period->studyTime->maximum_grade }}"
                                                                                    step="{{ $period->studyTime->step }}"
                                                                                    name="students[{{ $studentG->code }}][conceptual]"
                                                                                    value="{{ $gradesStudent->conceptual ?? null }}" />
                                                                            @else
                                                                                <div class="form-control bg-light">{{ $gradesStudent->conceptual ?? null }}</div>
                                                                            @endif
                                                                        </td>
                                                                        <td scope="row" class="col-1">
                                                                            @if ($period->active())
                                                                                <x-input type="number"
                                                                                    id="grade-{{ $gradeNumber + 1 }}"
                                                                                    min="{{ $period->studyTime->minimum_grade }}"
                                                                                    max="{{ $period->studyTime->maximum_grade }}"
                                                                                    step="{{ $period->studyTime->step }}"
                                                                                    name="students[{{ $studentG->code }}][procedural]"
                                                                                    value="{{ $gradesStudent->procedural ?? null }}" />
                                                                            @else
                                                                                <div class="form-control bg-light">{{ $gradesStudent->procedural ?? null }}</div>
                                                                            @endif
                                                                        </td>
                                                                        <td scope="row" class="col-1">
                                                                            @if ($period->active())
                                                                                <x-input type="number"
                                                                                    id="grade-{{ $gradeNumber + 2 }}"
                                                                                    min="{{ $period->studyTime->minimum_grade }}"
                                                                                    max="{{ $period->studyTime->maximum_grade }}"
                                                                                    step="{{ $period->studyTime->step }}"
                                                                                    name="students[{{ $studentG->code }}][attitudinal]"
                                                                                    value="{{ $gradesStudent->attitudinal ?? null }}" />
                                                                            @else
                                                                                <div class="form-control bg-light">{{ $gradesStudent->attitudinal ?? null }}</div>
                                                                            @endif
                                                                        </td>

                                                                        <td scope="row" class="col-1">
                                                                            <div class="form-control bg-light">{{ $gradesStudent->final ?? null }}</div>
                                                                        </td>
                                                                    </tr>
                                                                    @if ($period->active())
                                                                        @php $gradeNumber = $gradeNumber + 3 @endphp
                                                                    @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>

                                                        @if ($period->active())
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

                                    </div>

                                </div>
                                <!-- Periods Tab End -->

                            </div>
                        </div>
                        <!-- Right Side End -->
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
