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
                :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
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
                :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
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
                :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
            </textarea>
        </v-field>

        @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
            <x-admin::tinymce :selector="'textarea#' . $attributes->get('id')"></x-admin::tinymce>
        @endif

        @break

    @case('date')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-admin::flat-picker.date>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-admin::flat-picker.date>
        </v-field>

        @break

    @case('datetime')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-admin::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-admin::flat-picker.datetime>
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
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400']) }}
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
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'flex flex-col w-full min-h-[82px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400']) }}
            multiple
        >
            {{ $slot }}
        </v-field>

        @break

    @case('checkbox')
        <v-field
            v-slot="{ field }"
            name="{{ $name }}"
            type="checkbox"
            class="hidden"
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
            class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-blue-600 cursor-pointer"
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
            class="icon-radio-normal text-[24px] peer-checked:icon-radio-selected peer-checked:text-blue-600 cursor-pointer"
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
                class="rounded-full w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-blue-600 peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:ltr:left-[2px] after:rtl:right-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all"
                for="{{ $name }}"
            ></label>
        </label>

        @break

    @case('image')
        <x-admin::media.images
            name="{{ $name }}"
            ::class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
            {{ $attributes }}
        >
        </x-admin::media.images>

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
