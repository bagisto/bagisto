@props(['label', 'control', 'error'])

<div
    {{-- Merge Class Attributes --}}
    {{ $attributes->merge(['class' => 'control-group']) }}
    :class="[errors.has('{{ $control->attributes->get('name') }}') ? 'has-error' : '']"
>
    @if (! empty($label))
        <label
            {{-- Add "for" attrbute if "id" passed else add "name" as "for" --}}
            for="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
            {{-- Merge Class Attributes --}}
            {{ $label->attributes->merge(['class' => '']) }}
            {{-- Attach Other Attributes --}}
            {{ $label->attributes }}
        >
            {{ $label }}
        </label>
    @endif

    @switch($control->attributes->get('type'))
        @case('input')
            <input
                type="text"
                name="{{ $control->attributes->get('name') }}"
                {{-- Add "id" attrbute if passed else add "name" as "id" --}}
                id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                {{-- Merge Class Attributes --}}
                {{ $control->attributes->merge(['class' => 'control']) }}
                value="{{ $control->attributes->get('value') ?? '' }}"
                {{-- Add "data-vv-as" attrbute if passed else add "$label" as "data-vv-as" --}}
                data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                {{-- Attach Other Attributes --}}
                {{ $control->attributes }}
            >

            @break
    
        @case('select')
            <select
                name="{{ $control->attributes->get('name') }}"
                {{-- Add "id" attrbute if passed else add "name" as "id" --}}
                id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                {{-- Merge Class Attributes --}}
                {{ $control->attributes->merge(['class' => 'control']) }}
                {{-- Add "data-vv-as" attrbute if passed else add "$label" as "data-vv-as" --}}
                data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                {{-- Attach Other Attributes --}}
                {{ $control->attributes }}
            >
                {{ $control }}
            </select>

            @break
    
        @case('checkbox')
            <span class="checkbox">
                <input
                    type="checkbox"
                    name="{{ $control->attributes->get('name') }}"
                    {{-- Add "id" attrbute if passed else add "name" as "id" --}}
                    id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                    value="{{ $control->attributes->get('value') ?? '' }}"
                    {{-- Add "data-vv-as" attrbute if passed else add "$label" as "data-vv-as" --}}
                    data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                    {{-- Attach Other Attributes --}}
                    {{ $control->attributes }}
                >

                <label class="checkbox-view"></label>

                {{ $control }}
            </span>
            
            @break
    
        @case('radio')
            <span class="radio">
                <input
                    type="radio"
                    name="{{ $control->attributes->get('name') }}"
                    {{-- Add "id" attrbute if passed else add "name" as "id" --}}
                    id="{{ $control->attributes->get('id') ?? $control->attributes->get('name') }}"
                    value="{{ $control->attributes->get('value') ?? '' }}"
                    {{-- Add "data-vv-as" attrbute if passed else add "$label" as "data-vv-as" --}}
                    data-vv-as="&quot;{{ $control->attributes->get('data-vv-as') ?? ($label ?? '') }}&quot;"
                    {{-- Attach Other Attributes --}}
                    {{ $control->attributes }}
                >

                <label class="radio-view"></label>

                {{ $control }}
            </span>
            
            @break
    @endswitch

    <span
        @if (! empty($error))
            {{-- Merge Class Attributes --}}
            {{ $error->attributes->merge(['class' => 'error-class']) }}
        @else
            class="error-class"
        @endif
        v-if="errors.has('{{ $control->attributes->get('name') }}')"
    >
        @{{ errors.first('{!! $control->attributes->get('name') !!}') }}
    </span>
</div>