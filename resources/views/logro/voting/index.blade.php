@php
    $title = __('Voting');
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
    <script>
        jQuery("[data-started-voting]").click(function() {
            let _voting = $(this);
            let value = _voting.data('started-voting');
            if (value) {
                $("#voting-start-id").val(value);
                $("#votingStarted").modal('show');
            }
        });
    </script>
@endsection

@section('content')
    <div class="container">
        <div class="d-flex align-items-center justify-content-center">
            <div class="display-1 text-uppercase">{{ __('Voting') }}</div>
        </div>

        <section class="my-3 h5">
            <div class="row g-3 d-flex align-items-center justify-content-center">
                <div class="col-md-6 text-center">
                    <a href="{{ route('voting.create') }}" class="btn btn-outline-primary">
                        Crear votación
                    </a>
                </div>
                {{-- <div class="col-md-6 text-md-start text-center">
                    <a href="
                    @if ($votingStarted) {{ route('voting.to-vote') }}
                    @else
                    # @endif
                    "
                        class="btn @if (!$votingStarted) pointer-events-none btn-muted @else btn-primary @endif">
                        Acceso para Estudiantes
                    </a>
                </div> --}}
            </div>
        </section>

        <section class="my-5">
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">

                @foreach ($voting as $vt)
                    <div class="">
                        <div class="card border border-light">
                            <div class="card-body text-center position-relative">
                                <div class="display-5">{{ $vt->title }}</div>
                                <div class="mt-2 h3 m-0">{{ $vt->candidates_count }}</div>
                                <div>Candidatos</div>
                                <div class="mt-3 h3 m-0">

                                    @php $studentsForVote = 0; @endphp
                                    @foreach ($vt->constituencies as $groups)
                                        @php
                                            $studentsForVote += $groups->group->group_students_count;
                                        @endphp
                                    @endforeach

                                    {{ $studentsForVote }}

                                </div>
                                <div>Estudiantes habilitados para votar</div>
                                <div class="my-5 h5">
                                    @if ($vt->status->isCreated())
                                        <button type="button" class="btn btn-primary"
                                            data-started-voting="{{ $vt->id }}">
                                            {{ __('Start voting?') }}
                                        </button>
                                    @else
                                        <a href="{{ route('voting.report', $vt->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            Ver resultados
                                        </a>
                                    @endif
                                </div>
                                <span class="position-absolute text-extra-small text-alternate opacity-75 b-2 e-2">
                                    {{ $vt->creatorName() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </section>

    </div>

    <!-- Modal Voting Started -->
    <div class="modal fade" id="votingStarted" aria-labelledby="modalVotingStarted" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Start voting?') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>
                        Si está seguro de dar inicio al proceso de votación, de click en el botón de abajo
                        "{{ __('To initiate voting') }}".
                    </p>

                    <form action="{{ route('voting.start') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="voting" id="voting-start-id" value="">

                        <button type="submit" class="btn btn-outline-primary h5 my-4">
                            {{ __('To initiate voting') }}
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
