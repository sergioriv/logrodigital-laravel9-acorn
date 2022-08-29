@php
$title = __('Matriculate students');
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
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
                        <h1 class="mb-1 pb-0 display-4">
                            {{ __("Group") .' | ' . $group->name .' | '. $title  }}
                        </h1>
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
                </section>
                <!-- Title End -->

                <section class="scroll-section">


                    <!-- Validation Errors -->
                    {{-- <x-validation-errors class="mb-4" :errors="$errors" /> --}}

                    <form method="POST" action="{{ route('group.matriculate.update', $group) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <table class="table table-striped">
                            <tbody>
                                @foreach ($studentsNoEnrolled as $student)
                                    <tr>
                                        <td scope="row">
                                            <div class="form-check d-inline-block">
                                                <label class="form-check-label">
                                                    <input class="form-check-input" type="checkbox" name="students[]"
                                                        value="{{ $student->id }}">
                                                    {{ $student->getLastNames() . ' ' . $student->getNames() }}

                                                    @if (1 === $student->inclusive)
                                                        <span class="badge bg-outline-warning">{{ __('inclusive') }}</span>
                                                    @endif
                                                    @if ('new' === $student->status)
                                                        <span
                                                            class="badge bg-outline-primary">{{ __($student->status) }}</span>
                                                    @elseif ('repeat' === $student->status)
                                                        <span
                                                            class="badge bg-outline-danger">{{ __($student->status) }}</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <x-button type="submit" class="btn-primary">{{ __('Matriculate') }}</x-button>

                    </form>

                </section>

            </div>
        </div>
    </div>
@endsection
