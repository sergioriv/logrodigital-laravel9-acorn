@php
    $title = __('Documents');
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
    <script>
        jQuery("[logro='studentDocument']").click(function() {
            jQuery('#modalStudentDocuments img').attr('src', $(this).data('image'));
        });

        jQuery("#selectStudentDocument").change(function() {

            var info = $(this).find('option:selected').attr('fileInfo');

            $('#modalStudentDocumentsInfo .modal-title').html(info);
            $('#modalStudentDocumentsInfo').modal('show');

            jQuery("#infoStudentDocument").removeClass('d-none').html(info);
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
                        <div class="row">
                            <!-- Title Start -->
                            <div class="col-12 col-md-8 mb-2 mb-md-0">
                                <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                            </div>
                            <!-- Title End -->
                            <div class="col-12 col-md-4 d-flex align-items-start justify-content-end">

                                @if ($countGroupsYear)
                                <a href="{{ route('students.pdf.report_grades', $student) }}" class="btn btn-outline-info btn-icon">
                                    <i data-acorn-icon="download"></i>
                                    <span>Informe de notas 2023</span>
                                </a>
                                @endif

                            </div>
                        </div>
                    </div>
                </section>
                <!-- Title End -->

                {{-- @error('custom')
                    <x-validation-errors class="mb-4" :errors="$errors" />
                @enderror --}}

                <section class="scroll-section">
                    <div class="card mb-5 wizard">
                        <div class="card-header border-0 pb-0">
                            <ul class="nav nav-tabs justify-content-center disabled" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center active" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Documents') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Report books') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Persons in Charge') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Personal Information') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item d-none" role="presentation">
                                    <a class="nav-link text-center" role="tab"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" role="tabpanel">

                                    <div class="card-header">
                                        <form method="POST" action="{{ route('students.file', $student) }}"
                                            enctype="multipart/form-data" class="tooltip-label-end" novalidate>
                                            @csrf
                                            @method('PUT')

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="w-100 position-relative form-group">
                                                        <select data-placeholder="Seleccione documento" name="file_type"
                                                            logro="select2" id="selectStudentDocument">
                                                            <option label="&nbsp;"></option>
                                                            @foreach ($studentFileTypes as $fileType)
                                                                @if ($fileType->studentFile === null)
                                                                    <option value="{{ $fileType->id }}"
                                                                        fileInfo="{{ $fileType->description }}"
                                                                        @selected(old('file_type') == $fileType->id)>
                                                                        {{ $fileType->name }}
                                                                        @if (1 === $fileType->required)
                                                                            *
                                                                        @endif
                                                                    </option>
                                                                @else
                                                                    @if ($fileType->studentFile->checked !== 1)
                                                                        <option value="{{ $fileType->id }}"
                                                                            fileInfo="{{ $fileType->description }}"
                                                                            @selected(old('file_type') == $fileType->id)>
                                                                            {{ $fileType->name }}
                                                                            @if (1 === $fileType->required)
                                                                                *
                                                                            @endif
                                                                        </option>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group">
                                                        <x-input type="file" name="file_upload"
                                                            accept="image/jpg, image/jpeg, image/png, image/webp"
                                                            class="d-block" />
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-2 col-md-2 border-0 pt-0 d-flex justify-content-end align-items-start">
                                                    <x-button class="btn-primary" type="submit">{{ __('Upload') }}
                                                    </x-button>
                                                </div>
                                            </div>
                                            <div class="row mt-3 g-3 text-danger d-none" id="infoStudentDocument"></div>

                                        </form>
                                    </div>

                                    <div class="card-body">

                                        <div class="row g-2 row-cols-3 row-cols-md-5">
                                            @foreach ($studentFileTypes as $studentFile)
                                                <div class="col small-gutter-col">
                                                    <div class="h-100">
                                                        <div class="text-center d-flex flex-column">
                                                            <div>
                                                                @if ($studentFile->studentFile ?? null !== null)
                                                                    <i class="icon icon-70 cursor-pointer
                                                                            @if ($studentFile->studentFile->checked == 1) bi-file-earmark-check-fill text-muted
                                                                            @else bi-file-earmark-fill text-info @endif"
                                                                        logro="studentDocument"
                                                                        data-image="{{ $studentFile->studentFile->url }}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#modalStudentDocuments"></i>
                                                                @else
                                                                    <i class="icon bi-file-earmark icon-70 text-muted"></i>
                                                                @endif
                                                            </div>
                                                            <div>
                                                                {{ $studentFile->name }}
                                                                @if (1 === $studentFile->required)
                                                                    <x-required />
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <form action="{{ route('students.file.wizard.next', $student) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <!-- Documents Required Start -->
                                <section>
                                    @php $fileFails = 0 @endphp
                                    @foreach ($studentFileTypes as $studentFileRequired)
                                        @if (1 === $studentFileRequired->required && null === $studentFileRequired->studentFile)
                                            @php ++$fileFails @endphp
                                        @endif
                                    @endforeach
                                    <input type="hidden" name="docsFails" value="{{ $fileFails }}">
                                </section>
                                <!-- Documents Required End -->

                                <button class="btn btn-icon btn-icon-end btn-outline-primary btn-next" type="submit">
                                    <span>{{ __('Continue') }}</span>
                                    <i data-acorn-icon="chevron-right" class="icon" data-acorn-size="18"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- Modal Document Images -->
    <div class="modal fade modal-close-out" id="modalStudentDocuments" tabindex="-1" role="dialog"
        aria-labelledby="Document" aria-hidden="true">
        <div class="modal-dialog modal-semi-full modal-dialog-centered logro-modal-image">
            <img src="\img\other\none.png" alt="document">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>

    <!-- Modal Student Document Info -->
    <div class="modal fade" id="modalStudentDocumentsInfo" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title h5 text-danger"></h5>
                    <button type="button" class="btn btn-outline-primary ms-2" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
@endsection
