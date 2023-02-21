@php
    $title = $voting->title;
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
    <script></script>
@endsection

@section('content')
    <div class="container">

        <!-- Title Start -->
        <div class="d-flex flex-column align-items-center justify-content-center">
            <div class="display-1 text-uppercase">{{ $voting->title }}</div>
            <h5 class="text-uppercase h6">{{ $voting->status->getLabelText() }}</h5>
            <div class="mt-3 text-center">
                <div class="display-3">{{ $totalVotes }}</div>
                <div class="h5">Votos</div>
            </div>
        </div>
        <!-- Title End -->

        <!-- Content Start -->
        <section class="scroll-section mt-4">

            <div class="row g-4 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4">
                @foreach ($voting->candidates as $candidate)
                    <div class="">
                        <div class="card form-check-label w-100">
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
                                    <div class="mt-4 display-3 m-0">{{ $candidate->totalVotes->count() }}</div>
                                    <div class="h5">Votos obtenidos</div>
                                    <div class="display-1 mt-3">
                                        {{ number_format(($candidate->totalVotes->count() * 100) / ($totalVotes ?? 1), 2) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="">
                    <div class="card form-check-label w-100">
                        <div class="card-body text-center">
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <div class="rounded-md border shadow-deep border-light border-2">
                                    <div class="sw-20 sh-20 m-1">&nbsp;</div>
                                </div>
                                <span class="text-uppercase mt-5 display-5">
                                    Voto en blanco
                                </span>
                                <div class="mt-4 display-3 m-0">{{ $blankVotes }}</div>
                                <div class="h5">Votos obtenidos</div>
                                <div class="display-1 mt-3">
                                    {{ number_format(($blankVotes * 100) / ($totalVotes ?? 1), 2) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (!$voting->status->isFinished())
                <div class="mt-7 pt-7 text-center">

                    <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                        data-bs-target="#votingFinished">
                        {{ __('Finish voting?') }}
                    </button>

                </div>
            @endif

        </section>

    </div>

    <!-- Modal Voting Finished -->
    <div class="modal fade" id="votingFinished" aria-labelledby="modalVotingFinished" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Finish voting?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>
                        Si está seguro de dar por finalizada la votación, de click en el botón de abajo
                        "{{ __('To end the voting') }}".
                    </p>

                    <form action="{{ route('voting.finish', $voting) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="btn btn-sm btn-outline-danger my-4">
                            {{ __('To end the voting') }}
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
