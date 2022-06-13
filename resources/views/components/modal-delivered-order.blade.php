<div class="modal fade" id="modalConfirmDelivered" tabindex="-1" aria-labelledby="modalFullScreen"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title h4" id="modalFullScreen">{{ '¿'. __('Order delivered') .'?' }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    {{ __('Confirm order delivered') }}
                </p>
            </div>
            <div class="modal-footer">
                <x-button class="btn-outline-secondary" data-bs-dismiss="modal">{{ __('Close') }}</x-button>
                <x-button type="submit" name="finished" value="1" class="btn-primary">{{ __('Confirm') }}</x-button>
            </div>
        </div>
    </div>
</div>
