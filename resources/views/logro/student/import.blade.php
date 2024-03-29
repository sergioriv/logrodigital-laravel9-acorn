@php
$title = __('Import') .' '. __('students');
@endphp
@extends('layout',['title'=>$title])

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

                <!-- Validation Errors -->
                {{-- <x-validation-errors class="mb-4" :errors="$errors" /> --}}

                <div class="card mb-5">
                    <div class="card-body">
                        <span class="h5 text-danger">Importante!</span>
                        <p>
                            <ul>
                                <li>Los datos requeridos son:
                                    <ul>
                                        <li>first_name</li>
                                        <li>first_last_name</li>
                                        <li>document_type</li>
                                        <li>document</li>
                                        <li>institutional_email</li>
                                        <li>headquarters</li>
                                        <li>study_time</li>
                                        <li>study_year</li>
                                    </ul>
                                </li>
                                <li>Las cabeceras del documento no deben ser modificadas ni excluidas del documento a cargar</li>
                                <li><a href="{{ route("students.instructive") }}">{{ __("Download") .' '. __("instructive") }}</a></li>
                                <li>Recuerda copiar y pegar los datos del instructivo <a target="_blank" href="{{ route("students.data.instructive") }}">{{ __("Data instructive") }}</a> en las columnas correspondientes para no sufrir errores en la importación del excel</li>
                            </ul>
                        </p>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">

                        <form method="POST" action="{{ route('students.import.store') }}" enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="mb-3">
                                <input type="file" class="d-block form-control" name="file" accept=".xls,.xlsx" required>
                            </div>

                            <x-button type="submit" class="btn-primary">{{ __('Import') }}</x-button>

                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
