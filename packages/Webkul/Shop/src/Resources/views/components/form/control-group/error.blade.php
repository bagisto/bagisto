@props([
    'controlName' => '',
])

@if (! empty($controlName))
    {{--
        These are the errors that will come from the frontend, specifically
        vee-validate, and each error will be added to its respective field.
    --}}
    <v-error-message
        name="{{ $controlName }}"
        {{ $attributes }}
        v-slot="{ message }"
    >
        <p
            class="text-red-500 text-xs italic"
            v-text="message"
        >
        </p>
    </v-error-message>

    {{--
        These are the errors that will come from the backend, and each error
        will be added to its respective field.
    --}}
    @error($controlName)
        <p class="text-red-500 text-xs italic">
            {{ $message }}
        </p>
    @enderror
@endif
