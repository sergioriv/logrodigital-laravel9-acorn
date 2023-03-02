<div class="modal fade modal-close-out" id="addAnnotationObserverModal" tabindex="-1" role="dialog"
    aria-labelledby="addAnnotationObserverModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add annotation to Observer') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('students.observer.multiple') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="row g-2">

                        <div class="col-12">
                            <div class="w-100 form-group position-relative">
                                <x-label required>{{ __('Select students') }}</x-label>
                                <select multiple name="students_observer[]" id="students_observer"></select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="w-100 form-group position-relative">
                                <x-label required>{{ __('select the type of annotation') }}</x-label>
                                <select name="annotation_type" logro="select2" required>
                                    <option label="&nbsp;"></option>
                                    @foreach (\App\Models\Data\AnnotationType::getData() as $key => $annotation)
                                        <option value="{{ $key }}">{{ $annotation }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group position-relative">
                                <x-label required>{{ __('date observation') }}</x-label>
                                <x-input :value="old('date_observation', today()->format('Y-m-d'))" logro="datePickerBefore" name="date_observation"
                                    data-placeholder="yyyy-mm-dd" placeholder="yyyy-mm-dd" class="text-center"
                                    required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group position-relative">
                                <x-label required>{{ __('situation description') }}</x-label>
                                <textarea name="situation_description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-outline-primary">{{ __('Save') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
