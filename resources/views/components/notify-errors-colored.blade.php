@if ($errors->any())
    <div class="toast-container position-absolute p-3 top-0 end-0">

        @foreach ($errors->all() as $error)
            <div class="toast align-items-center bg-danger border-0 border-danger fade show mb-2" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-white d-inline-flex">
                        <i data-acorn-icon="error-hexagon" class="me-2"></i>
                        <div class="logro-label">{{ $error }}</div>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endforeach

    </div>
@endif
{{--
<div class="toast-container position-absolute p-3 top-0 end-0">
    <div class="toast align-items-center bg-primary border-0 fade show mb-2" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body text-white">Hello, world! This is a toast message.</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>
 --}}
@if (Session::get('notify'))
    <div class="toast-container position-absolute p-3 top-0 end-0">

        @php
            $toastNotify = explode('|', Session::get('notify'));
        @endphp
        @switch($toastNotify[0])
            @case('fail')
                <div class="toast align-items-center bg-danger border-0 border-danger fade show mb-2" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-white d-inline-flex">
                            <i data-acorn-icon="error-hexagon" class="me-2"></i>
                            <div class="logro-label">{{ $toastNotify[1] }}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @break

            @case('info')
                <div class="toast align-items-center bg-info border-0 border-info fade show mb-2" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-white d-inline-flex">
                            <i data-acorn-icon="info-hexagon" class="me-2 text-white"></i>
                            <div class="">{{ $toastNotify[1] }}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @break

            @default
                <div class="toast align-items-center bg-success border-0 border-success fade show mb-2" role="alert"
                    aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body text-white d-inline-flex">
                            <i data-acorn-icon="check" class="me-2 text-white"></i>
                            <div class="">{{ $toastNotify[1] }}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                    </div>
                </div>
            @break
        @endswitch

    </div>
@endif
