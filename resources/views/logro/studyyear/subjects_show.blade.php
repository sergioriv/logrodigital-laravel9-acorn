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

            <section class="scroll-section mb-5">
                @foreach ($areas as $area)
                <div class="card d-flex mb-2">
                    <div class="card-body">
                        <h2 class="small-title">{{ $area->name }}</h2>
                        <table class="col-12 col-xl-9">
                            <tbody>
                                @foreach ($area->subjects as $subject)
                                <tr>
                                    <td class="w-50">{{ $subject->resourceSubject->name }}</td>
                                    <td class="w-25">{{ $subject->studyYearSubject->hours_week }}
                                        @if (1 === $subject->studyYearSubject->hours_week)
                                            {{ __("hour") }}
                                        @else
                                            {{ __("hours") }}
                                        @endif
                                    </td>
                                    <td class="w-25">{{ $subject->studyYearSubject->course_load }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </section>

            <a href="{{ route('studyYear.index') }}" class="btn btn-primary">{{ __('Go back') }}</a>

        </div>
    </div>
</div>
@endsection
