@php
$title = __('Create Secretariat User');
@endphp
@extends('layout',['title'=>$title])

@section('css')
@endsection

@section('js_vendor')
<script src="/js/vendor/jquery.validate/jquery.validate.min.js"></script>
<script src="/js/vendor/jquery.validate/additional-methods.min.js"></script>
<script src="/js/vendor/jquery.validate/localization/messages_es.min.js"></script>
@endsection

@section('js_page')
<script src="/js/forms/genericuserforms.js"></script>
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

            <!-- Content Start -->
            <section class="scroll-section">
                <form method="post" action="{{ route('secreatariat.store') }}" class="tooltip-label-end" id="userCreateForm" novalidate>
                    @csrf

                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("name") }} <x-required/></x-label>
                                        <x-input :value="old('name')" name="name" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("last names") }} <x-required/></x-label>
                                        <x-input :value="old('last_names')" name="last_names" />
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("email") }} <x-required/></x-label>
                                        <x-input :value="old('email')" name="email" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3 position-relative form-group">
                                        <x-label>{{ __("telephone") }}</x-label>
                                        <x-input :value="old('telephone')" name="telephone" />
                                    </div>
                                </div>
                            </div>

                            <x-button class="btn-primary" type="submit">{{ __("Save") }}</x-button>

                        </div>
                    </div>

                </form>
            </section>
            <!-- Content End -->
        </div>
    </div>
</div>
@endsection
