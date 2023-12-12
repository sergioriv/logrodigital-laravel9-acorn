@php
    $title = $student->getFullName();
@endphp
@extends('layout', ['title' => $title])

@section('css')
    <link rel="stylesheet" href="/css/vendor/select2.min.css" />
    <link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="/css/vendor/bootstrap-datepicker3.standalone.min.css" />
@endsection

@section('js_vendor')
    <script src="/js/cs/responsivetab.js"></script>
    <script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
    <script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
    <script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
    <script src="/js/vendor/select2.full.min.js"></script>
    <script src="/js/vendor/select2.full.min.es.js"></script>
    <script src="/js/vendor/datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/js/vendor/datepicker/locales/bootstrap-datepicker.es.min.js"></script>

    @hasrole('STUDENT')
        @if (null === $student->signature_student || null === $student->signature_tutor)
            <script src="/js/vendor/singleimageupload.js"></script>
        @endif
    @endhasrole

    <!-- PSYCHOSOCIAL -->
    @can('students.psychosocial')
        <script src="/js/vendor/datatables.min.js"></script>
        <script src="/js/vendor/timepicker.js"></script>
    @endcan
@endsection

@section('js_page')
        <script src="/js/forms/select2.js"></script>
        <script src="/js/forms/student-profile.js"></script>
        <script src="/js/forms/person-charge.js"></script>
        <script src="/js/forms/signature.js?v=0.2"></script>
        <script src="/js/cs/datatable.extend.js?d=1670967386206"></script>
        <script src="/js/plugins/datatable/datatables_boxed.js"></script>

        @hasrole('STUDENT')
            @if (null === $student->signature_student)
                <script>
                    new SingleImageUpload(document.getElementById('sigLoadStudent'))
                </script>
            @endif

            @if (null === $student->signature_tutor)
                <script>
                    new SingleImageUpload(document.getElementById('sigLoadTutor'))
                </script>
            @endif
        @endhasrole

    @unlessrole('STUDENT')
        @can('students.delete')
            <script src="/js/forms/student-delete.js"></script>
            <script>
                jQuery("[delete-signature]").click(function () {
                    const _this = $(this);
                    const _person = _this.attr('delete-signature');

                    $('#sigDeleteInput').val(_person);
                    $('#sigDeleteImg').attr('src', _this.data('img'));
                    $('#deleteSignatureModal').modal('show');
                });
            </script>
        @endcan
    @endunlessrole

    @can('students.documents.checked')
        <script>
            var studentFileDelete = $('.openModalFileDelete');
            var modalDeleteFile = $('#modalStudentFileDelete');

            var studentReportBookDelete = $('.openModalReportBookDelete');
            var modalDeleteReportBook = $('#modalStudentReportBookDelete');

            studentFileDelete.click(function () {
                $('#studentFileDeleteInput').val($(this).data('file-id'));
                $('#studentFileDeleteName').html($(this).data('file-name'));

                modalDeleteFile.modal('show');
            });

            studentReportBookDelete.click(function () {
                $('#studentReportBookDeleteInput').val($(this).data('file-id'));
                $('#studentReportBookDeleteName').html($(this).data('file-name'));

                modalDeleteReportBook.modal('show');
            });


        </script>
    @endcan

    <!-- PSYCHOSOCIAL -->
    @can('students.psychosocial')
        <script src="/js/forms/student-advices.js"></script>
        <script>
            new TimePicker(document.querySelector('#timeAdvice'));

            /* Ver seguimiento */
            jQuery("[tracking='view']").click(function() {
                var trackingId = $(this).attr('tracking-id');
                $.get(HOST + '/students/tracking', {
                    tracking: trackingId
                }, function(data) {
                    $('#modalViewTracking').html(data.title);
                    $('#modalContentViewTracking').html(data.content);
                    $('#viewTracking').modal('show');
                })
            });
        </script>
    @endcan

    @if (count($student->observer))
        <script>
            jQuery("[data-observer]").click(function () {
                let _observer = $(this).data('observer');

                if (_observer) {
                    $("#observerDisclaimers").val(_observer);
                    $("#addDisclaimers").modal('show');
                }
            });
        </script>
    @endif

    @hasanyrole('SUPPORT|SECRETARY')
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

    <script>
        jQuery("#type_advice").on("change", function (e) {
            if (e.target.value === 'Family') $("#advice_family").removeClass("d-none");
            else $("#advice_family").addClass("d-none");
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <!-- Title and Top Buttons Start -->
        <section class="page-title-container">
            <div class="row">
                <!-- Title Start -->
                <div class="col-12 col-md-8 mb-2 mb-md-0">
                    <h1 class="mb-1 pb-0 display-4" id="title">
                        {{ __('Student') . ' | ' . $student->getCompleteNames() }}</h1>
                    <div aria-label="breadcrumb">
                        <div class="breadcrumb">
                            <span class="breadcrumb-item text-muted">
                                <div class="text-muted d-inline-block">
                                    <i data-acorn-icon="building-large" class="me-1" data-acorn-size="12"></i>
                                    <span class="align-middle">{{ $student->headquarters->name }}</span>
                                </div>
                            </span>
                            <span class="breadcrumb-item text-muted">
                                <div class="text-muted d-inline-block">
                                    <i data-acorn-icon="clock" class="me-1" data-acorn-size="12"></i>
                                    <span class="align-middle">{{ $student->studyTime->name }}</span>
                                </div>
                            </span>
                            <span class="breadcrumb-item text-muted">
                                <div class="text-muted d-inline-block">
                                    <i data-acorn-icon="calendar" class="me-1" data-acorn-size="12"></i>
                                    <span class="align-middle">{{ $student->studyYear->name ?? 'EGRESADO' }}</span>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
                <!-- Title End -->

                @can('students.matriculate')
                    @if (null !== $Y->available)
                        <!-- Top Buttons Start -->
                        <div class="col-12 col-md-4 d-flex align-items-start justify-content-end">
                            @if (!$student->isRetired())
                                <!-- Matriculate Button Start -->
                                <a class="btn btn-outline-info" href="{{ route('students.matriculate', $student) }}">
                                @if (!$student->enrolled)
                                {{ __('Matriculate') }}
                                @else
                                {{ __('Change group') }}
                                @endif
                                </a>
                                <!-- Matriculate Button End -->

                                <!-- Dropdown Button Start -->
                                <div class="ms-1">
                                    <button type="button" class="btn btn-outline-info btn-icon btn-icon-only" data-bs-offset="0,3"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-submenu>
                                        <i data-acorn-icon="more-horizontal"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">

                                            @if ($countGroupsYear)
                                                <x-dropdown-item type="button" :link="route('students.pdf.report_grades', $student)">
                                                    <i data-acorn-icon="download"></i>
                                                    <span>{{ __('Grade report') }}</span>
                                                </x-dropdown-item>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <x-dropdown-item type="button" :link="route('students.pdf.certificate', $student)">
                                                <i data-acorn-icon="download"></i>
                                                <span>{{ __('Download certificate study') }}</span>
                                            </x-dropdown-item>
                                            <x-dropdown-item type="button" :link="route('students.pdf.matriculate', $student)">
                                                <i data-acorn-icon="download"></i>
                                                <span>{{ __('Download enrollment sheet') }}</span>
                                            </x-dropdown-item>
                                            @if ($student->enrolled)
                                                <div class="dropdown-divider"></div>
                                                <x-dropdown-item type="button" :link="route('students.pdf.observations', $student)">
                                                    <i data-acorn-icon="download"></i>
                                                    <span>{{ __('Download observer') }}</span>
                                                </x-dropdown-item>
                                                <x-dropdown-item type="button" :link="route('students.pdf.template-observations', $student)">
                                                    <i data-acorn-icon="download"></i>
                                                    <span>{{ __('Download observer template') }}</span>
                                                </x-dropdown-item>
                                                <div class="dropdown-divider"></div>
                                                <x-dropdown-item type="button" :link="route('students.pdf.carnet', $student)">
                                                    <i data-acorn-icon="download"></i>
                                                    <span>{{ __('Download identification card') }}</span>
                                                </x-dropdown-item>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <x-dropdown-item type="button" :link="route('students.transfer', $student)">
                                                <i data-acorn-icon="destination"></i>
                                                <span>{{ __('Transfer') }}</span>
                                            </x-dropdown-item>
                                            @can('students.delete')
                                                @if (!$student->isRetired())
                                                    <x-dropdown-item type="button" data-bs-toggle="modal"
                                                        data-bs-target="#withdrawStudentModal">
                                                        <i data-acorn-icon="multiply" class="text-danger"></i>
                                                        <span>{{ __('Withdraw student') }}</span>
                                                    </x-dropdown-item>
                                                @endif
                                            @endcan
                                            @hasanyrole('SUPPORT|SECRETARY')
                                            <div class="dropdown-divider"></div>
                                            <x-dropdown-item type="button"
                                                data-bs-toggle="modal"
                                                data-bs-target="#restorePassword">
                                                <i data-acorn-icon="lock-off"></i>
                                                <span>{{ __('Restore password') }}</span>
                                            </x-dropdown-item>
                                            @endhasanyrole
                                    </div>
                                </div>
                                <!-- Dropdown Button End -->

                            @else

                                @can('students.delete')
                                    <!-- Activate Button Start -->
                                    <form action="{{ route('students.activate', $student) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <x-button type="submit" class="btn-success">{{ __('Activate') }}</x-button>
                                    </form>
                                    <!-- Activate Button End -->
                                @endcan

                            @endif
                        </div>
                        <!-- Top Buttons End -->
                    @endif
                @endcan

                @hasanyrole('STUDENT|PARENT')
                    <!-- Top Buttons Start -->
                    <div class="col-12 col-md-4 d-flex align-items-start justify-content-end">
                        <!-- Dropdown Button Start -->
                        <div class="ms-1">
                            <button type="button" class="btn btn-outline-info btn-icon btn-icon-only" data-bs-offset="0,3"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-submenu>
                                <i data-acorn-icon="more-horizontal"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                @if ($countGroupsYear)
                                <x-dropdown-item type="button" :link="route('students.pdf.report_grades', $student->id)">
                                    <i data-acorn-icon="download"></i>
                                    <span>{{ __('Grade report') }}</span>
                                </x-dropdown-item>
                                @endif
                                @if ($student->enrolled)
                                <div class="dropdown-divider"></div>
                                <x-dropdown-item type="button" :link="route('students.pdf.certificate', $student->id)">
                                    <i data-acorn-icon="download"></i>
                                    <span>{{ __('Download certificate study') }}</span>
                                </x-dropdown-item>
                                <div class="dropdown-divider"></div>
                                <x-dropdown-item type="button" :link="route('students.pdf.observations', $student->id)">
                                    <i data-acorn-icon="download"></i>
                                    <span>{{ __('Download observer') }}</span>
                                </x-dropdown-item>
                                <x-dropdown-item type="button" :link="route('students.pdf.template-observations', $student->id)">
                                    <i data-acorn-icon="download"></i>
                                    <span>{{ __('Download observer template') }}</span>
                                </x-dropdown-item>
                                <div class="dropdown-divider"></div>
                                <x-dropdown-item type="button" :link="route('students.pdf.carnet', $student->id)">
                                    <i data-acorn-icon="download"></i>
                                    <span>{{ __('Download identification card') }}</span>
                                </x-dropdown-item>
                                @endif
                                <x-dropdown-item type="button" :link="route('student.pdf.matriculate', $student->id)">
                                    <i data-acorn-icon="download"></i>
                                    <span>@if ($student->enrolled){{ __('Download enrollment sheet') }}@else{{ __('Download registration sheet') }}@endif</span>
                                </x-dropdown-item>
                            </div>
                        </div>
                    </div>
                @endhasanyrole

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
                                <x-avatar-profile :avatar="$student->user->avatar" :inclusive="$student->inclusive" class="mb-3" />
                                <!-- Avatar Form End -->

                                <div class="h5">{{ $student->getFullName() }}</div>
                                @if (null !== $student->birthdate)
                                    <span class="mb-2 text-muted">{{ $student->age() . ' ' . __('years') }}</span>
                                @endif

                                @if (!$student->enrolled && !is_null($student->group_id))
                                    <div class="mt-2 text-center">
                                        <h5 class="text-danger font-weight-bold mb-2">En transferencia</h5>
                                        <text class="text-medium">Grupo actual</text>
                                        <h5 class="text-primary font-weight-bold mb-0">{{ $student->group->name }}</h5>
                                    </div>
                                @elseif ($student->enrolled)
                                    <div class="mt-2 text-center">
                                        <h5 class="text-primary font-weight-bold mb-0">{{ $student->group->name }}</h5>
                                        <text class="text-primary text-small">{{ $student->enrolled_date }}</text>
                                    </div>
                                @endif

                                @hasrole('SUPPORT')
                                @if ($student->fallas->count())
                                    <div class="mt-2">
                                        <a href="{{ route('attendances.student.download', $student) }}">{{ $student->fallas->count() }}
                                            Fallas</a>
                                    </div>
                                @endif
                                @endhasrole

                            </div>
                        </div>

                        <div class="nav flex-column mb-5" role="tablist">
                            <a class="nav-link @empty(session('tab')) active @endempty logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#informationTab" role="tab">
                                <span class="align-middle">{{ __('Information') }}</span>
                            </a>
                            <a class="nav-link @if (session('tab') === 'personsCharge') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#personsChargeTab" role="tab">
                                <span class="align-middle">{{ __('Persons in Charge') }}</span>
                            </a>
                            @can('students.documents.edit')
                                <a class="nav-link @if (session('tab') === 'documents') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#documentsTab" role="tab">
                                    <span class="align-middle">{{ __('Documents') }}</span>
                                </a>
                                <a class="nav-link @if (session('tab') === 'reportBook') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#reportBookTab" role="tab">
                                    <span class="align-middle">{{ __('Report book') }}</span>
                                </a>
                            @endcan
                            <a class="nav-link @if (session('tab') === 'observer') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                data-bs-toggle="tab" href="#observerTab" role="tab">
                                <span class="align-middle">{{ __('Observer') }}</span>
                            </a>
                            @can('students.psychosocial')
                                <a class="nav-link @if (session('tab') === 'psychosocial') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#psychosocialTab" role="tab">
                                    <span class="align-middle">{{ __('Psychosocial Information') }}</span>
                                </a>
                                <a class="nav-link @if (session('tab') === 'tracking') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#trackingTab" role="tab">
                                    <span class="align-middle">{{ __('Tracking') }}</span>
                                </a>
                                {{-- @if (1 === $student->inclusive)
                                    <a class="nav-link logro-toggle px-0 border-bottom border-separator-light"
                                        data-bs-toggle="tab" href="#piarTab" role="tab">
                                        <span class="align-middle">PIAR</span>
                                    </a>
                                @endif --}}
                            @endcan
                            @hasanyrole('SUPPORT|SECRETARY|ORIENTATION')
                                <a class="nav-link @if (session('tab') === 'grades') active @endif logro-toggle px-0 border-bottom border-separator-light"
                                    data-bs-toggle="tab" href="#gradesTab" role="tab">
                                    <span class="align-middle">{{ __('Grades') }}</span>
                                </a>
                            @endhasanyrole
                        </div>

                        @if ($student->isRetired())
                        @if ($student->retiredStudent())
                        <div class="d-flex flex-column mb-3">
                            <text class="text-muted text-small">Retirado por: {{ $student->retiredStudent()->creatorName() }}</text>
                            <text class="text-muted text-small">{{ $student->retiredStudent()->created_at }}</text>
                        </div>
                        @endif
                        @endif

                        <div class="d-flex flex-column">
                            <text class="text-muted text-small">{{ __('created at') }}:</text>
                            <text class="text-muted text-small">{{ $student->created_at }}</text>
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

                        <form method="POST" action="{{ route('students.update', $student) }}" class="tooltip-label-end"
                            enctype="multipart/form-data"
                            @hasrole('STUDENT')
                        id="studentProfileInfoForm"
                        @else
                        id="studentInfoForm"
                        @endhasrole>

                            @csrf
                            @method('PUT')

                            @php $input_required = "" @endphp
                            @hasrole('STUDENT')
                                @php $input_required = '<span class="text-danger">*</span>' @endphp
                            @endhasrole


                            <!-- Basic Information Section Start -->
                            <h2 class="small-title">{{ __('Basic information') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('first name') }} <span class="text-danger">*</span></x-label>
                                                <x-input-error :value="$student->first_name" name="firstName" :hasError="'firstName'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('second name') }}</x-label>
                                                <x-input-error :value="$student->second_name" name="secondName" :hasError="'secondName'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('first last name') }} <span class="text-danger">*</span>
                                                </x-label>
                                                <x-input-error :value="$student->first_last_name" name="firstLastName" :hasError="'firstLastName'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('second last name') }}</x-label>
                                                <x-input-error :value="$student->second_last_name" name="secondLastName" :hasError="'secondLastName'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                @unlessrole('STUDENT')
                                                    <x-label>{{ __('institutional email') }}
                                                        <x-required />
                                                    </x-label>
                                                    <x-input-error :value="$student->institutional_email" name="institutional_email"
                                                        :hasError="'institutional_email'" />
                                                @else
                                                    <x-label>{{ __('institutional email') }}</x-label>
                                                    <span class="form-control text-muted">
                                                        {{ $student->institutional_email }}
                                                    </span>
                                                @endunlessrole
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('telephone') }} {!! $input_required !!}</x-label>
                                                <x-input-error :value="$student->telephone" name="telephone" :hasError="'telephone'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('document type') }} <span class="text-danger">*</span>
                                                </x-label>
                                                <x-select name="document_type" id="document_type" logro="select2"
                                                    :hasError="'document_type'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($documentType as $docType)
                                                        <option value="{{ $docType->code }}"
                                                            foreigner="{{ $docType->foreigner }}"
                                                            @if ($student->document_type_code !== null) @selected(old('document_type', $student->document_type_code) == $docType->code) @endif>
                                                            {{ $docType->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('document') }} <span class="text-danger">*</span></x-label>
                                                <x-input-error :value="$student->document" name="document" :hasError="'document'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('expedition city') }} {!! $input_required !!}</x-label>
                                                <x-select name="expedition_city" id="expedition_city" logro="select2"
                                                    :hasError="'expedition_city'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->expedition_city_id !== null) @selected(old('expedition_city', $student->expedition_city_id) == $city->id) @endif>
                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('number siblings') }} {!! $input_required !!}</x-label>
                                                <x-input-error type="number" :value="$student->number_siblings" name="number_siblings"
                                                    max="200" min="0" :hasError="'number_siblings'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('home country') }}
                                                    <x-required />
                                                </x-label>
                                                <select name="country" id="country" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}"
                                                            national="{{ $country->national }}"
                                                            @if ($student->country_id !== null) @selected(old('country', $student->country_id) == $country->id) @endif>
                                                            {{ __($country->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('birth city') }}</x-label>
                                                <select name="birth_city" id="birth_city" logro="select2"
                                                    @if ($student->country_id !== null) @if (old('country', $student->country_id) != $nationalCountry->id)
                                                    disabled @endif
                                                    @endif>
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->birth_city_id !== null) @selected(old('birth_city', $student->birth_city_id) == $city->id) @endif>
                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                @unlessrole('STUDENT')
                                                    <x-label>{{ __('birthdate') }} {!! $input_required !!}</x-label>
                                                    <x-input-error :value="$student->birthdate" logro="datePickerBefore" name="birthdate"
                                                        :hasError="'birthdate'" data-placeholder="yyyy-mm-dd"
                                                        placeholder="yyyy-mm-dd" />
                                                @else
                                                    <x-label>{{ __('birthdate') }}</x-label>
                                                    <span class="form-control text-muted">{{ $student->birthdate }}</span>
                                                    <x-input-error type="hidden" :value="$student->birthdate" name="birthdate"
                                                        :hasError="'birthdate'" />
                                                @endunlessrole
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('Do you have siblings in the institution?') }}
                                                    {!! $input_required !!}</x-label>
                                                <select name="siblings_in_institution" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(old('siblings_in_institution', 0) == $student->siblings_in_institution)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(old('siblings_in_institution', 1) == $student->siblings_in_institution)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="w-100 position-relative form-group">
                                                <x-label>{{ __('gender') }} {!! $input_required !!}</x-label>
                                                <x-select name="gender" logro="select2" :hasError="'gender'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($genders as $gender)
                                                        <option value="{{ $gender->id }}"
                                                            @if ($student->gender_id !== null) @selected(old('gender', $student->gender_id) == $gender->id) @endif>
                                                            {{ $gender->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="w-100 position-relative form-group">
                                                <x-label class="text-uppercase">RH {!! $input_required !!}</x-label>
                                                <x-select name="rh" logro="select2" :hasError="'rh'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($rhs as $rh)
                                                        <option value="{{ $rh->id }}"
                                                            @if ($student->rh_id !== null) @selected(old('rh', $student->rh_id) == $rh->id) @endif>
                                                            {{ $rh->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Basic Information Section End -->

                            <!-- Localization Section Start -->
                            <h2 class="small-title">{{ __('Domicile Place') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('zone') }} {!! $input_required !!}</x-label>
                                                <x-select name="zone" logro="select2" :hasError="'zone'">
                                                    <option label="&nbsp;"></option>
                                                    <option value="rural" @selected(old('zone', 'rural') == $student->zone)>
                                                        {{ __('Rural') }}
                                                    </option>
                                                    <option value="urban" @selected(old('zone', 'urban') == $student->zone)>
                                                        {{ __('Urban') }}
                                                    </option>
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('residence city') }} {!! $input_required !!}</x-label>
                                                <x-select name="residence_city" logro="select2" :hasError="'residence_city'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->residence_city_id !== null) @selected(old('residence_city', $student->residence_city_id) == $city->id) @endif>
                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('address') }} {!! $input_required !!}</x-label>
                                                <x-input-error :value="$student->address" name="address" :hasError="'address'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('social stratum') }} {!! $input_required !!}</x-label>
                                                <x-select name="social_stratum" logro="select2" :hasError="'social_stratum'">
                                                    <option label="&nbsp;"></option>
                                                    @for ($stratum = 1; $stratum <= 6; $stratum++)
                                                        <option value="{{ $stratum }}"
                                                            @if ($student->social_stratum !== null) @selected(old('social_stratum', $student->social_stratum) == $stratum) @endif>
                                                            {{ $stratum }}
                                                        </option>
                                                    @endfor
                                                </x-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('dwelling type') }} {!! $input_required !!}</x-label>
                                                <x-select name="dwelling_type" logro="select2" :hasError="'dwelling_type'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($dwellingTypes as $dwellingType)
                                                        <option value="{{ $dwellingType->id }}"
                                                            @if ($student->dwelling_type_id !== null) @selected(old('dwelling_type', $student->dwelling_type_id) == $dwellingType->id) @endif>
                                                            {{ __($dwellingType->name) }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('neighborhood') }} {!! $input_required !!}</x-label>
                                                <x-input-error :value="$student->neighborhood" name="neighborhood" :hasError="'neighborhood'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label class="d-block">{{ __('housing services') }}</x-label>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="electrical_energy" value="1"
                                                            @checked($student->electrical_energy)>
                                                        {{ __('electrical energy') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox" name="natural_gas"
                                                            value="1" @checked($student->natural_gas)>
                                                        {{ __('natural gas') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox" name="sewage_system"
                                                            value="1" @checked($student->sewage_system)>
                                                        {{ __('sewage system') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox" name="aqueduct"
                                                            value="1" @checked($student->aqueduct)>
                                                        {{ __('aqueduct') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox" name="internet"
                                                            value="1" @checked($student->internet)>
                                                        internet
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <x-label class="d-block">{{ __('who lives with you at home') }}
                                                </x-label>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="lives_with_father" value="1"
                                                            @checked($student->lives_with_father)>
                                                        {{ __('lives with father') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="lives_with_mother" value="1"
                                                            @checked($student->lives_with_mother)>
                                                        {{ __('lives with mother') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="lives_with_siblings" value="1"
                                                            @checked($student->lives_with_siblings)>
                                                        {{ __('lives with siblings') }}
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label logro-label">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="lives_with_other_relatives" value="1"
                                                            @checked($student->lives_with_other_relatives)>
                                                        {{ __('lives with other relatives') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Localization Section End -->

                            <!-- Social Safety Section Start -->
                            <h2 class="small-title">{{ __('Social Safety') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('health manager') }} {!! $input_required !!}</x-label>
                                                <x-select name="health_manager" logro="select2" :hasError="'health_manager'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($healthManager as $health)
                                                        <option value="{{ $health->id }}"
                                                            @if ($student->health_manager_id !== null) @selected(old('health_manager', $student->health_manager_id) == $health->id) @endif>
                                                            {{ $health->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('school insurance') }} {!! $input_required !!}</x-label>
                                                <x-input-error :value="$student->school_insurance" name="school_insurance" :hasError="'school_insurance'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="w-100 position-relative form-group">
                                                <x-label>sisben {!! $input_required !!}</x-label>
                                                <x-select name="sisben" logro="select2" :hasError="'sisben'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($sisbenes as $sisben)
                                                        <option value="{{ $sisben->id }}"
                                                            @if ($student->sisben_id !== null) @selected(old('sisben', $student->sisben_id) == $sisben->id) @endif>
                                                            {{ $sisben->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="w-100 position-relative form-group">
                                                <x-label>{{ __('disability') }} {!! $input_required !!}</x-label>
                                                <x-select name="disability" id="disability" logro="select2"
                                                    :hasError="'disability'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($disabilities as $disability)
                                                        <option value="{{ $disability->id }}"
                                                            @if ($student->disability_id !== null) @selected(old('disability', $student->disability_id) == $disability->id) @endif>
                                                            {{ __($disability->name) }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3 d-none" id="content-disability">
                                        <div class="col-md-12">
                                            <div class="mt-3 position-relative form-group">
                                                <x-label>{{ __('Disability certificate') }}</x-label>
                                                <x-input type="file" class="d-block" name="disability_certificate"
                                                    accept="image/jpg, image/jpeg, image/png, image/webp" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Social Safety Section End -->

                            <!-- Additional Information Section Start -->
                            <h2 class="small-title">{{ __('Additional Information') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('ethnic group') }}</x-label>
                                                <select name="ethnic_group" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($ethnicGroups as $ethnicGroup)
                                                        <option value="{{ $ethnicGroup->id }}"
                                                            @if ($student->ethnic_group_id !== null) @selected(old('ethnic_group', $student->ethnic_group_id) == $ethnicGroup->id) @endif>
                                                            {{ $ethnicGroup->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('reservation') }}</x-label>
                                                <select name="reservation" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($reservations as $reservation)
                                                        <option value="{{ $reservation->id }}"
                                                            @if ($student->reservation_id !== null) @selected(old('reservation', $student->reservation_id) == $reservation->id) @endif>
                                                            {{ $reservation->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('educational institution of origin') }}</x-label>
                                                <x-input :value="$student->origin_school" name="origin_school" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('type of educational institution') }}</x-label>
                                                <select name="type_origin_school" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($student->enumTypeSchoolOrigin() as $typeSchoolOrigin)
                                                        <option value="{{ $typeSchoolOrigin }}"
                                                            @selected(old('type_origin_school', $student->type_origin_school) == $typeSchoolOrigin)>
                                                            {{ __($typeSchoolOrigin) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label class="text-t-none">{{ __('ICBF protection measure') }}</x-label>
                                                <select name="icbf_protection" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($icbfProtections as $icbfProtection)
                                                        <option value="{{ $icbfProtection->id }}"
                                                            @if ($student->ICBF_protection_measure_id !== null) @selected(old('icbf_protection', $student->ICBF_protection_measure_id) == $icbfProtection->id) @endif>
                                                            {{ $icbfProtection->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('conflict victim') }}</x-label>
                                                <select name="type_conflic" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($typesConflict as $typeConflict)
                                                        <option value="{{ $typeConflict->id }}"
                                                            @if ($student->type_conflic_id !== null) @selected(old('type_conflic', $student->type_conflic_id) == $typeConflict->id) @endif>
                                                            {{ $typeConflict->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('foundation beneficiary') }}</x-label>
                                                <select name="foundation_beneficiary" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(old('foundation_beneficiary', 0) == $student->foundation_beneficiary)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(old('foundation_beneficiary', 1) == $student->foundation_beneficiary)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('linked to a process') }}</x-label>
                                                <select name="linked_process" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($linkageProcesses as $linkageProcess)
                                                        <option value="{{ $linkageProcess->id }}"
                                                            @if ($student->linked_to_process_id !== null) @selected(old('linked_process', $student->linked_to_process_id) == $linkageProcess->id) @endif>
                                                            {{ __($linkageProcess->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('religion') }}</x-label>
                                                <select name="religion" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($religions as $religion)
                                                        <option value="{{ $religion->id }}"
                                                            @if ($student->religion_id !== null) @selected(old('religion', $student->religion_id) == $religion->id) @endif>
                                                            {{ $religion->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('economic dependence') }}</x-label>
                                                <select name="economic_dependence" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($economicDependences as $economicDependence)
                                                        <option value="{{ $economicDependence->id }}"
                                                            @if ($student->economic_dependence_id !== null) @selected(old('economic_dependence', $student->economic_dependence_id) == $economicDependence->id) @endif>
                                                            {{ __($economicDependence->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Additional Information Section End -->

                            @hasanyrole('SECRETARY|ORIENTATION')
                                @if (1 !== $student->data_treatment)
                                    <section class="card mb-5">
                                        <div class="card-body">
                                            <b>{{ __('The student did not authorize the permissions of image use.') }}</b>
                                        </div>
                                    </section>
                                @endif

                                <!-- Signatures View Start -->
                                <h2 class="small-title">{{ __('Signatures') }}</h2>
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <x-label>{{ __('signature tutor') }}</x-label>
                                                @if (null === $student->signature_tutor)
                                                    <p><b>{{ __('Unsigned') }}</b></p>
                                                @else
                                                    <div class="text-center mb-3 mb-md-0">
                                                        <img src="{{ env('APP_URL') . '/' . $student->signature_tutor }}"
                                                            class="max-w-100 sh-19 border rounded-md" alt="signature" />
                                                    </div>

                                                    @can('students.delete')
                                                        <div class="mt-2">
                                                            <x-button
                                                                type="button"
                                                                class="btn-sm btn-outline-danger"
                                                                delete-signature="tutor"
                                                                data-img="{{ env('APP_URL') . '/' . $student->signature_tutor }}"
                                                                >{{ __('Delete') }}</x-button>
                                                        </div>
                                                    @endcan

                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <x-label>{{ __('signature student') }}</x-label>
                                                @if (null === $student->signature_student)
                                                    <p><b>{{ __('Unsigned') }}</b></p>
                                                @else
                                                    <div class="text-center mb-3 mb-md-0">
                                                        <img src="{{ env('APP_URL') . '/' . $student->signature_student }}"
                                                            class="max-w-100 sh-19 border rounded-md" alt="signature" />
                                                    </div>

                                                    @can('students.delete')
                                                        <div class="mt-2">
                                                            <x-button
                                                                type="button"
                                                                class="btn-sm btn-outline-danger"
                                                                delete-signature="student"
                                                                data-img="{{ env('APP_URL') . '/' . $student->signature_student }}"
                                                                >{{ __('Delete') }}</x-button>
                                                        </div>
                                                    @endcan
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <!-- Signatures View End -->

                                @hasrole('SECRETARY')
                                <div class="mb-5">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" @checked($student->isRepeat()) type="checkbox"
                                            id="isRepeat" name="isRepeat" value="1" />
                                        <label class="form-check-label"
                                            for="isRepeat">{{ __('Is the student repeating?') }}</label>
                                    </div>
                                </div>
                                @endhasrole
                            @endhasanyrole

                            @hasrole('STUDENT')
                                <!-- Data Treatment Policy Section Start -->
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-12 mb-3">
                                                {{ __('By continuing, you accept') }}
                                                <span class="text-primary cursor-pointer" data-bs-toggle="modal"
                                                    data-bs-target="#modalDataTreatmentPolicy">
                                                    {{ __('data treatment policy') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-check d-inline-block w-100">
                                                    <input class="form-check-input" type="checkbox" name="data_treatment"
                                                        value="1" @checked($student->data_treatment)>
                                                    <label class="form-check-label logro-label">
                                                        {{ __('I authorize the institution the permissions of') }}
                                                        <span class="text-primary cursor-pointer" data-bs-toggle="modal"
                                                            data-bs-target="#modalDataTreatmentImage">
                                                            {{ __('image use') }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        @if ($handbook !== null)
                                            <div class="row g-3">
                                                <div class="col-md-12">
                                                    <div>
                                                        <a class="btn btn-link p-0 mt-3" target="_blank"
                                                            href="{{ $handbook }}">
                                                            <i data-acorn-icon="book" data-acorn-size="16"></i>
                                                            {{ __('Handbook of coexistence') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Modal Data Treatment Policy Start -->
                                        <div class="modal fade scroll-out" id="modalDataTreatmentPolicy" tabindex="-1"
                                            role="dialog" aria-labelledby="modalCloseDataTreatmentPolicy" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable short modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title logro-label" id="modalCloseDataTreatmentPolicy">
                                                            {{ __('data treatment policy') }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="scroll-track-visible">
                                                            <x-data-treatment-policy />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Data Treatment Policy End -->

                                        <!-- Modal Data Treatment Imagen Rights Start -->
                                        <div class="modal fade scroll-out" id="modalDataTreatmentImage" tabindex="-1"
                                            role="dialog" aria-labelledby="modalCloseDataTreatmentImage" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable short modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title logro-label" id="modalCloseDataTreatmentImage">
                                                            {{ __('image use') }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="scroll-track-visible">
                                                            <x-data-treatment-image-rights />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Data Treatment Imagen Rights End -->

                                    </div>
                                </section>
                                <!-- Data Treatment Policy Section End -->

                                <!-- Signatures Start -->
                                <h2 class="small-title">{{ __('Signatures') }}</h2>
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <x-label>{{ __('signature tutor') }}</x-label>
                                                @if (null === $student->signature_tutor)
                                                    <div class="mb-2">
                                                        <button type="button" id="openSigTutor"
                                                            class="btn btn-outline-alternate" data-bs-toggle="modal"
                                                            data-bs-target="#modalSigTutor">
                                                            {{ __('Make signature') }}
                                                        </button>
                                                    </div>
                                                    <input type="hidden" id="sig-dataUrl-tutor" name="signature_tutor"
                                                        class="form-control">
                                                    <div class="text-center border rounded-md mb-3 mb-md-0 d-none">
                                                        <img id="sig-image-tutor" src="" class="max-w-100 sh-19"
                                                            alt="signature" />
                                                    </div>
                                                @else
                                                    <div class="text-center border rounded-md mb-3 mb-md-0">
                                                        <img src="{{ env('APP_URL') . '/' . $student->signature_tutor }}"
                                                            class="max-w-100 sh-19" alt="signature" />
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <x-label>{{ __('signature student') }}</x-label>
                                                @if (null === $student->signature_student)
                                                    <div class="mb-2">
                                                        <button type="button" id="openSigStudent"
                                                            class="btn btn-outline-alternate" data-bs-toggle="modal"
                                                            data-bs-target="#modalSigStudent">
                                                            {{ __('Make signature') }}
                                                        </button>
                                                    </div>
                                                    <input type="hidden" id="sig-dataUrl-student" name="signature_student"
                                                        class="form-control">
                                                    <div class="text-center border rounded-md d-none">
                                                        <img id="sig-image-student" src="" class="max-w-100 sh-19"
                                                            alt="signature" />
                                                    </div>
                                                @else
                                                    <div class="text-center border rounded-md">
                                                        <img src="{{ env('APP_URL') . '/' . $student->signature_student }}"
                                                            class="max-w-100 sh-19" alt="signature" />
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if (null === $student->signature_tutor)
                                            <!-- Signature Tutor modal-->
                                            <div class="modal fade" id="modalSigTutor" tabindex="-1" role="dialog"
                                                aria-labelledby="SigTutorLabel" data-bs-backdrop="static"
                                                data-bs-keyboard="false" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title logro-label" id="SigTutorLabel">
                                                                {{ __('signature tutor') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id="sigLoadTutor" class="text-center">
                                                                <div class="border rounded-md mb-2 d-none">
                                                                    <img src="" id="sig-img-tutor"
                                                                        class="form-signature rounded-0 max-w-100 sh-19 object-scale-down" />
                                                                </div>
                                                                <canvas id="sig-canvas-tutor"
                                                                    class="sig-canvas form-signature mb-1">
                                                                </canvas>
                                                                <button title="{{ __('load signature') }}"
                                                                    class="btn w-100 btn-icon btn-separator-light rounded-xl"
                                                                    type="button">
                                                                    <i data-acorn-icon="upload"></i>
                                                                    <span>{{ __('upload signature') }}</span>
                                                                </button>
                                                                <input name="fileSigLoad-tutor" id="fileSigLoad-tutor"
                                                                    class="file-upload d-none" type="file"
                                                                    accept="image/jpg, image/jpeg, image/png, image/webp" />

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="#"
                                                                class="btn btn-start btn-icon btn-icon-only btn-link">
                                                                <i class="icon bi-question-circle"></i>
                                                            </a>
                                                            <button type="button" id="sig-clearBtn-tutor"
                                                                class="btn btn-outline-danger">{{ __('Clear signature') }}</button>
                                                            <button type="button" id="sig-submitBtn-tutor"
                                                                data-bs-dismiss="modal"
                                                                class="btn btn-primary">{{ __('Confirm signature') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if (null === $student->signature_student)
                                            <!-- Signature Student modal-->
                                            <div class="modal fade" id="modalSigStudent" tabindex="-1" role="dialog"
                                                aria-labelledby="SigStudentLabel" data-bs-backdrop="static"
                                                data-bs-keyboard="false" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title logro-label" id="SigStudentLabel">
                                                                {{ __('signature student') }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div id="sigLoadStudent" class="text-center">
                                                                <div class="border rounded-md mb-2 d-none">
                                                                    <img src="" id="sig-img-student"
                                                                        class="form-signature rounded-0 max-w-100 sh-19 object-scale-down" />
                                                                </div>
                                                                <canvas id="sig-canvas-student"
                                                                    class="sig-canvas form-signature mb-1">
                                                                </canvas>
                                                                <button title="{{ __('load signature') }}"
                                                                    class="btn w-100 btn-icon btn-separator-light rounded-xl"
                                                                    type="button">
                                                                    <i data-acorn-icon="upload"></i>
                                                                    <span>{{ __('upload signature') }}</span>
                                                                </button>
                                                                <input name="fileSigLoad-student" id="fileSigLoad-student"
                                                                    class="file-upload d-none" type="file"
                                                                    accept="image/jpg, image/jpeg, image/png, image/webp" />

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <a href="#"
                                                                class="btn btn-start btn-icon btn-icon-only btn-link">
                                                                <i class="icon bi-question-circle"></i>
                                                            </a>
                                                            <button type="button" id="sig-clearBtn-student"
                                                                class="btn btn-outline-danger">{{ __('Clear signature') }}</button>
                                                            <button type="button" id="sig-submitBtn-student"
                                                                data-bs-dismiss="modal"
                                                                class="btn btn-primary">{{ __('Confirm signature') }}</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </section>
                                <!-- Signatures End -->

                                <!-- Documents Required Start -->
                                <section>
                                    @php $fileFails = 0 @endphp
                                    @foreach ($studentFileTypes as $studentFileRequired)
                                        @if (1 === $studentFileRequired->required && null === $studentFileRequired->studentFile)
                                            @php ++$fileFails @endphp
                                        @endif
                                    @endforeach
                                    <input type="hidden" name="docsFails" value="{{ $fileFails }}">
                                </section>
                                <!-- Documents Required End -->
                            @endhasrole


                            @can('students.delete')
                                @if ($student->groupStudents()->count() === 0)
                                    <div class="border-0 pt-0 d-flex justify-content-between align-items-center">
                                        <x-button class="btn-outline-danger" type="button" data-bs-toggle="modal"
                                            data-bs-target="#deleteStudentModal">{{ __('Delete student') }}</x-button>
                                        <x-button class="btn-primary" type="submit">{{ __('Save information') }}</x-button>
                                    </div>
                                @else
                                    <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                        <x-button class="btn-primary" type="submit">{{ __('Save information') }}</x-button>
                                    </div>
                                @endif
                            @else
                                <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                    <x-button class="btn-primary" type="submit">{{ __('Save information') }}</x-button>
                                </div>
                            @endcan

                        </form>
                    </div>
                    <!-- Information Tab End -->

                    <!-- Persons In Charge Tab Start -->
                    <div class="tab-pane fade @if (session('tab') === 'personsCharge') active show @endif" id="personsChargeTab"
                        role="tabpanel">

                        <form method="POST" action="{{ route('personsCharge', $student) }}" id="studentPersonChargeForm">
                            @csrf
                            @method('PUT')

                            <!-- Tutor Student Section Start -->
                            <div class="w-100 tooltip-start-top position-relative">
                                <h2 class="small-title">{{ __('Tutor') }} <span class="text-danger">*</span></h2>
                                <section class="card mb-5">
                                    <div class="card-body w-100">
                                        <select name="person_charge" logro="select2" id="person_charge" required>
                                            <option label="&nbsp;"></option>
                                            @foreach ($kinships as $kinship)
                                                <option value="{{ $kinship->id }}"
                                                    @if ($student?->myTutorIs !== null) @selected(old('person_charge', $student?->myTutorIs->kinship_id) == $kinship->id) @endif>
                                                    {{ __($kinship->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </section>
                            </div>
                            <!-- Tutor Student Section End -->

                            <!-- Mother Section Start -->
                            <h2 class="small-title">{{ __('Mother Information') }}</h2>
                            <input type="hidden" name="mother" value="{{ $student->mother->id ?? null }}">
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('full name') }}
                                                </x-label>
                                                <x-input-error
                                                    value="{{ old('mother_name', $student->mother->name ?? null) }}"
                                                    name="mother_name" :hasError="'mother_name'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('email') }}
                                                </x-label>
                                                @if (null === $student->mother)
                                                    <x-input-error
                                                        value="{{ old('mother_email', $student->mother->email ?? null) }}"
                                                        name="mother_email" :hasError="'mother_email'" />
                                                @else
                                                    <span class="form-control text-muted">
                                                        {{ $student->mother->email }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('document') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('mother_document', $student->mother->document ?? null) }}"
                                                    name="mother_document" :hasError="'mother_document'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('expedition city') }}</x-label>
                                                <x-select name="mother_expedition_city" logro="select2" :hasError="'mother_expedition_city'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->mother->expedition_city_id ?? null !== null) @selected(old('mother_expedition_city', $student->mother->expedition_city_id) == $city->id) @endif>
                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('residence city') }}</x-label>
                                                <x-select name="mother_residence_city" logro="select2" :hasError="'mother_residence_city'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->mother->residence_city_id ?? null !== null) @selected(old('mother_residence_city', $student->mother->residence_city_id) == $city->id) @endif>
                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('address') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('mother_address', $student->mother->address ?? null) }}"
                                                    name="mother_address" :hasError="'mother_address'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('telephone') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('mother_telephone', $student->mother->telephone ?? null) }}"
                                                    name="mother_telephone" :hasError="'mother_telephone'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('cellphone') }}
                                                </x-label>
                                                <x-input-error
                                                    value="{{ old('mother_cellphone', $student->mother->cellphone ?? null) }}"
                                                    name="mother_cellphone" :hasError="'mother_cellphone'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('birthdate') }}</x-label>
                                                <x-input-error data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd"
                                                    value="{{ old('mother_birthdate', $student->mother->birthdate ?? null) }}"
                                                    logro="datePickerBefore" name="mother_birthdate" :hasError="'mother_birthdate'"
                                                    data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('occupation') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('mother_occupation', $student->mother->occupation ?? null) }}"
                                                    name="mother_occupation" :hasError="'mother_occupation'" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Mother Section End -->

                            <!-- Father Section Start -->
                            <h2 class="small-title">{{ __('Father Information') }}</h2>
                            <input type="hidden" name="father" value="{{ $student->father->id ?? null }}">
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('full name') }}
                                                </x-label>
                                                <x-input-error
                                                    value="{{ old('father_name', $student->father->name ?? null) }}"
                                                    name="father_name" :hasError="'father_name'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('email') }}
                                                </x-label>
                                                @if (null === $student->father)
                                                    <x-input-error
                                                        value="{{ old('father_email', $student->father->email ?? null) }}"
                                                        name="father_email" :hasError="'father_email'" />
                                                @else
                                                    <span class="form-control text-muted">
                                                        {{ $student->father->email }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('document') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('father_document', $student->father->document ?? null) }}"
                                                    name="father_document" :hasError="'father_document'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('expedition city') }}</x-label>
                                                <x-select name="father_expedition_city" logro="select2" :hasError="'father_expedition_city'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->father->expedition_city_id ?? null !== null) @selected(old('father_expedition_city', $student->father->expedition_city_id) == $city->id) @endif>
                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('residence city') }}</x-label>
                                                <x-select name="father_residence_city" logro="select2" :hasError="'father_residence_city'">
                                                    <option label="&nbsp;"></option>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}"
                                                            @if ($student->father->residence_city_id ?? null !== null) @selected(old('father_residence_city', $student->father->residence_city_id) == $city->id) @endif>
                                                            {{ $city->department->name . ' | ' . $city->name }}
                                                        </option>
                                                    @endforeach
                                                </x-select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('address') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('father_address', $student->father->address ?? null) }}"
                                                    name="father_address" :hasError="'father_address'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('telephone') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('father_telephone', $student->father->telephone ?? null) }}"
                                                    name="father_telephone" :hasError="'father_telephone'" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('cellphone') }}
                                                </x-label>
                                                <x-input-error
                                                    value="{{ old('father_cellphone', $student->father->cellphone ?? null) }}"
                                                    name="father_cellphone" :hasError="'father_cellphone'" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('birthdate') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('father_birthdate', $student->father->birthdate ?? null) }}"
                                                    logro="datePickerBefore" name="father_birthdate" :hasError="'father_birthdate'"
                                                    data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 tooltip-label-end position-relative form-group">
                                                <x-label>{{ __('occupation') }}</x-label>
                                                <x-input-error
                                                    value="{{ old('father_occupation', $student->father->occupation ?? null) }}"
                                                    name="father_occupation" :hasError="'father_occupation'" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Father Section End -->

                            <!-- Tutor Section Start -->
                            <div class="@if (null === $student->tutor) d-none @endif" id="section-tutor">
                                <h2 class="small-title">{{ __('Tutor Information') }}</h2>
                                <input type="hidden" name="tutor" value="{{ $student->tutor->id ?? null }}">
                                <section class="card mb-5">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('full name') }}
                                                    </x-label>
                                                    <x-input-error
                                                        value="{{ old('tutor_name', $student->tutor->name ?? null) }}"
                                                        name="tutor_name" :hasError="'tutor_name'" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('email') }}
                                                    </x-label>
                                                    @if (null === $student->tutor)
                                                        <x-input-error
                                                            value="{{ old('tutor_email', $student->tutor->email ?? null) }}"
                                                            name="tutor_email" :hasError="'tutor_email'" />
                                                    @else
                                                        <span class="form-control text-muted">
                                                            {{ $student->tutor->email }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('document') }}</x-label>
                                                    <x-input-error
                                                        value="{{ old('tutor_document', $student->tutor->document ?? null) }}"
                                                        name="tutor_document" :hasError="'tutor_document'" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('expedition city') }}</x-label>
                                                    <x-select name="tutor_expedition_city" logro="select2" :hasError="'tutor_expedition_city'">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->id }}"
                                                                @if ($student->tutor->expedition_city_id ?? null !== null) @selected(old('tutor_expedition_city', $student->tutor->expedition_city_id) == $city->id) @endif>
                                                                {{ $city->department->name . ' | ' . $city->name }}
                                                            </option>
                                                        @endforeach
                                                    </x-select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 w-100 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('residence city') }}</x-label>
                                                    <x-select name="tutor_residence_city" logro="select2" :hasError="'tutor_residence_city'">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($cities as $city)
                                                            <option value="{{ $city->id }}"
                                                                @if ($student->tutor->residence_city_id ?? null !== null) @selected(old('tutor_residence_city', $student->tutor->residence_city_id) == $city->id) @endif>
                                                                {{ $city->department->name . ' | ' . $city->name }}
                                                            </option>
                                                        @endforeach
                                                    </x-select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('address') }}</x-label>
                                                    <x-input-error
                                                        value="{{ old('tutor_address', $student->tutor->address ?? null) }}"
                                                        name="tutor_address" :hasError="'tutor_address'" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('telephone') }}</x-label>
                                                    <x-input-error
                                                        value="{{ old('tutor_telephone', $student->tutor->telephone ?? null) }}"
                                                        name="tutor_telephone" :hasError="'tutor_telephone'" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('cellphone') }}
                                                    </x-label>
                                                    <x-input-error
                                                        value="{{ old('tutor_cellphone', $student->tutor->cellphone ?? null) }}"
                                                        name="tutor_cellphone" :hasError="'tutor_cellphone'" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="mb-3 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('birthdate') }}</x-label>
                                                    <x-input-error
                                                        value="{{ old('tutor_birthdate', $student->tutor->birthdate ?? null) }}"
                                                        logro="datePickerBefore" name="tutor_birthdate" :hasError="'tutor_birthdate'"
                                                        data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3 tooltip-label-end position-relative form-group">
                                                    <x-label>{{ __('occupation') }}</x-label>
                                                    <x-input-error
                                                        value="{{ old('tutor_occupation', $student->tutor->occupation ?? null) }}"
                                                        name="tutor_occupation" :hasError="'tutor_occupation'" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <!-- Tutor Section End -->

                            <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                <x-button class="btn-primary" type="submit">{{ __('Save persons in charge') }}</x-button>
                            </div>

                        </form>

                    </div>
                    <!-- Persons In Charge Tab End -->

                @can('students.documents.edit')
                    <!-- Documents Tab Start -->
                    <div class="tab-pane fade @if (session('tab') === 'documents') active show @endif" id="documentsTab"
                        role="tabpanel">
                        <h2 class="small-title">{{ __('Documents') }}</h2>
                        <section class="card mb-5">
                            @can('students.documents.edit')
                                <div class="card-header">
                                    <form method="POST" action="{{ route('students.file', $student) }}"
                                        enctype="multipart/form-data" class="tooltip-label-end" novalidate>
                                        @csrf
                                        @method('PUT')

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="w-100 position-relative form-group">
                                                    <select data-placeholder="Seleccione documento" name="file_type"
                                                        logro="select2" id="selectStudentDocument" data-bs-toggle="modal"
                                                        data-bs-target="#modalStudentDocumentsInfo">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($studentFileTypes as $fileType)
                                                            @if ($fileType->studentFile === null)
                                                                <option value="{{ $fileType->id }}"
                                                                    fileInfo="{{ $fileType->description }}"
                                                                    @selected(old('file_type') == $fileType->id)>
                                                                    {{ $fileType->name }}
                                                                    @if (1 === $fileType->required)
                                                                        *
                                                                    @endif
                                                                </option>
                                                            @else
                                                                @if ($fileType->studentFile->checked !== 1)
                                                                    <option value="{{ $fileType->id }}"
                                                                        fileInfo="{{ $fileType->description }}"
                                                                        @selected(old('file_type') == $fileType->id)>
                                                                        {{ $fileType->name }}
                                                                        @if (1 === $fileType->required)
                                                                            *
                                                                        @endif
                                                                    </option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group">
                                                    <x-input type="file" name="file_upload"
                                                        accept="image/jpg, image/jpeg, image/png, image/webp" class="d-block" />
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-2 col-md-2 border-0 pt-0 d-flex justify-content-end align-items-start">
                                                <x-button class="btn-primary" type="submit">{{ __('Upload') }}
                                                </x-button>
                                            </div>
                                        </div>
                                        <div class="row mt-3 g-3 text-danger d-none" id="infoStudentDocument"></div>

                                    </form>
                                </div>
                            @endcan

                            <div class="card-body">

                                @can('students.documents.checked')
                                    <div class="text-center">
                                        <div class="alert alert-warning" role="alert">
                                            {{ __('Unapproved documents will be deleted') }}</div>
                                    </div>

                                    <form method="POST" action="{{ route('students.file.checked', $student) }}"
                                        class="tooltip-label-end" novalidate>
                                        @csrf
                                        @method('PUT')
                                @endcan

                                    <div class="row g-2 row-cols-3 row-cols-md-5">
                                        @foreach ($studentFileTypes as $studentFile)
                                            <div class="col small-gutter-col">
                                                <div class="h-100">
                                                    <div class="text-center d-flex flex-column">
                                                        <div>
                                                            @if ($studentFile->studentFile ?? null !== null)
                                                                <i class="icon icon-70 cursor-pointer
                                                                    @if ($studentFile->studentFile->checked == 1) bi-file-earmark-check-fill text-muted
                                                                    @else bi-file-earmark-fill text-info @endif"
                                                                    logro="studentDocument"
                                                                    data-image="{{ $studentFile->studentFile->url }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalStudentDocuments"></i>
                                                            @else
                                                                <i class="icon bi-file-earmark icon-70 text-muted"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            {{ $studentFile->name }}
                                                            @if (1 === $studentFile->required)
                                                                <x-required />
                                                            @endif
                                                        </div>

                                                        @can('students.documents.checked')
                                                            @if ($studentFile->studentFile ?? null !== null)
                                                                @if ($studentFile->studentFile->checked !== 1)
                                                                    <div class="form-switch">
                                                                        <input class="form-check-input" name="student_files[]"
                                                                            value="{{ $studentFile->studentFile->id }}"
                                                                            type="checkbox" />
                                                                    </div>
                                                                @else
                                                                    <!-- Eliminar Documento -->
                                                                    <div class="text-center cursor-pointer openModalFileDelete"
                                                                        title="{{ __('Delete document') }}"
                                                                        data-file-id="{{ $studentFile->studentFile->id }}"
                                                                        data-file-name="{{ $studentFile->name }}">
                                                                        <i data-acorn-icon="bin" data-acorn-size="14"
                                                                            class="text-danger"></i>
                                                                    </div>

                                                                @endif
                                                            @endif
                                                        @endcan

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                @can('students.documents.checked')
                                        <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                            <x-button class="btn-primary" type="submit">{{ __('Save checked documents') }}
                                            </x-button>
                                        </div>
                                    </form>
                                @endcan
                            </div>
                        </section>
                    </div>
                    <!-- Documents Tab End -->

                    <!-- Report Books Tab Start -->
                    <div class="tab-pane fade @if (session('tab') === 'reportBook') active show @endif" id="reportBookTab"
                        role="tabpanel">
                        <h2 class="small-title">{{ __('Report books') }}</h2>
                        <section class="card mb-5">
                            @can('students.documents.edit')
                                <div class="card-header">
                                    <form method="POST" action="{{ route('students.reportBook', $student) }}"
                                        enctype="multipart/form-data" class="tooltip-label-end" novalidate>
                                        @csrf
                                        @method('PUT')

                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="w-100 position-relative form-group">
                                                    <select data-placeholder="Boletín a subir" name="reportbook" logro="select2">
                                                        <option label="&nbsp;"></option>
                                                        @foreach ($resourceStudyYears as $resourceSY)
                                                            @if ($resourceSY->studentReportBook === null)
                                                                <option value="{{ $resourceSY->id }}">
                                                                    {{ __($resourceSY->name) }}</option>
                                                            @else
                                                                @if ($resourceSY->studentReportBook->checked != 1)
                                                                    <option value="{{ $resourceSY->id }}">
                                                                        {{ __($resourceSY->name) }}</option>
                                                                @endif
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="position-relative form-group">
                                                    <x-input type="file" name="file_reportbook"
                                                        accept="image/jpg, image/jpeg, image/png, image/webp" class="d-block" />
                                                </div>
                                            </div>
                                            <div
                                                class="col-md-2 col-md-2 border-0 pt-0 d-flex justify-content-end align-items-start">
                                                <x-button class="btn-primary" type="submit">{{ __('Upload') }}
                                                </x-button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            @endcan

                            <div class="card-body">

                                @hasanyrole('STUDENT|SUPPORT')
                                <div class="text-center">
                                    <div class="alert alert-info" role="alert">
                                        <b>Estos boletines NO son obligatorios.</b>
                                        <br />
                                        Si estás en primaria, recomendamos subir boletines de años anteriores.
                                        <br />
                                        Para grado sexto o superior, recomendamos subir el boletín de Quinto grado.
                                    </div>
                                </div>
                                @endhasanyrole

                                @can('students.documents.checked')
                                    <div class="text-center">
                                        <div class="alert alert-warning" role="alert">
                                            {{ __('Unapproved report books will be deleted') }}</div>
                                    </div>

                                    <form method="POST" action="{{ route('students.reportBooks.checked', $student) }}"
                                        class="tooltip-label-end" novalidate>
                                        @csrf
                                        @method('PUT')
                                    @endcan

                                    <div class="row g-2 row-cols-3 row-cols-md-5">
                                        @foreach ($resourceStudyYears as $resourceSYview)
                                            <div class="col small-gutter-col">
                                                <div class="h-100">
                                                    <div class="text-center d-flex flex-column">
                                                        <div>
                                                            @if ($resourceSYview->studentReportBook ?? null !== null)
                                                                <i class="icon icon-70 cursor-pointer
                                                                        @if ($resourceSYview->studentReportBook->checked == 1) bi-file-earmark-check-fill text-muted
                                                                        @else bi-file-earmark-fill text-info @endif"
                                                                    logro="studentDocument"
                                                                    data-image="{{ $resourceSYview->studentReportBook->url ?? null }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modalStudentDocuments"></i>
                                                            @else
                                                                <i class="icon bi-file-earmark icon-70 text-muted"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            {{ __($resourceSYview->name) }}
                                                        </div>
                                                        @can('students.documents.checked')
                                                            @if ($resourceSYview->studentReportBook ?? null !== null)
                                                                @if ($resourceSYview->studentReportBook->checked != 1)
                                                                    <div class="form-switch">
                                                                        <input class="form-check-input"
                                                                            name="reportbooks_checked[]"
                                                                            value="{{ $resourceSYview->studentReportBook->id }}"
                                                                            type="checkbox" />
                                                                    </div>
                                                                @else
                                                                    <!-- Eliminar Boleín -->
                                                                    <div class="text-center cursor-pointer openModalReportBookDelete"
                                                                        title="{{ __('Delete report book') }}"
                                                                        data-file-id="{{ $resourceSYview->studentReportBook->id }}"
                                                                        data-file-name="{{ __($resourceSYview->name) }}">
                                                                        <i data-acorn-icon="bin" data-acorn-size="14"
                                                                            class="text-danger"></i>
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endcan
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    @can('students.documents.checked')
                                        <div class="mt-3 d-flex justify-content-end">
                                            <x-button class="btn-primary" type="submit">{{ __('Save approved report books') }}
                                            </x-button>
                                        </div>
                                    </form>
                                @endcan

                            </div>

                        </section>
                    </div>
                    <!-- Report Books Tab End -->
                @endcan

                <!-- Observer Tab Start -->
                <div class="tab-pane fade @if (session('tab') === 'observer') active show @endif" id="observerTab"
                    role="tabpanel">

                    <!-- Observer Section Start -->
                    <h2 class="small-title">{{ __('Observer') }}</h2>
                    <section class="card mb-5">
                        <div class="card-body">
                            @include('logro.student.observer.content-tab')
                        </div>
                    </section>
                    <!-- Observer Section End -->

                </div>
                <!-- Observer Tab End -->

                @can('students.psychosocial')
                    <!-- Psychosocial Information Tab Start -->
                    <div class="tab-pane fade @if (session('tab') === 'psychosocial') active show @endif" id="psychosocialTab"
                        role="tabpanel">
                        <form method="POST" action="{{ route('students.psychosocial.update', $student) }}"
                            class="tooltip-label-end">
                            @csrf
                            @method('PUT')

                            <!-- Psychosocial Information Section Start -->
                            <h2 class="small-title">{{ __('Psychosocial Information') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('plays sports') }}</x-label>
                                                <select name="plays_sports" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(old('plays_sports', 0) == $student->plays_sports)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(old('plays_sports', 1) == $student->plays_sports)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('freetime activity') }}</x-label>
                                                <x-input :value="$student->freetime_activity" name="freetime_activity" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('allergies that you suffer from') }}</x-label>
                                                <x-input :value="$student->allergies" name="allergies" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('medications you take') }}</x-label>
                                                <x-input :value="$student->medicines" name="medicines" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('favourite subjects?') }}</x-label>
                                                <x-input :value="$student->favorite_subjects" name="favorite_subjects" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('which subjects do you find most difficult?') }}
                                                </x-label>
                                                <x-input :value="$student->most_difficult_subjects" name="most_difficult_subjects" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="position-relative form-group">
                                            <x-label class="d-block">{{ __('exogenous Factors') }}</x-label>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="insomnia"
                                                        value="1" @checked($student->insomnia)>
                                                    {{ __('insomnia') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="colic"
                                                        value="1" @checked($student->colic)>
                                                    {{ __('colic') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="biting_nails"
                                                        value="1" @checked($student->biting_nails)>
                                                    {{ __('biting nails') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="sleep_talk"
                                                        value="1" @checked($student->sleep_talk)>
                                                    {{ __('sleep talk') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="nightmares"
                                                        value="1" @checked($student->nightmares)>
                                                    {{ __('nightmares') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="seizures"
                                                        value="1" @checked($student->seizures)>
                                                    {{ __('seizures') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="physical_abuse"
                                                        value="1" @checked($student->physical_abuse)>
                                                    {{ __('physical abuse') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="pee_at_night"
                                                        value="1" @checked($student->pee_at_night)>
                                                    {{ __('pee at night') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="hear_voices"
                                                        value="1" @checked($student->hear_voices)>
                                                    {{ __('hear voices') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="fever"
                                                        value="1" @checked($student->fever)>
                                                    {{ __('fever') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="fears_phobias"
                                                        value="1" @checked($student->fears_phobias)>
                                                    {{ __('fears or phobias') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="drug_consumption" value="1"
                                                        @checked($student->drug_consumption)>
                                                    {{ __('drug consumption') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="head_blows"
                                                        value="1" @checked($student->head_blows)>
                                                    {{ __('head blows') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="desire_to_die"
                                                        value="1" @checked($student->desire_to_die)>
                                                    {{ __('desire to die') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="see_shadows"
                                                        value="1" @checked($student->see_shadows)>
                                                    {{ __('see shadows') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="learning_problems" value="1"
                                                        @checked($student->learning_problems)>
                                                    {{ __('learning problems') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="dizziness_fainting" value="1"
                                                        @checked($student->dizziness_fainting)>
                                                    {{ __('dizziness or fainting') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="school_repetition" value="1"
                                                        @checked($student->school_repetition)>
                                                    {{ __('school repetition') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="accidents"
                                                        value="1" @checked($student->accidents)>
                                                    {{ __('accidents') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="asthma"
                                                        value="1" @checked($student->asthma)>
                                                    {{ __('asthma') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="suicide_attempts" value="1"
                                                        @checked($student->suicide_attempts)>
                                                    {{ __('suicide attempts') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="constipation"
                                                        value="1" @checked($student->constipation)>
                                                    {{ __('constipation') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="stammering"
                                                        value="1" @checked($student->stammering)>
                                                    {{ __('stammering') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="hands_sweating"
                                                        value="1" @checked($student->hands_sweating)>
                                                    {{ __('hands sweating') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="sleepwalking"
                                                        value="1" @checked($student->sleepwalking)>
                                                    {{ __('sleepwalking') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="nervous_tics"
                                                        value="1" @checked($student->nervous_tics)>
                                                    {{ __('nervous tics') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="sexual_abuse"
                                                        value="1" @checked($student->sexual_abuse)>
                                                    {{ __('sexual abuse') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="unmotivated_crying" value="1"
                                                        @checked($student->unmotivated_crying)>
                                                    {{ __('unmotivated crying') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-50">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="chest_pain"
                                                        value="1" @checked($student->chest_pain)>
                                                    {{ __('chest pain') }}
                                                </label>
                                            </div>
                                            <div class="form-check d-inline-block w-40">
                                                <label class="form-check-label logro-label">
                                                    <input class="form-check-input" type="checkbox" name="bullying"
                                                        value="1" @checked($student->bullying)>
                                                    {{ __('bullying') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!-- Psychosocial Information Section End -->

                            <!--  Psychosocial Assessment Section Start -->
                            <h2 class="small-title">{{ __('Psychosocial Evaluation') }}</h2>
                            <section class="card mb-5">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label>{{ __('student inclusive') }}</x-label>
                                                <select name="inclusive" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(old('inclusive', 0) == $student->inclusive)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(old('inclusive', 1) == $student->inclusive)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3 w-100 position-relative form-group">
                                                <x-label class="text-uppercase">SIMAT</x-label>
                                                <select name="simat" logro="select2">
                                                    <option label="&nbsp;"></option>
                                                    <option value="0" @selected(old('simat', 0) == $student->simat)>
                                                        {{ __('No') }}
                                                    </option>
                                                    <option value="1" @selected(old('simat', 1) == $student->simat)>
                                                        {{ __('Yes') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('medical diagnosis') }}</x-label>
                                                <textarea name="medical_diagnosis" rows="5" class="form-control">{{ $student->medical_diagnosis }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('medical prediagnosis') }}</x-label>
                                                <textarea name="medical_prediagnosis" rows="5" class="form-control">{{ $student->medical_prediagnosis }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="mb-3 position-relative form-group">
                                                <x-label>{{ __('risks or vulnerabilities') }}</x-label>
                                                <textarea name="risks_vulnerabilities" rows="5" class="form-control">{{ $student->risks_vulnerabilities }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <!--  Psychosocial Assessment Section End -->

                            <!--  Button Save Psychosocial Start -->
                            <div class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                <x-button class="btn-primary" type="submit">{{ __('Save psychosocial information') }}
                                </x-button>
                            </div>
                            <!--  Button Save Psychosocial End -->

                        </form>
                    </div>
                    <!-- Psychosocial Information Tab End -->

                    <!-- Tracking Tab Start -->
                    <div class="tab-pane fade @if (session('tab') === 'tracking') active show @endif" id="trackingTab"
                        role="tabpanel">
                        <div class="card mt-5">
                            <div class="card-header">
                                <!-- Top Tracking Tab Start -->
                                <div class="row">
                                    <div class="col-12 col-md-7">
                                        <h1 class="mb-1 pb-0 display-6">{{ __('Tracking') }}</h1>
                                    </div>
                                    <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">

                                        @hasanyrole('ORIENTATION|SUPPORT')
                                            @if ($student->enrolled === 1)
                                                <!-- Dropdown Button Start -->
                                                <div class="">
                                                    <button type="button" class="btn btn-outline-info btn-icon btn-icon-only"
                                                        data-bs-offset="0,3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false" data-submenu>
                                                        <i data-acorn-icon="more-horizontal"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <x-dropdown-item type="button" data-bs-toggle="modal"
                                                            data-bs-target="#addAdviceModal">
                                                            <span>{{ __('Add advice') }}</span>
                                                        </x-dropdown-item>
                                                        <x-dropdown-item type="button" data-bs-toggle="modal"
                                                            data-bs-target="#addRemitModal">
                                                            <span>{{ __('Remit') }}</span>
                                                        </x-dropdown-item>
                                                        <x-dropdown-item type="button" data-bs-toggle="modal"
                                                            data-bs-target="#addTeacherModal">
                                                            <span>{{ __('Add teacher referral') }}</span>
                                                        </x-dropdown-item>
                                                        <x-dropdown-item type="button" data-bs-toggle="modal"
                                                            data-bs-target="#addCoordinationModal">
                                                            <span>{{ __('Add coordination referral') }}</span>
                                                        </x-dropdown-item>
                                                        <x-dropdown-item type="button" data-bs-toggle="modal"
                                                            data-bs-target="#addFamilyModal">
                                                            <span>{{ __('Add referral to family') }}</span>
                                                        </x-dropdown-item>
                                                    </div>
                                                </div>
                                                <!-- Dropdown Button End -->
                                            @endif
                                        @endhasanyrole

                                    </div>
                                </div>
                                <!-- Top Advice Tab Start -->
                            </div>
                            <div class="card-body">
                                <!-- Table Start -->
                                <div class="">
                                    <table logro="dataTableBoxed"
                                        class="data-table w-100 responsive nowrap stripe dataTable no-footer dtr-inline"
                                        data-order='[[ 1, "desc" ]]'>
                                        <thead>
                                            <tr>
                                                <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                    {{ __('type') }}</th>
                                                <th class="text-muted text-small text-uppercase p-0 pb-2">
                                                    {{ __('date') }}</th>
                                                <th class="empty">&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($student->tracking as $studentTracking)
                                                <tr>
                                                    <td>
                                                        @if ($studentTracking->type_tracking === 'advice')
                                                            <div class="logro-label">
                                                                {{ __('advice') .' '. __($studentTracking->type_advice) }}
                                                                <div class="d-inline-block ms-2">
                                                                    <a href="{{ route('student.tracking.download', $studentTracking) }}">
                                                                        <i data-acorn-icon="download" data-acorn-size="16"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @elseif ($studentTracking->type_tracking === 'remit')
                                                            <div class="d-inline-block">{{ __('Referral to') }}:
                                                                {{ $studentTracking->entity_remit }}
                                                                <div class="d-inline-block ms-2">
                                                                    <a href="{{ route('student.tracking.download', $studentTracking) }}">
                                                                        <i data-acorn-icon="download" data-acorn-size="16"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @elseif ($studentTracking->type_tracking === 'family')
                                                            <div class="logro-label">
                                                                {{ __('recommendation to the family') }}</div>
                                                        @elseif ($studentTracking->type_tracking === 'teachers')
                                                            <div class="logro-label">{{ __('Recommendation for teachers') }}
                                                            </div>
                                                        @elseif ($studentTracking->type_tracking === 'coordination')
                                                            <div class="logro-label">
                                                                {{ __('Recommendation to coordination') }}</div>
                                                        @endif
                                                    </td>
                                                    <td class="text-small">
                                                        @if ($studentTracking->type_tracking === 'advice')
                                                            {{ $studentTracking->dateFull() }}
                                                        @else
                                                            {{ $studentTracking->created_at }}
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        @if ($studentTracking->type_tracking === 'advice' && $studentTracking->evolution === null)
                                                            <a class="btn btn-sm btn-link"
                                                                href="{{ route('students.tracking.evolution', [$student, $studentTracking]) }}">
                                                                {{ __('Evolve') }}
                                                            </a>
                                                        @else
                                                            <div class="btn btn-sm btn-link" tracking="view"
                                                                tracking-id="{{ $studentTracking->id }}">
                                                                {{ __('See') }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Table End -->
                            </div>
                        </div>
                    </div>
                    <!-- Tracking Tab End -->

                    <!-- PIAR Tab Start -->
                    {{-- @if (1 === $student->inclusive)
                        <div class="tab-pane fade " id="piarTab" role="tabpanel">
                            <section class="scroll-section">
                                <h2 class="small-title">PIAR</h2>
                                <div class="mb-n2" id="accordionCardsSubjects">
                                    @foreach ($groupsStudent as $groupS)
                                        <div class="card d-flex mb-2 card-color-background">
                                            <div class="d-flex flex-grow-1" role="button" data-bs-toggle="collapse"
                                                data-bs-target="#year-{{ $groupS->schoolYear->name }}"
                                                aria-expanded="true" aria-controls="year-{{ $groupS->schoolYear->name }}">
                                                <div class="card-body py-3 border-bottom">
                                                    <div class="btn btn-link list-item-heading p-0">
                                                        {{ $groupS->schoolYear->name }} -
                                                        {{ '(' . $groupS->studyYear->name . ' - ' . $groupS->name . ')' }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="year-{{ $groupS->schoolYear->name }}"
                                                class="collapse @if ($loop->first) show @endif"
                                                data-bs-parent="#accordionCardsSubjects">
                                                <div class="card mt-3 accordion-content">
                                                    <div class="card-body pb-3">

                                                        @if ($YAvailable === $groupS->school_year_id)
                                                            <form
                                                                method="POST"action="{{ route('students.piar', $student) }}"
                                                                novalidate>
                                                                @csrf
                                                                @method('PUT')
                                                        @endif
                                                        @php $groupSubjects = '' @endphp

                                                        @foreach ($groupS->studyYear->academicWorkload as $academicWorkload)
                                                            @if ($groupS->school_year_id === $academicWorkload->school_year_id)
                                                                <div class="mb-3">
                                                                    <h2 class="small-title">
                                                                        {{ $academicWorkload->subject->resourceSubject->public_name }}
                                                                        -

                                                                        @foreach ($academicWorkload->subject->teacherSubjectGroups as $groupTSG)
                                                                            @if ($groupS->id === $groupTSG->group_id && $groupS->school_year_id === $groupTSG->school_year_id)
                                                                                {{ '(' . $groupTSG->teacher->getFullName() . ')' }}
                                                                            @endif
                                                                        @endforeach

                                                                    </h2>
                                                                    <div class="w-100 position-relative form-group">
                                                                        @if ($YAvailable === $academicWorkload->subject->school_year_id)
                                                                            <textarea
                                                                                name="{{ $academicWorkload->subject->piarOne->id ?? 'null' }}~{{ $academicWorkload->subject->id }}~annotation"
                                                                                class="form-control" cols="2">{{ $academicWorkload->subject->piarOne->annotation ?? null }}</textarea>
                                                                        @else
                                                                            <span
                                                                                class="form-control">{{ $academicWorkload->subject->piarOne->annotation ?? null }}</span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @php
                                                                    $groupSubjects .= $academicWorkload->subject->id . '~';
                                                                @endphp
                                                            @endif
                                                        @endforeach

                                                        @if ($YAvailable === $groupS->school_year_id)
                                                            <input type="hidden" name="groupSubjects"
                                                                value="{{ $groupSubjects }}">
                                                            <div
                                                                class="border-0 pt-0 d-flex justify-content-end align-items-center">
                                                                <x-button class="btn-primary" type="submit">
                                                                    {{ __('Save') }} PIAR</x-button>
                                                            </div>
                                                            </form>
                                                        @endif


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </section>
                        </div>
                    @endif --}}
                    <!-- PIAR Tab End -->
                @endcan

                @hasanyrole('SUPPORT|SECRETARY|ORIENTATION')
                <!-- Grades Tab Start -->
                <div class="tab-pane fade @if (session('tab') === 'grades') active show @endif" id="gradesTab"
                    role="tabpanel">

                    <h2 class="small-title">{{ __('Grades') }}</h2>
                    <div class="card">
                        @if ($areasWithGrades && $student->isRetired())
                        <div class="card-header text-end">
                            <a class="btn btn-sm btn-icon btn-background-alternate" href="{{ route('students.pdf.report_grades', $student) }}">
                                <i data-acorn-icon="download" class="me-1"></i>
                                Descargar reporte de calificaciones
                            </a>
                        </div>
                        @endif
                        <div class="card-body">
                            @if ($areasWithGrades)
                                @include('logro.student.grades.report-tab')
                            @else
                                <h6 class="text-center">El estudiante no cuenta con calificaciones para este año</h6>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Grades Tab End -->
                @endhasanyrole

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

    <!-- Modal Student Document Info -->
    <div class="modal fade" id="modalStudentDocumentsInfo" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title h5 text-danger"></h5>
                    <button type="button" class="btn btn-outline-primary ms-2" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    @can('students.documents.checked')
        <!-- Modal Delete Student File -->
        <div class="modal fade"
            id="modalStudentFileDelete"
            aria-labelledby="modalDeleteStudentFile"
            data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="modalDeleteStudentFile">
                            {{ __('Delete document') }}</h5>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form
                        action="{{ route('students.file.delete', $student) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" value="" id="studentFileDeleteInput" name="studentFileDeleteInput">

                        <div class="modal-body">
                            Confirma la eliminación del documento <span id="studentFileDeleteName"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="btn btn-outline-primary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit"
                                class="btn btn-danger">
                                {{ __('Confirm deletion') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Delete Student Report-book -->
        <div class="modal fade"
            id="modalStudentReportBookDelete"
            aria-labelledby="modalDeleteStudentReportBook"
            data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="modalDeleteStudentReportBook">
                            {{ __('Delete report book') }}</h5>
                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form
                        action="{{ route('students.reportBook.delete', $student) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')

                        <input type="hidden" value="" id="studentReportBookDeleteInput" name="studentReportBookDeleteInput">

                        <div class="modal-body">
                            Confirma la eliminación del boletín <span id="studentReportBookDeleteName"></span>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                class="btn btn-outline-primary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit"
                                class="btn btn-danger">
                                {{ __('Confirm deletion') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @unlessrole('STUDENT')
        @can('students.delete')
            <!-- Modal Delete Student -->
            <div class="modal fade" id="deleteStudentModal" aria-labelledby="modalDeleteStudent" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDeleteStudent">{{ __('Delete student') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('students.delete', $student) }}" id="studentDeleteForm" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-body">
                                @if ($SCHOOL->securityEmail() === null)
                                    <div class="alert alert-info mb-0" role="alert">
                                        <h4 class="alert-heading">{{ __('No security email exists') }}.</h4>
                                        <hr>
                                        <div>
                                            {{ __('You must set up a security email to be able to delete students.') }}
                                        </div>
                                    </div>
                                @else
                                    <p>
                                        ¿Está seguro de eliminar al estudiante? Tenga en cuenta que el estudiante se eliminará
                                        permanentemente.
                                    </p>
                                    <p>
                                    <div class="alert alert-warning">
                                        <div class="mb-3">
                                            <i data-acorn-icon="warning-circle"></i>
                                            Por seguridad, es necesario generar un código de confirmación que le será enviado al
                                            correo
                                            electónico de seguridad.
                                        </div>
                                        <div class="text-center">
                                            <x-button class="btn-warning" id="btn-sendCodeConfirmation" type="button">
                                                {{ __('Generate confirmation code') }}
                                            </x-button>
                                        </div>
                                    </div>
                                    </p>
                                    <p>
                                    <div class="row mb-3">
                                        <span class="col-sm-3"></span>

                                    </div>
                                    <div class="row">
                                        <label for="inputSecurityCode" class="col-sm-3 col-form-label">
                                            {{ __('Code') }}
                                            <x-required />
                                        </label>
                                        <div class="col-sm-9 position-relative">
                                            <x-input name="code_confirm" id="inputSecurityCode" :hasError="true" />
                                        </div>
                                    </div>
                                    </p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" id="btn-confirmDelete" class="btn btn-danger"
                                    disabled>{{ __('Confirm deletion') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Withdraw Student -->
            <div class="modal fade" id="withdrawStudentModal" aria-labelledby="modalWithdrawStudent" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalWithdrawStudent">{{ __('Withdraw student') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('student.withdraw', $student) }}" id="studentWithdrawForm" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="modal-body">
                                <p>
                                    {{ __('Are you sure to withdraw the Student :STUDENT?', ['STUDENT' => $student->getCompleteNames()]) }}
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-danger"
                                    >{{ __('Confirm') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Delete Signature -->
            <div class="modal fade" id="deleteSignatureModal" aria-labelledby="modalDeleteSignature" data-bs-backdrop="static"
                data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDeleteSignature">{{ __('Delete signature') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('students.signature.delete', $student) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" id="sigDeleteInput" name="delete_signature" value="">

                            <div class="modal-body">
                                <div class="text-center mb-3 mb-md-0">
                                    <img src="" id="sigDeleteImg"
                                        class="max-w-100 sh-19 border rounded-md" alt="signature" />
                                </div>
                                <div class="mt-2 text-center">
                                    {{ __('Are you sure to remove the signature?') }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-primary"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-danger"
                                    >{{ __('Delete') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endcan
    @endunlessrole

    @can('students.psychosocial')
        @hasanyrole('ORIENTATION|SUPPORT')
            @if ($student->enrolled === 1)
                <!-- Modal Add Advice -->
                <div class="modal fade modal-close-out" id="addAdviceModal" aria-labelledby="modalAddAdvice" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAddAdvice">{{ __('Add advice') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            @include('logro.student.tracking.advice')
                        </div>
                    </div>
                </div>

                <!-- Modal Add Remit -->
                <div class="modal fade modal-close-out" id="addRemitModal" aria-labelledby="modalAddRemit" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAddRemit">{{ __('Remit') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            @include('logro.student.tracking.remit')
                        </div>
                    </div>
                </div>

                <!-- Modal Add Teachers -->
                <div class="modal fade modal-close-out" id="addTeacherModal" aria-labelledby="modalAddTeacher" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAddTeacher">{{ __('Recommendation for teachers') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            @include('logro.student.tracking.teacher')
                        </div>
                    </div>
                </div>

                <!-- Modal Add Coordination -->
                <div class="modal fade modal-close-out" id="addCoordinationModal" aria-labelledby="modalAddCoordination"
                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAddCoordination">{{ __('Recommendation to coordination') }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            @include('logro.student.tracking.coordination')
                        </div>
                    </div>
                </div>

                <!-- Modal Add Family -->
                <div class="modal fade modal-close-out" id="addFamilyModal" aria-labelledby="modalAddFamily" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalAddFamily">{{ __('recommendation to the family') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            @include('logro.student.tracking.family')
                        </div>
                    </div>
                </div>
            @endif
        @endhasanyrole

        <!-- Modal View Info Tracking -->
        <div class="modal fade modal-close-out" id="viewTracking" aria-labelledby="modalViewTracking" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title logro-label" id="modalViewTracking"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modalContentViewTracking"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary"
                            data-bs-dismiss="modal">{{ __('Close') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    @hasanyrole('SUPPORT|SECRETARY')
    <!-- Modal Restore Password -->
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
                        {!! __("Are you sure to reset <b>:USER's</b> password?", ['USER' => $student->getCompleteNames()]) !!}
                    </p>
                    <div class="btn btn-outline-primary"
                        modal="restorePassword"
                        data-role="STUDENT"
                        data-id="{{ $student->id }}">{{ __('Restore') }}</div>
                </div>
            </div>
        </div>
    </div>
    @endhasanyrole

@endsection
