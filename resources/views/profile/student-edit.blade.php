@php
$title = $student->user->name;
@endphp
@extends('layout',['title'=>$title])

@section('css')
@endsection

@section('js_vendor')
<script src="/js/vendor/singleimageupload.js"></script>
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
                    <h1 class="mb-0 pb-0 display-4">{{ $title . ' | ' . __('Edit') }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="card mb-5">
                    <div class="card-body">

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('user.profile.update') }}" class="tooltip-end-bottom"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12 col-xl-3 col-xxl-12">

                                    <!-- Avatar -->
                                    <div class="mb-3 d-flex align-items-center flex-column">
                                        <x-avatar-profile-edit :avatar="$student->user->avatar" />
                                    </div>

                                </div>
                                <div class="col-12 col-xl-9 col-xxl-12">

                                    <!-- Name -->
                                    <div class="mb-3">
                                        <x-label>{{ __('Name') }}</x-label>
                                        <x-input id="name" name="name" value="" required
                                            autofocus />
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <x-label>{{ __('Email') }}</x-label>
                                        <x-input value=""
                                            required disabled />
                                    </div>

                                    <!-- Nit -->
                                    <div class="mb-3">
                                        <x-label>{{ __('Nit') }}</x-label>
                                        <x-input id="nit" name="nit" value="" required />
                                    </div>

                                    <!-- Telephone -->
                                    <div class="mb-3">
                                        <x-label>{{ __('Telephone') }}</x-label>
                                        <x-input id="telephone" name="telephone" value=""
                                            required />
                                    </div>

                                </div>
                            </div>

                            <x-button type="submit" class="btn-primary">{{ __('Update') }}</x-button>

                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
