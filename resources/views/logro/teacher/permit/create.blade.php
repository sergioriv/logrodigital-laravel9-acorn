<form action="{{ route('add-permit') }}" id="addPermitTeacherForm"
    class="tooltip-label-end" method="POST" novalidate>
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="col-12">
                <div class="w-100 position-relative form-group">
                    <x-label required>{{ __('type permit') }}</x-label>
                    <select name="type_permit" logro="select2">
                        <option label=""></option>
                        @foreach ($typePermit as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

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
                        <x-input name="permit_date_start" placeholder="yyyy-mm-dd" required />
                    </div>
                    <div class="position-relative form-group col-6">
                        <x-label required>{{ __('end date') }}</x-label>
                        <x-input name="permit_date_end" placeholder="yyyy-mm-dd" required />
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger"
            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-outline-primary">
            {{ __('Request') }}</button>
    </div>
</form>

