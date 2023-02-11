@php
    $title = __('Dashboard');
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
            <x-dash.alerts-students :content="$alertsStudents" />
        </section>
        <!-- Alerts Section End -->

    </div>
@endsection
