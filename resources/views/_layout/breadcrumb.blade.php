@isset($breadcrumbs)
    <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">
        <ul class="breadcrumb pt-0">
            @foreach ($breadcrumbs as $key => $value)
                <li class="breadcrumb-item"><a href="{{ url($key) }}">{{$value}}</a></li>
            @endforeach
        </ul>
    </nav>
@endisset
