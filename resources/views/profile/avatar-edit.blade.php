@php
    $title = __('Edit avatar');
@endphp
@extends('layout', ['title' => $title])

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
                <!-- Title and Top Buttons Start -->
                <section class="page-title-container">
                    <div class="row">
                        <!-- Title Start -->
                        <div class="col-12 col-md-7">
                            <h1 class="mb-1 pb-0 display-4" id="title">{{ $title }}</h1>
                        </div>
                        <!-- Title End -->
                    </div>
                </section>
                <!-- Title and Top Buttons End -->

                <!-- Content Start -->
                <section class="scroll-section">
                    <form method="post" action="{{ route('profile.auth.avatar.update') }}"
                        enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="card mb-5">
                            <div class="card-body text-center">
                                <div class='position-relative d-inline-block' id="imageProfile">
                                    <img src="@if (NULL !== $user->avatar) {{ config('app.url') .'/'. $user->avatar }}
                                        @else {{ config('app.url') .'/img/other/profile-11.webp' }} @endif"
                                        alt="alternate text" class="rounded-xl border border-separator-light border-4 sw-30 sh-30" />
                                    <button class="btn btn-sm btn-icon btn-icon-only btn-separator-light rounded-xl position-absolute e-0 b-0" type="button">
                                        <i data-acorn-icon="upload"></i>
                                    </button>
                                    <input name="avatar" id="avatar" class="file-upload d-none" type="file" accept="image/jpg, image/jpeg, image/png, image/webp" />
                                </div>
                            </div>
                        </div>

                        <x-button class="btn-primary" type="submit">{{ __('Save') }}</x-button>

                    </form>
                </section>
                <!-- Content End -->
            </div>
        </div>
    </div>
@endsection
