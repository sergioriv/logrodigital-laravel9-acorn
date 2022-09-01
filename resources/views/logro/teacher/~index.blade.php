@php
$title = __('Teacher list');
@endphp
@extends('layout',['title'=>$title])

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
<script src="/js/plugins/datatable/teachers_datatable.ajax.js"></script>
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
                        <a href="{{ route('teacher.create') }}"
                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                            <i data-acorn-icon="plus"></i>
                            <span>{{ __('Add New') }}</span>
                        </a>
                        <!-- Add New Button End -->

                        <!-- Dropdown Button Start -->
                        <div class="ms-1">
                            <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only"
                                data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" data-submenu>
                                <i data-acorn-icon="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item btn-icon btn-icon-start" href="{{ route('teacher.export') }}">
                                    <i data-acorn-icon="download"></i>
                                    <span>{{ __("Download") }} Excel</span>
                                </a>
                                <a class="dropdown-item btn-icon btn-icon-start" href="{{ route('teacher.import') }}">
                                    <i data-acorn-icon="upload"></i>
                                    <span>{{ __("Import") }} Excel</span>
                                </a>
                            </div>
                        </div>
                        <!-- Dropdown Button End -->

                    </div>
                    <!-- Top Buttons End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="">
                <!-- Teachers Content Start -->

                        <!-- Controls Start -->
                        <div class="row mb-3">
                            <!-- Search Start -->
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 mb-1">
                                <div
                                    class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                    <input class="form-control datatable-search" placeholder="Search"
                                        data-datatable="#datatable_teachers" />
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
                        <div class="">
                            <table id="datatable_teachers" class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                            data-order='[[ 2, "asc" ]]'>
                            {{-- <table id="datatable_teachers" class="data-table nowrap w-100"> --}}
                                <thead>
                                    <tr>
                                        <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('names') }}</th>
                                        <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('last names') }}</th>
                                        <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('email') }}</th>
                                        <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('telephone') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teachers as $teacher)
                                    <tr>
                                        <td>
                                            <a href="{{ route('teacher.show', $teacher) }}"
                                                class="list-item-heading body">
                                                {{ $teacher->getNames() }}
                                            </a>
                                        </td>
                                        <td>{{ $teacher->getLastNames() }}</td>
                                        <td>{{ $teacher->institutional_email }}</td>
                                        <td>{{ $teacher->telephone }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Table End -->
                <!-- Teachers Content End -->
            </div>
            <!-- Content End -->
        </div>
    </div>
</div>
@endsection
