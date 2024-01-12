@php
$title = $student->getFullName() . ' | ' . __('Grade report');
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

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <section class="data-table-rows slim">

                    <!-- Cards Start -->
                    <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6" id="groupsList">
                        @foreach ($groups as $group)
                        @php
                            $resultSchoolYear = $resultSchoolYears->filter(fn($f) => $f->school_year_id === $group->school_year_id)->first();
                        @endphp
                        <div class="col small-gutter-col">
                            <div class="card h-100 hover-border-primary border-0">
                                <a href="{{ route('students.pdf.report_grades', ['student' => $student->id, 'Y' => $group->school_year_id]) }}">
                                    <div class="card-body text-center d-flex flex-column">
                                        <h5 class="font-weight-bold text-primary">{{ $group->name }}</h5>
                                        <small class="text-muted">{{ $group->schoolYear->name }}</small>
                                        <small class="text-muted">{{ $group->headquarters->name }}</small>
                                        <small class="text-muted">{{ $group->studyYear?->name }}</small>
                                        <div class="mt-3">
                                            @if ($resultSchoolYear)
                                            <span class="badge bg-outline-{{ $resultSchoolYear->result ? 'success' : 'danger' }}">{{ $resultSchoolYear->result ? 'Aprobado' : 'Reprobado' }}</span>
                                            @elseif ($YAvailable->id === $group->school_year_id)
                                            <span class="badge bg-primary">En curso</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- Cards End -->

                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
