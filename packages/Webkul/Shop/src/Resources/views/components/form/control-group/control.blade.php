@props([
    'type' => 'text',
    'name' => '',
])

@switch($type)
    @case('hidden')
    @case('text')
    @case('email')
    @case('password')
    @case('number')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
            >
        </v-field>
        @break

    @case('file')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
            >
        </v-field>
        @break

    @case('color')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->except('class') }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                :class="[errors.length ? 'border !border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'rounded-lg-md w-full appearance-none border text-base text-gray-600 transition-all hover:border-gray-400 ']) }}
            >
        </v-field>
        @break

    @case('textarea')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <textarea
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
            </textarea>

            @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
                <x-shop::tinymce 
                    :selector="'textarea#' . $attributes->get('id')"
                    :prompt="stripcslashes($attributes->get('prompt', ''))"
                    ::field="field"
                >
                </x-shop::tinymce>
            @endif
        </v-field>
        @break

    @case('date')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <x-shop::flat-picker.date>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base text-gray-600  transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.date>
        </v-field>
        @break

    @case('datetime')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <x-shop::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base text-gray-600  transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.datetime>
        </v-field>
        @break

    @case('select')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <select
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'custom-select mb-1.5 w-full rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-gray-600  transition-all hover:border-gray-400 focus-visible:outline-none max-md:py-2 max-sm:px-4 max-sm:text-sm']) }}
            >
                {{ $slot }}
            </select>
        </v-field>
        @break

    @case('multiselect')
        <v-field
            as="select"
            v-slot="{ value }"
            :class="[errors && errors['{{ $name }}'] ? 'border !border-red-500' : '']"
            {{ $attributes->except([])->merge(['class' => 'mb-1.5 w-full rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-gray-600  transition-all hover:border-gray-400 focus-visible:outline-none max-md:py-2 max-sm:px-4 max-sm:text-sm']) }}
            name="{{ $name }}"
            multiple
        >
            {{ $slot }}
        </v-field>
        @break

    @case('checkbox')
        <v-field
            type="checkbox"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
            name="{{ $name }}"
        >
            <input
                type="checkbox"
                v-bind="field"
                class="peer sr-only"
                {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
                name="{{ $name }}"
            />
        </v-field>

        <label
            class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl peer-checked:text-navyBlue"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
        </label>
        @break

    @case('radio')
        <v-field
            type="radio"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
            name="{{ $name }}"
        >
            <input
                type="radio"
                name="{{ $name }}"
                v-bind="field"
                class="peer sr-only"
                {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
            />
        </v-field>

        <label
            class="icon-radio-unselect peer-checked:icon-radio-select cursor-pointer text-2xl peer-checked:text-navyBlue"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
        </label>
        @break

    @case('switch')
        <label class="relative inline-flex cursor-pointer items-center">
            <v-field
                type="checkbox"
                class="hidden"
                v-slot="{ field }"
                {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
                name="{{ $name }}"
            >
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="peer sr-only"
                    v-bind="field"
                    {{ $attributes->except(['v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
                />
            </v-field>

            <label
                class="rounded-lg-full after:rounded-lg-full peer h-5 w-9 cursor-pointer bg-gray-200 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-navyBlue peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-blue-300"
                for="{{ $name }}"
            ></label>
        </label>
        @break

    @case('image')
        <x-shop::media
            ::class="[errors && errors['{{ $name }}'] ? 'border !border-red-500' : '']"
            {{ $attributes }}
            name="{{ $name }}"
        />
        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
