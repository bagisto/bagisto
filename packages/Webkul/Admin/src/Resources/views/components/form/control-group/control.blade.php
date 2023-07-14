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
                {{ $attributes->except(['value'])->merge(['class' => 'custom-select w-full py-2 px-3 appearance-none border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
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
        
    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
