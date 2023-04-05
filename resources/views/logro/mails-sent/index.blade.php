@php
    $title = __('List of mails sent');
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
    <script>
        jQuery("[mail-record='see']").on('click', function() {
            let modal = $("#seeRecordEmailsSentModal");
            const _this = $(this);

            if (_this.attr('mail-id')) {
                $.get(HOST + `/mails_sent/${_this.attr('mail-id')}/log`, function (res) {
                    if (res)
                        $(".modal-body", modal).html(res.content);
                });
                modal.modal('show');
            }
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title and Top Buttons Start -->
                <div class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 mb-2 mb-md-0">
                            <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
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
                            <div
                                class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
                                    data-datatable="#datatable_mails_sent" />
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
                    <!-- Controls End -->

                    <!-- Table Start -->
                    <div class="data-table-responsive-wrapper">
                        <table id="datatable_mails_sent" class="data-table hover" logro="datatable">
                            <thead>
                                <tr>
                                    <th class="text-muted text-small text-uppercase">{{ __('Email subject') }}</th>
                                    <th class="text-muted text-small text-uppercase">{{ __('Created at') }}</th>
                                    <th class="empty">&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mails as $mail)
                                    <tr>
                                        <td class="text-sub">{{ $mail->subject }}</td>
                                        <td class="text-sub text-small">{{ $mail->created_at }}</td>
                                        <td align="right" class="text-sub">
                                            <!-- Dropdown Button Start -->
                                            <div class="ms-1 dropstart">
                                                <button type="button"
                                                    class="btn btn-sm text-primary btn-icon btn-icon-only"
                                                    data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false" data-submenu>
                                                    <i data-acorn-icon="more-vertical"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <div class="dropdown-item cursor-pointer" mail-record="see" mail-id="{{ $mail->id }}">
                                                        <span>{{ __('See record') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Dropdown Button End -->
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Table End -->
                </div>
                <!-- Content End -->
            </div>
        </div>

        <div class="modal fade modal-close-out" id="seeRecordEmailsSentModal" tabindex="-1" role="dialog"
            aria-labelledby="seeRecordEmailsSentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="seeRecordEmailsSentModalLabel">{{ __('See record') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">



                    </div>
                    {{-- <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div> --}}

                </div>
            </div>
        </div>

    </div>
@endsection
