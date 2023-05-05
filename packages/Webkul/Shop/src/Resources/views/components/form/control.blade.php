@props(['label', 'control', 'error'])

<div
    {{ $attributes->merge(['class' => '']) }}
    :class="[errors.has('{{ $control->attributes->get('name') }}') ? 'has-error' : '']"
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

    @switch($control->attributes->get('type'))
        @case('text')
            <input
                type="text"
                name="{{ $control->attributes->get('name') }}"
                id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                {{ $control->attributes->merge(['class' => 'text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline']) }}
                value="{{ $control->attributes->get('value') ?? '' }}"
                data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                {{ $control->attributes }}
            >

            @break

        @case('email')
            <input
                type="email"
                name="{{ $control->attributes->get('name') }}"
                id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                {{ $control->attributes->merge(['class' => 'control']) }}
                value="{{ $control->attributes->get('value') ?? '' }}"
                data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                {{ $control->attributes }}
            >

            @break

        @case('password')
            <input
                type="password"
                name="{{ $control->attributes->get('name') }}"
                id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                {{ $control->attributes->merge(['class' => 'text-[14px] shadow appearance-none border rounded w-full py-2 px-3 focus:outline-none focus:shadow-outline']) }}
                value="{{ $control->attributes->get('value') ?? '' }}"
                data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                {{ $control->attributes }}
            >

            @break

        @case('select')
            <select
                name="{{ $control->attributes->get('name') }}"
                id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                {{ $control->attributes->merge(['class' => 'control']) }}
                data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                {{ $control->attributes }}
            >
                {{ $control }}
            </select>

            @break

        @case('checkbox')
            <span class="">
                <input
                    type="checkbox"
                    name="{{ $control->attributes->get('name') }}"
                    id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                    {{ $control->attributes->merge(['class' => '']) }}
                    value="{{ $control->attributes->get('value') ?? '' }}"
                    data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                    {{ $control->attributes }}
                >

                {{ $control }}
            </span>

            @break

        @case('radio')
            <span class="radio">
                <input
                    type="radio"
                    name="{{ $control->attributes->get('name') }}"
                    id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                    value="{{ $control->attributes->get('value') ?? '' }}"
                    data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                    {{ $control->attributes }}
                >

                {{ $control }}
            </span>

            @break
    @endswitch

    <span
        @if (! empty($error))
            {{ $error->attributes->merge(['class' => 'error-class']) }}
        @else
            class="error-class"
        @endif
        v-if="errors.has('{{ $control->attributes->get('name') }}')"
        v-text="errors.first('{!! $control->attributes->get('name') !!}')"
    >
    </span>
</div>
