@php
    $title = __('Create Group');
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
                    <form method="POST" action="{{ route('group.store') }}" novalidate>
                        @csrf

                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="row g-3">

                                    <!-- Headquarters -->
                                    <div class="col-md-6">
                                        <div class="position-relative form-group w-100">
                                            <x-label>{{ __('Headquarters') }}
                                                <x-required />
                                            </x-label>
                                            <select logro="select2" name="headquarters" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($headquarters as $hq)
                                                    <option value="{{ $hq->id }}">{{ $hq->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Study Time -->
                                    <div class="col-md-6">
                                        <div class="position-relative form-group w-100">
                                            <x-label>{{ __('Study time') }}
                                                <x-required />
                                            </x-label>
                                            <select logro="select2" name="study_time" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($studyTime as $st)
                                                    <option value="{{ $st->id }}">{{ $st->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Study Year -->
                                    <div class="col-md-6">
                                        <div class="position-relative form-group w-100">
                                            <x-label>{{ __('Study year') }}
                                                <x-required />
                                            </x-label>
                                            <select logro="select2" name="study_year" required>
                                                <option label="&nbsp;"></option>
                                                @foreach ($studyYear as $sy)
                                                    <option value="{{ $sy->id }}">{{ $sy->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Group Director -->
                                    <div class="col-md-6">
                                        <div class="position-relative form-group w-100">
                                            <x-label>{{ __('Group director') }}</x-label>
                                            <select logro="select2" name="group_director">
                                                <option label="&nbsp;"></option>
                                                @foreach ($teachers as $tc)
                                                    <option value="{{ $tc->uuid }}">{{ $tc->getFullName() }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('Name') }}
                                                <x-required />
                                            </x-label>
                                            <x-input name="name" :value="old('name')" required />
                                        </div>
                                    </div>

                                    @if ($existAreasSpecialty)
                                    <!-- Is Specialty -->
                                        <div class="col-md-6">
                                            <div class="position-relative form-group w-100">
                                                <x-label>{{ __('is it a specialty group?') }}</x-label>
                                                <select logro="select2" name="specialty">
                                                    <option value="no" selected>{{ __('No') }}</option>
                                                    <option value="yes">{{ __('Yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            </div>
                        </div>

                        <x-button type="submit" class="btn-primary">{{ __('Save group') }}</x-button>

                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
