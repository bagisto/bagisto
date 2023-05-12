@props([
    'method' => 'POST',
])

@php
    $method = strtoupper($method);
@endphp

<v-form
    method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
    {{ $attributes }}
    v-slot="{ meta, errors }"
>
    @unless(in_array($method, ['HEAD', 'GET', 'OPTIONS']))
        @csrf
    @endunless

    @if (! in_array($method, ['GET', 'POST']))
        @method($method)
    @endif

    {{ $slot }}
</v-form>
