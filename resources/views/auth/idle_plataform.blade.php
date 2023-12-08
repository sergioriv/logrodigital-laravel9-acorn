@php
$title = 'Acceso restringido';
$description = '';
@endphp
@extends('layout_full',['title'=>$title, 'description'=>$description])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
@endsection

@section('content_right')
<div
    class="sw-lg-70 min-h-100 bg-foreground d-flex justify-content-center align-items-center shadow-deep py-5 full-page-content-right-border">
    <div class="sw-lg-50 px-5">
        <div class="sh-13 mb-7 d-flex justify-content-center">
            <x-auth.logo :badge="$SCHOOL_badge" />
        </div>
        <div class="mb-5 text-center">
            <h2 class="cta-1 text-primary">{{ $SCHOOL_name }}</h2>
        </div>
        <div class="mb-5">
            <h2 class="cta-1 text-danger lh-1">
                <i data-acorn-icon="shield-warning"></i>
                Acceso restringido
            </h2>
        </div>
        <div class="mb-5">
            <p class="h6">
                <div class="font-weight-bold text-black">No reporta pago de facturación.</div>
                <div class="mt-3 text-black">
                    Para reanudar su servicio, póngase en contacto a través de:
                    <div class="mt-2">
                        <div class="text-medium">Correo electrónico:</div>
                        <div class="font-weight-bold">info@mantiztechnology.com</div>
                    </div>
                    <div class="mt-2">
                        <div class="text-medium">WhatsApp:</div>
                        <div class="font-weight-bold">571+3042020019</div>
                    </div>
                </div>
            </p>
        </div>
        <div>
            <a href="./" class="btn btn-lg btn-light">
            {{ __('Go Home') }}
            </a>
        </div>
    </div>
</div>
@endsection
