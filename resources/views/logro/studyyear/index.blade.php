@php
    $title = __('Study years list');
@endphp
@extends('layout', ['title' => $title])

@section('css')
<link rel="stylesheet" href="/css/vendor/select2.min.css" />
<link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
<script src="/js/vendor/bootstrap-submenu.js"></script>
<script src="/js/vendor/mousetrap.min.js"></script>
<script src="/js/vendor/select2.full.min.js"></script>
<script src="/js/vendor/select2.full.min.es.js"></script>
@endsection

@section('js_page')
<script src="/js/forms/select2.js"></script>
<script>
    let downloadConsolidateModal = $('#downloadConslidateModal');
    function downloadConsolidate(studyYear) {
        downloadConsolidateModal.find('#downloadConslidateId').val(studyYear);
        downloadConsolidateModal.modal('show');
    }
</script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7 mb-2 mb-md-0">
                            <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                        </div>
                        <!-- Title End -->

                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                            <!-- Add New Button Start -->
                            <a href="{{ route('studyYear.create') }}"
                                class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                <i data-acorn-icon="plus"></i>
                                <span>{{ __('Add New') }}</span>
                            </a>
                            <!-- Add New Button End -->
                        </div>
                        <!-- Top Buttons End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->


                <!-- Content Start -->
                <section class="scroll-section">
                    <div class="row g-3 row-cols-2 row-cols-md-4 row-cols-lg-5 row-cols-xl-6">
                        @foreach ($studyYears as $studyYear)
                            <div class="col small-gutter-col">
                                <div class="card">
                                    <div class="card-body text-center d-flex flex-column">
                                        <h4 class="mb-0 d-inline-block">{{ $studyYear->name }}
                                            <a class="font-weight-bold ms-1"
                                                href="{{ route('studyYear.edit', $studyYear) }}">
                                                <i data-acorn-icon="pen" data-acorn-size="11"></i>
                                            </a>
                                        </h4>
                                        <div class="text-small text-muted">{{ __($studyYear->resource->name) }}</div>

                                        <hr />

                                        @if (is_null($Y->available))
                                            <a onclick="downloadConsolidate('{{ $studyYear->id }}')"
                                            href="#">Descargar consolidado</a>
                                        @else
                                            <a
                                                href="{{ route('studyYear.subject.show', $studyYear) }}">{{ __('Subjects') . ' ' . $Y->name }}</a>
                                            @if ($studyYear->groups_count)
                                            <a
                                            href="{{ route('studyYear.groups-guide', $studyYear) }}">Descargar planillas</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
                <!-- Content End -->
            </div>
        </div>
    </div>

    <!-- Modal Accept or Deny Permit Start -->
    <div class="modal fade" id="downloadConslidateModal" aria-labelledby="modaldownloadConslidate"
    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modaldownloadConslidate">{{ __('Consolidation grades') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('studyYear.consolidate-grades') }}" method="POST">
                @csrf

                <div class="modal-body">

                    <input type="hidden" name="downloadConslidateId" id="downloadConslidateId" value="">

                    <div class="mb-3 w-100 position-relative form-group">
                        <x-label>{{ __('Study time') }}
                            <x-required />
                        </x-label>
                        <select name="downloadConslidateStudyTime" id="downloadConslidateStudyTime" logro="select2">
                            <option label="&nbsp;"></option>
                            @foreach (\App\Models\StudyTime::all() as $studyTime)
                                <option value="{{ $studyTime->id }}">
                                    {{ $studyTime->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-outline-primary">{{ __('Download') }}</button>
                </div>

            </form>
        </div>
    </div>
    </div>
@endsection
