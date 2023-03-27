<form action="{{ route('teacher.report.students.store', $student) }}" id="teacherReportForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <!-- Recommendation To Orientation -->
            <div class="form-group position-relative">
                <x-label required>{{ __('recommendation to orientation') }}</x-label>
                <textarea name="recommendations_orientation" class="form-control" minlength="10" maxlength="5000" rows="3"></textarea>
            </div>

            <!-- Actions Taken by The Teacher -->
            <div class="form-group position-relative">
                <x-label required>{{ __('Actions taken by the teacher') }}</x-label>
                <textarea name="actions_teacher" class="form-control" minlength="10" maxlength="5000" rows="3"></textarea>
            </div>

        </div>

    </div>
    <div class="modal-footer justify-content-between">
        <div>
            <div class="form-check form-check-inline">
                <x-label class="form-check-label text-danger font-weight-bold">
                    <input class="form-check-input" type="checkbox"
                        name="priority_orientation" value="1" />
                    {{ __('high priority') }}
                </x-label>
            </div>
            <i data-acorn-icon="question-circle" data-bs-toggle="tooltip" data-bs-placement="top"
                title="{{ __('By checking high priority, an email with the given information will be sent to the relevant users. If the user has not verified their account, the email will not be sent.') }}"></i>
        </div>
        <div>
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="btn btn-outline-primary">{{ __('Save') }}</button>
        </div>
    </div>
</form>

