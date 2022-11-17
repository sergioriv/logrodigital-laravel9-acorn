<form action="{{ route('students.tracking.coordination.store', $student) }}" id="addCoordinationForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="w-100 form-group position-relative">
                <x-label>{{ __('recommendation to the family') }}</x-label>
                <select name="trackingCoordinator" logro="select2" required>
                    <option label="&nbsp;"></option>
                    @foreach ($coordinators as $coordinator)
                        <option value="{{ $coordinator->uuid }}">{{ $coordinator->fullName() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group position-relative">
                <x-label>{{ __('recommendation to the coordinator') }}</x-label>
                <textarea name="recommendations_coordinator" class="form-control" rows="5"></textarea>
            </div>

        </div>

    </div>
    <div class="modal-footer justify-content-between">
        <div>
            <div class="form-check form-check-inline">
                <x-label class="form-check-label">
                    <input class="form-check-input" type="checkbox"
                        name="priority_coordinator" value="1" />
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