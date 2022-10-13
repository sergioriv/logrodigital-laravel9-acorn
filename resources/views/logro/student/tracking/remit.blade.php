<form action="{{ route('students.tracking.remit.store', $student) }}" id="addRemitForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="mb-2 form-group position-relative">
                <x-label>{{ __('Entidad a remitir') }}</x-label>
                <x-input name="entity_remit" />
            </div>
            <div class="form-group position-relative">
                <x-label>{{ __('Reason for remit') }}</x-label>
                <textarea name="reason_entity" class="form-control" rows="5"></textarea>
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

