@props([
    'type' => 'text',
    'name' => '',
])

@switch($type)
    @case('hidden')
    @case('text')
    @case('email')
    @case('password')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', 'rules', 'label']) }}
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', 'rules', 'label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
            >
        </v-field>

        @break

    @case('textarea')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', 'rules', 'label']) }}
        >
            <textarea
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', 'rules', 'label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
            >
            </textarea>
        </v-field>

        @break

    @case('date')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', 'rules', 'label']) }}
        >
            <x-admin::flat-picker.date>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', 'rules', 'label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-admin::flat-picker.date>
        </v-field>

        @break

    @case('datetime')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', 'rules', 'label']) }}
        >
            <x-admin::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', 'rules', 'label'])->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
                    autocomplete="off"
                >
            </x-admin::flat-picker.datetime>
        </v-field>
        @break

    @case('select')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->only(['value', 'rules', 'label']) }}
        >
            <select
                name="{{ $name }}"
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                {{ $attributes->except(['value', 'rules', 'label'])->merge(['class' => 'custom-select flex w-full min-h-[39px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400']) }}
            >
                {{ $slot }}
            </select>
        </v-field>

        @break

    @case('multiselect')
        <v-field
            as="select"
            name="{{ $name }}"
            :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
            {{ $attributes->except(['value', 'rules', 'label'])->merge(['class' => 'flex flex-col w-full min-h-[82px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal transition-all hover:border-gray-400']) }}
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
            {{ $attributes->only(['value', 'rules', 'label']) }}
        >
            <input
                type="checkbox"
                name="{{ $name }}"
                v-bind="field"
                class="sr-only peer"
                {{ $attributes->except(['rules', 'label']) }}
            />
        </v-field>

        <label
            class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-blue-600 cursor-pointer"
            {{ $attributes->except(['value', 'rules', 'label']) }}
        >
        </label>

        @break

    @case('radio')
        <v-field
            type="radio"
            name="{{ $name }}"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['value', 'rules', 'label']) }}
        >
            <input
                type="radio"
                name="{{ $name }}"
                v-bind="field"
                class="sr-only peer"
                {{ $attributes->except(['rules', 'label']) }}
            />
        </v-field>

        <label
            class="icon-radio-normal text-[24px] peer-checked:icon-radio-selected peer-checked:text-blue-600 cursor-pointer"
            {{ $attributes->except(['value', 'rules', 'label']) }}
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
                {{ $attributes->only(['value', 'rules', 'label']) }}
            >
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="sr-only peer"
                    v-bind="field"
                    {{ $attributes->except(['value', 'rules', 'label']) }}
                />
            </v-field>

            <label
                class="rounded-full w-11 h-6 bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-blue-600 peer peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none after:border after:rounded-full after:h-5 after:w-5 after:transition-all"
                for="{{ $name }}"
            ></label>
        </label>

        @break

    @case('image')
        <x-admin::media
            name="{{ $name }}"
            ::class="[errors['{{ $name }}'] ? 'border border-red-600 hover:border-red-600' : '']"
            {{ $attributes }}
        >
        </x-admin::media>

        @break

    @case('tinymce')
        <x-admin::tinymce
            name="{{ $name }}"
            {{ $attributes }}
        >
            {{ $slot }}
        </x-admin::tinymce>

        @break

    @case('tinymce')
        <x-admin::tinymce
            name="{{ $name }}"
            {{ $attributes }}
        >
            {{ $slot }}
        </x-admin::tinymce>

        @break
        
    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
