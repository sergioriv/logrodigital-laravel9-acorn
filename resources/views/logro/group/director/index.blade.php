@php
    $title = __('Group directors');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatable_standard.ajax.js?d=1674758885739"></script>
    <script>

        let modalEditGroupDirector = $("#modalEditGroupDirector");
        jQuery('[action="edit-director"]').on('click', function(e) {
            let group = $(this).attr('group');
            let urlEdit = HOST + `/group-directors/${group}/edit`;
            $.get(urlEdit, function(data) {
                modalEditGroupDirector.html(data).modal('show');
            });
        });
    </script>
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
                                    data-datatable="#datatable_group_directors" />
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
                        <table id="datatable_group_directors" class="data-table" logro="datatable"
                            data-order='[[0, "asc"]]'>
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">{{ __('headquarters') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('study time') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('study year') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('Group') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('director') }}</th>
                                    <th class="empty">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groups as $group)
                                    <tr>
                                        <td>{{ $group->headquarters->name }}</td>
                                        <td>{{ $group->studyTime->name }}</td>
                                        <td>{{ $group->studyYear->name }}</td>
                                        <td>{{ $group->name }}</td>
                                        <td>{{ $group->teacher ? $group->teacher->getFullName() : null }}</td>
                                        </td>
                                        <td align="right">
                                            <!-- Dropdown Button Start -->
                                            <div class="dropstart">
                                                <button type="button"
                                                    class="btn btn-sm text-primary hover-bg-primary btn-icon btn-icon-only"
                                                    data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" data-submenu>
                                                    <i data-acorn-icon="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <x-dropdown-item action="edit-director" group="{{ $group->id }}">
                                                        <span>{{ __('Change group director') }}</span>
                                                    </x-dropdown-item>
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

        <!-- Modal Edit Group Director Start -->
        <div class="modal fade" id="modalEditGroupDirector" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-hidden="true">
        </div>
        <!-- Modal Edit Group Director End -->
    </div>
@endsection
