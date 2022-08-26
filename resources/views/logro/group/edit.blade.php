@php
$title = $group->name;
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
                    <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="card mb-5">
                    <div class="card-body">

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('group.update', $group) }}" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Headquarters -->
                            <div class="mb-3 w-100">

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <x-label>{{ __('Headquarters') }} <x-required/></x-label>
                                        <select id="select2Headquarters" name="headquarters" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($headquarters as $hq)
                                            <option value="{{ $hq->id }}"
                                                @if ($group->headquarters_id !== null) @selected($group->headquarters_id === $hq->id) @endif>
                                                {{ $hq->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <x-label>{{ __('Study time') }} <x-required/></x-label>
                                        <select id="select2StudyTime" name="study_time" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyTime as $st)
                                            <option value="{{ $st->id }}"
                                                @if ($group->study_time_id !== null) @selected($group->study_time_id === $st->id) @endif>
                                                {{ $st->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <x-label>{{ __('Study year') }} <x-required/></x-label>
                                        <select id="select2StudyYear" name="study_year" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($studyYear as $sy)
                                            <option value="{{ $sy->id }}"
                                                @if ($group->study_year_id !== null) @selected($group->study_year_id === $sy->id) @endif>
                                                {{ $sy->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <x-label>{{ __('Group director') }}</x-label>
                                        <select id="select2Teacher" name="teacher">
                                            <option label="&nbsp;"></option>
                                            @foreach ($teachers as $tc)
                                            <option value="{{ $tc->id }}"
                                                @if ($group->teacher_id !== null) @selected($group->teacher_id === $tc->id) @endif>
                                                {{ $tc->getFullName() }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <x-label>{{ __('Name') }} <x-required/></x-label>
                                        <x-input name="name" :value="$group->name" required />
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
