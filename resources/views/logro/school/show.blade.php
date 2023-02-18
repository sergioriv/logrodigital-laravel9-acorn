@php
$title = $school->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <!-- DataTable -->
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/singleimageupload.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>

    <!-- DataTable -->
    <script src="/js/vendor/datatables.min.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/myinstitutionform.js?v=0.2"></script>

    <!-- DataTable -->
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_myintitution.ajax.js?d=1668181091077"></script>
    <script>
        new SingleImageUpload(document.getElementById('signatureRector'))
    </script>
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
                <h2 class="small-title text-muted">&nbsp;</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="nav flex-column" role="tablist">

                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#myInstitutionTab" role="tab">
                                <span class="align-middle">{{ __('My Institution') }}</span>
                            </a>
                            @can('coordination.index')
                                <a class="nav-link @if(session('tab') === 'coordination') active @endif logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                    href="#coordinationTab" role="tab">
                                    <span class="align-middle">{{ __('Coordination') }}</span>
                                </a>
                            @endcan
                            @can('teachers.index')
                                <a class="nav-link @if(session('tab') === 'teachers') active @endif logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                    href="#teachersTab" role="tab">
                                    <span class="align-middle">{{ __('Teachers') }}</span>
                                </a>
                            @endcan
                            @can('orientation.index')
                                <a class="nav-link @if(session('tab') === 'orientation') active @endif logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                    href="#orientationTab" role="tab">
                                    <span class="align-middle">{{ __('Orientation') }}</span>
                                </a>
                            @endcan
                            @can('secretariat.index')
                                <a class="nav-link @if(session('tab') === 'secretariat') active @endif logro-toggle px-0 border-bottom border-separator-light" data-bs-toggle="tab"
                                    href="#secretariatTab" role="tab">
                                    <span class="align-middle">{{ __('Secretariat') }}</span>
                                </a>
                            @endcan
                            <a class="nav-link @if(session('tab') === 'security') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#securityTab" role="tab">
                                <span class="align-middle">{{ __('Security') }}</span>
                            </a>
                            <a class="nav-link @if(session('tab') === 'signature') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#signaureTab" role="tab">
                                <span class="align-middle">{{ __('Info Rector') }}</span>
                            </a>
                        </div>

                    </div>
                </div>
                <!-- Biography End -->

                <!-- Students Number Start -->
                <h2 class="small-title">{{ __('Students Number') }}</h2>
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="text-end">
                            <h5 class="mb-0">
                                @thousands($studentsCount)
                                <small class="text-small text-muted">/ @thousands($school->number_students)</small>
                            </h5>

                        </div>
                    </div>
                </div>
                <!-- Students Number End -->

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
                                    <label for="inputDane" class="col-sm-3 col-form-label">Dane
                                        <x-required />
                                    </label>
                                    <div class="col-sm-9">
                                        <x-input :value="$school->dane" name="dane" id="inputDane" :hasError="true"
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
                            </div>
                        </section>

                        @if ($daysToUpdate > 0)
                        <section class="mb-5">
                            <div class="alert alert-info">
                                <i data-acorn-icon="warning-circle"></i>
                                Podrás cambiar la información de la institución después de {{ $daysToUpdate }} días.
                            </div>
                        </section>

                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                            <x-button class="btn-primary" type="submit" disabled>{{ __('Save') }}</x-button>
                        </div>
                        @else
                        <section class="mb-5">
                            <div class="alert alert-warning">
                                <i data-acorn-icon="warning-circle"></i>
                                Despues de guardar, lo podrá volver hacer dentro de 60 días.
                            </div>
                        </section>

                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                            <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>
                        </div>
                        @endif
                        <!-- My Institution Content End -->

                    </form>
                </div>
                <!-- My Institution Tab End -->

                <!-- Coordination Tab Start -->
                <div class="tab-pane fade @if(session('tab') === 'coordination') active show @endif" id="coordinationTab" role="tabpanel">

                    <!-- Coordination Content Start -->
                    <h2 class="small-title">{{ __('Coordination') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">
                            <!-- Controls Start -->
                            <div class="row mb-3">
                                <!-- Search Start -->
                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 mb-1">
                                    <div
                                        class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                        <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
                                            data-datatable="#datatable_coordination" />
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                            <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- Search End -->

                                @can('coordination.create')
                                    <!-- Top Buttons Start -->
                                    <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-start justify-content-end">

                                        <!-- Add New Button Start -->
                                        <a href="{{ route('coordination.create') }}"
                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                            <i data-acorn-icon="plus"></i>
                                            <span>{{ __('Add New') }}</span>
                                        </a>
                                        <!-- Add New Button End -->

                                    </div>
                                    <!-- Top Buttons End -->
                                @endcan

                            </div>
                            <!-- Controls End -->

                            <!-- Table Start -->
                            <div class="">
                                <table id="datatable_coordination"
                                    class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                    data-order='[[ 0, "asc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('names') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('last names') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('email') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('cellphone') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coordinations as $coordination)
                                            <tr>
                                                <td>{{ $coordination->names }}</td>
                                                <td>{{ $coordination->last_names }}</td>
                                                <td>{{ $coordination->institutional_email }}</td>
                                                <td>{{ $coordination->cellphone }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </section>
                    <!-- Coordination Content End -->

                </div>
                <!-- Coordination Tab End -->

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
                                        <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
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

                                    @can('teachers.create')
                                        <!-- Add New Button Start -->
                                        <a href="{{ route('teacher.create') }}"
                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                            <i data-acorn-icon="plus"></i>
                                            <span>{{ __('Add New') }}</span>
                                        </a>
                                        <!-- Add New Button End -->
                                    @endcan

                                    <!-- Dropdown Button Start -->
                                    <div class="ms-1">
                                        <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
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
                                                {{ __('names') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('last names') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('email') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('cellphone') }}</th>
                                            <th class="empty"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teachers as $teacher)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('teacher.show', $teacher) }}"
                                                        class="list-item-heading body">
                                                        {{ $teacher->names }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('teacher.show', $teacher) }}"
                                                        class="list-item-heading body">
                                                        {{ $teacher->last_names }}
                                                    </a>
                                                </td>
                                                <td>{{ $teacher->institutional_email }}</td>
                                                <td>{{ $teacher->cellphone }}</td>
                                                <td class="text-end">
                                                    <!-- Dropdown Button Start -->
                                                    <div class="ms-1">
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-icon btn-icon-only"
                                                            data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false" data-submenu>
                                                            <i data-acorn-icon="more-vertical"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item btn-icon btn-icon-start"
                                                                href="{{ route('teacher.guide-groups', $teacher) }}">
                                                                <i data-acorn-icon="download"></i>
                                                                <span>Descargar planillas</span>
                                                            </a>
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
                    </section>
                    <!-- Teachers Content End -->

                </div>
                <!-- Teachers Tab End -->

                <!-- Orientation Tab Start -->
                <div class="tab-pane fade @if(session('tab') === 'orientation') active show @endif" id="orientationTab" role="tabpanel">

                    <!-- Orientation Content Start -->
                    <h2 class="small-title">{{ __('Orientation') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">
                            <!-- Controls Start -->
                            <div class="row mb-3">
                                <!-- Search Start -->
                                <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 mb-1">
                                    <div
                                        class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
                                        <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
                                            data-datatable="#datatable_orientation" />
                                        <span class="search-magnifier-icon">
                                            <i data-acorn-icon="search"></i>
                                        </span>
                                        <span class="search-delete-icon d-none">
                                            <i data-acorn-icon="close"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- Search End -->

                                @can('orientation.create')
                                    <!-- Top Buttons Start -->
                                    <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-start justify-content-end">

                                        <!-- Add New Button Start -->
                                        <a href="{{ route('orientation.create') }}"
                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                            <i data-acorn-icon="plus"></i>
                                            <span>{{ __('Add New') }}</span>
                                        </a>
                                        <!-- Add New Button End -->

                                    </div>
                                    <!-- Top Buttons End -->
                                @endcan

                            </div>
                            <!-- Controls End -->

                            <!-- Table Start -->
                            <div class="">
                                <table id="datatable_orientation"
                                    class="data-table responsive nowrap stripe dataTable no-footer dtr-inline"
                                    data-order='[[ 0, "asc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('names') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('last names') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('email') }}
                                            </th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                {{ __('telephone') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orientations as $orientation)
                                            <tr>
                                                <td>{{ $orientation->name }}</td>
                                                <td>{{ $orientation->last_names }}</td>
                                                <td>{{ $orientation->email }}</td>
                                                <td>{{ $orientation->telephone }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Table End -->
                        </div>
                    </section>
                    <!-- Orientation Content End -->

                </div>
                <!-- Orientation Tab End -->

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
                                        <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
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

                                @can('secretariat.create')
                                    <!-- Top Buttons Start -->
                                    <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-start justify-content-end">

                                        <!-- Add New Button Start -->
                                        <a href="{{ route('secreatariat.create') }}"
                                            class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                                            <i data-acorn-icon="plus"></i>
                                            <span>{{ __('Add New') }}</span>
                                        </a>
                                        <!-- Add New Button End -->

                                    </div>
                                    <!-- Top Buttons End -->
                                @endcan

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
                                                {{ __('names') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('last names') }}
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

                <!-- Security Tab Start -->
                <div class="tab-pane fade @if(session('tab') === 'security') active show @endif" id="securityTab">
                    <form method="POST" action="{{ route('myinstitution.security.email') }}" class="tooltip-end-bottom"
                        id="mySecurityEmailForm" novalidate>
                        @csrf
                        @method('PATCH')

                        <!-- Security Email Start -->
                        <h2 class="small-title">{{ __('Security Email') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <p>
                                    Texto de información
                                </p>
                                <div class="row">
                                    <label for="inputSecurityEmail" class="col-sm-3 col-form-label logro-label">
                                        {{ __('security email') }} <x-required />
                                    </label>
                                    <div class="col-sm-9 position-relative">
                                        <x-input :value="$school->security_email" name="security_email" id="inputSecurityEmail"
                                            :hasError="true" />
                                    </div>
                                </div>
                                @if ($school->security_email === NULL || $daysToUpdate <= 0)
                                <div class="row mt-3 mb-3">
                                    <label for="inputSecurityCode" class="col-sm-3 col-form-label">
                                        {{ __('Code') }} <x-required />
                                    </label>
                                    <div class="col-sm-3 position-relative">
                                        <x-input name="code" id="inputSecurityCode"
                                            :hasError="true" />
                                    </div>
                                </div>
                                <div class="row">
                                    <span class="col-sm-3"></span>
                                    <div class="col-sm-9">
                                        <x-button class="btn-outline-primary" id="btn-sendConfirmation" type="button">
                                            {{ __('Send confirmation email') }}
                                        </x-button>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </section>

                        @if ($daysToUpdate > 0 && $school->security_email !== NULL)
                        <section class="mb-5">
                            <div class="alert alert-info">
                                <i data-acorn-icon="warning-circle"></i>
                                Podrás cambiar la información de la institución después de {{ $daysToUpdate }} días.
                            </div>
                        </section>

                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                            <x-button class="btn-primary" type="submit" disabled>{{ __('Save') }}</x-button>
                        </div>
                        @else
                        <section class="mb-5">
                            <div class="alert alert-warning">
                                <i data-acorn-icon="warning-circle"></i>
                                Despues de guardar, lo podrá volver hacer dentro de 60 días.
                            </div>
                        </section>

                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                            <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>
                        </div>
                        @endif
                    </form>
                </div>
                <!-- Security Tab End -->

                <!-- Info Rector Tab Start -->
                <div class="tab-pane fade @if(session('tab') === 'signature') active show @endif" id="signaureTab">
                    <form method="POST" action="{{ route('myinstitution.security.signature') }}" class="tooltip-end-bottom"
                        id="mySignatureForm" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PATCH')

                        <!-- Security Email Start -->
                        <h2 class="small-title">{{ __('Info Rector') }}</h2>
                        <section class="card mb-5">
                            <div class="card-body">
                                <label class="form-label" for="signature_rector">{{ __('Signature Rector') }}</label>
                                <div class="d-flex justify-content-center">
                                    <div id="signatureRector" class="col-12 text-center">
                                        <div class='position-relative d-inline-block tooltip-center-top'>
                                            @if (!is_null($school->signature_rector))
                                                <img src="{{ config('app.url') . '/' . $school->signature_rector }}" alt="signature"
                                                    class="form-signature rounded-0 max-w-100 sh-19 object-scale-down" />
                                            @else
                                                <img src="{{ config('app.url') . '/img/logo/logo-logro-gray.svg' }}"
                                                    alt="signature" class="form-signature rounded-0 max-w-100 sh-19 object-scale-down">
                                            @endif
                                            <button
                                                class="btn btn-sm btn-icon btn-icon-only btn-separator-light rounded-xl position-absolute e-0 b-0"
                                                type="button">
                                                <i data-acorn-icon="upload"></i>
                                            </button>
                                            <input name="signature_rector" id="signature_rector" class="file-upload d-none" type="file"
                                                accept="image/jpg, image/jpeg, image/png, image/webp" />
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-center mb-3">
                                    <div class="form-text">
                                        {{ __('formarts') }}: jpg, jpeg, png, webp
                                    </div>
                                </div>
                                <div class="position-relative form-group">
                                    <label class="form-label" for="rectorName" required>{{ __('Rector name') }}<x-required /></label>
                                    <x-input :value="$school->rector_name" name="rector_name" id="rectorName" :hasError="true"
                                        required />
                                </div>
                            </div>
                        </section>

                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                            <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>
                        </div>

                    </form>
                </div>
                <!-- Info Rector Tab End -->

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
