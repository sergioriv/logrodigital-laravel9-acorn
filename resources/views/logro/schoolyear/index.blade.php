@php
$title = __('School years');
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
                        <div class="col-12 col-md-7 mb-2 mb-md-0">
                            <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                        </div>
                        <!-- Title End -->

                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                            <!-- Add New Button Start -->
                            <a href="{{ route('schoolYear.create') }}"
                                class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                <i data-acorn-icon="plus"></i>
                                <span>{{ __('Add New') }}</span>
                            </a>
                            <!-- Add New Button End -->
                        </div>
                        <!-- Top Buttons End -->
                    </div>
                </div>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <form method="POST" action="{{ route('schoolYear.selected') }}" novalidate>
                    @csrf
                    @method('PUT')

                    <section class="row g-2 mb-3 row-cols-3 row-cols-md-4 row-cols-lg-6">

                        @foreach ($years as $year)
                            <div class="col small-gutter-col">
                                <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                    <input type="radio" class="form-check-input position-absolute e-2 t-2 z-index-1"
                                        name="school_year" value="{{ $year->id }}"
                                        @if (null !== Auth::user()->school_year_id) {{ Auth::user()->school_year_id === $year->id ? 'checked' : '' }}
                                    @elseif (1 === $year->available)
                                    checked @endif>
                                    <span class="card form-check-label w-100">
                                        <span class="card-body text-center">
                                            <span class="heading text-body text-primary d-block">{{ $year->name }}</span>
                                            <span class="mb-2 text-extra-small fw-medium text-muted text-uppercase d-block">
                                                {{ $year->groups_count . ' ' . __('Groups') }}</span>
                                            {{-- @if ($year->groups_sum_student_quantity !== null)
                                                <span class="text-extra-small fw-medium text-muted text-uppercase d-block">
                                                    {{ $year->groups_sum_student_quantity . ' ' . __('students') }}</span>
                                            @endif --}}
                                        </span>
                                    </span>
                                </label>
                            </div>
                        @endforeach

                    </section>

                    <x-button type="submit" class="btn-primary">
                        <i class="bi-clock-history"></i>
                        <span>{{ __('Choose Year') }}</span>
                    </x-button>

                </form>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection
