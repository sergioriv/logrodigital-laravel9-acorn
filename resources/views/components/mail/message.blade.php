@component('components.mail.layout')
{{-- Header --}}
@slot('header')
@component('components.mail.header', ['url' => config('app.url')])
{{ (new App\Http\Controllers\SchoolController)->name() }}
@endcomponent
@endslot

{{-- Body --}}
{!! $slot !!}

{{-- Footer --}}
@slot('footer')
@component('components.mail.footer')
© {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
