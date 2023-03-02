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
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
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
                        <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. __('Periods') .' '. $Y->name }}</h1>
                    </div>
                </section>
                <!-- Title End -->

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
                                        <option value="{{ $i }}"
                                            @if (count($periods) === $i) selected @endif>
                                            {{ $i . ' ' . __('Periods') }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                    </section>

                    <section class="scroll-section">

                        <h2 class="small-title @if (count($periods) == 0) d-none @endif" id="periods-title">{{ __('Periods') }}</h2>

                        @foreach ($periods as $period)
                        <input type="hidden" name="period[{{ $period->ordering }}][id]" value="{{ $period->id }}" />
                            <div class="card mb-3" logro="periods" id="period-{{ $period->ordering }}">
                                <div class="card-body">
                                    <h2 class="small-title">{{ __('Period') . ' ' . $period->ordering }}</h2>
                                    <div class="row g-2">

                                        <!-- Name -->
                                        <div class="col-4">
                                            <div class="form-group position-relative">
                                                <x-label>{{ __('Name') }}</x-label>
                                                <x-input name="period[{{ $period->ordering }}][name]"
                                                    placeholder="{{ __('Name') }}" value="{{ $period->name }}" />
                                            </div>
                                        </div>

                                        <!-- Academic Workload -->
                                        <div class="col-2">
                                            <div class="form-group position-relative">
                                                <x-label>{{ __('Academic workload') }}</x-label>
                                                <div class="input-group">
                                                    <x-input type="number" name="period[{{ $period->ordering }}][workload]"
                                                        placeholder="{{ __('Academic workload') }}" value="{{ $period->workload }}" />
                                                    <span class="input-group-text logro-input-disabled">%</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Range Date Period -->
                                        <div class="col-3">
                                            <div class="form-group position-relative text-center">
                                                <x-label>{{ __('date range') }}</x-label>
                                                <div class="input-daterange input-group datePickerRange">
                                                    <x-input name="period[{{ $period->ordering }}][start]"
                                                        placeholder="{{ __('Start') }}" value="{{ $period->start }}" />
                                                    <span class="p-gutter"></span>
                                                    <x-input name="period[{{ $period->ordering }}][end]"
                                                        placeholder="{{ __('End') }}" value="{{ $period->end }}" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Range Date Period Active -->
                                        <div class="col-3">
                                            <div class="form-group position-relative text-center">
                                                <x-label>{{ __('Grades upload') }}</x-label>
                                                <div class="input-daterange input-group datePickerRange">
                                                    <x-input name="period[{{ $period->ordering }}][start_grades]"
                                                        placeholder="{{ __('Start') }}" value="{{ $period->start_grades }}" />
                                                    <span class="p-gutter"></span>
                                                    <x-input name="period[{{ $period->ordering }}][end_grades]"
                                                        placeholder="{{ __('End') }}" value="{{ $period->end_grades }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @for ($i = (count($periods) + 1); $i <= 15; $i++)
                            <div class="card mb-3 d-none" logro="periods" id="period-{{ $i }}">
                                <div class="card-body">
                                    <h2 class="small-title">{{ __('Period') . ' ' . $i }}</h2>
                                    <div class="row g-2">

                                        <!-- Name -->
                                        <div class="col-4">
                                            <div class="form-group position-relative">
                                                <x-label>{{ __('Name') }}</x-label>
                                                <x-input name="period[{{ $i }}][name]"
                                                    placeholder="{{ __('Name') }}" disabled="disabled" />
                                            </div>
                                        </div>

                                        <!-- Academic Workload -->
                                        <div class="col-2">
                                            <div class="form-group position-relative">
                                                <x-label>{{ __('Academic workload') }}</x-label>
                                                <x-input type="number" name="period[{{ $i }}][workload]"
                                                    placeholder="{{ __('Academic workload') }}" disabled="disabled" />
                                            </div>
                                        </div>

                                        <!-- Range Date Period -->
                                        <div class="col-3">
                                            <div class="form-group position-relative text-center">
                                                <x-label>{{ __('date range') }}</x-label>
                                                <div class="input-daterange input-group datePickerRange">
                                                    <x-input name="period[{{ $i }}][start]"
                                                        placeholder="{{ __('Start') }}" disabled="disabled" />
                                                    <span class="p-gutter"></span>
                                                    <x-input name="period[{{ $i }}][end]"
                                                        placeholder="{{ __('End') }}" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Range Date Period Active -->
                                        <div class="col-3">
                                            <div class="form-group position-relative text-center">
                                                <x-label>{{ __('Grades upload') }}</x-label>
                                                <div class="input-daterange input-group datePickerRange">
                                                    <x-input name="period[{{ $i }}][start_grades]"
                                                        placeholder="{{ __('Start') }}" disabled="disabled" />
                                                    <span class="p-gutter"></span>
                                                    <x-input name="period[{{ $i }}][end_grades]"
                                                        placeholder="{{ __('End') }}" disabled="disabled" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endfor

                        <x-button type="submit" class="btn-primary">{{ __('Save periods') }}</x-button>

                    </section>
                    <!-- content End -->
                </form>


            </div>
        </div>
    </div>
@endsection
