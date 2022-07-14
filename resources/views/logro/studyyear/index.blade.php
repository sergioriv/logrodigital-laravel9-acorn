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
                            <h1 class="mb-0 pb-0 display-4" id="title">{{ $title . ' | ' . $Y }}</h1>
                        </div>
                        <!-- Title End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <div class="data-table-rows slim">
                    <!-- Controls Start -->
                    <div class="row">
                        <!-- Search Start -->
                        <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                            <div
                                class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                <input class="form-control datatable-search" placeholder="Search"
                                    data-datatable="#datatable_study_years" />
                                <span class="search-magnifier-icon">
                                    <i data-acorn-icon="search"></i>
                                </span>
                                <span class="search-delete-icon d-none">
                                    <i data-acorn-icon="close"></i>
                                </span>
                            </div>
                        </div>
                        <!-- Search End -->
                    </div>
                    <!-- Controls End -->

                    <!-- Table Start -->
                    <div class="data-table-responsive-wrapper">
                        <table id="datatable_study_years" class="data-table nowrap w-100" logro="datatable">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">{{ __('Name') }}</th>
                                    <th class="text-muted text-small text-uppercase text-center">{{ __('Subjects') }}</th>
                                    <th class="text-muted text-small text-uppercase text-center">{{ __('Groups') }}</th>
                                    <th class="text-muted text-small text-uppercase text-center">{{ __('Students') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($studyYears as $studyYear)
                                    <tr>
                                        <td>
                                            <a href="{{ route('studyYear.subject.show', $studyYear->id) }}"
                                                class="list-item-heading body">
                                                {{ $studyYear->name }}
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{ $studyYear->study_year_subject_count ?: null }}
                                        </td>
                                        <td class="text-center">
                                            {{ $studyYear->groups_count ?: null }}
                                        </td>
                                        <td class="text-center">
                                            {{ $studyYear->groups_sum_student_quantity ?: null }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Table End -->
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection
