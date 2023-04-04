<!-- Controls Start -->
<div class="row mb-3">
    <!-- Search Start -->
    <div class="col-sm-12 col-md-6 col-lg-4 col-xxl-3 mb-1">
        <div class="d-inline-block float-md-start me-1 mb-1 search-input-container w-100 shadow bg-foreground">
            <input class="form-control datatable-search" placeholder="{{ __('Search') }}"
                data-datatable="#datatable_student_observer" @if (!$observer) disabled @endif />
            <span class="search-magnifier-icon">
                <i data-acorn-icon="search"></i>
            </span>
            <span class="search-delete-icon d-none">
                <i data-acorn-icon="close"></i>
            </span>
        </div>
    </div>
    <!-- Search End -->

    @can('students.observer')
    <!-- Top Buttons Start -->
    <div class="col-sm-12 col-md-6 col-lg-8 col-xxl-9 d-flex align-items-start justify-content-end">

        @if ($student->enrolled)
        <!-- Add New Button Start -->
        <a href="#" class="btn btn-sm btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto"
            data-bs-toggle="modal" data-bs-target="#addObservation">
            <i data-acorn-icon="plus"></i>
            <span>{{ __('Add New') }}</span>
        </a>
        <!-- Add New Button End -->
        @endif

    </div>
    <!-- Top Buttons End -->
    @endcan

</div>
<!-- Controls End -->

@if (count($observer))

    <!-- Table Start -->
    <div class="">
        <table id="datatable_student_observer" class="w-100" logro="dataTableBoxed" data-order='[]'>
            <thead>
                <tr>
                    <th class="empty d-none">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($observer as $observation)
                    <tr>
                        <td>
                            <div class="col-12 d-flex align-items-end">
                                <div
                                    class="w-100 bg-separator-light d-inline-block rounded-md py-3 px-3 pe-7 position-relative text-alternate">

                                    <div class="text">
                                        @if ($observation->isAccept())
                                            <div class="fst-italic mb-1">
                                                <i data-acorn-icon="check" class="font-weight-bold text-success"></i>
                                                {{ __('Student accepts the observation') }}
                                            </div>
                                        @elseif ($observation->isReject())
                                            <div class="fst-italic mb-1">
                                                <i data-acorn-icon="close" class="font-weight-bold text-danger"></i>
                                                {{ __('Student does NOT accept the observation') }}
                                            </div>
                                        @endif
                                    </div>
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
                                        {{ $observation->user_creator?->getFullName() }}
                                        | {{ $observation->date }}
                                    </span>

                                    @if (auth()->id() === $observation->created_user_id)
                                        @if (is_null($observation->free_version))
                                            <div class="position-absolute text-alternate opacity-75 t-0 e-2">

                                                <div class="ms-1 dropstart">
                                                    <button type="button"
                                                        class="btn btn-sm btn-icon-only text-primary"
                                                        data-bs-offset="0,3" data-bs-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false" data-submenu>
                                                        <i data-acorn-icon="more-horizontal"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <x-dropdown-item type="button"
                                                            data-observer="{{ $observation->id }}">
                                                            <span>{{ __('Add disclaimers') }}</span>
                                                        </x-dropdown-item>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Table End -->

    <!-- Modal Add Disclaimers Start -->
    <div class="modal fade modal-close-out" id="addDisclaimers" aria-labelledby="modalAddDisclaimers" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add disclaimers') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('students.observer.disclaimers', $student) }}" id="addDisclaimersForm"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="observer" id="observerDisclaimers" value="">

                    <div class="modal-body">

                        <div class="row g-3">

                            <div class="col-12">
                                <div class="form-group position-relative">
                                    <x-label required>{{ __('free version and/or disclaimers') }}</x-label>
                                    <textarea name="free_version_or_disclaimers" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group position-relative">
                                    <x-label>{{ __('agreements or commitments') }}</x-label>
                                    <textarea name="agreements_or_commitments" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                        <input type="radio"
                                            class="form-check-input position-absolute e-2 t-2 z-index-1"
                                            name="accepts_or_rejects" value="accept" />
                                        <span
                                            class="card form-check-label form-check-label-success w-100 custom-border">
                                            <span class="card-body text-center">
                                                <span class="heading mt-3 text-body text-primary d-block">Acepta</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-6">
                                    <label class="form-check custom-card w-100 position-relative p-0 m-0">
                                        <input type="radio"
                                            class="form-check-input position-absolute e-2 t-2 z-index-1"
                                            name="accepts_or_rejects" value="reject" />
                                        <span class="card form-check-label form-check-label-danger w-100 custom-border">
                                            <span class="card-body text-center">
                                                <span class="heading mt-3 text-body text-primary d-block">No
                                                    acepta</span>
                                            </span>
                                        </span>
                                    </label>
                                </div>
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
    <!-- Modal Add Observation End -->

@endif

@can('students.observer')
<!-- Modal Add Observation Start -->
<div class="modal fade modal-close-out" id="addObservation" aria-labelledby="modalAddObservation" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
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
                                <x-label required>{{ __('select the type of annotation') }}</x-label>
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
                                <x-label required>{{ __('date observation') }}</x-label>
                                <x-input :value="old('date_observation', today()->format('Y-m-d'))" logro="datePickerBefore" name="date_observation"
                                    data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" class="text-center"
                                    required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group position-relative">
                                <x-label required>{{ __('situation description') }}</x-label>
                                <textarea name="situation_description" class="form-control" rows="3"></textarea>
                            </div>
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
<!-- Modal Add Observation End -->
@endcan
