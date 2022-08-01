@php
$title = __('Data instructive');
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
    jQuery("[logro='select2']").select2({minimumResultsForSearch: 30, placeholder: ''})
            .change(function () {
                $('#' + $(this).data('reference')).html( $(this).val() )
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

                <h2 class="small-title">{{ __("document type") }} | (document_type)</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="w-100 position-relative form-group">
                                    <select data-reference="documentType" logro="select2">
                                        <option label="&nbsp;"></option>
                                        @foreach ($documentType as $dt)
                                        <option value="{{ $dt->code }}">
                                            {{ $dt->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span id="documentType" class="form-control"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="small-title">{{ __("Headquarters") }} | (headquarters)</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="w-100 position-relative form-group">
                                    <select data-reference="headquarters" logro="select2">
                                        <option label="&nbsp;"></option>
                                        @foreach ($headquarters as $hq)
                                        <option value="{{ $hq->name }}">
                                            {{ $hq->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span id="headquarters" class="form-control"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="small-title">{{ __("Study times") }} | (study_time)</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="w-100 position-relative form-group">
                                    <select data-reference="studyTime" logro="select2">
                                        <option label="&nbsp;"></option>
                                        @foreach ($studyTime as $st)
                                        <option value="{{ $st->name }}">
                                            {{ $st->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span id="studyTime" class="form-control"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="small-title">{{ __("Study years") }} | (study_year)</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="w-100 position-relative form-group">
                                    <select data-reference="studyYear" logro="select2">
                                        <option label="&nbsp;"></option>
                                        @foreach ($studyYear as $sy)
                                        <option value="{{ $sy->name }}">
                                            {{ $sy->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span id="studyYear" class="form-control"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
