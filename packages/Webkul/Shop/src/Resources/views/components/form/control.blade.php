@props(['label', 'control', 'error'])

<div
    {{ $attributes->merge(['class' => '']) }}
    {{ $attributes }}
>
    @if (! empty($label))
        <label
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
                        {{ $control->attributes->merge(['class' => '']) }}
                        {{ $control->attributes }}
                    >

                    {{ $control }}
                </span>

                @break

            @case('radio')
                <span class="">
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
                    {{ $control->attributes->merge(['class' => 'custom-select shadow appearance-none bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px]']) }}
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
