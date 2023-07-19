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
            {{ $attributes->except('class') }}
        >
            <input
                type="{{ $type }}"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'w-full py-2 px-3 appearance-none border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
            >
        </v-field>

        @break

    @case('textarea')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->except('class') }}
        >
            <textarea
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'w-full mb-3 py-2 px-3 shadow appearance-none text-[14px] border rounded focus:outline-none focus:shadow-outline']) }}
            >
            </textarea>
        </v-field>

        @break

    @case('checkbox')
        <span class="">
            <input
                type="checkbox"
                name="{{ $name }}"
                {{ $attributes }}
            >

            {{ $slot }}
        </span>

        @break

    @case('radio')
        <span class="">
            <input
                type="radio"
                name="{{ $name }}"
                {{ $attributes }}
            >

            {{ $slot }}
        </span>

        @break

    @case('select')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes->except('class') }}
        >
            <select
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                {{ $attributes->except(['value'])->merge(['class' => 'custom-select inline-flex gap-x-[4px] justify-between items-center w-full py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400']) }}
            >
                {{ $slot }}
            </select>
        </v-field>

        @break

    @case('image')
        <x-admin::media
            name="{{ $name }}"
            ::class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
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

    @case('switch')
        <label class="relative inline-flex items-center cursor-pointer">
            <v-field
                name="{{ $name }}"
                v-slot="{ field }"
                {{ $attributes->except('class') }}
            >
                <input
                    type="checkbox"
                    :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                    v-bind="field"
                    {{ $attributes->except(['value'])->merge(['class' => 'sr-only peer']) }}
                >
            </v-field>

            <div class="rounded-full w-11 h-6 bg-gray-200 peer-focus:ring-blue-300 after:bg-white after:border-gray-300  dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-focus:ring-blue-800 peer dark:bg-gray-500 peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none peer-focus:ring-4 after:border after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
        </label>

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
