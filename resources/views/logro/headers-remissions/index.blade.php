@php
    $title = __('List of remission headers');
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
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatable_standard.ajax.js?d=1674758885739"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7 mb-2 mb-md-0">
                            <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                        </div>
                        <!-- Title End -->

                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                            <!-- Add New Button Start -->
                            <a href="{{ route('headers-remissions.create') }}"
                                class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
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
                                <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
                                    data-datatable="#datatable_headers_remission" />
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
                        <table id="datatable_headers_remission" class="data-table hover" logro="datatable">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">{{ __('title') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('Content') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('creator') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('Created at') }}</th>
                                    <th class="empty">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($headers as $header)
                                    <tr>
                                        <td class="text-sub">{{ $header->title }}</td>
                                        <td>{!! $header->contentHtml() !!}</td>
                                        <td class="text-sub text-small">{{ $header->orientator->getFullName() }}</td>
                                        <td class="text-sub text-small">{{ $header->created_at }}</td>
                                        <td align="right" class="text-sub">
                                            <!-- Dropdown Button Start -->
                                            <div class="ms-1 dropstart">
                                                <button type="button"
                                                    class="btn btn-sm text-primary btn-icon btn-icon-only"
                                                    data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" data-submenu>
                                                    <i data-acorn-icon="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a href="{{ route('headers-remissions.edit', $header) }}"
                                                        class="dropdown-item">
                                                        <span>{{ __('Edit') }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                            <!-- Dropdown Button End -->
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
