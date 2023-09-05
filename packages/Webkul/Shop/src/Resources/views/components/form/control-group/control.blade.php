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
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-2 px-3 shadow border rounded text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
        </v-field>

        @break

    @case('file')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-2 px-3 shadow border rounded text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
        </v-field>

        @break

    @case('color')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->except('class') }}
        >
            <input
                type="{{ $type }}"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'w-full appearance-none border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
            >
        </v-field>
        @break

    @case('textarea')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <textarea
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-2 px-3 shadow border rounded text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
            </textarea>
        </v-field>

        @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
            <x-shop::tinymce :selector="'textarea#' . $attributes->get('id')"></x-shop::tinymce>
        @endif

        @break

    @case('date')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-shop::flat-picker.date>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-2 px-3 shadow border rounded text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.date>
        </v-field>

        @break

    @case('datetime')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-shop::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full mb-3 py-2 px-3 shadow border rounded text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.datetime>
        </v-field>
        @break

    @case('select')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <select
                name="{{ $name }}"
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'custom-select block w-full py-2 px-3 shadow bg-white border border-[#E9E9E9] rounded-lg text-[16px] transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
                <option value="" selected disabled>Select</option>
                {{ $slot }}
            </select>
        </v-field>

        @break

    @case('multiselect')
        <v-field
            as="select"
            name="{{ $name }}"
            :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex flex-col w-full min-h-[82px] py-2 px-3 bg-white border border-[#E9E9E9] rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400']) }}
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
            class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-navyBlue cursor-pointer"
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
            class="icon-radio-normal text-[24px] peer-checked:icon-radio-selected peer-checked:text-navyBlue cursor-pointer"
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
                class="rounded-full w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
                for="{{ $name }}"
            ></label>
        </label>

        @break

    @case('image')
        <x-shop::media
            name="{{ $name }}"
            ::class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
            {{ $attributes }}
        >
        </x-shop::media>

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
