@php
    $title = __('Dashboard');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/select2.js"></script>
    <script src="/js/pages/dashboard.teacher.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row g-0">
                <!-- Title Start -->
                <div class="col-12 col-md-7 mb-2 mb-md-0">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                    <!-- Dropdown Button Start -->
                    <div class="">
                        <button class="btn btn-sm btn-icon btn-icon-only btn-foreground shadow align-top mt-n2"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
                            <i data-acorn-icon="more-horizontal" data-acorn-size="15"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end shadow">
                            <div class="dropdown-item btn-icon btn-icon-start cursor-pointer" data-bs-toggle="modal"
                                data-bs-target="#addAnnotationObserverModal">
                                <span>Hacer anotación al Observador</span>
                            </div>
                        </div>
                    </div>
                    <!-- Dropdown Button End -->
                </div>
                <!-- Top Buttons End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Alerts Section Start -->
        <section class="scroll-section">
            <h2 class="small-title">{{ __('Alerts') }}</h2>
            <x-dash.alerts-students :content="$alertsStudents" />
        </section>
        <!-- Alerts Section End -->

        <!-- Add Annotation Observer Modal Start -->
        <div class="modal fade modal-close-out" id="addAnnotationObserverModal" tabindex="-1" role="dialog"
            aria-labelledby="addAnnotationObserverModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar anotación al Observador</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('students.observer.multiple') }}" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="row g-2">

                                <div class="col-12">
                                    <div class="w-100 form-group position-relative">
                                        <x-label required>{{ __('Select students') }}</x-label>
                                        <select multiple name="students_observer[]"
                                            id="students_observer"></select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="w-100 form-group position-relative">
                                        <x-label required>{{ __('select the type of annotation') }}</x-label>
                                        <select name="annotation_type" logro="select2" required>
                                            <option label="&nbsp;"></option>
                                            @foreach (\App\Models\Data\AnnotationType::getData() as $key => $annotation)
                                                <option value="{{ $key }}">{{ $annotation }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group position-relative">
                                        <x-label required>{{ __('date observation') }}</x-label>
                                        <x-input :value="old('date_observation', today()->format('Y-m-d'))" logro="datePickerBefore" name="date_observation"
                                            data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" class="text-center"
                                            required />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group position-relative">
                                        <x-label required>{{ __('situation description') }}</x-label>
                                        <textarea name="situation_description" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-outline-primary">Guardar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <!-- Add Annotation Observer Modal End -->

    </div>
@endsection
