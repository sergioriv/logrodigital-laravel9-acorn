@php
    $title = __('Create area');
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
                    <form method="POST" action="{{ route('resourceArea.store') }}" class="tooltip-end-bottom" novalidate>
                        @csrf

                        <div class="card mb-5">
                            <div class="card-body">


                                <div class="row g-3">

                                    <!-- Name -->
                                    <div class="col-12">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('Name') }}</x-label>
                                            <x-input id="name" name="name" :value="old('name')" required autofocus />
                                        </div>
                                    </div>

                                    <!-- Specialty -->
                                    <div class="col-12">
                                        <div class="position-relative form-group">
                                            <label
                                                class="form-check cursor-pointer custom-icon icon-star unchecked-opacity-25 mb-0 p-0">
                                                <input type="checkbox"
                                                    class="form-check-input border-0 icon-24 lh-1-25 m-0 me-2"
                                                    name="specialty" value="1">
                                                <span class="form-check-label">
                                                    <span class="content text-alternate">
                                                        <span
                                                            class="heading mb-1 d-block lh-1-25">{{ __('Specialty area?') }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="alert alert-info m-0">
                                            <p>Tenga en cuenta que, al crear un área de especialidad, solo se podrá relacionar con asignaturas de especialidad.</p>
                                            <p>Para cada área de especialidad, deberá crear un grupo de especialidad y seleccionar el área que desea aplicar para el grupo en creación, en donde podrá registrar estudiantes ya matriculados y asignar los docentes a cargo de cada asignatura.</p>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <x-button type="submit" class="btn-primary">{{ __('Save area') }}</x-button>

                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
