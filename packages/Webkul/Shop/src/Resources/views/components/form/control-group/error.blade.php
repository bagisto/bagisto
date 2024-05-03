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
        {{ $attributes->merge(['class' => 'text-red-500 text-xs italic']) }}
        v-text="message"
    >
    </p>
</v-error-message>
