@php
    $title = __('Dashboard');
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
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
                <div class="col-12 col-md-7">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                </div>
                <!-- Title End -->
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

    </div>
@endsection
