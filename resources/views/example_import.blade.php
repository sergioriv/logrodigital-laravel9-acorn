@php
$title = 'Example upload file';
@endphp
@extends('layout',['title'=>$title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Title Start -->
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-1 pb-0 display-4">{{ $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="card mb-5">
                    <div class="card-body">

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('user.import') }}" class="tooltip-end-bottom"
                            enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="mb-3">
                                <input type="file" class="d-block form-control" name="file" accept=".xls,.xlsx" required>
                            </div>

                            <x-button type="submit" class="btn-primary">{{ __('Upload') }}</x-button>

                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
