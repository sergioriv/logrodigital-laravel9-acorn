@php
$title = $studyTime->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
    <script>
        jQuery('#number_periods').select2({
            minimumResultsForSearch: Infinity,
            placeholder: ''
        });

        jQuery("#number_periods").change(function() {
            $("[logro='periods']").addClass('d-none')
            $("[logro='periods'] input").attr('disabled', 'disabled');

            if ($(this).val() > 0) {

                $("#periods-title").removeClass('d-none');

                for (let i = 1; i <= $(this).val(); i++) {
                    $("#period-" + i).removeClass('d-none');
                    $("#period-" + i + " input").removeAttr('disabled');
                }
            } else {
                $("#periods-title").addClass('d-none');
            }
        });

        jQuery('.datePickerRange').datepicker({
            weekStart: 1,
            language: 'es',
            format: 'yyyy-mm-dd',
            autoclose: true,
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. __('Periods') }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">
                    <div class="mb-5 wizard">
                        <div class="border-0 pb-0">
                            <ul class="nav nav-tabs justify-content-center disabled" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 text-muted title d-none d-sm-block">{{ __('Main Information') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center active" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Periods') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <form action="{{ route('studyTime.periods.store', $studyTime) }}" method="post" novalidate autocomplete="off">
                    @csrf

                    <!-- content Start -->
                    <section class="scroll-section">
                        <h2 class="small-title">{{ __('Number of periods') }}</h2>
                        <div class="card mb-3">
                            <div class="card-body w-100">
                                <select name="number_periods" id="number_periods"
                                    data-placeholder="{{ __('Number of periods') }}">
                                    <option label="&nbsp;"></option>
                                    @for ($i = 1; $i <= 15; $i++)
                                        <option value="{{ $i }}">{{ $i . ' ' . __('Periods') }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                    </section>

                    <section class="scroll-section">

                        @for ($i = 1; $i <= 15; $i++)
                            <div class="card mb-3 d-none" logro="periods" id="period-{{ $i }}">
                                <div class="card-body">
                                    <h2 class="small-title">{{ __('Period') . ' ' . $i }}</h2>
                                    <div class="row">
                                        <div class="col-4">
                                            <x-input name="period[{{ $i }}][name]"
                                                placeholder="{{ __('Name') }}" disabled="disabled" />
                                        </div>
                                        <div class="col-4">
                                            <div class="input-daterange input-group datePickerRange">
                                                <x-input name="period[{{ $i }}][start]"
                                                    placeholder="{{ __('Start') }}" disabled="disabled" />
                                                <span class="p-gutter"></span>
                                                <x-input name="period[{{ $i }}][end]"
                                                    placeholder="{{ __('End') }}" disabled="disabled" />
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <x-input type="number" name="period[{{ $i }}][workload]"
                                                placeholder="{{ __('Academic workload') }}" disabled="disabled" />
                                        </div>
                                        <div class="col-2">
                                            <x-input type="number" name="period[{{ $i }}][days]"
                                                placeholder="{{ __('Deadline days') }}" disabled="disabled" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor

                        <div class="text-center">
                            <x-button type="submit" class="btn-primary btn-icon btn-icon-end">
                                <span>{{ __('Finish') }}</span>
                                <i data-acorn-icon="chevron-right" class="icon" data-acorn-size="18"></i>
                            </x-button>
                        </div>

                    </section>
                    <!-- content End -->
                </form>

            </div>
        </div>
    </div>
@endsection
