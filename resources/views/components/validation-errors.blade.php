@props(['errors', 'message' => null])

@if ($errors->any())
    <div class="text-danger">
        <h3 class="h4 text-danger">
            {{ __('Whoops! Something went wrong.') }}
        </h3>
        <hr>
        @if ($message)
            <ul class="mb-4">
                <li>{{ $message }}</li>
            </ul>
        @else
        <ul class="mb-4">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
    </div>
@endif
