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
                    <form method="POST" action="{{ route('subject.descriptors.store', $subject) }}" class="tooltip-end-bottom" novalidate>
                        @csrf

                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="row g-3">

                                    <!-- Study Year -->
                                    <div class="col-12">
                                        <div class="position-relative form-group w-100">
                                            <x-label>{{ __('Study Year') }}
                                                <x-required />
                                            </x-label>
                                            <select logro="select2" name="study_year" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($studyYears as $sy)
                                                    <option value="{{ $sy->uuid }}"
                                                        @selected(old('study_year') == $sy->uuid)>{{ __($sy->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div class="col-12">
                                        <div class="form-group">
                                            <x-label>{{ __('Content') }}</x-label>
                                            <textarea class="form-control" name="content" rows="2" required autofocus>{{ old('content') }}</textarea>
                                        </div>
                                    </div>

                                    <!-- Public Name -->
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input cursor-pointer" type="checkbox" id="switchInclusive"
                                            name="inclusive" value="1" />
                                            <label class="form-check-label cursor-pointer" for="switchInclusive">{{ __('Is it an inclusion descriptor?') }}</label>
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
