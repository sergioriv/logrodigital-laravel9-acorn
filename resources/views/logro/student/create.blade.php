@php
$title = __('Create') .' '. __('Student');
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

            <!-- Content Start -->
            <section class="scroll-section">
                <form method="post" action="{{ route('students.store') }}" class="tooltip-end-bottom" id="teacherForm">
                    @csrf

                    <!-- Validation Errors -->
                    <x-validation-errors class="mb-4" :errors="$errors" />

                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <x-label>{{ __("first name") }}</x-label>
                                        <x-input :value="old('firstName')" name="firstName" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <x-label>{{ __("second name") }}</x-label>
                                        <x-input :value="old('secondName')" name="secondName" />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <x-label>{{ __("father's last name") }}</x-label>
                                        <x-input :value="old('fatherLastName')" name="fatherLastName" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <x-label>{{ __("mother's last name") }}</x-label>
                                        <x-input :value="old('motherLastName')" name="motherLastName" />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <x-label>{{ __("institutional email") }}</x-label>
                                        <x-input :value="old('institutional_email')" name="institutional_email" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 w-100">
                                        <x-label>{{ __("headquarters") }}</x-label>
                                        <select name="headquarters" id="select2Headquarters">
                                            <option label="&nbsp;"></option>
                                            @foreach ($headquarters as $hq)
                                            <option value="{{ $hq->id }}" @selected(old('headquarters') == $hq->id)>
                                                {{ $hq->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 w-100">
                                        <x-label>{{ __("study time") }}</x-label>
                                        <select name="studyTime" id="select2StudyTime">
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyTime as $st)
                                            <option value="{{ $st->id }}" @selected(old('studyTime') == $st->id)>
                                                {{ $st->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 w-100">
                                        <x-label>{{ __("study year") }}</x-label>
                                        <select name="studyYear" id="select2StudyYear">
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyYear as $sy)
                                            <option value="{{ $sy->id }}" @selected(old('studyYear') == $sy->id)>
                                                {{ $sy->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <x-button class="btn-primary" type="submit">{{ __("Save") }}</x-button>

                </form>
            </section>
            <!-- Content End -->

        </div>
    </div>
</div>
@endsection
