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

        let showCandidateModal = $('#showCandidateModal');
        function showCandidate(vt_id) {
            showCandidateModal.find('voting-show-id').val(vt_id);
            showCandidateModal.modal('show');
        }
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
            </div>
        </section>

        <section class="my-5">
            <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">

                @foreach ($voting as $vt)
                    <div class="">
                        <div class="card border border-light">
                            <div class="card-body text-center position-relative">
                                <div class="display-5">{{ $vt->title }}</div>
                                <div class="mt-2 h3 m-0">
                                    <span class="text-primary cursor-pointer" title="ver candidatos" data-bs-toggle="modal"
                                        data-bs-target="#showCandidateModal-{{ $vt->id }}">{{ $vt->candidates_count }}</a>
                                </div>
                                <div>Candidatos</div>
                                <div class="mt-3 h3 m-0">

                                    @php $studentsForVote = 0; @endphp
                                    @foreach ($vt->constituencies as $groups)
                                        @php
                                            $studentsForVote += $groups->group->group_students_count ?? 0;
                                        @endphp
                                    @endforeach

                                    {{ $studentsForVote }}

                                </div>
                                <div>Estudiantes habilitados para votar</div>
                                <div>
                                    <a href="{{ route('voting.download.students', $vt) }}">
                                        Descargar lista
                                    </a>
                                </div>
                                <div class="my-5 h5 d-flex flex-column">
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
                                        @if ($vt->status->isFinished())
                                            <span class="text-danger mt-3 font-weight-bold">FINALIZADA</span>
                                        @endif
                                    @endif
                                </div>
                                <span class="position-absolute text-extra-small text-alternate opacity-75 b-2 e-2">
                                    {{ $vt->creatorName() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="showCandidateModal-{{ $vt->id }}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('Candidates') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{ route('voting.update-info-candidates', $vt->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-body text-center">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Candidato</th>
                                                <th>Número</th>
                                                <th>Color</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($vt->candidates as $candidate)
                                            <tr class="">
                                                <td class="align-middle text-start">{{ $candidate->student->getCompleteNames() }}</td>
                                                <td>
                                                    <input type="number" min="0" max="9999" class="form-control" name="candidates[{{$candidate->id}}][number]" value="{{ $candidate->number }}">
                                                </td>
                                                <td>
                                                    <input type="color" class="form-control sh-0" name="candidates[{{$candidate->id}}][color]" value="{{ $candidate->color }}">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger"
                                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    <button type="submit" class="btn btn-outline-primary">
                                        {{ __('Save') }}</button>
                                </div>
                            </form>
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
