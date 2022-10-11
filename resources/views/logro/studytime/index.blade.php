@php
$title = __('Study times list');
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
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7">
                            <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                        </div>
                        <!-- Title End -->

                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                            <!-- Add New Button Start -->
                            <a href="{{ route('studyTime.create') }}"
                                class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                <i data-acorn-icon="plus"></i>
                                <span>{{ __('Add New') }}</span>
                            </a>
                            <!-- Add New Button End -->
                        </div>
                        <!-- Top Buttons End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <div class="row g-3 row-cols-2 row-cols-md-4 row-cols-lg-5 row-cols-xl-6">
                        @foreach ($studyTimes as $studyTime)
                            <div class="col small-gutter-col">
                                <div class="card hover-border-primary">
                                    <a href="{{ route('studyTime.show', $studyTime->id) }}">
                                        <div class="card-body text-center d-flex flex-column">
                                            <h4>{{ $studyTime->name }}</h4>
                                            <div class="row text-capitalize">
                                                <div class="col-8 text-start text-small text-muted">{{ __('conceptual') }}</div>
                                                <div class="col-4 text-small text-muted">{{ $studyTime->conceptual }}%</div>
                                            </div>
                                            <div class="row text-capitalize">
                                                <div class="col-8 text-start text-small text-muted">{{ __('procedural') }}</div>
                                                <div class="col-4 text-small text-muted">{{ $studyTime->procedural }}%</div>
                                            </div>
                                            <div class="row text-capitalize">
                                                <div class="col-8 text-start text-small text-muted">{{ __('attitudinal') }}</div>
                                                <div class="col-4 text-small text-muted">{{ $studyTime->attitudinal }}%</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
