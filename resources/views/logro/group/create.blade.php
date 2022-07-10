@php
$title = __('Create Group');
@endphp
@extends('layout',['title'=>$title])

@section('css')
<link rel="stylesheet" href="/css/vendor/select2.min.css" />
<link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
<script src="/js/vendor/select2.full.min.js"></script>
@endsection

@section('js_page')
<script>
    jQuery('#select2Headquarters').select2({minimumResultsForSearch: Infinity, placeholder: ''});
    jQuery('#select2StudyTime').select2({minimumResultsForSearch: Infinity, placeholder: ''});
    jQuery('#select2StudyYear').select2({minimumResultsForSearch: Infinity, placeholder: ''});
    jQuery('#select2Teacher').select2({minimumResultsForSearch: Infinity, placeholder: ''});
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Title Start -->
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="card mb-5">
                    <div class="card-body">

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('group.store') }}" novalidate>
                            @csrf

                            <!-- Headquarters -->
                            <div class="mb-3 w-100">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-label>{{ __('Headquarters') }}</x-label>
                                        <select id="select2Headquarters" name="headquarters" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($headquarters as $hq)
                                            <option value="{{ $hq->id }}">{{ $hq->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <x-label>{{ __('Study time') }}</x-label>
                                        <select id="select2StudyTime" name="study_time" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyTime as $st)
                                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <x-label>{{ __('Study year') }}</x-label>
                                        <select id="select2StudyYear" name="study_year" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyYear as $sy)
                                            <option value="{{ $sy->id }}">{{ $sy->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <x-label>{{ __('Group director') }}</x-label>
                                        <select id="select2Teacher" name="teacher" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($teachers as $tc)
                                            <option value="{{ $tc->id }}">{{ $tc->getFullName() }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <x-label>{{ __('Name') }}</x-label>
                                        <x-input name="name" :value="old('name')" required />
                                    </div>
                                </div>

                            </div>

                            <x-button type="submit" class="btn-primary">{{ __('Save group') }}</x-button>

                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
