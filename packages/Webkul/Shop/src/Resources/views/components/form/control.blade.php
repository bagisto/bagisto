@props(['label', 'control', 'error'])

<div
    {{ $attributes->merge(['class' => '']) }}
    {{ $attributes }}
>
    @if (! empty($label))
        <label
            for="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
            {{ $label->attributes->merge(['class' => 'block text-[16px] mb-[15px] mt-[30px]']) }}
            {{ $label->attributes }}
        >
            {{ $label }}
        </label>
    @endif

    @if (! empty($control))
        @switch($control->attributes->get('type'))
            @case('text')
            @case('email')
            @case('password')
                <v-field
                    type="{{ $control->attributes->get('type') }}"
                    {{ $control->attributes->merge(['class' => 'text-[14px] shadow appearance-none border rounded w-full mb-3 py-2 px-3 focus:outline-none focus:shadow-outline']) }}
                    {{ $control->attributes }}
                    :class="[errors['{{ $control->attributes->get('name') }}'] ? 'border border-red-500' : '']"
                ></v-field>

                @break

            @case('checkbox')
                <span class="">
                    <input
                        type="checkbox"
                        name="{{ $control->attributes->get('name') }}"
                        id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                        {{ $control->attributes->merge(['class' => '']) }}
                        value="{{ $control->attributes->get('value') ?? '' }}"
                        {{ $control->attributes }}
                    >

                    {{ $control }}
                </span>

                @break

            @case('radio')
                <span class="radio">
                    <input
                        type="radio"
                        {{ $control->attributes->merge(['class' => '']) }}
                        {{ $control->attributes }}
                    >

                    {{ $control }}
                </span>

                @break

            @case('select')
                <select
                    {{ $control->attributes->merge(['class' => '']) }}
                    {{ $control->attributes }}
                >
                    {{ $control }}
                </select>

                @break
        @endswitch

        <v-error-message name="{{ $control->attributes->get('name') }}" v-slot="{ message }">
            <p
                class="text-red-500 text-xs italic"
                v-text="message"
            >
            </p>
        </v-error-message>
    @endif
</div>
