<form action="{{ route('students.tracking.teachers.store', $student) }}" id="addTeacherForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="mb-2 form-group position-relative">
                <x-label>{{ __('Alert due date') }}</x-label>
                <x-input name="date_limit_teachers" logro="datePickerAfter" />
            </div>
            <div class="form-group position-relative">
                <x-label>{{ __('Recommendation for teachers') }}</x-label>
                <textarea name="recommendations_teachers" class="form-control" rows="5"></textarea>
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

