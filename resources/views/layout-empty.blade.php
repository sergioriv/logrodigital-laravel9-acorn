<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-url-prefix="/" data-footer="true">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title .' | '. config('app.name') }}</title>
    @include('layout.head')
</head>

<body>
<div id="root">

    <div class="container-fluid p-0 h-100 position-relative">

        <x-notify-errors-colored :errors="$errors" />

        {{-- <div class="toast-container position-absolute p-3 top-0 end-0">
            <div class="toast align-items-center bg-primary border-0 fade show mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body text-white">Hello, world! This is a toast message.</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div> --}}

        <div class="row g-0 h-100 justify-content-center">
            <!-- Content Start -->
            <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-4">
                @yield('content')
            </div>
            <!-- Content End -->
        </div>
    </div>

    @include('layout.footer')
</div>
@include('layout.scripts')
</body>

</html>
