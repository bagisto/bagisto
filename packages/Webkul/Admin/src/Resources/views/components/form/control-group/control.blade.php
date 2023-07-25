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
            type="{{ $type }}"
            name="{{ $name }}"
            :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
            {{ $attributes->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 appearance-none border rounded-[6px] text-[14px] text-gray-600 transition-all hover:border-gray-400']) }}
            {{ $attributes->except('class') }}
        >
        </v-field>

        @break

    @case('textarea')
        <v-field
            type="textarea"
            name="{{ $name }}"
            :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
            {{ $attributes->merge(['class' => 'flex w-full min-h-[39px] py-2 px-3 shadow appearance-none text-[14px] border rounded focus:outline-none focus:shadow-outline']) }}
        >
        </v-field>

        @break

    @case('select')
        <v-field
            as="select"
            name="{{ $name }}"
            :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
            {{ $attributes->except(['value'])->merge(['class' => 'custom-select inline-flex gap-x-[4px] justify-between items-center flex w-full min-h-[39px] py-[6px] px-[12px] bg-white border border-gray-300 rounded-[6px] text-[14px] text-gray-600 font-normal cursor-pointer marker:shadow appearance-none focus:ring-2 focus:outline-none focus:ring-black transition-all hover:border-gray-400']) }}
            {{ $attributes->except('class') }}
        >
            {{ $slot }}
        </v-field>

        @break

    @case('checkbox')
        <v-field
            type="checkbox"
            name="{{ $name }}"
            id="{{ $name }}"
            value="1"
            :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
            {{ $attributes->merge(['class' => 'hidden peer']) }}
        >
        </v-field>

        <label
            class="icon-uncheckbox text-[24px] peer-checked:icon-checked peer-checked:text-blue-600 cursor-pointer"
            for="{{ $name }}"
            {{ $attributes }}
        >
        </label>

        @break

    @case('radio')
        <v-field
            type="radio"
            name="{{ $name }}"
            id="{{ $name }}"
            value="1"
            :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
            {{ $attributes->merge(['class' => 'hidden peer']) }}
        >
        </v-field>

        <label
            class="icon-radio-normal text-[24px] peer-checked:icon-radio-selected peer-checked:text-blue-600 cursor-pointer"
            for="{{ $name }}"
            {{ $attributes }}
        >
        </label>

        @break
    
    @case('switch')
        <label class="relative inline-flex items-center cursor-pointer">
            <v-field
                type="checkbox"
                name="{{ $name }}"
                value="1"
                id="{{ $name }}"
                :class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
                {{ $attributes->get('checked') ? 'checked' : '' }}
                {{ $attributes->merge(['class' => 'sr-only peer']) }}
            >
            </v-field>

            <label
                class="rounded-full w-11 h-6 bg-gray-200 peer-focus:ring-blue-300 after:bg-white after:border-gray-300  dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-focus:ring-blue-800 peer dark:bg-gray-500 peer-checked:after:border-white peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] peer-focus:outline-none peer-focus:ring-4 after:border after:rounded-full after:h-5 after:w-5 after:transition-all cursor-pointer"
                for="{{ $name }}"
            ></label>
        </label>

        @break

    @case('image')
        <x-admin::media
            name="{{ $name }}"
            ::class="[errors['{{ $name }}'] ? 'border border-red-500 hover:border-red-500' : '']"
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
