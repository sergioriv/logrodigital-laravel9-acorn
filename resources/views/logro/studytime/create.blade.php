@php
$title = __('Create Study Time');
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
    <script src="/js/vendor/imask.js"></script>
@endsection

@section('js_page')
    <script>
        IMask(document.querySelector('#conceptual'), {
            mask: Number,
            min: 0,
            max: 100,
        });
        IMask(document.querySelector('#inputMissingAreas'), {
            mask: Number,
            min: 1,
            max: 10,
        });

        jQuery('#checkMissingAreas').change(function() {
            let checked = $(this).prop('checked');
            if (1 == checked) {
                $('#inputMissingAreas').prop('disabled', false);
            } else {
                $('#inputMissingAreas').prop('disabled', true);
            }
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
                        <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">

                    <form method="POST" action="{{ route('studyTime.store') }}" class="tooltip-end-bottom" novalidate>
                        @csrf

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
                                        <x-label>{{ __('Missing areas') }}</x-label>
                                        <div class="input-group">
                                            <div class="input-group-text">
                                                <input name="missing_areas_check" id="checkMissingAreas"
                                                    class="form-check-input mt-0" type="checkbox" value="1" />
                                            </div>
                                            <x-input :value="old('missing_areas')" name="missing_areas" id="inputMissingAreas"
                                                :hasError="true" disabled="true" />
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Evaluation Components Start -->
                        <h2 class="small-title text-capitalize">{{ __('evaluation components') }}</h2>
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4 form-group position-relative">
                                        <x-label>{{ __('conceptual') }} <x-required /></x-label>
                                        <div class="input-group">
                                            <x-input name="conceptual" id="conceptual" :value="old('conceptual', 40)" required />
                                            <span class="input-group-text logro-input-disabled">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group position-relative">
                                        <x-label>{{ __('procedural') }} <x-required /></x-label>
                                        <div class="input-group">
                                            <x-input name="procedural" id="procedural" :value="old('procedural', 40)" required />
                                            <span class="input-group-text logro-input-disabled">%</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group position-relative">
                                        <x-label>{{ __('attitudinal') }} <x-required /></x-label>
                                        <div class="input-group">
                                            <x-input name="attitudinal" id="attitudinal" :value="old('attitudinal', 20)" required />
                                            <span class="input-group-text logro-input-disabled">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Evaluation Components End -->

                        <x-button type="submit" class="btn-primary">{{ __('Save Study Time') }}</x-button>
                    </form>


                </section>

            </div>
        </div>
    </div>
@endsection
