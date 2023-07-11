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
                {{ $attributes->except(['value'])->merge(['class' => 'w-full mb-3 py-2 px-3 text-[14px] shadow appearance-none border rounded focus:ring-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline']) }}
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
                {{ $attributes->except(['value'])->merge(['class' => 'w-full mb-3 py-2 px-3 text-[14px] shadow appearance-none border rounded focus:ring-gray-500 focus:border-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline']) }}
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
                {{ $attributes->except(['value'])->merge(['class' => 'block w-full py-2 px-3 custom-select shadow appearance-none bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-gray-500 focus:border-gray-500 focus:outline-none focus:shadow-outline']) }}
            >
                {{ $slot }}
            </select>
        </v-field>

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
