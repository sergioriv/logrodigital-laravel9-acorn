@php
$title = __('Areas & Subjects');
@endphp
@extends('layout',['title'=>$title])

@section('css')
@endsection

@section('js_vendor')
<script src="/js/cs/scrollspy.js"></script>
<script src="/js/vendor/sortable.min.js"></script>
@endsection


@section('js_page')
@if (NULL !== $Y->available)
<script>
    if (document.getElementById('areaGroupNull')) {
      Sortable.create(document.getElementById('areaGroupNull'), {
        animation: 200,
        group: {
          name: 'groupNull',
          put: true,
          pull: true
        }
      });
    }

    @foreach ($resourceAreas as $area)
    @if(is_null($area->specialty))
    if (document.getElementById('areaGroup{{ $area->id }}')) {
      Sortable.create(document.getElementById('areaGroup{{ $area->id }}'), {
        draggable: ".input_subject",
        animation: 200,
        group: {
          name: 'group{{ $area->id }}',
          put: true,
          pull: true
        }
      });
    }
    @endif
    @endforeach

    jQuery("#confirm_save").click(function () {
        if ( $(this).is(':checked') ) {
            $("#save_areas_subjects").prop('disabled', false);
        } else {
            $("#save_areas_subjects").prop('disabled', true);
        }
    });

    jQuery("#save_areas_subjects").click(function (e) {
        var subejcts = document.querySelectorAll(".input_subject");
        subejcts.forEach(s => {
            const value = $(s).attr('data-subject');
            const area = $(s).parent().data('area');
            $(s).children('input').attr('name', 'area-'+ area +'[]');
        });
    });
</script>
@endif
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Title and Top Buttons Start -->
            <div class="page-title-container">
                <div class="row">
                    <!-- Title Start -->
                    <div class="col-12 col-md-7">
                        <h1 class="mb-1 pb-0 display-4" id="title">{{ $title .' | ' . $Y->name }}</h1>
                    </div>
                    <!-- Title End -->

                    <!-- Top Buttons Start -->
                    <div class="col-12 col-md-5 d-flex align-items-start justify-content-end">
                        <!-- Add Areas Button Start -->
                        <a href="{{ route('resourceArea.index') }}"
                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto">
                            <span>{{ __('Areas') }}</span>
                        </a>
                        <!-- Add Areas Button End -->
                        <!-- Add Subjects Button Start -->
                        <a href="{{ route('resourceSubject.index') }}"
                            class="btn btn-outline-primary btn-icon btn-icon-start w-100 w-md-auto ms-1">
                            <span>{{ __('Subjects') }}</span>
                        </a>
                        <!-- Add Subjects Button End -->
                    </div>
                    <!-- Top Buttons End -->
                </div>
            </div>
            <!-- Title and Top Buttons End -->

            <!-- Content Start -->
            <div class="">

                @if (NULL !== $Y->available && count($resourceSubjects) > 0)
                <form method="POST" action="{{ route('subject.store') }}" class="tooltip-end-bottom" novalidate>
                    @csrf
                @endif
                    <!-- Moving Start -->
                    <section class="scroll-section">
                        @if (NULL !== $Y->available && count($resourceSubjects) > 0)
                        <div class="row">
                            <section class="col-12">
                                <div class="card mb-5 border border-pink">
                                    <div class="card-body card-areas" data-area="null">
                                        <div class="pt-1 pb-1 d-inline-flex flex-wrap gap-2 w-100 min-height-sm sortable"
                                            id="areaGroupNull" data-area="null">
                                            @foreach ($resourceSubjects as $subject)
                                            <span
                                                class="logro-tag badge bg-outline-primary hover-bg-primary text-uppercase input_subject"
                                                data-subject="{{ $subject->id }}">
                                                {!! $subject->name !!}
                                                <input readonly type="hidden" name="area-null[]" value="{{ $subject->id }}">
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        @endif
                        <div class="row">
                            @foreach ($resourceAreas as $area)
                            @if ($area->specialty)
                            @if (count($area->subjects))
                                <section class="col-sm-6 col-xxl-3">
                                    <h2 class="small-title">{{ $area->name }}</h2>
                                    <div class="card mb-5 card-areas">
                                        <div class="card-body d-inline-flex">
                                            <div
                                                class="pt-1 pb-1 d-inline-flex flex-wrap gap-2 w-100 min-height-sm">
                                                @foreach ($area->subjects as $subjectA)
                                                <span class="logro-tag badge bg-light text-alternate font-weight-bold text-uppercase disabled">
                                                    {!! $subjectA->resourceSubject->name !!}
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            @endif
                            @else
                            <section class="col-sm-6 col-xxl-3">
                                <h2 class="small-title">{{ $area->name }}</h2>
                                <div class="card mb-5 card-areas" @if(is_null($area->specialty)) data-area="{{ $area->id }}" @endif>
                                    <div class="card-body d-inline-flex">
                                        <div @if(is_null($area->specialty)) id="areaGroup{{ $area->id }}" data-area="{{ $area->id }}" @endif
                                            class="pt-1 pb-1 d-inline-flex flex-wrap gap-2 w-100 min-height-sm">
                                            @foreach ($area->subjects as $subjectA)
                                            <span class="logro-tag badge bg-light text-alternate font-weight-bold text-uppercase disabled">
                                                {!! $subjectA->resourceSubject->name !!}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </section>
                            @endif
                            @endforeach
                        </div>
                    </section>
                    <!-- Moving End -->

                @if (NULL !== $Y->available && count($resourceSubjects) > 0)
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="confirm_save" />
                        <label class="form-check-label" for="confirm_save">{{ __('This process is irreversible. Please confirm that you are sure to save.') }}</label>
                    </div>

                    <x-button type="submit" disabled id="save_areas_subjects" class="btn-primary">{{ __('Save') .' '. __('Areas & Subjects') }}</x-button>
                </form>
                @endif
                <!-- Advanced End -->

            </div>
            <!-- Content End -->
        </div>
    </div>
</div>
@endsection
