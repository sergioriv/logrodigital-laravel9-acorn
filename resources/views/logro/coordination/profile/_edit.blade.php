@php
$title = $coordination->name;
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
                    <h1 class="mb-1 pb-0 display-4">{{ $title . ' | ' . __('Profile') }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">

                <form method="POST" action="{{ route('user.profile.update') }}" class="tooltip-end-bottom"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                <div class="card mb-5">
                    <div class="card-body">



                            <div class="row">
                                <div class="col-12 col-xl-3 col-xxl-12">

                                    <!-- Avatar -->
                                    <div class="mb-3 d-flex align-items-center flex-column">
                                        <x-avatar-profile-edit :avatar="$coordination->user->avatar" />
                                    </div>

                                </div>
                                <div class="col-12 col-xl-9 col-xxl-12">

                                    <div class="row g-3">
                                        <!-- Name -->
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <x-label required>{{ __('names') }}</x-label>
                                                <x-input :value="old('name', $coordination)" name="name" required />
                                            </div>
                                        </div>

                                        <!-- Last Names -->
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <x-label required>{{ __('last names') }}</x-label>
                                                <x-input :value="old('last_names', $coordination)" name="last_names" required />
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <x-label>{{ __('Email') }}</x-label>
                                                <div class="form-control bg-light">{{ $coordination->email }}</div>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <x-label>{{ __('telephone') }}</x-label>
                                                <x-input :value="old('telephone', $coordination)" name="telephone" />
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>


                        </div>
                    </div>

                    <x-button type="submit" class="btn-primary">{{ __('Update') }}</x-button>
                </form>
            </section>

        </div>
    </div>
</div>
@endsection
