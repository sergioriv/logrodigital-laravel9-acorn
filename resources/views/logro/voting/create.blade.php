@php
    $title = __('Create vote');
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
        jQuery('#selectCandidates').select2({
            minimumResultsForSearch: 1,
            placeholder: '',
            templateSelection: function formatText(item) {
                if (jQuery(item.element).val()) {
                    return jQuery(
                        '<div><span class="align-middle d-inline-block me-2 badge bg-muted">' +
                        jQuery(item.element).data('group') +
                        '</span><span class="align-middle d-inline-block lh-1">' +
                        item.text +
                        '</span></div>',
                    );
                }
            },
            templateResult: function formatText(item) {
                if (jQuery(item.element).val()) {
                    return jQuery(
                        '<div><span class="align-middle d-inline-block me-2 badge bg-muted">' +
                        jQuery(item.element).data('group') +
                        '</span><span class="align-middle d-inline-block lh-1">' +
                        item.text +
                        '</span></div>',
                    );
                }
            },
        });
    </script>
@endsection

@section('content')
    <div class="container">

        <!-- Title Start -->
        <div class="d-flex align-items-center justify-content-center">
            <div class="display-1 text-uppercase">{{ $title }}</div>
        </div>
        <!-- Title End -->

        <!-- Content Start -->
        <section class="scroll-section mt-2">

            <form action="{{ route('voting.store') }}" method="POST">
                @csrf


                <div class="card mb-5">
                    <div class="card-body">

                        <p class="card-text text-alternate fst-italic mb-4">
                            Asigne un nombre a las votaciones.
                        </p>

                        <!-- Name -->
                        <div class="position-relative form-group">
                            <x-label class="h5" requried>{{ __('Name') }}</x-label>
                            <x-input :value="old('name')" name="name" class="display-4" required />
                        </div>


                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">

                        <p class="card-text text-alternate fst-italic mb-4">
                            Agregue a los estudiantes que serán candidatos.
                        </p>

                        <div class="w-100 position-relative form-group">
                            <x-label class="h5" required>{{ __('students') }}</x-label>
                            <x-select multiple name="candidates[]" id="selectCandidates" :hasError="'students'">
                                <option label="&nbsp;"></option>
                                @foreach ($students as $student)
                                    <option data-group="{{ $student->group->name }}" value="{{ $student->id }}">
                                        {{ $student->getCompleteNames() }} {{ $student->tag() }}</option>
                                @endforeach
                            </x-select>
                        </div>


                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">

                        <p class="card-text text-alternate fst-italic mb-4">
                            Seleccione los grupos que votarán.
                        </p>

                        <div class="row g-3">

                            <!-- Headquarters -->
                            <div class="col-md-4">
                                <div class="w-100 position-relative form-group">
                                    <x-label class="h5" required>{{ __('Headquarters') }}</x-label>
                                    <x-select multiple name="headquarters[]" logro="select2" :hasError="'headquarters'">
                                        <option label="&nbsp;"></option>
                                        @foreach ($headquarters as $hq)
                                            <option selected value="{{ $hq->id }}">{{ $hq->name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>

                            <!-- Study Times -->
                            <div class="col-md-4">
                                <div class="w-100 position-relative form-group">
                                    <x-label class="h5" required>{{ __('Study times') }}</x-label>
                                    <x-select multiple name="study_times[]" logro="select2" :hasError="'study_times'">
                                        <option label="&nbsp;"></option>
                                        @foreach ($studyTimes as $st)
                                            <option selected value="{{ $st->id }}">{{ $st->name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>

                            <!-- Study Years -->
                            <div class="col-md-4">
                                <div class="w-100 position-relative form-group">
                                    <x-label class="h5" required>{{ __('Study years') }}</x-label>
                                    <x-select multiple name="study_years[]" logro="select2" :hasError="'study_years'">
                                        <option label="&nbsp;"></option>
                                        @foreach ($studyYears as $sy)
                                            <option selected value="{{ $sy->id }}">{{ $sy->name }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">
                        <div class="text-center">
                            {{-- <h5 class="card-title">Thank You!</h5> --}}
                            <p class="card-text text-alternate mb-4">Recuerde el link para la votación: <a href="{{ config('app.url') .'/votacion' }}" target="_blank" rel="noopener noreferrer">{{ config('app.url') .'/votacion' }}</a></p>
                            <button class="btn btn-lg btn-primary" type="submit">
                                <span>Crear</span>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
        </section>
        <!-- Content End -->

    </div>
@endsection
