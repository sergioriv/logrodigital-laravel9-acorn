@php
    $title = $coordination->names;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>

    <!-- DataTable -->
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js"></script>
    <script src="/js/forms/teacher-permit-create.js"></script>
    @hasanyrole('SUPPORT|COORDINATOR')
        <script>
            jQuery('[modal="acceptOrDenyPermit"]').click(function() {
                let _this = $(this);
                var contentModal = $("#acceptOrDenyPermitModal");

                if (_this.data('permit')) {
                    contentModal.find('[name="permit"]').val(_this.data('permit'));
                    contentModal.modal('show');
                }
            });
        </script>
    @endhasanyrole
    @hasanyrole('SUPPORT|SECRETARY')
    <script src="/js/forms/change-email-administrative.js"></script>
    <script>
        jQuery('[modal="restorePassword"]').click(function() {

            let _restore = $(this);

            $.get(HOST + "/user/restore-password", {
                role: _restore.data('role'),
                id: _restore.data('id')
            }, function(data) {
                $('#restorePassword .modal-body').html(data);
                $('#restorePassword').modal('show');
            });
        });
    </script>
    @endhasanyrole
@endsection

@section('content')
    <input type="hidden" id="teacher" value="{{ $coordination->uuid }}">
    <div class="container">
        <!-- Title Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-8 mb-2 mb-md-0">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $coordination->getFullName() }}</h1>
                </div>
                <!-- Title End -->

                @hasanyrole('SUPPORT|SECRETARY')
                    <!-- Top Buttons Start -->
                    <div class="col-12 col-md-4 d-flex align-items-start justify-content-end">

                        <!-- Dropdown Button Start -->
                        <div class="ms-1">
                            <button type="button" class="btn btn-sm btn-outline-info btn-icon btn-icon-only"
                                data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                data-submenu>
                                <i data-acorn-icon="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <x-dropdown-item type="button" data-bs-toggle="modal" data-bs-target="#restorePassword">
                                    <i data-acorn-icon="lock-off" class="me-1"></i>
                                    <span>{{ __('Restore password') }}</span>
                                </x-dropdown-item>
                                <div class="dropdown-divider"></div>
                                <x-dropdown-item type="button" data-bs-toggle="modal" data-bs-target="#mutateUser">
                                    <i data-acorn-icon="login" class="me-1"></i>
                                    <span>{{ __('Login') }}</span>
                                </x-dropdown-item>
                            </div>
                        </div>
                        <!-- Dropdown Button End -->

                    </div>
                    <!-- Top Buttons End -->
                @endhasanyrole
            </div>
        </section>
        <!-- Title End -->

        <section class="row">
            <!-- Left Side Start -->
            <div class="col-12 col-xl-3">

                <!-- Biography Start -->
                <h2 class="small-title">{{ __('Profile') }}</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="d-flex align-items-center flex-column">
                            <div class="mb-5 d-flex align-items-center flex-column">

                                <!-- Avatar Form Start -->
                                <x-avatar-profile :avatar="$coordination->user->avatar" class="mb-3" />
                                <!-- Avatar Form End -->

                                <div class="h5">{{ $coordination->getFullName() }}</div>
                                <div class="text-muted text-uppercase">{{ __('coordinator') }}</div>
                                <div class="text-muted text-capitalize">{{ __($coordination->type_appointment) }}</div>
                            </div>
                        </div>

                        <div class="nav flex-column mb-5" role="tablist">

                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#informationTab" role="tab">
                                <span class="align-middle">{{ __('Information') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'permits') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#permitsTab" role="tab">
                                <span class="align-middle">{{ __('Permits') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'hierarchies') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#hierarchyTab" role="tab">
                                <span class="align-middle">{{ __('Hierarchy') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'degrees') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#degreeTab" role="tab">
                                <span class="align-middle">{{ __('Degrees') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'employments') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#employmentsTab" role="tab">
                                <span class="align-middle">{{ __('Employment history') }}</span>
                            </a>

                        </div>

                        <div class="d-flex flex-column">
                            <text class="text-muted text-small">{{ __('created at') }}:</text>
                            <text class="text-muted text-small">{{ $coordination->created_at }}</text>
                        </div>

                    </div>
                </div>
                <!-- Biography End -->

            </div>
            <!-- Left Side End -->

            <!-- Right Side Start -->
            <div class="col-12 col-xl-9 mb-5 tab-content">

                <!-- Information Tab Start -->
                <div class="tab-pane fade @empty(session('tab')) active show @endempty" id="informationTab"
                    role="tabpanel">

                    <!-- Information Content Tab Start -->
                    <h2 class="small-title">{{ __('Information') }}</h2>
                    <section class="mb-5">

                        <div class="card mb-5">
                            <div class="card-body row g-3">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('names') }}</x-label>
                                        <text class="form-control">{{ $coordination->names }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('last names') }}</x-label>
                                        <text class="form-control">{{ $coordination->last_names }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('document number') }}</x-label>
                                        <text class="form-control">{{ $coordination->document }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('expedition city') }}</x-label>
                                        <text
                                            class="form-control">{{ $coordination->expeditionCity?->department->name . ' | ' . $coordination->expeditionCity?->name }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('birth city') }}</x-label>
                                        <text
                                            class="form-control">{{ $coordination->birthCity?->department->name . ' | ' . $coordination->birthCity?->name }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('birthdate') }}</x-label>
                                        <text class="form-control">{{ $coordination->birthdate }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('residence city') }}</x-label>
                                        <text
                                            class="form-control">{{ $coordination->residenceCity?->department->name . ' | ' . $coordination->residenceCity?->name }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('residence address') }}</x-label>
                                        <text class="form-control">{{ $coordination->address }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('telephone') }}</x-label>
                                        <text class="form-control">{{ $coordination->telephone }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('cellphone') }}</x-label>
                                        <text class="form-control">{{ $coordination->cellphone }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label required>{{ __('institutional email') }}</x-label>
                                        <text class="form-control">{{ $coordination->institutional_email }}</text>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <x-label>{{ __('marital status') }}</x-label>
                                        <text class="form-control">{{ __($coordination->marital_status) }}</text>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Appointment, possession, transfer Start -->
                        <div class="card mb-5">
                            <div class="card-body row g-3">

                                @if ($coordination->file_appointment)
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <text class="form-control">{{ $coordination->appointment_number }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <text class="form-control">{{ $coordination->date_appointment }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end justify-content-center">
                                        <a href="{{ config('app.url') . '/' . $coordination->file_appointment }}"
                                            target="_blank" class="btn bt-sm btn-outline-primary icon-start">
                                            <i class="icon bi-box-arrow-up-right me-2"></i>
                                            {{ __('View document') }}
                                        </a>
                                    </div>
                                @endif

                                @if ($coordination->file_possession_certificate)
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('possession certificate number') }}</x-label>
                                            <text class="form-control">{{ $coordination->possession_certificate }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <text
                                                class="form-control">{{ $coordination->date_possession_certificate }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end justify-content-center">
                                        <a href="{{ config('app.url') . '/' . $coordination->file_possession_certificate }}"
                                            target="_blank" class="btn bt-sm btn-outline-primary icon-start">
                                            <i class="icon bi-box-arrow-up-right me-2"></i>
                                            {{ __('View document') }}
                                        </a>
                                    </div>
                                @endif

                                @if ($coordination->file_transfer_resolution)
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('transfer resolution number') }}</x-label>
                                            <text class="form-control">{{ $coordination->transfer_resolution }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <text
                                                class="form-control">{{ $coordination->date_transfer_resolution }}</text>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end justify-content-center">
                                        <a href="{{ config('app.url') . '/' . $coordination->file_transfer_resolution }}"
                                            target="_blank" class="btn bt-sm btn-outline-primary icon-start">
                                            <i class="icon bi-box-arrow-up-right me-2"></i>
                                            {{ __('View document') }}
                                        </a>
                                    </div>
                                @endif

                            </div>
                        </div>
                        <!-- Appointment, possession, transfer End -->

                        @if ($coordination->signature)
                            <!-- Signature Section Start -->
                            <div class="card mb-5">
                                <div class="card-body text-center">
                                    <div class="border rounded-md mb-2 form-signature">
                                        <img src="{{ config('app.url') . '/' . $coordination->signature }}"
                                            class="rounded-0 max-w-100 sh-19 object-scale-down" />
                                    </div>
                                </div>
                            </div>
                            <!-- Signature Section End -->
                        @endif

                    </section>
                    <!-- Information Content Tab End -->

                </div>
                <!-- Information Tab End -->

                <!-- Permits Tab Start -->
                <div class="tab-pane fade @if (session('tab') === 'permits') active show @endif" id="permitsTab"
                    role="tabpanel">

                    <!-- Permits Content Start -->
                    <h2 class="small-title">{{ __('Permits') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">

                            <!-- Table Start -->
                            <div class="table-responsive-sm">
                                <table logro='dataTableBoxed' data-order='[]'
                                    class="table responsive stripe">
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2 text-center">
                                                {{ __('Date of application') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2 text-center">
                                                {{ __('type') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('short description') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('status') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2 text-center">
                                                {{ __('date range') }}</th>
                                            @hasanyrole('SUPPORT|COORDINATOR')
                                                <th class="empty p-0">&nbsp;</th>
                                            @endhasanyrole
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coordination->permits as $permit)
                                            <tr>
                                                <td align="center" class="text-small">{{ $permit->created_at }}</td>
                                                <td>{{ $permit->typePermit->name }}</td>
                                                <td>{{ $permit->description }} </td>
                                                <td>
                                                    {!! $permit->status->getLabelHtml() !!}
                                                    @if (!$permit->status->isPending())
                                                        <span
                                                            class="text-small fst-italic">{{ $permit?->accept_deny?->getFullName() }}</span>
                                                    @endif
                                                </td>
                                                <td align="center" class="text-small">{{ $permit->dateRange() }}</td>
                                                @hasanyrole('SUPPORT|COORDINATOR')
                                                    <td align="right">

                                                        @if ($permit->status->isPending())
                                                            <!-- Dropdown Button Start -->
                                                            <div class="ms-1">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-icon-only text-primary p-1"
                                                                    data-bs-offset="0,3" data-bs-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false" data-submenu>
                                                                    <i data-acorn-icon="more-vertical"
                                                                        data-acorn-size="17"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    @if ( $permit->support_document )
                                                                        <a class="dropdown-item" target="_blank"
                                                                            href="{{ $permit->support_document }}">
                                                                            <i class="icon bi-box-arrow-up-right me-1"></i>
                                                                            {{ __('View document') }}
                                                                        </a>

                                                                        <div class="dropdown-divider"></div>
                                                                    @endif
                                                                    <div class="dropdown-item cursor-pointer"
                                                                        modal="acceptOrDenyPermit"
                                                                        data-permit="{{ $permit->id }}">
                                                                        <span>{{ __('Accept or Deny') }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Dropdown Button End -->
                                                        @endif

                                                        @if ( ! $permit->status->isPending() && $permit->support_document )
                                                            <!-- Dropdown Button Start -->
                                                            <div class="ms-1">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-icon-only text-primary p-1"
                                                                    data-bs-offset="0,3" data-bs-toggle="dropdown"
                                                                    aria-haspopup="true" aria-expanded="false" data-submenu>
                                                                    <i data-acorn-icon="more-vertical"
                                                                        data-acorn-size="17"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item" target="_blank"
                                                                        href="{{ $permit->support_document }}">
                                                                        <i class="icon bi-box-arrow-up-right me-1"></i>
                                                                        {{ __('View document') }}
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <!-- Dropdown Button End -->
                                                        @endif

                                                    </td>
                                                @endhasanyrole
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </section>
                    <!-- Permits Content End -->

                </div>
                <!-- Permits Tab End -->

                <!-- Hierarchy Tab Start -->
                <div class="tab-pane fade show @if (session('tab') === 'hierarchies') active show @endempty" id="hierarchyTab"
                    role="tabpanel">

                    <!-- Hierarchy Content Tab Start -->
                    <h2 class="small-title">{{ __('Hierarchy') }}</h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                    logro="dataTableBoxed" data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('number') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('Resolution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date') }}
                                            </th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coordination->hierarchies as $hierarchy)
                                            <tr>
                                                <td>{{ $hierarchy->number }}</td>
                                                <td>{{ $hierarchy->resolution }}</td>
                                                <td class="text-small">{{ $hierarchy->date }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') . '/' . $hierarchy->url }}"
                                                        class="btn btn-sm btn-link text-capitalize" target="_blank">
                                                        <i class="icon bi-box-arrow-up-right me-1"></i>
                                                        {{ __('open') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                    <!-- Hierarchy Content Tab End -->

                </div>
                <!-- Hierarchy Tab End -->

                <!-- Degree Tab Start -->
                <div class="tab-pane fade show @if (session('tab') === 'degrees') active show @endempty" id="degreeTab"
                    role="tabpanel">

                    <!-- Degree Content Tab Start -->
                    <h2 class="small-title">{{ __('Degrees') }}</h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                    logro="dataTableBoxed" data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('institution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('degree') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date') }}
                                            </th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coordination->degrees as $degree)
                                            <tr>
                                                <td>{{ $degree->institution }}</td>
                                                <td>{{ $degree->degree }}</td>
                                                <td class="text-small">{{ $degree->date }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') . '/' . $degree->url }}"
                                                        class="btn btn-sm btn-link text-capitalize" target="_blank">
                                                        <i class="icon bi-box-arrow-up-right me-1"></i>
                                                        {{ __('open') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                    <!-- Degree Content Tab End -->

                </div>
                <!-- Degree Tab End -->

                <!-- Employment History Tab Start -->
                <div class="tab-pane fade show @if (session('tab') === 'employments') active show @endempty"
                    id="employmentsTab" role="tabpanel">

                    <!-- Employment History Content Tab Start -->
                    <h2 class="small-title">{{ __('Employment history') }}</h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                    logro="dataTableBoxed" data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('institution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('date of entry') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('date of withdrawal') }}</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coordination->employments as $employment)
                                            <tr>
                                                <td>{{ $employment->institution }}</td>
                                                <td class="text-small">{{ $employment->date_start }}</td>
                                                <td class="text-small">{{ $employment->date_end }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') . '/' . $employment->url }}"
                                                        class="btn btn-sm btn-link text-capitalize" target="_blank">
                                                        <i class="icon bi-box-arrow-up-right me-1"></i>
                                                        {{ __('open') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </section>
                    <!-- Employment History Content Tab End -->

                </div>
                <!-- Employment History Tab End -->

            </div>
            <!-- Right Side End -->
        </section>
    </div>


    @hasanyrole('SUPPORT|SECRETARY')

        <!-- Modal Restore Password Start -->
        <div class="modal fade" id="restorePassword" aria-labelledby="modalRestorePassword" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Restore password') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>
                            {!! __("Are you sure to reset <b>:USER's</b> password?", ['USER' => $coordination->getFullName()]) !!}
                        </p>
                        <div class="btn btn-outline-primary" modal="restorePassword" data-role="COORDINATOR"
                            data-id="{{ $coordination->uuid }}">{{ __('Restore') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Restore Password End -->

        <!-- Modal Mutate User Start -->
        <div class="modal fade" id="mutateUser" aria-labelledby="modalMutateUser" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Login') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p>
                            ¿Está seguro de iniciar sesión como <strong>{{ $coordination->getFullName() }}</strong>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('coordination.mutate', $coordination) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-danger"
                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-outline-primary">
                                {{ __('Login') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Mutate User End -->
    @endhasanyrole

    @hasanyrole('SUPPORT|COORDINATOR')
        <!-- Modal Accept or Deny Permit Start -->
        <div class="modal fade" id="acceptOrDenyPermitModal" aria-labelledby="modalAcceptOrDenyPermit"
            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAcceptOrDenyPermit">{{ __('Accept or Deny Permission') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('coordination.permit.accepted', $coordination) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="permit" value="">

                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-check custom-card cursor-pointer w-100 position-relative p-0 m-0">
                                        <input type="radio" class="form-check-input position-absolute e-2 t-2 z-index-1"
                                            name="accept_or_deny" value="accept" />
                                        <span class="card form-check-label form-check-label-success w-100 custom-border">
                                            <span class="card-body text-center">
                                                <span class="heading mt-3 text-body text-primary d-block">Aceptar</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="form-check custom-card cursor-pointer w-100 position-relative p-0 m-0">
                                        <input type="radio" class="form-check-input position-absolute e-2 t-2 z-index-1"
                                            name="accept_or_deny" value="deny" />
                                        <span class="card form-check-label form-check-label-danger w-100 custom-border">
                                            <span class="card-body text-center">
                                                <span class="heading mt-3 text-body text-primary d-block">Denegar</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger"
                                data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                            <button type="submit" class="btn btn-outline-primary">
                                {{ __('Save') }}</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- Modal Accept or Deny Permit End -->
    @endhasanyrole
@endsection
