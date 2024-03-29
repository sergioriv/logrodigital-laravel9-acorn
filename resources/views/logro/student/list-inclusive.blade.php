@php
    $title = __('Inclusive students');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/bootstrap-submenu.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/mousetrap.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatable_standard.ajax.js?d=1674758885739"></script>

    @hasanyrole('SUPPORT|ORIENTATION')
        <script>
            jQuery('[modal="non-inclusive"]').click(function() {
                let _this = $(this);
                var _modal = $('#nonInclusiveModal');

                if (_this.data('student')) {

                    _modal.find('[name="student"]').val(_this.data('student'));
                    _modal.find('b[studentName]').html(_this.data('student-name'));
                    _modal.modal('show');
                }
            });
        </script>
    @endhasanyrole
@endsection

@section('content')
    <div class="container">

        @if (!is_null($pendingStudents))
            @if ($pendingStudents !== 0)
                <div class="alert alert-danger">{!! __('You have :COUNT students pending assessment.', ['COUNT' => '<b>' . $pendingStudents . '</b>']) !!}</div>
            @endif
        @endif

        <!-- Title and Top Buttons Start -->
        <div class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $title . " ({$students->count()})" }}</h1>
                </div>
                <!-- Title End -->

            </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Content Start -->
        <div class="data-table-rows slim">
            <!-- Controls Start -->
            <div class="row">
                <!-- Search Start -->
                <div class="col-sm-12 col-md-5 col-lg-3 col-xxl-2 mb-1">
                    <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                        <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
                            data-datatable="#datatable_students_inclusive" />
                        <span class="search-magnifier-icon">
                            <i data-acorn-icon="search"></i>
                        </span>
                        <span class="search-delete-icon d-none">
                            <i data-acorn-icon="close"></i>
                        </span>
                    </div>
                </div>
                <!-- Search End -->

                <div class="col-sm-12 col-md-7 col-lg-9 col-xxl-10 text-end mb-1">
                    <div class="d-inline-block">
                        <!-- Length Start -->
                        <div class="dropdown-as-select d-inline-block datatable-length"
                            data-datatable="#datatable_students_inclusive" data-childSelector="span">
                            <button class="btn p-0 shadow" type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false" data-bs-offset="0,3">
                                <span class="btn btn-foreground-alternate dropdown-toggle" data-bs-toggle="tooltip"
                                    data-bs-placement="top" data-bs-delay="0" title="{{ __('Item Count') }}">
                                    10 Items
                                </span>
                            </button>
                            <div class="dropdown-menu shadow dropdown-menu-end">
                                <a class="dropdown-item active" href="#">10 Items</a>
                                <a class="dropdown-item" href="#">20 Items</a>
                                <a class="dropdown-item" href="#">50 Items</a>
                                <a class="dropdown-item" href="#">100 Items</a>
                                <a class="dropdown-item" href="#">200 Items</a>
                            </div>
                        </div>
                        <!-- Length End -->
                    </div>
                </div>

            </div>
            <!-- Controls End -->

            <!-- Table Start -->
            <div class="data-table-responsive-wrapper">
                <table id="datatable_students_inclusive" class="data-table nowrap hover" logro="datatable" data-order="[]">
                    <thead>
                        <tr>
                            <th class="text-muted text-small text-uppercase">{{ __('names') }}</th>
                            <th class="empty d-none">{{ __('document') }}</th>
                            <th class="empty d-none">{{ __('email') }}</th>
                            <th class="text-muted text-small text-uppercase">{{ __('disability') }}</th>
                            <th class="text-muted text-small text-uppercase">{{ __('headquarters') }}</th>
                            <th class="text-muted text-small text-uppercase">{{ __('study time') }}</th>
                            <th class="text-muted text-small text-uppercase">{{ __('study year') }}</th>
                            <th class="text-muted text-small text-uppercase">{{ __('Group') }}</th>
                            @hasanyrole('SUPPORT|ORIENTATION')
                                <th class="empty">&nbsp;</th>
                            @endhasanyrole
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr>
                                <td>
                                    <a href="{{ route('students.show', $student) }}" class="list-item-heading body">
                                        {{ $student->getCompleteNames() }}
                                    </a>
                                    {!! $student->tag() !!}
                                </td>
                                <td class="d-none">{{ $student->document_type_code }} {{ $student->document }}</td>
                                <td class="d-none">{{ $student->institutional_email }}</td>
                                <td>{{ __($student->disability->name ?? null) }}</td>
                                <td>{{ $student->headquarters->name ?? null }}</td>
                                <td>{{ $student->studyTime->name ?? null }}</td>
                                <td>{{ $student->studyYear->name ?? null }}</td>
                                <td>{{ $student->group->name ?? null }}</td>
                                @hasanyrole('SUPPORT|ORIENTATION')
                                    <td align="right">
                                        <!-- Dropdown Button Start -->
                                        <div class="ms-1 dropstart">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                aria-expanded="false" data-submenu>
                                                <i data-acorn-icon="more-vertical"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <div class="dropdown-item btn-icon btn-icon-start cursor-pointer"
                                                    data-student="{{ $student->id }}"
                                                    data-student-name="{{ $student->getCompleteNames() }}"
                                                    modal="non-inclusive">
                                                    <i data-acorn-icon="multiply" class="text-danger font-weight-bold"></i>
                                                    <span>{{ __('Remove from inclusive') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Dropdown Button End -->
                                    </td>
                                @endhasanyrole
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Table End -->
        </div>
        <!-- Content End -->

        @hasanyrole('SUPPORT|ORIENTATION')
            <!-- Modal Non Inclusive Start -->
            <div class="modal fade modal-close-out" id="nonInclusiveModal" aria-labelledby="modalNonInclusive"
                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalNonInclusive">{{ __('Remove from inclusive') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="{{ route('students.non-inclusive') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <input type="hidden" name="student" value="">

                            <div class="modal-body">
                                <div class="alert alert-info m-0 text-center">
                                    {!! __('If confirmed, student :STUDENT_NAME will be removed from this list.', [
                                        'STUDENT_NAME' => '<b studentName></b>',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger"
                                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="submit" class="btn btn-outline-primary">
                                    {{ __('Confirm') }}</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Non Inclusive End -->
        @endhasanyrole

    </div>
@endsection
