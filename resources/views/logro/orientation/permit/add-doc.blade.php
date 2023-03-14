<form action="{{ route('orientation.permits.document', $orientation) }}"
    class="tooltip-label-end" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PATCH')

    <input type="hidden" name="permit" value="">

    <div class="modal-body">

        <div class="row g-3">
            <div class="col-12">
                <div class="position-relative form-group">
                    <x-label required>{{ __('support document') }} (pdf)</x-label>
                    <x-input type="file" name="support_document" accept=".pdf" class="d-block" required />
                </div>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-danger"
            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button type="submit" class="btn btn-outline-primary">
            {{ __('Upload') }}</button>
    </div>
</form>

