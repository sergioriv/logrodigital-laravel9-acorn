@php
    $title = __('Create Study Time');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/studytime-create.js?d=1671455422797"></script>
    <script src="/js/forms/select2.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">
                    <div class="mb-5 wizard">
                        <div class="border-0 pb-0">
                            <ul class="nav nav-tabs justify-content-center disabled" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center active" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Main Information') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center" role="tab">
                                        <div class="mb-1 text-muted title d-none d-sm-block">{{ __('Periods') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <section class="scroll-section">

                    <form method="POST" id="studyTimeCreateForm" action="{{ route('studyTime.store') }}"
                        class="tooltip-start-bottom" novalidate>
                        @csrf


                        <!-- Info primary -->
                        <div class="card mb-5">
                            <div class="card-body">

                                <div class="row g-3">

                                    <!-- Name -->
                                    <div class="col-md-6 form-group position-relative">
                                        <x-label>{{ __('Name') }}
                                            <x-required />
                                        </x-label>
                                        <x-input :value="old('name')" name="name" id="inputName" :hasError="true"
                                            required />
                                    </div>

                                    <!-- Missing Areas -->
                                    <div class="col-md-6 form-group position-relative">
                                        <x-label>{{ __('Is it lost by learning area?') }} {{ __('how many?') }}</x-label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input name="missing_areas_check" id="checkMissingAreas"
                                                    class="form-check-input mt-0" type="checkbox" value="1" />
                                            </div>
                                            <x-input :value="old('missing_areas')" name="missing_areas" id="missingAreas"
                                                :hasError="true" disabled="true" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <!-- Evaluation Components Start -->
                        <h2 class="small-title logro-label">{{ __('evaluation components') }}</h2>
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4 form-group position-relative">
                                        <x-label>{{ __('conceptual') }}
                                            <x-required />
                                        </x-label>
                                        <div class="input-group">
                                            <x-input name="conceptual" id="conceptual" :value="old('conceptual', 40)" required />
                                            <span class="input-group-text logro-input-disabled">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group position-relative">
                                        <x-label>{{ __('procedural') }}
                                            <x-required />
                                        </x-label>
                                        <div class="input-group">
                                            <x-input name="procedural" id="procedural" :value="old('procedural', 40)" required />
                                            <span class="input-group-text logro-input-disabled">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group position-relative">
                                        <x-label>{{ __('attitudinal') }}
                                            <x-required />
                                        </x-label>
                                        <div class="input-group">
                                            <x-input name="attitudinal" id="attitudinal" :value="old('attitudinal', 20)" required />
                                            <span class="input-group-text logro-input-disabled">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Evaluation Components End -->


                        <!-- Performance Start -->
                        <h2 class="small-title logro-label">{{ __('performance range') }}</h2>
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('number of decimal places for qualification') }}
                                            </x-label>
                                            <x-input name="decimal" id="decimal" value="2" type="number" min="0"
                                                max="2" />
                                            <div class="form-text">min: 0 - max: 2</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label required>{{ __('round') }}</x-label>
                                            <select name="round" logro='select2'>
                                                <option value="up" selected>{{ __('Upward') }}</option>
                                                <option value="down">{{ __('Downward') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="col small-gutter-col">
                                            <div class="border-2 rounded-md border border-danger">
                                                <div class="card-body text-center">
                                                    <h5 class="text-capitalize">{{ __('low performance') }}</h5>
                                                    <div class="row g-2">
                                                        <div class="col-6 position-relative form-group">
                                                            <x-input name="minimum_grade" id="minimum_grade" type="number"
                                                                min="0.00" max="2.98" step="0.01"
                                                                value="0.00" class="text-center" required />
                                                        </div>
                                                        <div class="col-6 position-relative form-group">
                                                            <x-input name="low_performance" id="low_performance"
                                                                type="number" min="0.01" max="3.99"
                                                                step="0.01" value="2.99" class="text-center"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col small-gutter-col">
                                            <div class="border-2 rounded-md border border-warning">
                                                <div class="card-body text-center">
                                                    <h5 class="text-capitalize">{{ __('basic performance') }}</h5>
                                                    <div class="row g-2">
                                                        <div class="col-6 position-relative form-group">
                                                            <div id="minBasic" class="form-control bg-light text-muted">3.00</div>
                                                        </div>
                                                        <div class="col-6 position-relative form-group">
                                                            <x-input name="basic_performance"
                                                                id="basic_performance" type="number" min="3.01"
                                                                max="4.59" step="0.01" value="3.99"
                                                                class="text-center" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col small-gutter-col">
                                            <div class="border-2 rounded-md border border-primary">
                                                <div class="card-body text-center">
                                                    <h5 class="text-capitalize">{{ __('high performance') }}</h5>
                                                    <div class="row g-2">
                                                        <div class="col-6 position-relative form-group">
                                                            <div id="minHigh" class="form-control bg-light text-muted">4.00</div>
                                                        </div>
                                                        <div class="col-6 position-relative form-group">
                                                            <x-input name="high_performance" id="high_performance"
                                                                type="number" min="4.01" max="5.00"
                                                                step="0.01" value="4.59" class="text-center"
                                                                required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="col small-gutter-col">
                                            <div class="border-2 rounded-md border border-success">
                                                <div class="card-body text-center">
                                                    <h5 class="text-capitalize">{{ __('superior performance') }}</h5>
                                                    <div class="row g-2">
                                                        <div class="col-6 position-relative form-group">
                                                            <div id="minSuperior" class="form-control bg-light text-muted">4.60</div>
                                                        </div>
                                                        <div class="col-6 position-relative form-group">
                                                            <x-input name="maximum_grade" id="maximum_grade"
                                                                type="number" min="4.61" step="0.01"
                                                                value="5.00" class="text-center" required />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Performance End -->


                        <div class="text-center">
                            <x-button type="submit" class="btn-primary btn-icon btn-icon-end">
                                <span>{{ __('Continue') }}</span>
                                <i data-acorn-icon="chevron-right" class="icon" data-acorn-size="18"></i>
                            </x-button>
                        </div>

                    </form>


                </section>

            </div>
        </div>
    </div>
@endsection
