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
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-3 px-5 shadow border rounded-lg text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
        </v-field>

        @break

    @case('file')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-3 px-5 shadow border rounded-lg text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
        </v-field>

        @break

    @case('color')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->except('class') }}
        >
            <input
                type="{{ $type }}"
                :class="[errors.length ? 'border !border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'w-full appearance-none border rounded-lg-md text-sm text-gray-600 transition-all hover:border-gray-400']) }}
            >
        </v-field>
        @break

    @case('textarea')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <textarea
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-3 px-5 shadow border rounded-lg text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
            </textarea>
        </v-field>

        @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
            <x-shop::tinymce :selector="'textarea#' . $attributes->get('id')" />
        @endif

        @break

    @case('date')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-shop::flat-picker.date>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-3 px-5 shadow border rounded-lg text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.date>
        </v-field>

        @break

    @case('datetime')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-shop::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-3 px-5 shadow border rounded-lg text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.datetime>
        </v-field>
        @break

    @case('select')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <select
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'custom-select w-full mb-3 py-3 px-5 shadow bg-white border border-[#E9E9E9] rounded-lg text-sm text-gray-600 transition-all hover:border-gray-400 focus-visible:outline-none']) }}
            >
                {{ $slot }}
            </select>
        </v-field>

        @break

    @case('multiselect')
        <v-field
            as="select"
            name="{{ $name }}"
            :class="[errors && errors['{{ $name }}']  ? 'border !border-red-500' : '']"
            {{ $attributes->except([])->merge(['class' => 'flex flex-col w-full min-h-[82px] py-3 px-5 bg-white border border-[#E9E9E9] rounded-lg-md text-sm text-gray-600 font-normal transition-all hover:border-gray-400']) }}
            multiple
        >
            {{ $slot }}
        </v-field>

        @break

    @case('checkbox')
        <v-field
            type="checkbox"
            name="{{ $name }}"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="checkbox"
                name="{{ $name }}"
                v-bind="field"
                class="sr-only peer"
                {{ $attributes->except(['rules', 'label', ':label']) }}
            />
        </v-field>

        <label
            class="icon-uncheckbox text-2xl peer-checked:icon-checked peer-checked:text-navyBlue cursor-pointer"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
        </label>

        @break

    @case('radio')
        <v-field
            type="radio"
            name="{{ $name }}"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="radio"
                name="{{ $name }}"
                v-bind="field"
                class="sr-only peer"
                {{ $attributes->except(['rules', 'label', ':label']) }}
            />
        </v-field>

        <label
            class="icon-radio-normal text-2xl peer-checked:icon-radio-selected peer-checked:text-navyBlue cursor-pointer"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
        </label>

        @break

    @case('switch')
        <label class="relative inline-flex items-center cursor-pointer">
            <v-field
                type="checkbox"
                name="{{ $name }}"
                class="hidden"
                v-slot="{ field }"
                {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            >
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="sr-only peer"
                    v-bind="field"
                    {{ $attributes->except(['v-model', 'rules', ':rules', 'label', ':label']) }}
                />
            </v-field>

            <label
                class="rounded-lg-full w-9 h-5 bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-0.5 after:left-0.5 peer-focus:outline-none after:border after:rounded-lg-full after:h-4 after:w-4 after:transition-all"
                for="{{ $name }}"
            ></label>
        </label>

        @break

    @case('image')
        <x-shop::media
            name="{{ $name }}"
            ::class="[errors && errors['{{ $name }}'] ? 'border !border-red-500' : '']"
            {{ $attributes }}
        />

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
