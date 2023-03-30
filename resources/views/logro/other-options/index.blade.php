@php
    $title = __('Other options');
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>
    <script>
        jQuery("[type-permission]").click(function() {
            const _this = $(this);
            let _modal = $('#modalOtherOptions');

            if (_this.attr('type-permission') === 'create') {
                $.get(HOST + '/other-options/type-permission', {}, function(data) {
                    _modal.html(data);
                    _modal.modal('show');
                })
            }

            if (_this.attr('type-permission') === 'edit') {
                $.get(HOST + '/other-options/type-permission', {
                    id: _this.attr('type-permission-id')
                }, function(data) {
                    _modal.html(data);
                    _modal.modal('show');
                })
            }

        });
    </script>
@endsection

@section('content')
    <div class="container">

        <div class="row g-3">

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-md-8">
                                <div class="">{{ __('Permission type') }}</div>
                            </div>
                            <div class="col-md-4 text-end">
                                <button type="button" class="btn btn-icon-start btn-link py-0" type-permission="create">
                                    <i data-acorn-icon="plus" data-acorn-size="13"></i>
                                    <span>{{ __('Create') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">

                        <div>
                            <table logro='dataTableBoxed' data-order='[]' class="data-table responsive stripe">
                                <thead>
                                    <tr>
                                        <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('Name') }}</th>
                                        <th class="empty">&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($typePermits as $typePermit)
                                        <tr>
                                            <td>{{ $typePermit->name }}</td>
                                            <td align="right">
                                                <!-- Dropdown Button Start -->
                                                <div class="ms-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon-only text-primary px-1"
                                                        data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-submenu>
                                                        <i data-acorn-icon="more-vertical" data-acorn-size="15"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <div class="dropdown-item cursor-pointer" type-permission="edit"
                                                            type-permission-id="{{ $typePermit->id }}">
                                                            <span>{{ __('Edit') }}</span>
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

                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Type Permission Teacher Start -->
        <div class="modal fade modal-close-out" id="modalOtherOptions" tabindex="-1" role="dialog"
            aria-labelledby="modalOtherOptionsLabel" aria-hidden="true">
        </div>

        <!-- Modal Type Permission Teacher End -->

    </div>
@endsection
