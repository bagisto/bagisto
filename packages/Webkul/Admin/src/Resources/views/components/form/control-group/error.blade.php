@props([
    'name'        => null,
    'controlName' => '',
])

<v-error-message
    {{ $attributes }}
    name="{{ $name ?? $controlName }}"
    v-slot="{ message }"
>
    <p
        {{ $attributes->merge(['class' => 'mt-1 text-red-600 text-xs italic']) }}
        v-text="message"
    >
    </p>
</v-error-message>
