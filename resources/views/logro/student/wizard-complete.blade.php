@php
$title = __('Register completed');
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
                        <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                {{-- <x-validation-errors class="mb-4" :errors="$errors" /> --}}

                <section class="scroll-section">
                    <div class="card mb-5 wizard">
                        <div class="card-header border-0 pb-0">
                            <ul class="nav nav-tabs justify-content-center disabled" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Documents') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Report books') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Persons in Charge') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">{{ __('Personal Information') }}</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item d-none" role="presentation">
                                    <a class="nav-link text-center active" role="tab"></a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" role="tabpanel">
                                    <form method="POST" action="{{ route('student.wizard.complete') }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="text-center mt-5">
                                            <h5 class="card-title">{{ __("Thank You!") }}</h5>
                                            <p class="card-text text-alternate mb-1">
                                                {{ __("You have completed the registration and document submission process.") }}
                                            </p>
                                            <p class="card-text text-alternate mb-4">
                                                {{ __("The information will be checked and validated by the educational institution.") }}
                                            </p>
                                            <p>
                                                <a class="btn btn-link btn-icon btn-icon-start" href="{{ route('student.pdf.matriculate') }}">
                                                    <i data-acorn-icon="download"></i>
                                                    <span>{{ __("Download registration sheet") }}</span>
                                                </a>
                                            </p>
                                            <button class="btn btn-icon btn-icon-end btn-primary btn-reset"
                                                type="submit">
                                                <span>{{ __("Finish") }}</span>
                                                <i data-acorn-icon="chevron-right"></i>
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection
