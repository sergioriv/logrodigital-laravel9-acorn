@php
    $title = $group->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
<script>
    jQuery("#confirm_save").click(function () {
        if ( $(this).is(':checked') ) {
            $("#save_specialty").prop('disabled', false);
        } else {
            $("#save_specialty").prop('disabled', true);
        }
    });
</script>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- Title Start -->
                <section class="scroll-section" id="title">
                    <div class="page-title-container">
                        <h1 class="mb-1 pb-0 display-4">{{ $title . ' | ' . __('Specialty') }}</h1>
                    </div>
                </section>
                <!-- Title End -->

                <section class="scroll-section mt-5">
                    <form method="POST" action="{{ route('group.specialty.store', $group) }}" novalidate>
                        @csrf

                        <div class="bg-light text-alternate h5 mb-5 text-center">
                            <i class="icon-20 bi-exclamation-diamond"></i><br />
                            {{ __('Select the area you want to apply for the group') }}
                        </div>

                        <div class="row g-4 mb-5">

                            @foreach ($resourceAreas as $area)
                            <div class="col-sm-6 col-xxl-3">
                                <h2 class="small-title">{{ $area->name }}</h2>
                                <label class="form-check custom-card cursor-pointer w-100 position-relative p-0 m-0">
                                    <input type="radio" class="form-check-input position-absolute e-2 t-2 z-index-1"
                                        name="area_specialty" value="{{ $area->id }}" />
                                    <span class="card form-check-label w-100">
                                        <span class="card-body">
                                            @foreach ($area->subjects as $subjectA)
                                            <div class="pt-1 pb-1 d-inline-flex flex-wrap gap-2 w-100 min-height-sm">
                                                <span
                                                    class="logro-tag badge bg-light text-alternate font-weight-bold text-uppercase">
                                                    {!! $subjectA->resourceSubject->name !!}
                                                </span>
                                            </div>
                                            @endforeach
                                        </span>
                                    </span>
                                </label>
                            </div>
                            @endforeach

                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="confirm_save" />
                            <label class="form-check-label" for="confirm_save">{{ __('This process is irreversible. Please confirm that you are sure to save.') }}</label>
                        </div>

                        <x-button type="submit" disabled id="save_specialty" class="btn-primary">{{ __('Save group') }}</x-button>

                    </form>
                </section>

            </div>
        </div>
    </div>
@endsection
