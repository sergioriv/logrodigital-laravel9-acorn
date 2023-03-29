<form action="{{ route('students.tracking.remit.store', $student) }}" id="addRemitForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <!-- Entity remit -->
            <div class="col-md-6">
                <div class="form-group position-relative">
                    <x-label required>{{ __('Entity to be remitted') }}</x-label>
                    <x-input name="entity_remit" />
                </div>
            </div>

            <!-- Type header -->
            <div class="col-md-6">
                <div class="w-100 form-group position-relative">
                    <x-label>{{ __('Header remission') }}</x-label>
                    <select name="header_remit" logro="select2">
                        <option label="&nbsp;"></option>
                        @foreach ($headers_remission as $header)
                            <option value="{{ $header->id }}">{{ $header->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <!-- Reason for remit -->
            <div class="col-12">
                <div class="form-group position-relative">
                    <x-label required>{{ __('Reason for remit') }}</x-label>
                    <textarea name="reason_entity" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <!-- Orientation interventio -->
            <div class="col-12">
                <div class="form-group position-relative">
                    <x-label required>{{ __('Orientation intervention') }}</x-label>
                    <textarea name="orientation_intervention" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <!-- Risk or Vulnerabilities -->
            <div class="col-12">
                <div class="form-group position-relative">
                    <x-label required>{{ __('risks or vulnerabilities') }}</x-label>
                    <textarea name="risk_or_vulnerabilities" class="form-control" rows="3">{{ $student?->risks_vulnerabilities }}</textarea>
                </div>
            </div>

        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="btn btn-primary">
            {{ __('Save') }}</button>
    </div>
</form>
