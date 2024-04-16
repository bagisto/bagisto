@props([
    'name' => null,
    'controlName' => null,
])

<v-error-message
    {{ $attributes }}
    name="{{ $name ?? $controlName }}"
    v-slot="{ message }"
>
    <p
        {{ $attributes->merge(['class' => 'text-xs italic text-red-500']) }}
        v-text="message"
    >
    </p>
</v-error-message>
