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
            class="text-red-500 text-xs italic absolute"
            v-text="message"
        >
        </p>
    </v-error-message>
@endif
