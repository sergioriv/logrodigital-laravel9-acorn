<form action="{{ route('students.tracking.advice.store', $student) }}" id="addAdviceForm" method="POST">
    @csrf

    <div class="modal-body">

        <div class="row g-3">

            <div class="col-12">
                <div class="position-relative form-group">
                    <x-label>{{ __('Type advice') }}</x-label>
                    <x-select name="type_advice" id="type_advice" logro="select2">
                        @foreach (\App\Models\StudentTrackingAdvice::enumTypeAdvice() as $typeAdvice)
                        <option value="{{ $typeAdvice }}" @selected(old('type_advice')==$typeAdvice)>
                            {{ __($typeAdvice) }}
                        </option>
                        @endforeach
                    </x-select>
                </div>
            </div>

            <div class="col-12 d-none" id="advice_family">
                <div class="form-group position-relative">
                    <x-label>Por medio de la presente, notifico a quien corresponda, que:</x-label>
                    <textarea name="advice_family" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative form-group">
                    <x-label>{{ __('date') }}</x-label>
                    <x-input name="date" :value="old('date', now()->format('Y-m-d'))" placeholder="yyyy-mm-dd" logro="datePickerAll" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="time-picker-container">
                    <div class="position-relative form-group">
                        <x-label>{{ __('hour') }}</x-label>
                        <input class="form-control time-picker" name="time" data-format="12"
                            data-minutes="0,10,20,30,40,50" id="timeAdvice" />
                    </div>
                </div>
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


