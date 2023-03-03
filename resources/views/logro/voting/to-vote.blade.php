@php
    $title = __('To vote');
@endphp
@extends('layout-empty', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
@endsection

@section('content')
    <div class="container">

        <!-- Title Start -->
        <div class="d-flex align-items-center justify-content-center">
            <div class="display-1 text-uppercase">{{ $title }}</div>
        </div>
        <!-- Title End -->

        <!-- Content Start -->
        <section class="scroll-section mt-2">

            <div class="card">
                <div class="card-body text-center">

                    <form action="{{ route('voting.to-start') }}" method="GET">

                        <h5 class="h5 mt-3">Digite su número de documento</h5>

                        <x-input name="document" class="display-2 text-center spaci" value="" autofocus required />

                        <div class="mt-7 mb-3 h5">
                            <x-button type="submit" class="btn-primary btn-icon btn-icon-end text-capitalize">
                                {{ __('start') }}
                                <i class="icon bi-chevron-right ms-2"></i>
                            </x-button>
                        </div>

                    </form>

                </div>
            </div>

        </section>

    </div>
@endsection
