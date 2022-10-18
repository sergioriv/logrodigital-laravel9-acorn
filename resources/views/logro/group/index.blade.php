@php
$title = __('Groups');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/select2.full.min.js"></script>
@endsection

@section('js_page')
    <script>
        jQuery('#select2Headquarters').select2({
            minimumResultsForSearch: Infinity
        });
        jQuery('#select2StudyTime').select2({
            minimumResultsForSearch: Infinity
        });
        jQuery('#select2StudyYear').select2({
            minimumResultsForSearch: Infinity
        });
    </script>
    <script src="/js/pages/groups.filters.js"></script>
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

                            @can('groups.create')
                            @if (null !== $Y->available)
                                <!-- Add New Button Start -->
                                <a href="{{ route('group.create') }}"
                                    class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                    <i data-acorn-icon="plus"></i>
                                    <span>{{ __('Add New') }}</span>
                                </a>
                                <!-- Add New Button End -->
                            @endif
                            @endcan

                        </div>
                        <!-- Top Buttons End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <div class="data-table-rows slim">
                    <!-- Controls Start -->
                    <section class="row">
                        <!-- Search Headquarters Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-3 mb-1">
                            <div class="w-100">
                                <select data-placeholder="{{ __('Headquarters') }}" id="select2Headquarters">
                                    <option label="&nbsp;"></option>
                                    @foreach ($headquarters as $hq)
                                        <option value="{{ $hq->id }}">{{ $hq->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Search Headquarters End -->
                        <!-- Search Study Time Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-3 mb-1">
                            <div class="w-100">
                                <select data-placeholder="{{ __('Study Time') }}" id="select2StudyTime">
                                    <option label="&nbsp;"></option>
                                    @foreach ($studyTimes as $st)
                                        <option value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Search Study Time End -->
                        <!-- Search Study Year Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-3 mb-1">
                            <div class="w-100">
                                <select data-placeholder="{{ __('Study Year') }}" id="select2StudyYear">
                                    <option label="&nbsp;"></option>
                                    @foreach ($studyYears as $sy)
                                        <option value="{{ $sy->id }}">{{ $sy->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Search Study Year End -->
                        <!-- Search Name Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-3 mb-1">
                            <div
                                class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                <input class="form-control" id="searchName" placeholder="Search" />
                                <span class="search-magnifier-icon">
                                    <i data-acorn-icon="search"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Search Name End -->
                    </section>
                    <!-- Controls End -->

                    <!-- Cards Start -->
                    <section class="row g-2 row-cols-3 row-cols-md-4 row-cols-lg-6" id="groupsList">
                        @foreach ($groups as $group)
                        <x-group.card :group="$group">
                            <small class="mt-2 text-muted">{{ $group->student_quantity .' '. __("students") }}</small>
                        </x-group.card>
                            {{-- <div class="col small-gutter-col">
                                <div class="card h-100">

                                    <a href="{{ route('group.show', $group) }}">
                                    <div class="card-body text-center d-flex flex-column">
                                            <h5 class="text-primary font-weight-bold">{{ $group->name }}</h5>
                                            <small class="text-muted">{{ $group->headquarters->name }}</small>
                                            <small class="text-muted">{{ $group->studyTime->name }}</small>
                                            <small class="text-muted">{{ $group->studyYear->name }}</small>
                                            <small class="btn-icon-start text-muted">
                                                @if (NULL !== $group->teacher_id)
                                                    <i class="icon icon-15 bi-award text-muted"></i>
                                                    <span>
                                                        {{ $group->teacher->fullName() }}
                                                    </span>
                                                @else
                                                <span>&nbsp;</span>
                                                @endif
                                            </small>
                                            <small class="mt-2 text-muted">{{ $group->student_quantity .' '. __("students") }}</small>
                                        </div>
                                    </a>
                                </div>
                            </div> --}}
                        @endforeach
                    </section>
                    <!-- Cards End -->
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection
