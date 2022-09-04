@php
$title = __('Import') . ' ' . __('teachers');
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

                    <div class="card mb-5">
                        <div class="card-body">
                            <p>
                                Los datos requeridos son:
                            <ul>
                                <li>first_name</li>
                                <li>first_last_name</li>
                                <li>email</li>
                            </ul>

                            </p>
                            <p>
                                <a
                                    href="{{ route('teachers.instructive') }}">{{ __('Download') . ' ' . __('instructive') }}</a>
                            </p>
                        </div>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">

                            <form method="POST" action="{{ route('teacher.import') }}" enctype="multipart/form-data"
                                novalidate>
                                @csrf

                                <div class="mb-3">
                                    <input type="file" class="d-block form-control" name="file" accept=".xls,.xlsx"
                                        required>
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
