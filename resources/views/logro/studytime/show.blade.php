@php
    $title = $studyTime->name;
@endphp
@extends('layout', ['title' => $title])

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
                                        data-bs-toggle="tab" href="#primaryTab" role="tab">
                                        <span class="align-middle">{{ __('Primary') }}</span>
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
                        <div class="tab-pane fade active show" id="primaryTab" role="tabpanel">
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
                                            <h5 class="text-capitalize">{{ __('acceptable') }}</h5>
                                            <h4 class="font-weight-bold">{{ '(' . $studyTime->acceptableRange() . ')' }}
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
                                    <h4 class="m-0">{{ __('School year') .': '. $Y->name }}</h4>
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


                            <div class="row g-3">
                                @foreach ($periods as $period)
                                    <div class="card">
                                        <div class="card-body p-3 row">
                                            <div class="col-md-4">{{ $period->name }}</div>
                                            <div class="col-md-4">{{ $period->start }} <span
                                                    class="font-weight-bold p-2">/</span> {{ $period->end }}</div>
                                            <div class="col-md-2">{{ $period->workload }}%</div>
                                            <div class="col-md-2">{{ $period->days }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Edit Periods Button Start -->
                            <div class="col-12 mt-3 d-flex align-items-start justify-content-end">




                            </div>
                            <!-- Edit Periods Button End -->

                        </div>
                    </div>
                    <!-- Right Side End -->
                </section>

            </div>
        </div>
    </div>
@endsection
