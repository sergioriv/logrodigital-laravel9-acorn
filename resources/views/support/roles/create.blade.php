@php
$title = 'Create Role';
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
                    <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">
                <div class="card mb-5">
                    <div class="card-body">

                        <!-- Validation Errors -->
                        <x-validation-errors class="mb-4" :errors="$errors" />

                        <form method="POST" action="{{ route('support.roles.store') }}" class="tooltip-end-bottom" novalidate>
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <x-label>{{ __('Name') }}</x-label>
                                <x-input id="name" name="name" :value="old('name')" required autofocus />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('Permissions') }}</label>

                                @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <label class="form-check-label">
                                        {{ $permission->name }}
                                        <input name="permissions[]" class="form-check-input" type="checkbox" value="{{ $permission->id }}" />
                                    </label>
                                </div>
                                @endforeach
                            </div>

                            <x-button type="submit" class="btn-primary">{{ __('Save role') }}</x-button>

                        </form>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
