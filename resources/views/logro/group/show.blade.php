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
            const permission = await navigator.permissions.query({
                name: 'clipboard-read'
            });

            /* const items = await navigator.clipboard.read();
            const textBlob = await items[0].getType("text/plain");
            const text = await (new Response(textBlob)).text();

            console.error(text); */

            // for (const item of clipboardContents) {
            // if (item.types.includes('text/plain')) {
            // console.error((new Response(textBlob)).text(););
            // }
            // }

            /* navigator.clipboard.readText().then(function(data) {
                console.error("Your string: ", data);
            }); */
            navigator.clipboard
                    .readText()
                    .then((value) => (initPasteValues(value)));

            // alert(clipboardContents);
            // let inputPaste = document.getElementById('input-values-paste');
            // inputPaste.select();
            // document.execCommand('paste');
        }

        function initPasteValues(values) {
            document.getElementById("qualify-period").reset();

            data = values.replaceAll(",", ".").replaceAll("\r", "");

            pasteValues();
        };
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
                                @hasrole('TEACHER')
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" data-bs-toggle="tab" href="#periodsTab" role="tab"
                                            aria-selected="false">{{ __('Periods') }}</a>
                                    </li>
                                @endhasrole
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
                                                                                    {{ $teacher_subject->teacher->getFullName() }}
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
                                @endcan

                                @hasrole('TEACHER')
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
                                                                        {{-- <div class="ms-3 lh-lg align-self-center">({{ $period->getFullDate() }})</div> --}}
                                                                    </div>
                                                                    <div class="col-2 lh-lg m-0 text-center">
                                                                        {{ __('Start date') }}<br />{{ $period->start }}</div>
                                                                    <div class="col-2 lh-lg m-0 text-center">
                                                                        {{ __('Enabled as from') }}<br />{{ $period->dateUploadingNotes() }}
                                                                    </div>
                                                                    <div class="col-2 lh-lg m-0 text-center">
                                                                        {{ __('Deadline date') }}<br />{{ $period->end }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="period-{{ $period->id }}"
                                                        class="collapse @if ($period->active()) show qualify-period @endif"
                                                        data-bs-parent="#periodsCard">
                                                        <div class="card-body accordion-content pt-0">

                                                            @if ($period->active())
                                                                <div class="mb-3 d-flex justify-content-end">
                                                                    <x-button type="button" class="btn-outline-info btn-sm"
                                                                        onclick="clickPaste()">Pegas valores</x-button>
                                                                    <input type="text" id="input-values-paste">
                                                                </div>

                                                                <form action="#" method="POST" id="qualify-period">
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
                                                                    @php $gradeNumber = 1 @endphp
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
                                                                            <td scope="row" class="col-1">
                                                                                @if ($period->active())
                                                                                    <x-input type="number"
                                                                                        id="grade-{{ $gradeNumber }}"
                                                                                        min="{{ $period->studyTime->minimum_grade }}"
                                                                                        max="{{ $period->studyTime->maximum_grade }}"
                                                                                        step="{{ $period->studyTime->step }}" />
                                                                                @else
                                                                                    <div class="form-control bg-light"></div>
                                                                                @endif
                                                                            </td>
                                                                            <td scope="row" class="col-1">
                                                                                @if ($period->active())
                                                                                    <x-input type="number"
                                                                                        id="grade-{{ $gradeNumber + 1 }}"
                                                                                        min="{{ $period->studyTime->minimum_grade }}"
                                                                                        max="{{ $period->studyTime->maximum_grade }}"
                                                                                        step="{{ $period->studyTime->step }}" />
                                                                                @else
                                                                                    <div class="form-control bg-light"></div>
                                                                                @endif
                                                                            </td>
                                                                            <td scope="row" class="col-1">
                                                                                @if ($period->active())
                                                                                    <x-input type="number"
                                                                                        id="grade-{{ $gradeNumber + 2 }}"
                                                                                        min="{{ $period->studyTime->minimum_grade }}"
                                                                                        max="{{ $period->studyTime->maximum_grade }}"
                                                                                        step="{{ $period->studyTime->step }}" />
                                                                                @else
                                                                                    <div class="form-control bg-light"></div>
                                                                                @endif
                                                                            </td>

                                                                            <td scope="row" class="col-1">
                                                                                <div class="form-control bg-light"></div>
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
                                @endhasrole
                            </div>
                        </div>
                        <!-- Right Side End -->
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
