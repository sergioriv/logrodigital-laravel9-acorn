@php
$title = $student->getFullName();
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. __("advice") .': ' . $advice->dateFull() }}</h1>
                    </div>
                    <a href="{{ URL::previous() }}" class="muted-link pb-3 d-inline-block lh-1">
                        <i class="me-1" data-acorn-icon="chevron-left" data-acorn-size="13"></i>
                        <span class="text-small align-middle">{{ __("Go back") }}</span>
                    </a>
                </section>
                <!-- Title End -->

                <!-- Content Start -->
                <section class="scroll-section">

                        <div class="card mb-5">
                            <div class="card-body">

                                <div id="attendance-content">
                                    <div class="row mb-3 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Type advice') }}
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $advice->type_advice }}
                                        </div>
                                    </div>
                                    <div class="row mb-5 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Evolución') }}
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $advice->evolution }}
                                        </div>
                                    </div>


                                    @if (NULL !== $advice->recommendations_teachers)
                                    <div class="row mb-3 position-relative pt-5 border-top">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Recomendación para los docentes') }}
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $advice->recommendatios_teachers }}
                                        </div>
                                    </div>
                                    <div class="row mb-5 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Alert due date') }}
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $advice->date_limit_teacher }}
                                        </div>
                                    </div>
                                    @endif

                                    @if (NULL !== $advice->recommendatios_family)
                                    <div class="row mb-5 position-relative pt-5 border-top">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Recomendaciones para la familia') }}
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $advice->recommendatios_family }}
                                        </div>
                                    </div>
                                    @endif

                                    <div class="row mb-3 position-relative pt-5 border-top">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Entidad a remitir') }}
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $advice->entity_remit }}
                                        </div>
                                    </div>
                                    @if ('NINGUNA' !== $advice->entity_remit)
                                    <div class="row mb-3 position-relative">
                                        <label class="col-sm-3 col-form-label">
                                            {{ __('Observaciones para la entidad') }}
                                        </label>
                                        <div class="col-sm-9">
                                            {{ $advice->observations_entity }}
                                        </div>
                                    </div>
                                    @endif

                                </div>

                            </div>
                        </div>
                </section>
                <!-- Content End -->

            </div>
        </div>
    </div>
@endsection
