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
            v-slot="{ field }"
            {{ $attributes }}
        >
            <input
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
                {{ $attributes->except(['value'])->merge(['class' => 'text-[14px] shadow appearance-none border rounded w-full mb-3 py-2 px-3 focus:outline-none focus:shadow-outline']) }}
            >
        </v-field>

        @break

    @case('textarea')
        <v-field
            type="{{ $type }}"
            name="{{ $name }}"
            v-slot="{ field }"
            {{ $attributes }}
        >
            <textarea
                v-bind="field"
                :class="[errors['{{ $name }}'] ? 'border border-red-500' : '']"
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

    {{--
        This type is currently in the development phase, and I will update
        it accordingly.
    --}}
    @case('image')
        <div class="flex items-center w-full gap-[30px]">
            <div class="w-[200px] h-[200px] rounded-[12px] cursor-pointer bg-[#F5F5F5]">
                <img class="" src="../images/user-placeholder.png" title="" alt="">
            </div>

            <label
                for="dropzone-file"
                class="m-0 block bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer"
            >
                Add Image <input id="dropzone-file" type="file" class="hidden">
            </label>
        </div>

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
