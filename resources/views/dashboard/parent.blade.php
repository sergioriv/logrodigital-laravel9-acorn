@extends('layout', ['title' => __('My students')])

@section('content')
<div class="container">

    <!-- Title and Top Buttons Start -->
    <div class="page-title-container">
        <div class="row">

            <!-- Title Start -->
            <div class="col-12 col-md-7 mb-2 mb-md-0">
                <h1 class="mb-1 pb-0 display-4" id="title">{{ __('My students') }}</h1>
            </div>
            <!-- Title End -->

        </div>
    </div>
    <!-- Title and Top Buttons End -->

    <!-- Students Start -->
    <section class="row g-3 row-cols-md-1 row-cols-lg-2 row-cols-xl-3 row-cols-xxl-4">
        @foreach ($myStudents as $student)
        <div class="col small-gutter-col">
            <a href="{{ route('students.show', $student->id) }}">
            <div class="card h-100 hover-border-primary border-0">
                    <div class="card-body text-center d-flex flex-column">
                        <h4 class="text-primary font-weight-bold">{{ $student->getCompleteNames() }}</h4>
                        @if ($student->enrolled)
                        <h5 class="font-weight-bold text-alternate">{{ $student->group->name }}</h5>
                        <div class="text-muted text-medium d-flex justify-content-center gap-2">
                            <aside class="text-uppercase">{{ __('headquarters') }}</aside>
                            <text>{{ $student->group->headquarters->name }}</text>
                        </div>
                        <div class="text-muted text-medium d-flex justify-content-center gap-2">
                            <aside class="text-uppercase">{{ __('study time') }}</aside>
                            <text>{{ $student->group->studyTime->name }}</text>
                        </div>
                        <div class="text-muted text-medium d-flex justify-content-center gap-2">
                            <aside class="text-uppercase">{{ __('study year') }}</aside>
                            <text>{{ $student->group->studyYear->name }}</text>
                        </div>
                        @else
                        <div><span class="badge bg-outline-danger mt-2 px-3">Sin matrícula</span></div>
                        @endif
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </section>
    <!-- Students End -->

</div>
@endsection
