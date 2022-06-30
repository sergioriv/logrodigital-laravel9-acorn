@php
$title = $studyYear->name;
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
                    <h1 class="mb-0 pb-0 display-4">{{ $title .' | '. __('Subjects') }}</h1>
                </div>
            </section>
            <!-- Title End -->


            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('studyYear.subject.store', $studyYear) }}" novalidate>
                @csrf

                <section class="scroll-section mb-5">
                    <div class="mb-n2" id="accordionCardsSubjects">
                        @foreach ($areas as $area)
                        <div class="card d-flex mb-2">
                            <div class="card-body">
                                <h2 class="small-title">{{ $area->name }}</h2>
                                <table>
                                    <tbody>
                                        @foreach ($area->subjects as $subject)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="subjects[]"
                                                            value="{{ $subject->id }}" class="form-check-input" />
                                                        <span>{{ $subject->resourceSubject->name }}</span>
                                                    </label>
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
