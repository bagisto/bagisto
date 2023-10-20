{{--
    If a component has the `as` attribute, it indicates that it uses
    the ajaxified form or some customized slot form.
--}}
@if ($attributes->has('as'))
    <v-form {{ $attributes }}>
        {{ $slot }}
    </v-form>

{{--
    Otherwise, a traditional form will be provided with a minimal
    set of configurations.
--}}
@else
    @props([
        'method' => 'POST',
    ])

    @php
        $method = strtoupper($method);
    @endphp

    <v-form
        method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
        {{-- :initial-errors="{{ json_encode($errors->getMessages()) }}" --}}
        v-slot="{ meta, errors, setValues }"
        {{ $attributes }}
    >
        @unless(in_array($method, ['HEAD', 'GET', 'OPTIONS']))
            @csrf
        @endunless

        @if (! in_array($method, ['GET', 'POST']))
            @method($method)
        @endif

        {{ $slot }}
    </v-form>
@endif