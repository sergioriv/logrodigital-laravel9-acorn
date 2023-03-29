@php
    $title = __('Create header remission');
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

                    <form method="POST" action="{{ route('headers-remissions.store') }}" class="tooltip-end-bottom"
                        novalidate>
                        @csrf

                        <div class="card mb-5">
                            <div class="card-body">

                                @csrf

                                <div class="row g-3">

                                    <!-- Title -->
                                    <div class="col-12">
                                        <div class="form-group position-relative">
                                            <x-label requried>{{ __('title') }}</x-label>
                                            <x-input name="title" :value="old('title')" required autofocus />
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="col-12">
                                        <div class="form-group position-relative">
                                            <x-label requried>{{ __('Content') }}</x-label>
                                            <textarea name="content" required class="form-control" rows="5">{{ old('content') }}</textarea>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <x-button type="submit" class="btn-primary">{{ __('Create') }}</x-button>

                    </form>

                </section>

            </div>
        </div>
    </div>
@endsection
