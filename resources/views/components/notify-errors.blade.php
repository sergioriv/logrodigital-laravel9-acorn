@if ($errors->any())
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">

        @error('custom')
            <div class="toast align-items-center bg-background border-1 border-danger fade show mb-2" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-danger d-inline-flex">
                        <i data-acorn-icon="error-hexagon" class="me-2"></i>
                        <span class="logro-label">{{ $message }}</span>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @else
            @foreach ($errors->all() as $error)
                <div class="toast align-items-center bg-background border-1 border-danger fade show mb-2" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-danger d-inline-flex">
                            <i data-acorn-icon="error-hexagon" class="me-2"></i>
                            <div class="logro-label">{{ $error }}</div>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @endforeach
        @enderror

    </div>
@endif


@if (Session::get('notify'))
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 5">

        @php
            $toastNotify = explode('|', Session::get('notify'));
        @endphp
        @switch($toastNotify[0])
            @case('fail')
                <div class="toast align-items-center bg-background border-1 border-danger fade show mb-2" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-danger d-inline-flex">
                            <i data-acorn-icon="error-hexagon" class="me-2"></i>
                            <div class="logro-label">{{ $toastNotify[1] }}</div>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @break

            @case('info')
                <div class="toast align-items-center bg-background border-1 border-info fade show mb-2" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-base d-inline-flex">
                            <i data-acorn-icon="info-hexagon" class="me-2 text-info"></i>
                            <div class="">{{ $toastNotify[1] }}</div>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @break

            @case('welcome')
            <div class="toast align-items-center bg-background border-1 border-info fade show mb-2" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-base d-inline-flex">
                        <i class="icon bi-emoji-wink icon-18 me-2"></i>
                        <div>{{ $toastNotify[1] }}</div>
                    </div>
                    <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
            @break

            @default
                <div class="toast align-items-center bg-background border-1 border-success fade show mb-2" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-base d-inline-flex">
                            <i data-acorn-icon="check" class="me-2 text-success"></i>
                            <div class="">{{ $toastNotify[1] }}</div>
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @break
        @endswitch

    </div>
@endif
