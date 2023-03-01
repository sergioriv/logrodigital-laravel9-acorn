@php
    $title = $studyTime->name;
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
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <section class="page-title-container">
                    <div class="row">

                        <!-- Title Start -->
                        <div class="col-12 col-md-7">
                            <h1 class="mb-1 pb-0 display-4">{{ __('Study time') . ' | ' . $title }}</h1>
                        </div>
                        <!-- Title End -->

                    </div>
                </section>
                <!-- Title End -->

                <section class="row scroll-section">
                    <!-- Left Side Start -->
                    <div class="col-12 col-xl-3 col-xxl-2">
                        <!-- Biography Start -->
                        {{-- <h2 class="small-title text-muted">&nbsp;</h2> --}}
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="nav flex-column mb-5" role="tablist">
                                    <a class="nav-link active logro-toggle px-0 border-bottom border-separator-light"
                                        data-bs-toggle="tab" href="#generalTab" role="tab">
                                        <span class="align-middle">{{ __('General') }}</span>
                                    </a>
                                    <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                        data-bs-toggle="tab" href="#periodsTab" role="tab">
                                        <span class="align-middle">{{ __('Periods') }}</span>
                                    </a>
                                </div>

                                <div class="col">
                                    <div class="text-muted text-small">{{ __('created at') }}:</div>
                                    <div class="text-muted text-small">{{ $studyTime->created_at }}</div>
                                </div>

                            </div>
                        </div>
                        <!-- Biography End -->

                    </div>
                    <!-- Left Side End -->

                    <!-- Right Side Start -->
                    <div class="col-12 col-xl-9 col-xxl-10 mb-5 tab-content">

                        <!-- Primary Tab Start -->
                        <div class="tab-pane fade active show" id="generalTab" role="tabpanel">
                            <div class="row g-3 row-cols-3 mb-5">
                                <div class="col small-gutter-col">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h4 class="text-capitalize">{{ __('conceptual') }}</h4>
                                            <span class="display-1 text-primary">{{ $studyTime->conceptual }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col small-gutter-col">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h4 class="text-capitalize">{{ __('procedural') }}</h4>
                                            <span class="display-1 text-primary">{{ $studyTime->procedural }}%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col small-gutter-col">
                                    <div class="card">
                                        <div class="card-body text-center">
                                            <h4 class="text-capitalize">{{ __('attitudinal') }}</h4>
                                            <span class="display-1 text-primary">{{ $studyTime->attitudinal }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h2 class="small-title">{{ __('Performance ranges') }}</h2>
                            <div class="row g-3 row-cols-4">
                                <div class="col small-gutter-col">
                                    <div class="card border-2 border-danger">
                                        <div class="card-body text-center">
                                            <h5 class="text-capitalize">{{ __('low') }}</h5>
                                            <h4 class="font-weight-bold">{{ '(' . $studyTime->lowRange() . ')' }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col small-gutter-col">
                                    <div class="card border-2 border-warning">
                                        <div class="card-body text-center">
                                            <h5 class="text-capitalize">{{ __('basic') }}</h5>
                                            <h4 class="font-weight-bold">{{ '(' . $studyTime->basicRange() . ')' }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col small-gutter-col">
                                    <div class="card border-2 border-primary">
                                        <div class="card-body text-center">
                                            <h5 class="text-capitalize">{{ __('high') }}</h5>
                                            <h4 class="font-weight-bold">{{ '(' . $studyTime->highRange() . ')' }}</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col small-gutter-col">
                                    <div class="card border-2 border-success">
                                        <div class="card-body text-center">
                                            <h5 class="text-capitalize">{{ __('superior') }}</h5>
                                            <h4 class="font-weight-bold">{{ '(' . $studyTime->superiorRange() . ')' }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Periods Tab Start -->
                        <div class="tab-pane fade" id="periodsTab" role="tabpanel">

                            <!-- Top Content Start -->
                            <div class="row mb-3">

                                <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-center">
                                    <h4 class="m-0">{{ __('School year') . ': ' . $Y->name }}</h4>
                                </div>

                                <div
                                    class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 d-flex align-items-start justify-content-end">
                                    <a href="{{ route('studyTime.periods.edit', $studyTime) }}"
                                        class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                        @if ($periods->count())
                                            <i data-acorn-icon="edit-square"></i>
                                            <span>{{ __('Edit periods') }}</span>
                                        @else
                                            <i data-acorn-icon="plus"></i>
                                            <span>{{ __('Create periods') }}</span>
                                        @endif
                                    </a>
                                </div>

                            </div>
                            <!-- Top Content End -->


                            <!-- Periods Content Start -->
                            <div class="card">
                                <div class="card-body">
                                    <!-- Table Periods Start -->
                                    <div class="data-table-responsive-wrapper">
                                        <table id="datatable_periods" logro="dataTableBoxed"
                                            class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                            data-order='[]'>
                                            <thead>
                                                <tr>
                                                    <th class="text-muted text-small text-uppercase p-0 pb-2 empty">
                                                        {{ __('Name') }}</th>
                                                    <th class="text-muted text-small text-uppercase p-0 pb-2 empty text-center">
                                                        {{ __('Academic workload') }}
                                                    </th>
                                                    <th class="text-muted text-small text-uppercase p-0 pb-2 empty text-center">
                                                        {{ __('Start') }} / {{ __('End') }}
                                                    </th>
                                                    <th class="text-muted text-small text-uppercase p-0 pb-2 empty text-center">
                                                        {{ __('Grades upload') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($periods as $period)
                                                    <tr>
                                                        <td class="py-2 px-1">{{ $period->name }}</td>
                                                        <td class="py-2 px-1" align="center">{{ $period->workload }}%</td>
                                                        <td class="py-2 px-1" align="center">
                                                            {{ $period->startLabel() }}
                                                            <span class="font-weight-bold p-2">/</span>
                                                            {{ $period->endLabel() }}
                                                        </td>
                                                        <td class="py-2 px-1" align="center">{{ $period->dateUploadingNotes() }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Table Periods End -->
                                </div>
                            </div>
                            <!-- Periods Content End -->

                        </div>
                    </div>
                    <!-- Right Side End -->
                </section>

            </div>
        </div>
    </div>
@endsection
