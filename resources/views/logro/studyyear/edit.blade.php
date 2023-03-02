@php
$title = $studyYear->name;
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
                        <h1 class="mb-1 pb-0 display-4">{{ $title . ' | ' . __('Edit') }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">

                    <form method="POST" action="{{ route('studyYear.update', $studyYear) }}" class="tooltip-end-bottom"
                        novalidate>
                        @csrf
                        @method('PUT')

                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-6 mb-md-0 mb-3">
                                        <!-- Name -->
                                        <div class="form-group position-relative">
                                            <x-label>{{ __('Name') }}</x-label>
                                            <x-input name="name" :value="old('name', $studyYear->name)" required autofocus />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Belongs to -->
                                        <div class="w-100 form-group position-relative">
                                            <x-label>{{ __('belongs to') }}</x-label>
                                            <select name="study_year" logro="select2" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($resourceStudyYears as $resource)
                                                    <option value="{{ $resource->uuid }}"
                                                        @selected($resource->id == $studyYear->resource_study_year_id)>{{ __($resource->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <x-button type="submit" class="btn-primary">{{ __('Update') }}</x-button>

                    </form>

                </section>

            </div>
        </div>
    </div>
@endsection
