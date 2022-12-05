@php
$title = __('Students') .' '. __('no-enrolled');
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
                        <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
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

                        <!-- Dropdown Button Start -->
                        {{-- <div class="ms-1">
                            <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only"
                                data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" data-submenu>
                                <i data-acorn-icon="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item btn-icon btn-icon-start" href="{{ route('students.export') }}">
                                    <i data-acorn-icon="download"></i>
                                    <span>{{ __("Download") }} Excel</span>
                                </a>
                                <a class="dropdown-item btn-icon btn-icon-start" href="{{ route('students.import') }}">
                                    <i data-acorn-icon="upload"></i>
                                    <span>{{ __("Import") }} Excel</span>
                                </a>
                            </div>
                        </div> --}}
                        <!-- Dropdown Button End -->
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
                    <table id="datatable_students" class="data-table " logro="datatable"> {{-- nowrapw-100 --}}
                        <thead>
                            <tr>
                                <th class="text-muted text-small text-uppercase">{{ __('names') }}</th>
                                <th class="empty d-none">{{ __('document') }}</th>
                                <th class="empty d-none">{{ __('email') }}</th>
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
                                    @if ($countFileTypes <= $student->filesRequired()->count())
                                        <div class="badge bg-success">{{ __('Complete documents') }}</div>
                                    @endif
                                    <a href="{{ route('students.show', $student) }}"
                                        class="list-item-heading body">
                                        {{ $student->getCompleteNames() }}
                                    </a>
                                    {!! $student->tag() !!}
                                </td>
                                <td class="d-none">{{ $student->document_type_code }} {{ $student->document }}</td>
                                <td class="d-none">{{ $student->institutional_email }}</td>
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
