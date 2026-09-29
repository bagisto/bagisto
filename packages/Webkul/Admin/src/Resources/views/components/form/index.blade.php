<!--
    If a component has the `as` attribute, it indicates that it uses
    the ajaxified form or some customized slot form.
-->
@if ($attributes->has('as'))
    <v-form {{ $attributes }}>
        {{ $slot }}
    </v-form>

<!--
    Otherwise, a traditional form will be provided with a minimal
    set of configurations.
-->
@else
    @props([
        'method' => 'POST',
    ])

    {{--
        Laravel keys a nested field's errors with dots while the input carries the same path in
        brackets, so each message is seeded under both spellings for the field to find its own.
    --}}
    @php
        $method = strtoupper($method);

        $initialErrors = [];

        foreach ($errors->getMessages() as $key => $messages) {
            $segments = explode('.', $key);

            $bracketed = array_shift($segments);

            foreach ($segments as $segment) {
                $bracketed .= '['.$segment.']';
            }

            $initialErrors[$key] = $messages;

            $initialErrors[$bracketed] = $messages;
        }
    @endphp

    <v-form
        method="{{ $method === 'GET' ? 'GET' : 'POST' }}"
        :initial-errors="{{ json_encode($initialErrors) }}"
        v-slot="{ meta, errors, setValues }"
        @invalid-submit="onInvalidSubmit"
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