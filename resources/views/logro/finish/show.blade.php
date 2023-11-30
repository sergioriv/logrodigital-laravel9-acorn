@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/boxed-students-matriculate.js"></script>
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

                        <div class="mb-3">
                            <!-- Stripe Controls Start -->
                            <div class="row">
                                <!-- Search Start -->
                                <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-3">
                                    <div
                                        class="d-inline-block float-md-start search-input-container w-100 shadow bg-foreground">
                                        <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
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
                                            <th class="text-muted text-small text-uppercase">{{ __('names') }}</th>
                                            <th class="text-muted text-small text-uppercase text-center">aprueba / reprueba</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($groupStudents as $groupStudent)
                                            <tr>
                                                <td class="text-alternate">
                                                    {{ $groupStudent->student->getCompleteNames() }}
                                                    {!! $groupStudent->student->tag() !!}
                                                </td>
                                                <td class="text-center">
                                                    @if ($groupStudent->lossesArea >= 1)
                                                    <span class="badge bg-outline-danger">REPROBADO</span>
                                                    @else
                                                    <span class="badge bg-outline-success">aprobado</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <form action="{{ route('group.finish.store', $group->id) }}" method="POST">
                        @csrf

                        <div class="alert alert-info my-5">Al confirmar, los estudiantes <strong>REPROBADOS mantendrán el mismo año de estudio</strong>, mientras que aquellos estudiantes <strong>APROBADOS serán promovidos al siguiente años de estudio.</strong></div>
                        <x-button type="submit" class="btn-primary">Confirmar</x-button>
                    </form>

                </section>

            </div>
        </div>
    </div>
@endsection
