@php
$title = 'Groups';
@endphp
@extends('layout',['title'=>$title])

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
                    <!-- Search Start -->
                    <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                        <div
                            class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                            <input class="form-control datatable-search" placeholder="Search"
                                data-datatable="#datatable_school_years" />
                            <span class="search-magnifier-icon">
                                <i data-acorn-icon="search"></i>
                            </span>
                            <span class="search-delete-icon d-none">
                                <i data-acorn-icon="close"></i>
                            </span>
                        </div>
                    </div>
                    <!-- Search End -->
                </section>
                <!-- Controls End -->

                <!-- Cards Start -->
                <section class="row g-2 row-cols-3 row-cols-md-4 row-cols-lg-6">
                    @foreach ($groups as $group)
                    <div class="col small-gutter-col">
                        <div class="card h-100">
                            <div class="card-body text-center d-flex flex-column">
                                <h5 class="text-primary font-weight-bold">{{ $group->name }}</h5>
                                <span>{{ $group->headquarters->name }}</span>
                                <span>{{ $group->studyTime->name }}</span>
                                <span>{{ $group->studyYear->name }}</span>
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
