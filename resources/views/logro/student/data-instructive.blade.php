@php
$title = __('Data') .' '. __('instructive');
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
                    <h1 class="mb-0 pb-0 display-4">{{ $title }}</h1>
                </div>
            </section>
            <!-- Title End -->

            <section class="scroll-section">

                <div class="card mb-5">
                    <div class="card-body">

                    </div>
                </div>

                <div class="card mb-5">
                    <div class="card-body">

                    </div>
                </div>
            </section>

        </div>
    </div>
</div>
@endsection
