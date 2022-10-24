<form action="{{ route('teachers.permits.store', $teacher) }}" id="addPermitTeacherForm"
    class="tooltip-label-end" method="POST" enctype="multipart/form-data" novalidate>
    @csrf

    <div class="modal-body">

        <div class="row g-3">
            <div class="col-12">
                <div class="position-relative form-group">
                    <x-label required>{{ __('short description') }}</x-label>
                    <x-input name="short_description" :value="old('short_description')" required />
                </div>
            </div>
            <div class="col-12">
                <div class="input-daterange input-group row g-3" datePickerRange>
                    <div class="position-relative form-group col-6">
                        <x-label required>{{ __('start date') }}</x-label>
                        <x-input name="permit_date_start" required />
                    </div>
                    <div class="position-relative form-group col-6">
                        <x-label required>{{ __('end date') }}</x-label>
                        <x-input name="permit_date_end" required />
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="position-relative form-group">
                    <x-label required>{{ __('support document') }} (pdf)</x-label>
                    <x-input name="support_document" type="file" accept="application/pdf"
                        class="d-block" required />
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

