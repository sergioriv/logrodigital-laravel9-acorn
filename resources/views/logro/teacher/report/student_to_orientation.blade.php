<form action="{{ route('teacher.report.students.store', $student) }}" id="teacherReportForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="form-group position-relative">
                <x-label>{{ __('recommendation to orientation') }}</x-label>
                <textarea name="recommendations_orientation" class="form-control" rows="5"></textarea>
            </div>

        </div>

    </div>
    <div class="modal-footer justify-content-between">
        <div>
            <div class="form-check form-check-inline">
                <x-label class="form-check-label">
                    <input class="form-check-input" type="checkbox"
                        name="priority_orientation" value="1" />
                    {{ __('priority') }}
                </x-label>
            </div>
        </div>
        <div>
            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}</button>
        </div>
    </div>
</form>
