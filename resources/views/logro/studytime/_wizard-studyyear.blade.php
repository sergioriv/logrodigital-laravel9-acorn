@php
$title = $studyTime->name;
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
                        <h1 class="mb-1 pb-0 display-4">{{ $title . ' | ' . __('study year') }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">
                    <div class="mb-5 wizard">
                        <div class="border-0 pb-0">
                            <ul class="nav nav-tabs justify-content-center disabled" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">TAB 1</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center done" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">TAB 1</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link text-center active" role="tab">
                                        <div class="mb-1 title d-none d-sm-block">TAB 1</div>
                                        <div class="text-small description d-none d-md-block"></div>
                                    </a>
                                </li>
                                <li class="nav-item d-none" role="presentation">
                                    <a class="nav-link text-center active" role="tab"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </section>

                <form action="{{ route('studyTime.studyYear.store', $studyTime) }}" method="post" novalidate
                    autocomplete="off">
                    @csrf

                    <!-- content Start -->
                    <section class="scroll-section">

                        <div class="mb-3">
                            <div class="row g-3 row-cols-3 row-cols-md-4 row-cols-lg-6">
                                @foreach ($studyYears as $studyYear)
                                    <div class="col small-gutter-col">
                                        <label class="card form-check custom-icon mb-0 unchecked-opacity-25">
                                            <div class="card-body text-center">
                                                <input name="studyyears[]" value="{{ $studyYear->id }}" type="checkbox"
                                                    class="form-check-input" checked />
                                                <span class="form-check-label">
                                                    <span class="content">
                                                        <span class="heading mb-1 d-block">{{ $studyYear->name }}</span>
                                                    </span>
                                                </span>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-center">
                            <x-button type="submit" class="btn-primary btn-icon btn-icon-end">
                                <span>{{ __('Finish') }}</span>
                                <i data-acorn-icon="chevron-right" class="icon" data-acorn-size="18"></i>
                            </x-button>
                        </div>

                    </section>

                </form>

            </div>
        </div>
    </div>
@endsection
