@php
    $title = $group->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
@endsection

@section('js_page')
    <script>
        jQuery('.logro-select2').select2({
            minimumResultsForSearch: true
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
                        <h1 class="mb-1 pb-0 display-4">{{ __('Group') . ' | ' . $title . ' | ' . __('Edit') }}</h1>
                    </div>
                </section>
                <!-- Title End -->


                <section class="scroll-section">

                    <form method="POST" action="{{ route('group.teachers.update', $group) }}" novalidate>
                        @csrf
                        @method('PUT')

                        @foreach ($areas as $area)
                            <div class="card d-flex mb-2">
                                <div class="card-body">
                                    <h2 class="small-title">{{ $area->name }}</h2>
                                    <table class="table table-striped">
                                        <tbody>
                                            @foreach ($area->subjects as $subject)

                                            @php $TSG = \App\Http\Controllers\TeacherSubjectGroupController::forSubject($Y->id, $group->id, $subject->id) @endphp

                                                <tr>
                                                    <td scope="row" class="col-4">{!! $subject->resourceSubject->nameSpecialty() !!}
                                                    </td>
                                                    <td class="col-6">
                                                        <div class="w-100">
                                                            <select name="teachers[]"
                                                                data-placeholder="{{ __(' Choose') . ' ' . __('Teacher') }}"
                                                                class="logro-select2">
                                                                <option label="&nbsp;"></option>
                                                                @foreach ($teachers as $teacher)
                                                                    <option @if($TSG) @selected($TSG->teacher?->id === $teacher->id) @endif
                                                                        value="{{ $subject->id . '~' . $teacher->uuid }}">
                                                                        {{ $teacher->getFullName() }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
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
                                                        {{ $subject->academicWorkload->course_load }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <x-button type="submit" class="btn-primary mt-3">{{ __('Save') . ' ' . __('Teachers') }}
                        </x-button>
                    </form>

                </section>

            </div>
        </div>
    </div>
@endsection
