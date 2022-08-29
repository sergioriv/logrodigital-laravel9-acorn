@if ($errors->any())
    @error('custom')
        <script>
            callNotify("fail", "{{ $message }}")
        </script>
    @else
        <script>
            @foreach ($errors->all() as $error)
                callNotify("fail", "{{ $error }}")
            @endforeach
        </script>
    @enderror
@endif


@if (Session::has('notify'))
<script>
    callNotify( "{{ Session::get('notify') }}", "{{ Session::get('title') }}")
</script>
@endif
