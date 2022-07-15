@php
$title = $studyYear->name;
@endphp
@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
    <script>
        jQuery('input.form-check-input').click(function() {
            var subject = $(this).data('subject');

            if ($(this).prop('checked')) {
                $("[subject='" + subject + "']").attr('disabled', false);
            } else {
                $("[subject='" + subject + "']").attr('disabled', true).val('');
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
                        <h1 class="mb-0 pb-0 display-4">{{ $title . ' | ' . __('Subjects') }}</h1>
                    </div>
                </section>
                <!-- Title End -->


                <!-- Validation Errors -->
                <x-validation-errors class="mb-4" :errors="$errors" />

                <form method="POST" action="{{ route('studyYear.subject.store', $studyYear) }}" autocomplete="off">
                    @csrf

                    <section class="scroll-section mb-5">
                        <div class="mb-n2" id="accordionCardsSubjects">
                            @foreach ($areas as $area)
                                <div class="card d-flex mb-2">
                                    <div class="card-body">
                                        <h2 class="small-title">{{ $area->name }}</h2>
                                        <table class="w-75">
                                            <tbody>
                                                @foreach ($area->subjects as $subject)
                                                    <tr>
                                                        <td class="w-40">
                                                            <div class="form-check">
                                                                <label class="form-check-label">
                                                                    <input type="checkbox" name="subjects[]"
                                                                        value="{{ $subject->resource_area_id . '~' . $subject->id }}"
                                                                        data-subject="{{ $subject->id }}"
                                                                        class="form-check-input" />
                                                                    <span>{{ $subject->resourceSubject->name }}</span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="number" disabled max="20" min="0"
                                                                placeholder="{{ __('Hours week') }}"
                                                                subject="{{ $subject->id }}" class="form-control"
                                                                name="{{ $subject->id }}~hours_week" required>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <span class="input-group-text logro-input-disabled">%</span>
                                                                <input type="number" disabled max="100" min="0" step="10"
                                                                placeholder="{{ __('Course load') }}"
                                                                subject="{{ $subject->id }}" class="form-control"
                                                                name="{{ $subject->id }}~course_load" required>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <x-button type="submit" class="btn-primary">{{ __('Save Subjects') }}</x-button>
                </form>


            </div>
        </div>
    </div>
@endsection
