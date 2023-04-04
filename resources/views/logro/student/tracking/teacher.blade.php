<form action="{{ route('students.tracking.teachers.store', $student) }}" id="addTeacherForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="w-100 form-group position-relative">
                <x-label required>{{ __('select teachers') }}</x-label>
                <select name="tracking_teachers[]" logro="select2" required multiple>
                    <option label="&nbsp;"></option>
                    @foreach ($myTeachers as $myTeacher)
                        <option value="{{ $myTeacher->uuid }}" selected>{{ $myTeacher->getFullName() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group position-relative">
                <x-label>{{ __('Recommendation for teachers') }}</x-label>
                <textarea name="recommendations_teachers" class="form-control" rows="5"></textarea>
            </div>

        </div>

    </div>
    <div class="modal-footer justify-content-between">
        <div>
            <div class="form-check form-check-inline">
                <x-label class="form-check-label text-danger font-weight-bold">
                    <input class="form-check-input" type="checkbox" name="priority_teacher" value="1" />
                    {{ __('high priority') }}
                </x-label>
            </div>
            <i data-acorn-icon="question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('By checking high priority, an email with the given information will be sent to the relevant users. If the user has not verified their account, the email will not be sent.') }}"></i>
        </div>
        <div>
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}</button>
        </div>
    </div>
</form>
