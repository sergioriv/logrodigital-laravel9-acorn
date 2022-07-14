@php
$title = __('Students') .' '. __('enrolled');
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
                        <h1 class="mb-0 pb-0 display-4" id="title">{{ $title }}</h1>
                    </div>
                    <!-- Title End -->

                    <!-- Top Buttons Start -->
                    <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                        <!-- Add New Button Start -->
                        <a href="{{ route('students.create') }}"
                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
                <div class="row">
                    <!-- Search Start -->
                    <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                        <div
                            class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                            <input class="form-control datatable-search" placeholder="Search"
                                data-datatable="#datatable_students" />
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
                    <table id="datatable_students" class="data-table nowrap w-100" logro="datatable">
                        <thead>
                            <tr>
                                <th class="text-muted text-small text-uppercase">{{ __('names') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('last names') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('email') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('headquarters') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('study time') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('study year') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('created at') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($students as $student)
                            <tr>
                                <td>
                                    <a href="{{ route('students.show', $student) }}"
                                        class="list-item-heading body">
                                        {{ $student->getNames() }}
                                    </a>
                                    <span
                                        class="badge @if ('new' === $student->status) bg-outline-primary @elseif ('repeat' === $student->status) bg-outline-danger @endif">
                                        {{ $student->status }}
                                    </span>
                                </td>
                                <td>{{ $student->getLastNames() }}</td>
                                <td>{{ $student->institutional_email }}</td>
                                <td>{{ $student->headquarters->name }}</td>
                                <td>{{ $student->studyTime->name }}</td>
                                <td>{{ $student->studyYear->name }}</td>
                                <td class="text-small">{{ $student->created_at }}</td>
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
