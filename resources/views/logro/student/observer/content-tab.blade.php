<!-- Controls Start -->
<div class="row mb-3">
    <!-- Search Start -->
    <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 mb-1">
        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
            <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
                data-datatable="#datatable_student_observer" @if (!$student->observer) disabled @endif />
            <span class="search-magnifier-icon">
                <i data-acorn-icon="search"></i>
            </span>
            <span class="search-delete-icon d-none">
                <i data-acorn-icon="close"></i>
            </span>
        </div>
    </div>
    <!-- Search End -->

    {{-- @can('students.observer.create') --}}
    <!-- Top Buttons Start -->
    <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-start justify-content-end">

        <!-- Add New Button Start -->
        <a href="#" class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto"
            data-bs-toggle="modal" data-bs-target="#addObservation">
            <i data-acorn-icon="plus"></i>
            <span>{{ __('Add New') }}</span>
        </a>
        <!-- Add New Button End -->

    </div>
    <!-- Top Buttons End -->
    {{-- @endcan --}}

</div>
<!-- Controls End -->

@if ($student->observer)

    <!-- Table Start -->
    <div class="">
        <table id="datatable_student_observer" logro="dataTableBoxed"
        data-order='[]'>
        <thead>
            <tr>
                <th class="empty d-none">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($student->observer as $observation)
                <tr>
                    <td>
                        <div class="col-12 d-flex align-items-end">
                            <div
                                class="w-100 bg-separator-light d-inline-block rounded-md pt-3 pb-4 px-3 pe-7 position-relative text-alternate">
                                <div class="text">
                                    <span class="text-uppercase font-weight-bold">
                                        {{ $observation->annotation_type->getLabelText() }}:
                                    </span>
                                    {{ $observation->situation_description }}
                                </div>

                                @if (!is_null($observation->free_version))
                                    <div class="text mt-1">
                                        <span class="text-uppercase font-weight-bold">
                                            {{ __('free version and/or disclaimers') }}:
                                        </span>
                                        {{ $observation->free_version }}
                                    </div>
                                @endif

                                @if (!is_null($observation->agreements))
                                    <div class="text mt-1">
                                        <span class="text-uppercase font-weight-bold">
                                            {{ __('agreements or commitments') }}:
                                        </span>
                                        {{ $observation->agreements }}
                                    </div>
                                @endif

                                <span class="position-absolute text-extra-small text-alternate opacity-75 b-2 e-2">
                                    {{-- {{ $alert->createdRol->getFullName() }} --}}
                                    (Firmado por el Estudiante, Firmado por el Acudiente)
                                    | FABIO EDILBERTO JARA SANCHEZ
                                    | {{ $observation->date }}
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        </table>
        {{-- <table id="datatable_student_observer" logro="dataTableBoxed"
            class="data-table responsive nowrap stripe dataTable no-footer dtr-inline" data-order='[]'>
            <thead>
                <tr>
                    <th class="text-muted text-small text-uppercase p-0 pb-2">
                        {{ __('date') }}</th>
                    <th class="text-muted text-small text-uppercase p-0 pb-2">
                        {{ __('situation description') }}
                    </th>
                    <th class="text-muted text-small text-uppercase p-0 pb-2">
                        {{ __('free version and/or disclaimers') }}
                    </th>
                    <th class="text-muted text-small text-uppercase p-0 pb-2">
                        {{ __('agreements or commitments') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($student->observer as $observation)
                    <tr>
                        <td>{{ $observation->date }}</td>
                        <td>
                            <div class="text-uppercase">{{ $observation->annotation_type->getLabelText() }}</div>
                            {{ $observation->situation_description }}
                        </td>
                        <td>{{ $observation->free_version }}</td>
                        <td>{{ $observation->agreements }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
    </div>
    <!-- Table End -->

@endif

<!-- Modal Add Observation Start -->
<div class="modal fade" id="addObservation" aria-labelledby="modalAddObservation" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add observation') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('students.observer.create', $student) }}" id="addObservationForm" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="w-100 form-group position-relative">
                                <x-label>{{ __('select the type of annotation') }}</x-label>
                                <select name="annotation_type" logro="select2" required>
                                    <option label="&nbsp;"></option>
                                    @foreach (\App\Models\Data\AnnotationType::getData() as $key => $annotation)
                                        <option value="{{ $key }}">{{ $annotation }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <x-label>{{ __('date annotation') }}</x-label>
                                <x-input :value="old('date', today()->format('Y-m-d'))" logro="datePickerBefore" name="date_annotation"
                                    data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" class="text-center"
                                    required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group position-relative">
                                <x-label>{{ __('situation description') }}</x-label>
                                <textarea name="situation_description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group position-relative">
                                <x-label>{{ __('free version and/or disclaimers') }}</x-label>
                                <textarea name="free_version_or_disclaimers" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group position-relative">
                                <x-label>{{ __('agreements or commitments') }}</x-label>
                                <textarea name="agreements_or_commitments" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!-- Modal Add Observation End -->
