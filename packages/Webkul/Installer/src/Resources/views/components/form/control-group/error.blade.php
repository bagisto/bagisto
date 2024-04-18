@props(['controlName' => ''])

@if (! empty($controlName))
    <v-error-message
        name="{{ $controlName }}"
        {{ $attributes }}
        v-slot="{ message }"
    >
        <p
            {{ $attributes->merge(['class' => 'mt-1 text-xs italic text-red-600']) }}
            v-text="message"
        >
        </p>
    </v-error-message>
@endif
