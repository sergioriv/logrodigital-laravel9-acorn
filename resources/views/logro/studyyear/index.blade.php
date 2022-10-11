@php
$title = __('Study years list');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/mousetrap.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js"></script>
    <script src="/js/plugins/datatable/datatable_standard.ajax.js"></script>
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
                            <a href="{{ route('studyYear.create') }}"
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
                        @foreach ($studyYears as $studyYear)
                            <div class="col small-gutter-col">
                                <div class="card">
                                    <div class="card-body text-center d-flex flex-column">
                                        <h4 class="mb-0">{{ $studyYear->name }}</h4>
                                        <hr />
                                        <div class="text-muted text-small d-flex flex-column">
                                            <a class="font-weight-bold muted-link text-uppercase"
                                            href="{{ route('studyYear.edit', $studyYear) }}">
                                                <span class="me-1">{{ __('next year') }}</span>
                                                <i data-acorn-icon="pen" data-acorn-size="11"></i>
                                            </a>
                                            @if ($studyYear->next_year !== NULL)
                                            @foreach ($studyYear->nextYears() as $nextYear)
                                            @if (!$loop->first) | @endif
                                            {{ $nextYear->name }}
                                            @endforeach
                                            @else
                                            {{ __('None') }}
                                            @endif
                                        </div>
                                        <hr />
                                        <a href="{{ route('studyYear.subject.show', $studyYear) }}">{{ __('Subjects') .' '. $Y }}</a>
                                    </div>
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
