@php
$title = $school->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    {{-- <link rel="stylesheet" href="/css/vendor/select2.min.css" /> --}}
    {{-- <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" /> --}}
    {{-- <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" /> --}}

    <!-- DataTable -->
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/singleimageupload.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    {{-- <script src="/js/vendor/select2.full.min.js"></script> --}}
    {{-- <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script> --}}
    {{-- <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script> --}}

    <!-- DataTable -->
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/myinstitutionform.js"></script>

    <!-- DataTable -->
    <script src="/js/cs/datatable.extend.js"></script>
    <script src="/js/plugins/datatable/datatables_myintitution.ajax.js"></script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                </div>
                <!-- Title End -->
            </div>
        </section>
        <!-- Title and Top Buttons End -->

        <section class="row">
            <!-- Left Side Start -->
            <div class="col-12 col-xl-3 col-xxl-2">
                <!-- Biography Start -->
                <h2 class="small-title">{{ __('Falta un titulo') }}</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="nav flex-column" role="tablist">

                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#myInstitutionTab" role="tab">
                                <span class="align-middle">{{ __('My Institution') }}</span>
                            </a>
                            @can('teachers.index')
                                <a class="nav-link @if(session('tab') === 'teachers') active @endif logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                    href="#teachersTab" role="tab">
                                    <span class="align-middle">{{ __('Teachers') }}</span>
                                </a>
                            @endcan
                            @can('secretariat.index')
                                <a class="nav-link @if(session('tab') === 'secretariat') active @endif logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                    href="#secretariatTab" role="tab">
                                    <span class="align-middle">{{ __('Secretariat') }}</span>
                                </a>
                            @endcan
                        </div>

                    </div>
                </div>
                <!-- Biography End -->
            </div>
            <!-- Left Side End -->

            <!-- Right Side Start -->
            <div class="col-12 col-xl-9 col-xxl-10 mb-5 tab-content">

                <!-- My Institution Tab Start -->
                <div class="tab-pane fade @empty(session('tab')) active show @endempty" id="myInstitutionTab" role="tabpanel">

                    <form method="POST" action="{{ route('myinstitution.update') }}" class="tooltip-center-bottom"
                        enctype="multipart/form-data" id="myInstitutionForm" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- My Institution Content Start -->
                        <h2 class="small-title">{{ __('My Institution') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <div class="d-flex justify-content-center">

                                    <div class='position-relative d-inline-block tooltip-center-top' id="imageProfile">
                                        @if (null !== $school->badge)
                                            <img src="{{ config('app.url') . '/' . $school->badge }}" alt="badge"
                                                class="sw-13 sh-13 object-fit-fill" />
                                        @else
                                            <img src="{{ config('app.url') . '/img/logo/logo-logro-gray.svg' }}"
                                                alt="badge" class="sw-13 sh-13 object-fit-fill">{{-- shadow-deep --}}
                                        @endif
                                        <button
                                            class="btn btn-sm btn-icon btn-icon-only btn-separator-light rounded-xl position-absolute e-0 b-0"
                                            type="button">
                                            <i data-acorn-icon="upload"></i>
                                        </button>
                                        <input name="badge" id="avatar" class="file-upload d-none" type="file"
                                            accept="image/jpg, image/jpeg, image/png, image/webp" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="form-text">
                                        {{ __('formarts') }}: jpg, jpeg, png, webp
                                    </div>
                                </div>
                                <div class="row mb-3 position-relative">
                                    <label for="inputName" class="col-sm-3 col-form-label">{{ __('Name') }}
                                        <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-input :value="$school->name" name="name" id="inputName" :hasError="true"
                                            required />
                                    </div>
                                </div>
                                <div class="row mb-3 position-relative">
                                    <label for="inputNit" class="col-sm-3 col-form-label">Nit
                                        <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-input :value="$school->nit" name="nit" id="inputNit" :hasError="true"
                                            required />
                                    </div>
                                </div>
                                <div class="row mb-3 position-relative">
                                    <label for="inputContactEmail"
                                        class="col-sm-3 col-form-label">{{ __('Contact Email') }}
                                        <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-input :value="$school->contact_email" name="contact_email" id="inputContactEmail"
                                            :hasError="true" required />
                                    </div>
                                </div>
                                <div class="row mb-3 position-relative">
                                    <label for="inputContactTelephone"
                                        class="col-sm-3 col-form-label">{{ __('Contact Telephone') }}
                                        <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-input :value="$school->contact_telephone" name="contact_telephone" id="inputContactTelephone"
                                            :hasError="true" required />
                                    </div>
                                </div>
                                <div class="row mb-3 position-relative">
                                    <label for="inputInstitutionalEmail"
                                        class="col-sm-3 col-form-label">{{ __('institutional email') }}</label>
                                    <div class="col-sm-9">
                                        <x-input :value="$school->institutional_email" placeholder="Ej: @logro.digital"
                                            name="institutional_email" id="inputInstitutionalEmail" :hasError="true" />
                                    </div>
                                </div>
                                <div class="row mb-3 position-relative">
                                    <label for="inputHandbook" class="col-sm-3 col-form-label">
                                        URL: {{ __('Handbook of coexistence') }}
                                        <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-input :value="$school->handbook_coexistence" placeholder="https://" name="handbook_coexistence"
                                            id="inputHandbook" :hasError="true" required />
                                    </div>
                                </div>
                                <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                    <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>
                                </div>
                            </div>
                        </section>
                        <!-- My Institution Content End -->

                    </form>
                </div>
                <!-- My Institution Tab End -->

                <!-- Teachers Tab Start -->
                <div class="tab-pane fade @if(session('tab') === 'teachers') active show @endif" id="teachersTab" role="tabpanel">

                    <!-- Teachers Content Start -->
                    <h2 class="small-title">{{ __('Teachers') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">
                            <!-- Controls Start -->
                            <div class="row mb-3">
                                <!-- Search Start -->
                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 mb-1">
                                    <div
                                        class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                        <input class="form-control datatable-search" placeholder="Search"
                                            data-datatable="#datatable_teachers" />
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                            <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- Search End -->

                                <!-- Top Buttons Start -->
                                <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-start justify-content-end">

                                    <!-- Add New Button Start -->
                                    <a href="{{ route('teacher.create') }}"
                                        class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                        <i data-acorn-icon="plus"></i>
                                        <span>{{ __('Add New') }}</span>
                                    </a>
                                    <!-- Add New Button End -->

                                    <!-- Dropdown Button Start -->
                                    <div class="ms-1">
                                        <button type="button" class="btn btn-outline-primary btn-icon btn-icon-only"
                                            data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false" data-submenu>
                                            <i data-acorn-icon="more-horizontal"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item btn-icon btn-icon-start"
                                                href="{{ route('teacher.export') }}">
                                                <i data-acorn-icon="download"></i>
                                                <span>{{ __('Download') }} Excel</span>
                                            </a>
                                            <a class="dropdown-item btn-icon btn-icon-start"
                                                href="{{ route('teacher.import') }}">
                                                <i data-acorn-icon="upload"></i>
                                                <span>{{ __('Import') }} Excel</span>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Dropdown Button End -->

                                </div>
                                <!-- Top Buttons End -->
                            </div>
                            <!-- Controls End -->

                            <!-- Table Start -->
                            <div class="">
                                <table id="datatable_teachers"
                                    class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                    data-order='[[ 0, "asc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('last names') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('names') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('email') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('telephone') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teachers as $teacher)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('teacher.show', $teacher) }}"
                                                        class="list-item-heading body">
                                                        {{ $teacher->getLastNames() }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('teacher.show', $teacher) }}"
                                                        class="list-item-heading body">
                                                        {{ $teacher->getNames() }}
                                                    </a>
                                                </td>
                                                <td>{{ $teacher->institutional_email }}</td>
                                                <td>{{ $teacher->telephone }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </section>
                    <!-- Teachers Content End -->

                </div>
                <!-- Teachers Tab End -->

                <!-- Secretariat Tab Start -->
                <div class="tab-pane fade @if(session('tab') === 'secretariat') active show @endif" id="secretariatTab" role="tabpanel">

                    <!-- Secretariat Content Start -->
                    <h2 class="small-title">{{ __('Secretariat') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">
                            <!-- Controls Start -->
                            <div class="row mb-3">
                                <!-- Search Start -->
                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 mb-1">
                                    <div
                                        class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                        <input class="form-control datatable-search" placeholder="Search"
                                            data-datatable="#datatable_secretariat" />
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                            <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- Search End -->

                                <!-- Top Buttons Start -->
                                <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-start justify-content-end">

                                    <!-- Add New Button Start -->
                                    <a href="{{ route('secreatariat.create') }}"
                                        class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                        <i data-acorn-icon="plus"></i>
                                        <span>{{ __('Add New') }}</span>
                                    </a>
                                    <!-- Add New Button End -->

                                </div>
                                <!-- Top Buttons End -->
                            </div>
                            <!-- Controls End -->

                            <!-- Table Start -->
                            <div class="">
                                <table id="datatable_secretariat"
                                    class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                    data-order='[[ 0, "asc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('last names') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('names') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('email') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('telephone') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($secretariats as $secretariat)
                                            <tr>
                                                <td>{{ $secretariat->name }}</td>
                                                <td>{{ $secretariat->last_names }}</td>
                                                <td>{{ $secretariat->email }}</td>
                                                <td>{{ $secretariat->telephone }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </section>
                    <!-- Secretariat Content End -->

                </div>
                <!-- Secretariat Tab End -->


            </div>
            <!-- Right Side End -->
        </section>

    </div>

    <!-- Modal Document Images -->
    <div class="modal fade modal-close-out" id="modalStudentDocuments" tabindex="-1" role="dialog"
        aria-labelledby="Document" aria-hidden="true">
        <div class="modal-dialog modal-semi-full modal-dialog-centered logro-modal-image">
            <img src="\img\other\none.png" alt="document">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    </div>
@endsection