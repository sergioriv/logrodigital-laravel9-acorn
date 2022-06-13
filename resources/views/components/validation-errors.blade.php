@props(['errors'])

@if ($errors->any())
    <div class="text-danger">
        <h3 class="h4 text-danger">
            {{ __('Whoops! Something went wrong.') }}
        </h3>
        <hr>
        <ul class="mb-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
