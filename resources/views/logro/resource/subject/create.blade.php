@php
    $title = __('Create subject');
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

                <section class="scroll-section">
                    <form method="POST" action="{{ route('resourceSubject.store') }}" class="tooltip-end-bottom" novalidate>
                        @csrf

                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="row g-3">

                                    <!-- Name -->
                                    <div class="mb-3 col-md-6">
                                        <div class="form-group">
                                            <x-label>{{ __('Descriptive name') }}</x-label>
                                            <x-input name="descriptive_name" :value="old('descriptive_name')" required autofocus />
                                        </div>
                                    </div>

                                    <!-- Public Name -->
                                    <div class="mb-3 col-md-6">
                                        <div class="form-group">
                                            <x-label>{{ __('Public name') }}</x-label>
                                            <x-input name="public_name" :value="old('public_name')" required />
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-check cursor-pointer custom-icon icon-star unchecked-opacity-25 mb-0 p-0">
                                                <input type="checkbox" class="form-check-input border-0 icon-24 lh-1-25 m-0 me-2" name="specialty" value="1">
                                                <span class="form-check-label">
                                                    <span class="content text-alternate">
                                                        <span class="heading mb-1 d-block lh-1-25">{{ __('Specialty subject?') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-info m-0">Tenga en cuenta que, al crear una asignatura como especialidad, deberá crear un grupo para las asignaturas de especialidad, en donde podrá asignar estudiantes y el docente a cargo</div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <x-button type="submit" class="btn-primary">{{ __('Save subject') }}</x-button>

                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
