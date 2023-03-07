@php
    $title = __('Dashboard');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/select2.js"></script>
    <script src="/js/pages/dashboard.coordination.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row g-0">
                <!-- Title Start -->
                <div class="col-12 col-md-7 mb-2 mb-md-0">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Dropdown Button Start -->
                    <div class="">
                        <button class="btn btn-sm btn-icon btn-icon-only btn-foreground shadow align-top mt-n2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            <i data-acorn-icon="more-horizontal" data-acorn-size="15"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end shadow">
                            <div class="dropdown-item btn-icon btn-icon-start cursor-pointer" data-bs-toggle="modal"
                                data-bs-target="#addAnnotationObserverModal">
                                <i class="icon bi-journal-medical icon-16 me-1"></i>
                                <span class="lh-1-5">{{ __('Add annotation to Observer') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        @if (count($teacherPermits) || count($coordinationPermits) || count($orientationPermits))
            <!-- Alerts Section Start -->
            <section class="scroll-section">
                <h2 class="small-title">{{ __('Permits requested') }}</h2>

                <div class="card mb-5">
                    <div class="card-body">

                        <div class="accordion accordion-flush" id="accordionFlushPermits">

                            @if (count($teacherPermits))
                                <!-- Accordeon Teacher Permits Start -->
                                <div class="accordion-item">
                                    <div class="accordion-header" id="flush-heading-teachers">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse-teachers" aria-expanded="false"
                                            aria-controls="flush-collapse-teachers">
                                            <span
                                                class="font-weight-bold me-1">{{ '(' . $teacherPermits->count() . ')' }}</span>
                                            {{ __('Teachers') }}
                                        </button>
                                    </div>
                                    <div id="flush-collapse-teachers" class="accordion-collapse collapse"
                                        aria-labelledby="flush-heading-teachers" data-bs-parent="#accordionFlushPermits">
                                        <div class="accordion-body px-5">

                                            @foreach ($teacherPermits as $permit)
                                                <div class="w-100 mb-2">
                                                    <span
                                                        class="font-weight-bold me-1">{{ '(' . $permit->count() . ')' }}</span>
                                                    {{ $permit[0]->teacher->getFullName() }}
                                                    <a href="{{ route('teacher.show', $permit[0]->teacher->uuid) }}"><i
                                                            class="icon bi-box-arrow-in-up-right text-primary ms-2"></i></a>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                <!-- Accordeon Teacher Permits End -->
                            @endif

                            @if (count($coordinationPermits))
                                <!-- Accordeon Coordination Permits Start -->
                                <div class="accordion-item">
                                    <div class="accordion-header" id="flush-heading-coordinators">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse-coordinators" aria-expanded="false"
                                            aria-controls="flush-collapse-coordinators">
                                            <span
                                                class="font-weight-bold me-1">{{ '(' . $coordinationPermits->count() . ')' }}</span>
                                            {{ __('Coordinators') }}
                                        </button>
                                    </div>
                                    <div id="flush-collapse-coordinators" class="accordion-collapse collapse"
                                        aria-labelledby="flush-heading-coordinators"
                                        data-bs-parent="#accordionFlushPermits">
                                        <div class="accordion-body px-5">

                                            @foreach ($coordinationPermits as $permit)
                                                <div class="w-100 mb-2">
                                                    <span
                                                        class="font-weight-bold me-1">{{ '(' . $permit->count() . ')' }}</span>
                                                    {{ $permit[0]->coordination->getFullName() }}
                                                    <a
                                                        href="{{ route('coordination.show', $permit[0]->coordination->uuid) }}"><i
                                                            class="icon bi-box-arrow-in-up-right text-primary ms-2"></i></a>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                <!-- Accordeon Coordination Permits End -->
                            @endif

                            @if (count($orientationPermits))
                                <!-- Accordeon Orientation Permits Start -->
                                <div class="accordion-item">
                                    <div class="accordion-header" id="flush-heading-orientation">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#flush-collapse-orientation" aria-expanded="false"
                                            aria-controls="flush-collapse-orientation">
                                            <span
                                                class="font-weight-bold me-1">{{ '(' . $orientationPermits->count() . ')' }}</span>
                                            {{ __('Counselors') }}
                                        </button>
                                    </div>
                                    <div id="flush-collapse-orientation" class="accordion-collapse collapse"
                                        aria-labelledby="flush-heading-orientation" data-bs-parent="#accordionFlushPermits">
                                        <div class="accordion-body px-5">

                                            @foreach ($orientationPermits as $permit)
                                                <div class="w-100 mb-2">
                                                    <span
                                                        class="font-weight-bold me-1">{{ '(' . $permit->count() . ')' }}</span>
                                                    {{ $permit[0]->orientation->getFullName() }}
                                                    <a
                                                        href="{{ route('orientation.show', $permit[0]->orientation->uuid) }}"><i
                                                            class="icon bi-box-arrow-in-up-right text-primary ms-2"></i></a>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                                <!-- Accordeon Orientation Permits End -->
                            @endif
                        </div>
                    </div>
                </div>

            </section>
            <!-- Alerts Section Start -->
        @endif

        <!-- Alerts Section Start -->
        <section class="scroll-section">
            <h2 class="small-title">{{ __('Alerts') }}</h2>
            <x-dash.alerts-students :content="$alertsStudents" />
        </section>
        <!-- Alerts Section End -->

        <!-- Add Annotation Observer Modal Start -->
        <section>
            <x-dash.modal.multiple-annotation-observer />
        </section>
        <!-- Add Annotation Observer Modal End -->

    </div>
@endsection
