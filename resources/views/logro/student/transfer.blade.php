@php
$title = __('Student transfer');
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

            $.get("../parents.filter", {
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
                        <h1 class="mb-1 pb-0 display-4">{{ $student->getFullName() .' | '. $title }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('students.transfer.store', $student) }}" class="tooltip-label-end"
                        id="studentTransferForm" novalidate autocomplete="off">
                        @csrf

                        <!-- Validation Errors -->
                        {{-- <x-validation-errors class="mb-4" :errors="$errors" /> --}}

                        <div class="card mb-5">
                            <div class="card-body">


                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <div class="mb-3 w-100 position-relative form-group">
                                            <x-label>{{ __('headquarters') }} <x-required/></x-label>
                                            <select name="headquarters" id="headquarters" class="filter" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($headquarters as $hq)
                                                    <option value="{{ $hq->id }}" @selected(old('headquarters', $student->headquarters_id) == $hq->id)>
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
                                                    <option value="{{ $st->id }}" @selected(old('studyTime', $student->study_time_id) == $st->id)>
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
                                                    <option value="{{ $sy->id }}" @selected(old('studyYear', $student->study_year_id) == $sy->id)>
                                                        {{ $sy->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <x-button class="btn-primary" type="submit">{{ __('Transfer') }}</x-button>

                        <input type="checkbox" name="matriculate" id="matriculate" class="d-none" value="1">
                        <button class="btn btn-outline-primary" id="transferAndMatriculate" type="submit"
                            @if (0 === $countGroups) disabled="disabled" @endif>
                            {{ __('Transfer and Matriculate') }}
                        </button>

                    </form>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
