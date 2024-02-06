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
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'text-[14px] text-gray-600 appearance-none border rounded-md w-full py-2 px-3 transition-all hover:border-gray-400']) }}
            >
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
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'inline-flex gap-x-1 items-center justify-between text-gray-600 text-[14px] font-normal py-[0.8rem] px-3 w-full bg-white border border-gray-300 rounded-md cursor-pointer transition-all hover:border-gray-400']) }}
            >
                {{ $slot }}
            </select>
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

            <v-checkbox-handler
                :field="field"
                checked="{{ $attributes->get('checked') }}"
            >
            </v-checkbox-handler>
        </v-field>

        <label
            class="icon-checkbox-normal text-[24px] peer-checked:icon-checkbox-active peer-checked:text-blue-600  cursor-pointer"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
        </label>

        @break
@endswitch