@php
$title = $student->getFullName();
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
    <script src="/js/forms/select2.js"></script>
    {{-- <script src="/js/forms/student-advices.js"></script> --}}
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title . ' | ' . __('advice') . ': ' . $advice->dateFull() }}
                        </h1>
                    </div>
                    <a href="{{ URL::previous() }}" class="muted-link pb-3 d-inline-block lh-1">
                        <i class="me-1" data-acorn-icon="chevron-left" data-acorn-size="13"></i>
                        <span class="text-small align-middle">{{ __('Go back') }}</span>
                    </a>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('students.tracking.evolution.store', [$student, $advice]) }}"
                        class="tooltip-center-bottom" id="studentAdviceEditForm" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="card mb-5">
                            <div class="card-body w-100">

                                <div class="row mb-3 position-relative">
                                    <label class="col-sm-3 col-form-label">
                                        {{ __('Attendance') }} <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-select name="attendance" id="attendance" logro="select2" required>
                                            <option value="done" @selected(old('attendance') == 'done')>{{ __('Done') }}</option>
                                            <option value="not done" @selected(old('attendance') == 'not done')>{{ __('Not done') }}
                                            </option>
                                        </x-select>
                                    </div>
                                </div>

                                <div class="row mb-3 position-relative">
                                    <label class="col-sm-3 col-form-label">
                                        {{ __('Type advice') }} <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-select name="type_advice" logro="select2">
                                            @foreach ($advice->enumTypeAdvice() as $typeAdvice)
                                                <option value="{{ $typeAdvice }}" @selected(old('type_advice') == $typeAdvice)>
                                                    {{ __($typeAdvice) }}
                                                </option>
                                            @endforeach
                                        </x-select>
                                    </div>
                                </div>
                                <div class="row mb-5 position-relative">
                                    <label class="col-sm-3 col-form-label">
                                        {{ __('Evolución') }} <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <textarea name="evolution" id="evolution" class="form-control" rows="5" required>{{ old('evolution') }}</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>

                    </form>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
