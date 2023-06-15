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
            {{ $attributes }}
        >
            <input
                type="{{ $type }}"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'text-[14px] shadow appearance-none border rounded w-full mb-3 py-2 px-3 focus:outline-none focus:shadow-outline']) }}
            >
        </v-field>

        @break

    @case('textarea')
        <v-field
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes }}
        >
            <textarea
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'text-[14px] shadow appearance-none border rounded w-full mb-3 py-2 px-3 focus:outline-none focus:shadow-outline']) }}
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
            {{ $attributes }}
        >
            <select
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                {{ $attributes->except(['value'])->merge(['class' => 'custom-select shadow appearance-none bg-white border border-[#E9E9E9] text-[16px] rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-[14px] pr-[36px]']) }}
            >
                {{ $slot }}
            </select>
        </v-field>

        @break

    @case('image')
        <div class="flex w-full">
            <label class="flex flex-col w-[286px] h-[286px] items-center justify-center rounded-[12px] cursor-pointer bg-[#F5F5F5] hover:bg-gray-100">
                <div class="m-0 block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center">
                    @lang('shop::app.products.add-image')
                </div>

                <input
                    type="file"
                    class="hidden"
                />
            </label>
        </div>

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
