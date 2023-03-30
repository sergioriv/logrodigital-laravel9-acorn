<div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{ __('Permission type') . ' | ' . $title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <form action="{{ $route }}" method="POST" id="typePermissionForm">
            @csrf
            @method($method)

            <div class="modal-body">
                <div class="row g-2">

                    <div class="col-12">
                        <div class="form-group position-relative">
                            <x-label required>{{ __('Name') }}</x-label>
                            <x-input name="name" :value="old('name', $typePermission)" required />
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" class="btn btn-outline-primary">
                    @if ($method === 'PUT')
                        {{ __('Update') }}
                    @else
                        {{ __('Create') }}
                    @endif
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    const typePermissionForm = document.getElementById("typePermissionForm");

    typePermissionForm.addEventListener("submit", (event) => {
        $("button[type='submit']", typePermissionForm).prop("disabled", true);
    });
</script>
