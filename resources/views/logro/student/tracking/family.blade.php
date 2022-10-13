<form action="{{ route('students.tracking.family.store', $student) }}" id="addTeacherForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="form-group position-relative">
                <x-label>{{ __('recommendation to the family') }}</x-label>
                <textarea name="recommendations_family" class="form-control" rows="5"></textarea>
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

