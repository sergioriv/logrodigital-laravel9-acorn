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

    @if ($alertsStudents->getAlerts()->count())
        <script>
            $(document).ready(function() {
                $("#modalFullScreen").modal('show');
            });
        </script>
    @endif
@endsection



@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
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
                                data-bs-target="#addPermitTeacherModal">
                                <i data-acorn-icon="send"></i>
                                <span class="lh-1-5">{{ __('Request permission') }}</span>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown Button End -->
                </div>
            </div>
        </div>
        <!-- Title and Top Buttons End -->


        @if ($pendingStudents)
            <!-- Pending Students Content Start -->
            <div class="mb-5">
                <div class="card">
                    <div class="card-body">
                        {{ __('You have :COUNT students pending assessment.', ['COUNT' => $pendingStudents]) }},
                        <a href="{{ route('students.inclusive') }}"
                            class="text-primary">{{ __('go to inclusive students') }}</a>
                    </div>
                </div>
            </div>
            <!-- Pending Students Content End -->
        @endif

        <!-- Alerts Section Start -->
        <section class="scroll-section">
            <h2 class="small-title">{{ __('Alerts') }}</h2>
            <x-dash.alerts-students :content="$alertsStudents->groupByStudents()" />
        </section>
        <!-- Alerts Section End -->

        <!-- Quality Alert Students Modal Start -->
        <section>
            <x-dash.modal.modal-fullscreen>
                <div class="text-center">
                    <div class="display-1">
                        {{ __('You have :COUNT alerts pending to read.', ['COUNT' => $alertsStudents->getAlerts()->count()]) }}
                    </div>

                    @if ($alertsStudents->getAlerts()->where('priority', 1)->count())
                        <div class="display-2 text-danger">
                            {{ __(':COUNT are high priority alerts.', ['COUNT' => $alertsStudents->getAlerts()->where('priority', 1)->count()]) }}
                        </div>
                    @endif

                    <div class="display-6 mt-4">{{ __('Check them on your main panel.') }}</div>
                </div>
            </x-dash.modal.modal-fullscreen>
        </section>
        <!-- Quality Alert Students Modal End -->

        <!-- Modal Add Permit Start -->
        <x-modal.add-permit>
            @include('logro.orientation.permit.create')
        </x-modal.add-permit>
        <!-- Modal Add Permit End -->
    </div>
@endsection
