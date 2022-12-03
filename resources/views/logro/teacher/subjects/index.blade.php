@php
$title = __('My subjects');
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
                <div class="data-table-rows slim">

                    <!-- Cards Start -->
                    <div class="row g-3 row-cols-3 row-cols-md-4 row-cols-lg-6">
                        @foreach ($subjects as $subject)
                                <x-group.subjects :subject="$subject">
                                    <span class="mt-3 text-black btn-icon-start">
                                        <i data-acorn-icon="notebook-1"
                                            class="icon"data-acorn-size="15"></i>
                                        {{ $subject->subject->resourceSubject->public_name }}
                                    </span>
                                </x-group.subjects>
                        @endforeach
                    </div>
                    <!-- Cards End -->
                </div>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection
