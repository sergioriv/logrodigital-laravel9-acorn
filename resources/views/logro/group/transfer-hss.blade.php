@php
$title = __('Transfer students');
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
    <script src="/js/forms/student-transfer.js"></script>
    <script src="/js/forms/select2.js"></script>
    <script>
        jQuery(".filter").change(function() {
            studentParentFilter();
        });

        function studentParentFilter() {

            $.get(HOST + "/students/parents.filter", {
                headquarters: jQuery("#headquarters").val(),
                studyTime: jQuery("#studyTime").val(),
                studyYear: jQuery("#studyYear").val(),
            }, function(data) {
                if (0 != data) {
                    jQuery("#transferAndMatriculate").removeAttr('disabled');
                } else {
                    jQuery("#transferAndMatriculate").prop('disabled', 'disabled');
                }
            });
        }

        jQuery("#transferAndMatriculate").click(function() {
            $("#matriculate").prop("checked", "checked");
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
                        <h1 class="mb-1 pb-0 display-4">
                            {{ __('Group') . ' | ' . $group->name . ' | ' . $title }}
                        </h1>
                        <div aria-label="breadcrumb">
                            <div class="breadcrumb">
                                <span class="breadcrumb-item text-muted">
                                    <div class="text-muted d-inline-block">
                                        <i data-acorn-icon="building-large" class="me-1" data-acorn-size="12"></i>
                                        <span class="align-middle">{{ $group->headquarters->name }}</span>
                                    </div>
                                </span>
                                <span class="breadcrumb-item text-muted">
                                    <div class="text-muted d-inline-block">
                                        <i data-acorn-icon="clock" class="me-1" data-acorn-size="12"></i>
                                        <span class="align-middle">{{ $group->studyTime->name }}</span>
                                    </div>
                                </span>
                                <span class="breadcrumb-item text-muted">
                                    <div class="text-muted d-inline-block">
                                        <i data-acorn-icon="calendar" class="me-1" data-acorn-size="12"></i>
                                        <span class="align-middle">{{ $group->studyYear->name }}</span>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('group.transfer-students.hss', $group) }}" class="tooltip-label-end"
                        id="studentTransferForm" novalidate autocomplete="off">
                        @csrf

                        <input type="hidden" value="{{ $students }}" name="students">

                        <div class="card mb-5">
                            <div class="card-body">


                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('headquarters') }} <x-required/></x-label>
                                            <select name="headquarters" id="headquarters" class="filter" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($headquarters as $hq)
                                                    <option value="{{ $hq->id }}" @selected(old('headquarters', $group->headquarters_id) == $hq->id)>
                                                        {{ $hq->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('study time') }} <x-required/></x-label>
                                            <select name="studyTime" id="studyTime" class="filter" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($studyTime as $st)
                                                    <option value="{{ $st->id }}" @selected(old('studyTime', $group->study_time_id) == $st->id)>
                                                        {{ $st->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('study year') }} <x-required/></x-label>
                                            <select name="studyYear" id="studyYear" class="filter" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($studyYear as $sy)
                                                    <option value="{{ $sy->id }}" @selected(old('studyYear', $group->study_year_id) == $sy->id)>
                                                        {{ $sy->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="checkbox" name="matriculate" id="matriculate" class="d-none" value="1">
                        <button class="btn btn-primary" id="transferAndMatriculate" type="submit"
                            @if (0 === $countGroups) disabled="disabled" @endif>
                            {{ __('Transfer') }}
                        </button>

                    </form>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
