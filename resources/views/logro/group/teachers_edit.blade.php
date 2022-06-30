@php
$title = $group->name;
@endphp
@extends('layout',['title'=>$title])

@section('css')
<link rel="stylesheet" href="/css/vendor/select2.min.css" />
<link rel="stylesheet" href="/css/vendor/select2-bootstrap4.min.css" />
@endsection

@section('js_vendor')
<script src="/js/vendor/select2.full.min.js"></script>
@endsection

@section('js_page')
<script>
    jQuery('.logro-select2').select2({minimumResultsForSearch: Infinity});
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <!-- Title Start -->
            <section class="scroll-section" id="title">
                <div class="page-title-container">
                    <h1 class="mb-0 pb-0 display-4">{{ __('Group') .' | '. $title .' | '. __("Edit") }}</h1>
                </div>
            </section>
            <!-- Title End -->


            <section class="scroll-section">

                <form method="POST" action="{{ route('group.teachers.update', $group) }}" novalidate>
                    @csrf
                    @method('PUT')

                    @foreach ($areas as $area)
                    <div class="card d-flex mb-2">
                        <div class="card-body">
                            <h2 class="small-title">{{ $area->name }}</h2>
                            <table class="table table-striped">
                                <tbody>
                                    @foreach ($area->subjects as $subject)
                                    @php
                                    $selected = 'null~'. $subject->id;
                                    @endphp
                                    <tr>
                                        <td scope="row" class="col-6">{{ $subject->resourceSubject->name
                                            }}</td>
                                        <td>
                                            <div class="w-100">
                                                <select name="teachers[]" data-placeholder="{{ __(" Choose") .' '. __("Teacher") }}" class="logro-select2">
                                            <option label="&nbsp;"></option>
                                            @foreach ($teachers as $teacher)

                                            @foreach ($subject->teacherSubjectGroups as $teacher_subject)
                                            @if ($loop->first)
                                                @if ($teacher_subject->teacher->id === $teacher->id)
                                                @php
                                                    $selected = $teacher_subject->id .'~'. $subject->id
                                                @endphp
                                                @break
                                                @endif
                                            @endif
                                            @endforeach

                                            <option
                                                @foreach ($subject->teacherSubjectGroups as $teacher_subject)
                                                @if ($loop->first)

                                                    @if ($teacher_subject->teacher->id === $teacher->id)
                                                        selected
                                                    @endif
                                                @endif
                                                @endforeach
                                                value="{{ $selected .'~'. $teacher->id }}"
                                                    >
                                                    {{ $teacher->getFullName() }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
            </section>

            <x-button type="submit" class="btn-primary">{{ __("Save") .' '. __("Teachers") }}</x-button>

        </div>
    </div>
</div>
@endsection
