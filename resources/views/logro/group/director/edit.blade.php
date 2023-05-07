<form action="{{ route('group-directors.update', $groupID) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="icon bi-award me-1"></i>{{ __('Change group director') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <select name="new_director" id="new_director_{{ $groupID }}" logro="select2" required>
                    <option label="&nbsp;"></option>
                    @foreach ($teachers as $teacher)
                        <option value="{{ $teacher['uuid'] }}" @selected($teacher['isDirector'])>{{ $teacher['names'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <x-button class="btn-outline-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</x-button>
                <x-button type="submit" class="btn-primary">{{ __('Save') }}</x-button>
            </div>
        </div>
    </div>
</form>
<script>
    jQuery("#new_director_{{ $groupID }}").select2({
        dropdownParent: $('#modalEditGroupDirector'),
        minimumResultsForSearch: 5,
        placeholder: "{{ __('Select a teacher') }}"
    });
</script>
