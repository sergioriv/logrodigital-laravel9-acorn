@php
$title = $teacher->getFullName();
@endphp
@extends('layout',['title'=>$title])

@section('css')
<link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
<script src="/js/cs/responsivetab.js"></script>
{{-- <script src="/js/vendor/bootstrap-submenu.js"></script> --}}
{{-- <script src="/js/vendor/datatables.min.js"></script> --}}
{{-- <script src="/js/vendor/mousetrap.min.js"></script> --}}
@endsection

@section('js_page')
{{-- <script src="/js/cs/datatable.extend.js"></script> --}}
{{-- <script src="/js/plugins/datatable/teacher_subjects_datatable.ajax.js"></script> --}}
@endsection

@section('content')
<input type="hidden" id="teacher" value="{{ $teacher->id }}">
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Title Start -->
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ __('Teacher') .' | '. __($title) }}</h1>
                </div>
            </section>
            <!-- Title End -->


            <section class="scroll-section">
                <div class="row gx-4 gy-5">
                    <!-- Left Side Start -->
                    <div class="col-12 col-xl-4 col-xxl-3">
                        <!-- Biography Start -->
                        <div class="card">
                            <div class="card-body mb-n5">
                                <div class="d-flex align-items-center flex-column mb-3">
                                    <div class="mb-5 d-flex align-items-center flex-column">

                                        @if ($teacher->user->avatar != NULL)
                                        <div class="sw-13 position-relative mb-3">
                                            <img src="{{ $teacher->user->avatar }}" class="img-fluid rounded-xl"
                                                alt="thumb" />
                                        </div>
                                        @else
                                        <div
                                            class="sw-13 sh-13 mb-3 d-inline-block bg-separator d-flex justify-content-center align-items-center rounded-xl">
                                            <i class="bi-person-circle icon icon-24" class="icon"></i>
                                        </div>
                                        @endif

                                        <div class="h5 mb-0">{{ $teacher->getFullName() }}</div>
                                        <div class="text-muted">{{ $teacher->bonding_type }}</div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <p class="text-small text-uppercase text-muted mb-2">{{ __('contact') }}</p>
                                    <div class="d-block mb-1">
                                        <i data-acorn-icon="phone" class="me-2" data-acorn-size="17"></i>
                                        <span class="align-middle">{{ $teacher->telephone }}</span>
                                    </div>
                                    <div class="d-block">
                                        <i data-acorn-icon="email" class="me-2" data-acorn-size="17"></i>
                                        <span class="align-middle">{{ $teacher->institutional_email }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Biography End -->
                    </div>
                    <!-- Left Side End -->

                    <!-- Right Side Start -->
                    <div class="col-12 col-xl-8 col-xxl-9">
                        <!-- Title Tabs Start -->
                        <ul class="nav nav-tabs nav-tabs-title nav-tabs-line-title responsive-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#subjectsTab" role="tab"
                                    aria-selected="true">{{ __('Subjects') }}</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#otherTab" role="tab"
                                    aria-selected="false">{{ __('Other') }}</a>
                            </li>
                        </ul>
                        <!-- Title Tabs End -->

                        <div class="tab-content">
                            <!-- Groups Tab Start -->
                            <div class="tab-pane fade active show" id="subjectsTab" role="tabpanel">

                                <!-- Groups Buttons Start -->
                                <!-- Groups Buttons End -->

                                <!-- Groups Content Tab Start -->
                                <section class="scroll-section">
                                    @if($schoolYear->count() === 0)
                                    <h5 class="text-muted">{{ __("No Subjects") }}</h5>
                                    @endif
                                    <div class="mb-n2" id="accordionCardsSubjects">
                                        @foreach ($schoolYear as $schy)
                                        <div class="card d-flex mb-2 card-color-background">
                                            <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                                data-bs-target="#year-{{ $schy->name }}" aria-expanded="true"
                                                aria-controls="year-{{ $schy->name }}">
                                                <div class="card-body py-3 border-bottom">
                                                    <div class="btn btn-link list-item-heading p-0">{{ $schy->name }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="year-{{ $schy->name }}"
                                                class="collapse @if ($loop->first) show @endif"
                                                data-bs-parent="#accordionCardsSubjects">
                                                <div class="card-body accordion-content">
                                                    @if($teacher->teacherSubjectGroups->count() === 0)
                                                    <h5 class="text-muted">{{ __("No Subjects") }}</h5>
                                                    @endif
                                                    <div class="row g-2 row-cols-3 row-cols-md-4">
                                                        @foreach ($teacher->teacherSubjectGroups as $teacherSubject)
                                                        @if ($teacherSubject->school_year_id === $schy->id)
                                                        <div class="col small-gutter-col">
                                                            <div class="card h-100">
                                                                <div class="card-body text-center d-flex flex-column">
                                                                    <small class="text-muted">{{ $teacherSubject->teacher->getFullName() }}</small>
                                                                    <small class="text-muted">{{ $teacherSubject->group->headquarters->name }}</small>
                                                                    <small class="text-muted">{{ $teacherSubject->group->studyTime->name }}</small>
                                                                    <small class="text-muted">{{ $teacherSubject->group->studyYear->name }}</small>
                                                                    <h5 class="text-primary font-weight-bold">{{
                                                                        $teacherSubject->group->name }}</h5>
                                                                    <span class="btn-icon-start">
                                                                        <i data-acorn-icon="notebook-1"
                                                                            class="icon text-primary"
                                                                            data-acorn-size="15"></i>
                                                                        {{ $teacherSubject->subject->resourceSubject->name }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </section>
                                <!-- Groups Content Tab End -->
                            </div>
                            <!-- Groups Tab End -->

                            <!-- Branches Tab Start -->
                            <div class="tab-pane fade" id="otherTab" role="tabpanel">
                                other
                            </div>
                            <!-- Branches Tab End -->
                        </div>
                    </div>
                    <!-- Right Side End -->
                </div>
            </section>
        </div>
    </div>
</div>
@endsection
