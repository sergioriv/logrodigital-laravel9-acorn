@php
$title = __('Matriculate students');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js"></script>
    <script src="/js/plugins/datatable/boxed-students-matriculate.js"></script>
    <script>
        jQuery("#studentsMatriculateCheckAll").click(function() {
            if ($(this).is(':checked')) {
                $("[logro='studentCheck']").prop('checked', 'checked');
                $("#saveStudentsMatriculate").prop('disabled', false);
            } else {
                $("[logro='studentCheck']").prop('checked', '');
                $("#saveStudentsMatriculate").prop('disabled', true);
            }
        });
        jQuery("[logro='studentCheck']").click(function() {
            var check = $("[logro='studentCheck']:checked").length;
            if (check > 0) {
                $("#saveStudentsMatriculate").prop('disabled', false);
            } else {
                $("#saveStudentsMatriculate").prop('disabled', true);
            }
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">
                            {{ __('Group') . ' | ' . $group->name . ' | ' . $title }}
                        </h1>
                        <div aria-label="breadcrumb">
                            <div class="breadcrumb">
                                <span class="breadcrumb-item text-muted">
                                    <div class="text-muted d-inline-block">
                                        <i data-acorn-icon="building-large" class="me-1" data-acorn-size="12"></i>
                                        <span class="align-middle">{{ $group->headquarters->name }}</span>
                                    </div>
                                </span>
                                <span class="breadcrumb-item text-muted">
                                    <div class="text-muted d-inline-block">
                                        <i data-acorn-icon="clock" class="me-1" data-acorn-size="12"></i>
                                        <span class="align-middle">{{ $group->studyTime->name }}</span>
                                    </div>
                                </span>
                                <span class="breadcrumb-item text-muted">
                                    <div class="text-muted d-inline-block">
                                        <i data-acorn-icon="calendar" class="me-1" data-acorn-size="12"></i>
                                        <span class="align-middle">{{ $group->studyYear->name }}</span>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section">

                    <form method="POST" action="{{ route('group.matriculate.update', $group) }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <!-- Stripe Controls Start -->
                            <div class="row">
                                <!-- Search Start -->
                                <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-3">
                                    <div
                                        class="d-inline-block float-md-start search-input-container w-100 shadow bg-foreground">
                                        <input class="form-control datatable-search" placeholder="Search"
                                            data-datatable="#boxedStudentsMatriculate" />
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                            <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- Search End -->

                            </div>
                            <!-- Stripe Controls End -->

                            <!-- Stripe Table Start -->
                            <div class="mb-3">
                                <table id="boxedStudentsMatriculate"
                                    class="data-table responsive nowrap stripe dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th class="empty all sw-3">
                                                <!-- Check Button Start -->
                                                <span class="form-check ms-2 mb-0">
                                                    <input type="checkbox" class="form-check-input"
                                                        id="studentsMatriculateCheckAll" />
                                                </span>
                                                <!-- Check Button End -->
                                            </th>
                                            <th class="text-muted text-small text-uppercase">{{ __('document') }}</th>
                                            <th class="text-muted text-small text-uppercase">{{ __('names') }}</th>
                                            @if ($group->specialty)
                                            <th class="text-muted text-small text-uppercase">{{ __('Group') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studentsForMatriculate as $student)
                                            <tr>
                                                <td class="text-alternate">
                                                    <div class="form-check ms-2 mb-0">
                                                        <input class="form-check-input" logro="studentCheck" type="checkbox"
                                                            name="students[]" id="student{{ $student->id }}" value="{{ $student->id }}">
                                                    </div>
                                                </td>
                                                <td class="text-alternate">
                                                    <label for="student{{ $student->id }}">
                                                        {{ $student->document_type_code . ' - ' . $student->document }}
                                                    </label>
                                                </td>
                                                <td class="text-alternate">
                                                    <label for="student{{ $student->id }}">
                                                        {{ $student->getCompleteNames() }}
                                                        {!! $student->tag() !!}
                                                    </label>
                                                </td>
                                                @if ($group->specialty)
                                                <td class="text-alternate">
                                                    <label for="student{{ $student->id }}">
                                                        {{ $student->group->name }}
                                                    </label>
                                                </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <x-button type="submit" id="saveStudentsMatriculate" disabled class="btn-primary">
                            {{ __('Matriculate') }}</x-button>

                    </form>

                </section>

            </div>
        </div>
    </div>
@endsection
