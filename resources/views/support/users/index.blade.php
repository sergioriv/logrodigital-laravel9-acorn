@php
$title = 'User list';
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
<script src="/js/plugins/datatable/users_datatable.ajax.js"></script>
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
                        <a href="{{ route('support.users.create') }}"
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
                <div class="row">
                    <!-- Search Start -->
                    <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                        <div
                            class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                            <input class="form-control datatable-search" placeholder="Search"
                                data-datatable="#datatable_users" />
                            <span class="search-magnifier-icon">
                                <i data-acorn-icon="search"></i>
                            </span>
                            <span class="search-delete-icon d-none">
                                <i data-acorn-icon="close"></i>
                            </span>
                        </div>
                    </div>
                    <!-- Search End -->

                    <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">
                        <div class="d-inline-block">
                            <!-- Options Start -->
                            {{-- <div class="dropdown-as-select d-inline-block datatable-length"
                                data-datatable="#datatable_users" data-childSelector="span">
                                <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false" data-bs-offset="0,3">
                                    <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-delay="0" title="Item Count">
                                        10 Items
                                    </span>
                                </button>
                                <div class="dropdown-menu shadow dropdown-menu-end">
                                    <a class="dropdown-item" href="#">5 Items</a>
                                    <a class="dropdown-item active" href="#">10 Items</a>
                                    <a class="dropdown-item" href="#">20 Items</a>
                                </div>
                            </div> --}}
                            <!-- Options End -->
                        </div>
                    </div>
                </div>
                <!-- Controls End -->

                <!-- Table Start -->
                <div class="data-table-responsive-wrapper">
                    <table id="datatable_users" class="data-table nowrap w-100">
                        <thead>
                            <tr>
                                <th class="text-muted text-small text-uppercase">{{ __('Name') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('Email') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('Email verified at') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('Created at') }}</th>
                                <th class="text-muted text-small text-uppercase">{{ __('Role') }}</th>
                                {{-- <th class="empty">&nbsp;</th> --}}
                            </tr>
                        </thead>
                    </table>
                </div>
                <!-- Table End -->
            </div>
            <!-- Content End -->
        </div>
    </div>
</div>
@endsection
