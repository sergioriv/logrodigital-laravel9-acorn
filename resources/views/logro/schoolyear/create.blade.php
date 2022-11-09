@php
    $title = __('Create School Year');
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
                    <form method="POST" action="{{ route('schoolYear.store') }}" class="tooltip-end-bottom" novalidate>
                        @csrf
                        <div class="card mb-5">
                            <div class="card-body">

                                <!-- Name -->
                                <div class="position-relative form-group">
                                    <x-label>{{ __('Name') }}</x-label>
                                    <x-input id="name" name="name" :value="old('name')" required autofocus />
                                </div>
                            </div>
                        </div>
                        <x-button type="submit" class="btn-primary">{{ __('Save school year') }}</x-button>
                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
