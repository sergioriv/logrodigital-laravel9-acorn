@php
$title = __('Students Number');
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
<script src="/js/vendor/input-spinner.min.js"></script>
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
                        <h1 class="mb-1 pb-0 display-4">{{ $title .' | '. __("limit") }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">

                    <form id="registerForm" method="POST" action="{{ route('support.number_students.update') }}"
                        class="tooltip-end-bottom" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="input-group spinner" data-trigger="spinner">
                                    <input type="text" name="students" id="students" class="form-control text-center h1 mb-0" value="{{ $number_students }}"
                                        data-min="300" data-step="300" />
                                    <div class="input-group-text h1 mb-0">
                                        <button type="button" class="spin-up" data-spin="up">
                                            <span class="arrow"></span>
                                        </button>
                                        <button type="button" class="spin-down" data-spin="down">
                                            <span class="arrow"></span>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <button type="submit" class="btn btn-lg btn-primary">{{ __('Save') }}</button>

                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
