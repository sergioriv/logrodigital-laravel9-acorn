@extends('layout', ['title' => $title])

@section('css')
@endsection

@section('js_vendor')
@endsection

@section('js_page')
@endsection

@section('content')
    <div class="container">

        <div class="card">
            <div class="card-body text-center">

                <div class="display-1 mb-3">
                    {{ $title }}
                </div>

                <div class="display-5 mb-2">{{ __('Email') }}: {{ $email }}</div>
                <div class="display-5">{{ __('Temporary password') }}: <div class="font-weight-bold d-inline-block readable-text">{{ $password}}</div></div>

                <div class="mt-5">
                    <a href="{{ url()->previous() }}" class="btn btn-primary">Crear nuevo</a>
                    <a href="{{ $redirect['action'] }}" class="btn btn-outline-alternate ms-2">{{ $redirect['title'] }}</a>
                </div>

            </div>
        </div>


    </div>
@endsection
