@php
$title = __('Import descriptors');
@endphp
@extends('layout',['title'=>$title])

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
                    <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. $subject->public_name }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">

                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row g-2">
                            <span class="h3 text-danger">Importante!</span>
                            <div>Las columnas requeridas son: (<strong>period</strong>, <strong>inclusive</strong>, <strong>content</strong>)</div>
                            <div class="alert alert-light ps-0">
                                <ul>
                                    <li>La columna <strong>period</strong> debe contener un número entre 1 y 6 que hace referencia al número del periodo.</li>
                                    <li>La columna <strong>inclusive</strong> recibe "si" o "1" para el caso de ser un descriptor de inclusion.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">

                        <form method="POST" action="{{ route('subject.descriptors.import.store', $subject) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group position-relative mb-3">
                                <x-label required>{{ __('Study year') }}</x-label>
                                <select logro="select2" name="study_year" class="w-100" required>
                                    <option label="&nbsp;"></option>
                                    @foreach ($studyYears as $sy)
                                        <option value="{{ $sy->uuid }}"
                                            @selected(old('study_year') == $sy->uuid)>{{ __($sy->name) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group position-relative mb-3">
                                <x-label required>{{ __('file') }} (.xls, .xlsx)</x-label>
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
