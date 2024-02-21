@php
    $title = $student->document;
@endphp
@extends('layout-empty', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
    <script>
        jQuery(".form-check-input").click(function() {
            $("#save-vote").prop('disabled', false);
        });
    </script>
@endsection

@section('content')
    <div class="container">

        <!-- Title Start -->
        <div class="d-flex flex-column align-items-center justify-content-center">
            <div class="display-6">{{ $countVoting }}</div>
            <div class="my-3 card">
                <div class="card-body font-weight-bold d-flex flex-column text-center">
                    <span>{{ $student->document }}</span>
                    <span>{{ $student->getCompleteNames() }}</span>
                </div>
            </div>
            <div class="display-1 text-uppercase">{{ $voting->title }}</div>
        </div>
        <!-- Title End -->

        <!-- Content Start -->
        <section class="scroll-section mt-4">

            <form action="{{ route('voting.save-vote') }}" method="POST">
                @csrf
                @method('PATCH')

                <input type="hidden" name="document" value="{{ $student->document }}">
                <input type="hidden" name="voting" value="{{ $voting->id }}">

                <div class="row g-4 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4">
                    @foreach ($voting->candidates as $candidate)
                        <div class="">
                            <label class="form-check custom-card form-voting w-100 position-relative p-0 m-0">
                                <input type="radio" class="form-check-input position-absolute e-0 t-0 z-index-1"
                                    name="vote" value="{{ $candidate->id }}" requried />
                                <div class="card form-check-label w-100 border border-5 @if(is_null($candidate->color)) border-light @endif" @if( ! is_null($candidate->color) ) style="border-color: {{ $candidate->color }} !important" @endif>
                                    <div class="card-body text-center">
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            <div class="rounded-md border shadow-deep border-light border-2">
                                                @if ($candidate->student->user->avatar)
                                                    @php $avatar = $candidate->student->user->avatar @endphp
                                                @else
                                                    @php $avatar = 'img/other/profile-11.webp' @endphp
                                                @endif
                                                <img src="{{ config('app.url') . '/' . $avatar }}" alt="avatar"
                                                    class="rounded-md sw-20 sh-20 m-1">
                                            </div>
                                            <span class="text-uppercase mt-5 display-5">
                                                {{ $candidate->student->getCompleteNames() }}
                                            </span>
                                            <span class="mt-3 mb-0 badge bg-muted h6 text-uppercase">
                                                {{ $candidate->student->group->name }}
                                            </span>
                                            @if(!is_null($candidate->number))
                                            <div class="mt-3">
                                                <div class="align-items-center bg-light d-flex justify-content-center rounded-circle sh-10 sw-10 text-white" style="background-color: {{ $candidate->color }} !important">
                                                    <span class="display-4">{{ $candidate->number }}</span>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    @endforeach
                    <div class="">
                        <label class="form-check custom-card form-voting w-100 position-relative p-0 m-0">
                            <input type="radio" class="form-check-input position-absolute e-0 t-0 z-index-1"
                                name="vote" value="BLANK" requried />
                            <div class="card form-check-label w-100 border border-5 border-light">
                                <div class="card-body text-center">
                                    <div class="d-flex flex-column justify-content-center align-items-center">
                                        <div class="rounded-md border shadow-deep border-light border-2">
                                                <div class="sw-20 sh-20 m-1">&nbsp;</div>
                                        </div>
                                        <span class="text-uppercase mt-5 display-5">
                                            Voto en blanco
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <x-button type="submit" class="btn-primary h5" id="save-vote" disabled>GUARDAR VOTO</x-button>
                </div>

            </form>

        </section>

    </div>
@endsection
