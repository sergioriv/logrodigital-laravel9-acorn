@php
    $title = __('Create descriptor');
@endphp
@extends('layout', ['title' => $title])

@section('css')
<link rel="stylesheet" href="/css/vendor/select2.min.css" />
<link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
<script src="/js/vendor/select2.full.min.js"></script>
<script src="/js/vendor/select2.full.min.es.js"></script>
@endsection

@section('js_page')
<script src="/js/forms/select2.js"></script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. $subject->name }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">
                    <form method="POST" action="{{ route('subject.descriptors.store', $subject) }}" class="tooltip-end-bottom">
                        @csrf

                        <div class="card mb-3">
                            <div class="card-body">

                                <div>

                                    <!-- Study Year -->
                                    <div class="row mb-3 align-items-start form-group">
                                        <label
                                            class="col-sm-5 col-md-4 col-lg-3 col-form-label"
                                        >{{ __('Study Year') }} <x-required /></label>
                                        <div
                                            class="col-sm-7 col-md-8 col-lg-9 position-relative">
                                            <select logro="select2" name="study_year" class="w-100" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($studyYears as $sy)
                                                    <option value="{{ $sy->uuid }}"
                                                        @selected(old('study_year') == $sy->uuid)>{{ __($sy->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Period -->
                                    <div class="row mb-3 align-items-start form-group">
                                        <label
                                            class="col-sm-5 col-md-4 col-lg-3 col-form-label"
                                        >{{ __('Period') }} <x-required /></label>
                                        <div
                                            class="col-sm-7 col-md-8 col-lg-9 position-relative">
                                            <select logro="select2" name="period" class="w-100" required>
                                                <option label="&nbsp;"></option>
                                                @for ($period = 1; $period <= 6; $period++)
                                                    <option
                                                        value="{{ $period }}"
                                                        @selected(old('period') == $period)
                                                    >{{ __('Period') }} {{ $period }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Is Inclusive -->
                                    <div class="row mb-3 align-items-start form-group">
                                        <label
                                            class="col-sm-5 col-md-4 col-lg-3 col-form-label"
                                        >{{ __('Is it an inclusion descriptor?') }} <x-required /></label>
                                        <div
                                            class="col-sm-7 col-md-8 col-lg-9 position-relative">
                                            <select logro="select2" name="inclusive" class="w-100" required>
                                                <option value="0">{{ __('No') }}</option>
                                                <option value="1">{{ __('Yes') }}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="row align-items-start form-group">
                                        <label
                                            class="col-sm-5 col-md-4 col-lg-3 col-form-label">{{ __('Content') }} <x-required /></label>
                                        <div
                                            class="col-sm-7 col-md-8 col-lg-9 position-relative">
                                            <textarea class="form-control" name="content" rows="2" maxlength="1000" required autofocus>{{ old('content') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-button type="submit" class="btn-primary">{{ __('Create') }}</x-button>

                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
