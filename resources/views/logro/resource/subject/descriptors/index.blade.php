@php
    $title = __('Descriptors') .' | '. $subject->public_name;
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
                            <a href="{{ route($studyYear ? 'teacher.subject.descriptors.create' : 'subject.descriptors.create', [$subject, $studyYear]) }}"
                                class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                <i data-acorn-icon="plus"></i>
                                <span>{{ __('Add New') }}</span>
                            </a>
                            <!-- Add New Button End -->

                            @unless ($studyYear)
                            <!-- Dropdown Button Start -->
                            <div class="ms-1">
                                <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only"
                                    data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" data-submenu>
                                    <i data-acorn-icon="more-horizontal"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item btn-icon btn-icon-start" href="{{ route('subject.descriptors.import', $subject) }}">
                                        <i data-acorn-icon="upload"></i>
                                        <span>{{ __("Import") }} excel</span>
                                    </a>
                                </div>
                            </div>
                            <!-- Dropdown Button End -->
                            @endunless
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
                                    data-datatable="#datatable_descriptors" />
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
                        <table id="datatable_descriptors" class="data-table" logro="datatable">
                            <thead>
                                <tr>
                                    @unless ($studyYear)
                                    <th class="text-muted text-small text-uppercase">{{ __('Study Year') }}</th>
                                    @endunless
                                    <th class="text-muted text-small text-uppercase text-center">{{ __('Period') }}</th>
                                    <th class="text-muted text-small text-uppercase text-center">{{ __('inclusive') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('Content') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('Created at') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($descriptors as $descriptor)
                                    <tr>
                                        @unless ($studyYear)
                                        <td>{{ __($descriptor->resourceStudyYear->name) }}</td>
                                        @endunless
                                        <td align="center">{{ $descriptor->period }}</td>
                                        <td align="center">{!! $descriptor->isInclusiveHtml() !!}</td>
                                        <td>{{ $descriptor->content }}</td>
                                        <td class="text-small">{{ $descriptor->created_at }}</td>
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
