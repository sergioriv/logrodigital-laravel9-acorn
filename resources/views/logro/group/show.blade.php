@php
$title = $group->name;
@endphp
@extends('layout',['title'=>$title])

@section('css')
@endsection

@section('js_vendor')
<script src="/js/cs/responsivetab.js"></script>
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
                    <h1 class="mb-0 pb-0 display-4">{{ __('Group') .' | '. $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="row">

                    <!-- Right Side Start -->
                    <div class="col-12">
                        <!-- Title Tabs Start -->
                        <ul class="nav nav-tabs nav-tabs-title nav-tabs-line-title responsive-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#subjectsTab" role="tab"
                                    aria-selected="true">{{ __('Subjects') .' & '. __('Teachers') }}</a>
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
                                <div class="col-12 mb-2 d-flex align-items-start justify-content-end">
                                    @if ($areas->count() !== 0)
                                        <!-- Add New Button Start -->
                                            <a href="{{ route('group.teachers.edit', $group) }}"
                                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                            <i data-acorn-icon="edit-square"></i>
                                            <span>{{ __("Edit") .' '. __("Teachers") }}</span>
                                        </a>
                                        <!-- Add New Button End -->
                                    @else
                                        <!-- Assing Teachers Button Start -->
                                        <a href="{{ route('studyYear.subject.show', $group->studyYear) }}"
                                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                            <i data-acorn-icon="edit-square"></i>
                                            <span>{{ __("Assign") .' '. __("Subjects") .' '. $group->studyYear->name }}</span>
                                        </a>
                                        <!-- Assing Teachers Button End -->
                                    @endif

                                </div>
                                <!-- Groups Buttons End -->

                                <!-- Groups Content Tab Start -->
                                <section class="scroll-section">
                                    @foreach ($areas as $area)
                                    <div class="card d-flex mb-2">
                                        <div class="card-body">
                                            <h2 class="small-title">{{ $area->name }}</h2>
                                            <table class="table table-striped">
                                                <tbody>
                                                    @foreach ($area->subjects as $subject)
                                                    <tr>
                                                        <td scope="row" class="col-6">{{ $subject->resourceSubject->name
                                                            }}</td>
                                                        <td>
                                                            @foreach ($subject->teacherSubjectGroups as $teacher_subject)
                                                            @if ($loop->first)
                                                            {{ $teacher_subject->teacher->getFullName() }}
                                                            @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @endforeach
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
