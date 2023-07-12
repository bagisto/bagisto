@props([
    'controlName' => '',
])

@if (! empty($controlName))
    <v-error-message
        name="{{ $controlName }}"
        {{ $attributes }}
        v-slot="{ message }"
    >
        <p
            {{ $attributes->merge(['class' => 'text-red-500 text-xs italic']) }}
            v-text="message"
        >
        </p>
    </v-error-message>
@endif
