@php
$title = __('Groups');
@endphp
@extends('layout',['title'=>$title])

@section('css')
<link rel="stylesheet" href="/css/vendor/select2.min.css" />
<link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
<script src="/js/vendor/select2.full.min.js"></script>
@endsection

@section('js_page')
<script>
    jQuery('#select2Headquarters').select2({minimumResultsForSearch: Infinity});
    jQuery('#select2StudyTime').select2({minimumResultsForSearch: Infinity});
    jQuery('#select2StudyYear').select2({minimumResultsForSearch: Infinity});
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
                        <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    </div>
                    <!-- Title End -->

                    <!-- Top Buttons Start -->
                    <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                        <!-- Add New Button Start -->
                        <a href="{{ route('group.create') }}"
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
            <div class="data-table-rows slim">
                <!-- Controls Start -->
                <section class="row">
                    <!-- Search Headquarters Start -->
                    <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-3 mb-1">
                        <div class="w-100">
                            <select data-placeholder="{{ __("Headquarters") }}" id="select2Headquarters">
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
                            <select data-placeholder="{{ __("Study Time") }}" id="select2StudyTime">
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
                            <select data-placeholder="{{ __("Study Year") }}" id="select2StudyYear">
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
                    <div class="col small-gutter-col">
                        <div class="card h-100">
                            <div class="card-body text-center d-flex flex-column">
                                <a href="{{ route('group.show', $group) }}">
                                    <h5 class="text-primary font-weight-bold">{{ $group->name }}</h5>
                                </a>
                                <small>{{ $group->headquarters->name }}</small>
                                <small>{{ $group->studyTime->name }}</small>
                                <small>{{ $group->studyYear->name }}</small>
                                <span class="btn-icon-start">
                                    <i data-acorn-icon="badge" class="icon text-primary" data-acorn-size="15"></i>
                                    {{ $group->teacher->getFullName() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </section>
                <!-- Cards End -->
            </div>
            <!-- Content End -->
        </div>
    </div>
</div>
@endsection
