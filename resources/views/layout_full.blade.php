<!DOCTYPE html>
<html lang="en" data-url-prefix="/">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <title>{{ $title .' | '. config('app.name') }}</title>
    <meta name="description" content="{{$description}}"/>
    @include('_layout.head')
</head>
<body class="h-100">
<div id="root" class="h-100">
    <!-- Background Start -->
    <div class="fixed-background"></div>
    <!-- Background End -->

    <div class="container-fluid p-0 h-100 position-relative">
        <div class="row g-0 h-100">
            <!-- Left Side Start -->
            <div class="offset-0 col-12 d-none d-lg-flex offset-md-1 col-lg h-lg-100">
                @include('_layout.content-left')
            </div>
            <!-- Left Side End -->

            <!-- Right Side Start -->
            <div class="col-12 col-lg-auto h-100 pb-4 px-4 pt-0 p-lg-0">
                @yield('content_right')
            </div>
            <!-- Right Side End -->
        </div>
    </div>
</div>
@include('_layout.modal_settings')
@include('_layout.scripts')
</body>
</html>
