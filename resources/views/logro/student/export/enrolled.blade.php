@php
    $title = __('Export students');
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
@endsection



@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                </div>
                <!-- Title End -->
            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Content Start -->
        <form action="{{ route('students.export.enrolled.generate') }}" method="POST">
            @csrf

            <div class="card mb-3">

                <div class="card-body">
                    <div class="alert alert-light" role="alert">
                        {{ __('Select the columns you want to generate') }}
                    </div>


                    <!-- Columns -->
                    <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 row-cols-xxl-6">

                        <!-- Headquarters -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[headquarters]"
                                        value="1" id="headquarters" checked>
                                    {{ __('headquarters') }}
                                </label>
                            </div>
                        </div>

                        <!-- Study Time -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[study_time]"
                                        value="1" checked>
                                    {{ __('study time') }}
                                </label>
                            </div>
                        </div>

                        <!-- Study Year -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[study_year]"
                                        value="1" checked>
                                    {{ __('study year') }}
                                </label>
                            </div>
                        </div>

                        <!-- Group -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[group]" value="1"
                                        checked>
                                    {{ __('Group') }}
                                </label>
                            </div>
                        </div>

                        <!-- Document -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[document]" value="1">
                                    {{ __('Document') }}
                                </label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[email]" value="1">
                                    {{ __('institutional email') }}
                                </label>
                            </div>
                        </div>

                        <!-- Telephone -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[telephone]"
                                        value="1">
                                    {{ __('telephone') }}
                                </label>
                            </div>
                        </div>

                        <!-- Home Country -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[country]" value="1">
                                    {{ __('home country') }}
                                </label>
                            </div>
                        </div>

                        <!-- Bith City -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[bith_city]"
                                        value="1">
                                    {{ __('birth city') }}
                                </label>
                            </div>
                        </div>

                        <!-- Birthdate -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[birthdate]"
                                        value="1">
                                    {{ __('birthdate') }}
                                </label>
                            </div>
                        </div>

                        <!-- Age -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[age]" value="1">
                                    {{ __('age') }}
                                </label>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label text-capitalize">
                                    <input class="form-check-input" type="checkbox" name="columns[gender]" value="1">
                                    {{ __('gender') }}
                                </label>
                            </div>
                        </div>

                        <!-- RH -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" name="columns[rh]" value="1">
                                    RH
                                </label>
                            </div>
                        </div>

                        <!-- Residential Zone -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[zone]" value="1">
                                    {{ __('residential zone') }}
                                </label>
                            </div>
                        </div>

                        <!-- Residence City -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[residence_city]"
                                        value="1">
                                    {{ __('residence city') }}
                                </label>
                            </div>
                        </div>

                        <!-- Residence Address -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[address]"
                                        value="1">
                                    {{ __('residence address') }}
                                </label>
                            </div>
                        </div>

                        <!-- Social Stratum -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[social_stratum]"
                                        value="1">
                                    {{ __('social stratum') }}
                                </label>
                            </div>
                        </div>

                        <!-- Dwelling Type -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[dwelling_type]"
                                        value="1">
                                    {{ __('dwelling type') }}
                                </label>
                            </div>
                        </div>

                        <!-- Neighborhood -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[neighborhood]"
                                        value="1">
                                    {{ __('neighborhood') }}
                                </label>
                            </div>
                        </div>

                        <!-- Sisben -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[sisben]"
                                        value="1">
                                    {{ __('sisben') }}
                                </label>
                            </div>
                        </div>

                        <!-- Health Manager -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[health_manager]"
                                        value="1">
                                    {{ __('health manager') }}
                                </label>
                            </div>
                        </div>

                        <!-- Tutor -->
                        <div class="position-relative form-group">
                            <div class="form-check form-check-inline">
                                <label class="form-check-label logro-label">
                                    <input class="form-check-input" type="checkbox" name="columns[tutor]"
                                        value="1">
                                    {{ __('Tutor Information') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mt-3">
                        <!-- Inclusive -->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="inclusive" name="inclusive" value="1" />
                            <label class="form-check-label" for="inclusive">Solo estudiantes inclusivos</label>
                        </div>
                    </div>

                </div>

            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-xl-4">
                            <div class="w-100 position-relative form-group">
                                <x-label>{{ __('Headquarters') }}</x-label>
                                <x-select multiple name="headquarters[]" logro="select2" :hasError="'headquarters'">
                                    @foreach ($headquarters as $hq)
                                    <option selected value="{{ $hq->id }}">{{ $hq->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-4">
                            <div class="w-100 position-relative form-group">
                                <x-label>{{ __('Study Time') }}</x-label>
                                <x-select multiple name="study_time[]" logro="select2" :hasError="'study_time'">
                                    @foreach ($studyTimes as $st)
                                    <option selected value="{{ $st->id }}">{{ $st->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-xl-4">
                            <div class="w-100 position-relative form-group">
                                <x-label>{{ __('Study Year') }}</x-label>
                                <x-select multiple name="study_year[]" logro="select2" :hasError="'study_year'">
                                    @foreach ($studyYears as $sy)
                                    <option selected value="{{ $sy->id }}">{{ $sy->name }}</option>
                                    @endforeach
                                </x-select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <x-button class="btn-primary">{{ __('Generate') }}</x-button>
        </form>
        <!-- Content End -->
    </div>
@endsection
