@php
    $title = $teacher->names;
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/datatables.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/datatables.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>
    <script src="/js/vendor/imask.js"></script>
    <script src="/js/vendor/singleimageupload.js"></script>
@endsection

@section('js_page')
    <script src="/js/forms/teacher-edit.js?d=1674053239410"></script>
    <script src="/js/forms/select2.js"></script>
    <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
    <script src="/js/plugins/datatable/datatables_boxed.js?d=1670967386206"></script>
    <script>
        IMask(document.querySelector('[name="document"]'), {
            mask: Number,
        });
        IMask(document.querySelector('[name="telephone"]'), {
            mask: Number,
        });
        IMask(document.querySelector('[name="cellphone"]'), {
            mask: Number,
        });
        new SingleImageUpload(document.getElementById('signature'));

        jQuery("#signature input[type='file']").change(function () {
            $("#signature .form-signature").removeClass('d-none');
        });
    </script>
@endsection

@section('content')
    <div class="container">

        <!-- Title and Top Buttons Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-7 mb-2 mb-md-0">
                    <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                </div>
                <!-- Title End -->

                <!-- Top Buttons Start -->
                <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">

                    <!-- Add New Button Start -->
                    <a href="{{ route('profile.auth.avatar.edit') }}"
                        class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                        <i data-acorn-icon="edit-square"></i>
                        <span>{{ __('Edit avatar') }}</span>
                    </a>
                    <!-- Add New Button End -->

                </div>
                <!-- Top Buttons End -->
            </div>
        </section>
        <!-- Title and Top Buttons End -->

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
                                <x-avatar-profile :avatar="$teacher->user->avatar" class="mb-3" />
                                <!-- Avatar Form End -->

                                <div class="h5">{{ $teacher->getFullName() }}</div>
                                @if ($teacher instanceof \App\Models\Coordination )
                                <div class="text-muted text-uppercase">{{ __('coordinator') }}</div>
                                @elseif ($teacher instanceof \App\Models\Teacher )
                                <div class="text-muted text-uppercase">{{ __('Teacher') }}</div>
                                @endif
                                <div class="text-muted">{{ __($teacher->type_appointment) }}</div>
                            </div>
                        </div>

                        <div class="nav flex-column mb-5" role="tablist">

                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#infoTab" role="tab">
                                <span class="align-middle">{{ __('Information') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'hierarchies') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#hierarchyTab" role="tab">
                                <span class="align-middle">{{ __('Hierarchy') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'degrees') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#degreeTab" role="tab">
                                <span class="align-middle">{{ __('Last titles obtained') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'employments') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#employmentsTab" role="tab">
                                <span class="align-middle">{{ __('Employment history') }}</span>
                            </a>

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
            <div class="col-12 col-xl-9 mb-5 tab-content">

                <!-- Info Tab Start -->
                <div class="tab-pane fade @empty(session('tab')) active show @endempty" id="infoTab"
                    role="tabpanel">

                    <!-- Info Content Tab Start -->
                    <h2 class="small-title">{{ __('Information') }}</h2>
                    <section class="scroll-section mb-5">
                        <form method="post" action="{{ route('user.profile.update') }}" class="tooltip-label-end"
                            id="teacherProfileForm" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="card mb-5">
                                <div class="card-body row g-3">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('names') }}</x-label>
                                            <x-input :value="old('names', $teacher)" name="names" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('last names') }}</x-label>
                                            <x-input :value="old('lastNames', $teacher->last_names)" name="lastNames" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('document number') }}</x-label>
                                            <x-input :value="old('document', $teacher)" name="document" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('expedition city') }}</x-label>
                                            <select name="expedition_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected(old('expedition_city', $teacher) == $city->id)>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('birth city') }}</x-label>
                                            <select name="birth_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected(old('birth_city', $teacher) == $city->id)>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('birthdate') }}</x-label>
                                            <x-input :value="old('birthdate', $teacher)" logro="datePickerBefore" name="birthdate"
                                                data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('residence city') }}</x-label>
                                            <select name="residence_city" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($cities as $city)
                                                    <option value="{{ $city->id }}" @selected(old('residence_city', $teacher) == $city->id)>
                                                        {{ $city->department->name . ' | ' . $city->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('residence address') }}</x-label>
                                            <x-input :value="old('address', $teacher)" name="address" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('telephone') }}</x-label>
                                            <x-input :value="old('telephone', $teacher)" name="telephone" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('cellphone') }}</x-label>
                                            <x-input :value="old('cellphone', $teacher)" mask="number" name="cellphone" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <x-label required>{{ __('institutional email') }}</x-label>
                                            <x-input :value="old('institutional_email', $teacher)" name="institutional_email" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('marital status') }}</x-label>
                                            <select name="marital_status" logro="select2">
                                                <option label="&nbsp;"></option>
                                                @foreach ($maritalStatus as $marital)
                                                    <option value="{{ $marital }}" @selected(old('marital_status', $teacher) == $marital)>
                                                        {{ __($marital) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Appointment, possession, transfer Start -->
                            <div class="card mb-5">
                                <div class="card-body row g-3">
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('appointment number') }}</x-label>
                                            <x-input :value="old('appointment_number', $teacher)" name="appointment_number" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_appointment', $teacher)" logro="datePickerBefore" name="date_appointment"
                                                data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                        <div class="row g-2">
                                            <div class="@if($teacher->file_appointment) col-10 @endif position-relative form-group">
                                                <x-label>{{ __('upload file') }} (.pdf)</x-label>
                                                <x-input type="file" accept=".pdf" name="file_appointment"
                                                    class="d-block" />
                                            </div>
                                            @if ($teacher->file_appointment)
                                                <div class="col-2 d-flex align-items-end">
                                                    <a href="{{ config('app.url') .'/'. $teacher->file_appointment }}"
                                                        target="_blank" class="lh-lg">
                                                        <i class="icon icon-18 text-primary bi-box-arrow-up-right"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('possession certificate number') }}</x-label>
                                            <x-input :value="old('possession_certificate', $teacher)" name="possession_certificate" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_possession_certificate', $teacher)" logro="datePickerBefore"
                                                name="date_possession_certificate" data-placeholder="yyyy-mm-dd"
                                                placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                        <div class="row g-2">
                                            <div class="@if($teacher->file_possession_certificate) col-10 @endif position-relative form-group">
                                                <x-label>{{ __('upload file') }} (.pdf)</x-label>
                                                <x-input type="file" accept=".pdf" name="file_possession_certificate"
                                                    class="d-block" />
                                            </div>
                                            @if ($teacher->file_possession_certificate)
                                                <div class="col-2 d-flex align-items-end">
                                                    <a href="{{ config('app.url') .'/'. $teacher->file_possession_certificate }}"
                                                        target="_blank" class="lh-lg">
                                                        <i class="icon icon-18 text-primary bi-box-arrow-up-right"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="w-100 position-relative form-group">
                                            <x-label>{{ __('transfer resolution number') }}</x-label>
                                            <x-input :value="old('transfer_resolution', $teacher)" name="transfer_resolution" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <x-label>{{ __('date') }}</x-label>
                                            <x-input :value="old('date_transfer_resolution', $teacher)" logro="datePickerBefore"
                                                name="date_transfer_resolution" data-placeholder="yyyy-mm-dd"
                                                placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                        <div class="row g-2">
                                            <div class="@if($teacher->file_transfer_resolution) col-10 @endif position-relative form-group">
                                                <x-label>{{ __('upload file') }} (.pdf)</x-label>
                                                <x-input type="file" accept=".pdf" name="file_transfer_resolution"
                                                    class="d-block" />
                                            </div>
                                            @if ($teacher->file_transfer_resolution)
                                                <div class="col-2 d-flex align-items-end">
                                                    <a href="{{ config('app.url') .'/'. $teacher->file_transfer_resolution }}"
                                                        target="_blank" class="lh-lg">
                                                        <i class="icon icon-18 text-primary bi-box-arrow-up-right"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- Appointment, possession, transfer End -->

                            <div class="card mb-5">
                                <div class="card-body">

                                    <div id="signature" class="text-center">
                                        <div class="border rounded-md mb-2 form-signature @if(is_null($teacher->signature)) d-none @endif">
                                            <img src="@if(!is_null($teacher->signature)) {{ config('app.url') .'/'. $teacher->signature }} @endif"
                                                class="rounded-0 max-w-100 sh-19 object-scale-down" />
                                        </div>
                                        <button title="{{ __('load signature') }}"
                                            class="btn w-100 btn-icon btn-separator-light rounded-xl"
                                            type="button">
                                            <i data-acorn-icon="upload"></i>
                                            <span>{{ __('upload signature') }}</span>
                                        </button>
                                        <input name="signature"
                                            class="file-upload d-none" type="file"
                                            accept="image/jpg, image/jpeg, image/png, image/webp" />

                                    </div>

                                </div>
                            </div>

                            <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>

                        </form>
                    </section>
                    <!-- Info Content Tab End -->

                </div>

                <!-- Hierarchy Tab Start -->
                <div class="tab-pane fade show @if (session('tab') === 'hierarchies') active show @endempty" id="hierarchyTab"
                    role="tabpanel">

                    <!-- Hierarchy Content Tab Start -->
                    <h2 class="small-title">{{ __('Hierarchy') }} <i>(max. 5)</i></h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-header">
                                <div id="addHierarchy" class="d-flex justify-content-end">
                                    <div data-bs-toggle="modal" data-bs-target="#addHierarchyModal"
                                        class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                        <i data-acorn-icon="plus"></i>
                                        <span>{{ __('Add New') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" logro="dataTableBoxed"
                                    data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('number') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('Resolution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date') }}</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacher->hierarchies as $hierarchy)
                                            <tr>
                                                <td>{{ $hierarchy->number }}</td>
                                                <td>{{ $hierarchy->resolution }}</td>
                                                <td class="text-small">{{ $hierarchy->date }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') .'/'. $hierarchy->url }}" class="btn btn-sm btn-link text-capitalize" target="_blank">
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
                    <h2 class="small-title">{{ __('Last titles obtained') }} <i>(max. 5)</i></h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-header">
                                <div id="addHierarchy" class="d-flex justify-content-end">
                                    <div data-bs-toggle="modal" data-bs-target="#addDegreeModal"
                                        class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                        <i data-acorn-icon="plus"></i>
                                        <span>{{ __('Add New') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" logro="dataTableBoxed"
                                    data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('institution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('degree') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date') }}</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacher->degrees as $degree)
                                            <tr>
                                                <td>{{ $degree->institution }}</td>
                                                <td>{{ $degree->degree }}</td>
                                                <td class="text-small">{{ $degree->date }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') .'/'. $degree->url }}" class="btn btn-sm btn-link text-capitalize" target="_blank">
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
                <div class="tab-pane fade show @if (session('tab') === 'employments') active show @endempty" id="employmentsTab"
                    role="tabpanel">

                    <!-- Employment History Content Tab Start -->
                    <h2 class="small-title">{{ __('Employment history') }} <i>(max. 5)</i></h2>
                    <section class="scroll-section mb-5">

                        <div class="card">
                            <div class="card-header">
                                <div id="addHierarchy" class="d-flex justify-content-end">
                                    <div data-bs-toggle="modal" data-bs-target="#addEmploymentsModal"
                                        class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto add-datatable">
                                        <i data-acorn-icon="plus"></i>
                                        <span>{{ __('Add New') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" logro="dataTableBoxed"
                                    data-order='[[ 2, "desc" ]]'>
                                    <thead>
                                        <tr>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('institution') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date of entry') }}</th>
                                            <th class="text-muted text-small text-uppercase p-0 pb-2">{{ __('date of withdrawal') }}</th>
                                            <th class="empty">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teacher->employments as $employment)
                                            <tr>
                                                <td>{{ $employment->institution }}</td>
                                                <td class="text-small">{{ $employment->date_start }}</td>
                                                <td class="text-small">{{ $employment->date_end }}</td>
                                                <td class="text-center">
                                                    <a href="{{ config('app.url') .'/'. $employment->url }}" class="btn btn-sm btn-link text-capitalize" target="_blank">
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

    <!-- Modal Add Hierarchy -->
    <div class="modal fade" id="addHierarchyModal" aria-labelledby="modalAddHierarchy" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddHierarchy">{{ __('Add hierarchy') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="teacherHierarchyForm" class="tooltip-end-top"
                    action="

                    @hasrole('TEACHER')
                    {{ route('teacher.hierarchy.store') }}
                    @endhasrole

                    @hasrole('COORDINATOR')
                    {{ route('coordination.hierarchy.store') }}
                    @endhasrole

                    " method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('number') }}</x-label>
                                    <x-input name="hierarchy_number" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('Resolution') }}</x-label>
                                    <x-input name="hierarchy_resolution" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('date') }}</x-label>
                                    <x-input name="hierarchy_date" required
                                    logro="datePickerBefore" data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('file') }} (pdf)</x-label>
                                    <x-input type="file" accept=".pdf" class="d-block" name="hierarchy_file" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Add Hierarchy End -->

    <!-- Modal Add Degree -->
    <div class="modal fade" id="addDegreeModal" aria-labelledby="modalAddDegree" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddDegree">{{ __('Add degree') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="teacherDegreeForm" class="tooltip-end-top"
                    action="

                    @hasrole('TEACHER')
                    {{ route('teacher.degree.store') }}
                    @endhasrole

                    @hasrole('COORDINATOR')
                    {{ route('coordination.degree.store') }}
                    @endhasrole

                    " method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('degree') }}</x-label>
                                    <x-input name="degree_name" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('institution where obtained') }}</x-label>
                                    <x-input name="degree_institution" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('date') }}</x-label>
                                    <x-input name="degree_date" required
                                    logro="datePickerBefore" data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('file') }} (pdf)</x-label>
                                    <x-input type="file" accept=".pdf" class="d-block" name="degree_file" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Add Degree End -->

    <!-- Modal Add Employment History -->
    <div class="modal fade" id="addEmploymentsModal" aria-labelledby="modalAddEmployments" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddEmployments">{{ __('Add employments history') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="teacherEmploymentFrom" class="tooltip-end-top"
                    action="

                    @hasrole('TEACHER')
                    {{ route('teacher.employment.store') }}
                    @endhasrole

                    @hasrole('COORDINATOR')
                    {{ route('coordination.employment.store') }}
                    @endhasrole

                    " method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('institution') }}</x-label>
                                    <x-input name="employment_institution" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('date of entry') }}</x-label>
                                    <x-input name="employment_date_start"
                                    logro="datePickerBefore" data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('date of withdrawal') }}</x-label>
                                    <x-input name="employment_date_end" required
                                    logro="datePickerBefore" data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <x-label required>{{ __('file') }} (pdf)</x-label>
                                    <x-input type="file" accept=".pdf" class="d-block" name="employment_file" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Add Degree End -->
@endsection
