@if (NULL === $student->group_id)
@php
$title = __('Matriculate') . ' ' . __('student');
@endphp
@else
@php
$title = __('Change group');
@endphp
@endif

@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
<script>
    jQuery(".logro-studentsGroup").click(function () {
        $('#labelGroupStudents').html( $(this).data('group-title') );
        $('#contentModalGroupStudents').html( $('#studentsGroup-' + $(this).data('group-id') ).html() );
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
                        <h1 class="mb-1 pb-0 display-4">
                            {{ $title . ' | ' . $student->getNames() . ' ' . $student->getLastNames() }}
                            @if (1 === $student->inclusive)
                                <span class="badge bg-outline-warning">{{ __('inclusive') }}</span>
                            @endif
                            @if ('new' === $student->status)
                                <span class="badge bg-outline-primary">{{ __($student->status) }}</span>
                            @elseif ('repeat' === $student->status)
                                <span class="badge bg-outline-danger">{{ __($student->status) }}</span>
                            @endif
                        </h1>
                    </div>
                </section>
                <!-- Title End -->

                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" :errors="$errors" />

                <!-- Content Start -->
                <form method="POST" action="{{ route('students.matriculate', $student) }}" novalidate>
                    @csrf
                    @method('PUT')

                    <section class="row g-2 mb-3 row-cols-3 row-cols-md-4 row-cols-lg-6">

                        @foreach ($groups as $group)
                            <div class="col small-gutter-col position-relative">
                                <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                    <input type="radio" class="form-check-input position-absolute e-2 t-2 z-index-1"
                                        name="group" value="{{ $group->id }}"
                                        @if (1 === $group->group_students_count )
                                            checked
                                        @endif
                                        />
                                    <div class="card form-check-label w-100">
                                        <div class="card-body text-center d-flex flex-column">
                                            <small class="text-muted">{{ $group->headquarters->name }}</small>
                                            <small class="text-muted">{{ $group->studyTime->name }}</small>
                                            <small class="text-muted">{{ $group->studyYear->name }}</small>
                                            <small class="text-muted btn-icon-start mb-2">
                                                <i class="icon icon-15 bi-award text-muted"></i>
                                                <span>{{ $group->teacher->getFullName() }}</span>
                                            </small>
                                            <h5 class="text-primary font-weight-bold mb-2">{{ $group->name }}</h5>
                                            <span class="mb-2 text-extra-small fw-medium text-muted text-uppercase d-block">
                                                {{ $group->student_quantity . ' ' . __('students') }}
                                            </span>
                                        </div>
                                    </div>
                                </label>
                                <span class="badge rounded-pill text-primary me-1 position-absolute s-2 t-2 z-index-1 cursor-pointer">
                                    <i class="icon icon-18 bi-people logro-studentsGroup"
                                        data-group-id="{{ $group->id }}"
                                        data-group-title="{{ __("Students") .' - '. $group->name }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalGroupStudents"></i>
                                </span>
                                <div class="d-none" id="studentsGroup-{{ $group->id }}">
                                    <table class="w-100">
                                        <tbody>
                                            @foreach ($group->groupStudents as $groupStudent)
                                            <tr>
                                                <td>
                                                    {{ $groupStudent->student->getNames() .' '. $groupStudent->student->getLastNames() }}
                                                    @if (1 === $groupStudent->student->inclusive)
                                                        <span class="badge bg-outline-warning">{{ __('inclusive') }}</span>
                                                    @endif

                                                    @if ('new' === $groupStudent->student->status)
                                                        <span class="badge bg-outline-primary">{{ __($groupStudent->student->status) }}</span>
                                                    @elseif ('repeat' === $groupStudent->student->status)
                                                        <span class="badge bg-outline-danger">{{ __($groupStudent->student->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                    </section>

                    <x-button type="submit" class="btn-primary">
                        @if (NULL === $student->group_id)
                        <span>{{ __('Matriculate') }}</span>
                        @else
                        <span>{{ __('Change group') }}</span>
                        @endif
                    </x-button>

                </form>
                <!-- Content End -->

            </div>
        </div>
    </div>

    <!-- Modal Students Group -->
    <div class="modal fade modal-close-out" id="modalGroupStudents" tabindex="-1" role="dialog"
        aria-labelledby="labelGroupStudents" aria-hidden="true">
        <div class="modal-dialog modal-lg short modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelGroupStudents">{{ __("Students") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contentModalGroupStudents"></div>
            </div>
        </div>
    </div>
@endsection
