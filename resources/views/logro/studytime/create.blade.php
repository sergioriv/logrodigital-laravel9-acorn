@php
    $title = __('Create Study Time');
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/studytime-create.js"></script>
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
                        class="tooltip-end-bottom" novalidate>
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
                                    <div class="col-md-2">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('minimun grade') }}</x-label>
                                            <x-input name="minimum_grade" id="minimum_grade"
                                                type="number" min="0.00" max="2.99" step="0.01"
                                                value="0.00" required />
                                        </div>
                                    </div>
                                    <div class="col-md-1">&nbsp;</div>
                                    <div class="col-md-2">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('low performance') }}</x-label>
                                            <x-input name="low_performance" id="low_performance"
                                                type="number" min="0.00" max="3.99" step="0.01"
                                                value="2.99" required />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('acceptable performance') }}</x-label>
                                            <x-input name="acceptable_performance" id="acceptable_performance"
                                                type="number" min="2.99" max="4.59" step="0.01"
                                                value="3.99" required />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('high performance') }}</x-label>
                                            <x-input name="high_performance" id="high_performance"
                                                type="number" min="3.99" max="5.00" step="0.01"
                                                value="4.59" required />
                                        </div>
                                    </div>
                                    <div class="col-md-1">&nbsp;</div>
                                    <div class="col-md-2">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('maximum grade') }}</x-label>
                                            <x-input name="maximum_grade" id="maximum_grade"
                                                type="number" min="4.59" step="0.01"
                                                value="5.00" required />
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
