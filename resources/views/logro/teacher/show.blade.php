@php
$title = $teacher->names;
@endphp
@extends('layout', ['title' => $title])

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
    <input type="hidden" id="teacher" value="{{ $teacher->uuid }}">
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ __('Teacher') . ' | ' . __($title) }}</h1>
                    </div>
                </section>
                <!-- Title End -->


                <section class="scroll-section">
                    <div class="row gx-4 gy-5">
                        <!-- Left Side Start -->
                        <div class="col-12 col-xl-3">
                            <!-- Biography Start -->
                            <div class="card mb-5">
                                <div class="card-body">
                                    <div class="d-flex align-items-center flex-column mb-3">
                                        <div class="mb-5 d-flex align-items-center flex-column">

                                            <!-- Avatar Form Start -->
                                            <x-avatar-profile :avatar="$teacher->user->avatar" class="mb-3" />
                                            <!-- Avatar Form End -->

                                            <div class="h5">{{ $teacher->fullName() }}</div>
                                            <div class="text-muted">{{ __($teacher->type_appointment) }}</div>
                                            <div class="text-muted">{{ __($teacher->type_admin_act) }}</div>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <p class="text-small text-uppercase text-muted mb-2">{{ __('contact') }}</p>
                                        @if ($teacher->telephone)
                                            <div class="d-block mb-1">
                                                <i data-acorn-icon="phone" class="me-2" data-acorn-size="17"></i>
                                                <span class="align-middle">{{ $teacher->telephone }}</span>
                                            </div>
                                        @endif
                                        @if ($teacher->cellphone)
                                            <div class="d-block mb-1">
                                                <i data-acorn-icon="phone" class="me-2" data-acorn-size="17"></i>
                                                <span class="align-middle">{{ $teacher->cellphone }}</span>
                                            </div>
                                        @endif
                                        <div class="d-block">
                                            <i data-acorn-icon="email" class="me-2" data-acorn-size="17"></i>
                                            <span class="align-middle">{{ $teacher->institutional_email }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column">
                                        <text class="text-muted text-small">{{ __('created at') }}:</text>
                                        <text class="text-muted text-small">{{ $teacher->created_at }}</text>
                                    </div>

                                </div>
                            </div>
                            <!-- Biography End -->
                        </div>
                        <!-- Left Side End -->

                        <!-- Right Side Start -->
                        <div class="col-12 col-xl-9">
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

                                    <!-- Groups Content Tab Start -->
                                    <section class="scroll-section">
                                        @if ($schoolYear->count() === 0)
                                            <h5 class="text-muted">{{ __('No Subjects') }}</h5>
                                        @endif
                                        <div class="mb-n2" id="accordionCardsSubjects">
                                            @foreach ($schoolYear as $schy)
                                                <div class="card d-flex mb-2 card-color-background">
                                                    <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                                        data-bs-target="#year-{{ $schy->name }}" aria-expanded="true"
                                                        aria-controls="year-{{ $schy->name }}">
                                                        <div class="card-body py-3 border-bottom">
                                                            <div class="btn btn-link list-item-heading p-0">
                                                                {{ $schy->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="year-{{ $schy->name }}"
                                                        class="collapse @if ($loop->first) show @endif"
                                                        data-bs-parent="#accordionCardsSubjects">
                                                        <div class="card-body accordion-content">
                                                            @if ($teacher->teacherSubjectGroups->count() === 0)
                                                                <h5 class="text-muted">{{ __('No Subjects') }}</h5>
                                                            @endif
                                                            <div class="row g-2 row-cols-3 row-cols-md-4">
                                                                @foreach ($teacher->teacherSubjectGroups as $teacherSubject)
                                                                    @if ($teacherSubject->school_year_id === $schy->id)
                                                                        <x-group.card :group="$teacherSubject->group">
                                                                            <span class="mt-3 text-black btn-icon-start">
                                                                                <i data-acorn-icon="notebook-1"
                                                                                    class="icon"data-acorn-size="15"></i>
                                                                                {{ $teacherSubject->subject->resourceSubject->name }}
                                                                            </span>
                                                                        </x-group.card>
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
