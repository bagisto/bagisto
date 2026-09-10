@props([
    'type' => 'text',
    'name' => '',
])

@switch($type)
    @case('hidden')
    @case('text')
    @case('email')
    @case('number')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
            >
        </v-field>
        @break

    @case('password')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <v-password-visibility v-slot="{ isVisible, toggle }">
                <div class="relative">
                    <input
                        :type="isVisible ? 'text' : 'password'"
                        name="{{ $name }}"
                        v-bind="field"
                        :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                        {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm ltr:!pr-12 rtl:!pl-12 [&::-ms-reveal]:hidden']) }}
                    >

                    <button
                        type="button"
                        class="absolute bottom-1.5 top-0 flex items-center px-4 text-zinc-500 transition-colors hover:text-zinc-700 ltr:right-0 rtl:left-0"
                        aria-label="@lang('shop::app.components.form.control-group.control.show-password')"
                        :aria-pressed="isVisible"
                        @click="toggle"
                    >
                        <svg
                            v-if="isVisible"
                            class="h-5 w-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                            />
                        </svg>

                        <svg
                            v-else
                            class="h-5 w-5"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                            />

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                            />
                        </svg>
                    </button>
                </div>
            </v-password-visibility>
        </v-field>

        @pushOnce('scripts')
            <script
                type="text/x-template"
                id="v-password-visibility-template"
            >
                <slot
                    :is-visible="isVisible"
                    :toggle="toggle"
                ></slot>
            </script>

            <script type="module">
                app.component('v-password-visibility', {
                    template: '#v-password-visibility-template',

                    data() {
                        return {
                            isVisible: false,
                        };
                    },

                    methods: {
                        toggle() {
                            this.isVisible = ! this.isVisible;
                        },
                    },
                });
            </script>
        @endPushOnce
        @break

    @case('file')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
            >
        </v-field>
        @break

    @case('color')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->except('class') }}
            name="{{ $name }}"
        >
            <input
                type="{{ $type }}"
                :class="[errors.length ? 'border !border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'rounded-lg-md w-full appearance-none border text-base text-gray-600 transition-all hover:border-gray-400']) }}
            >
        </v-field>
        @break

    @case('textarea')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <textarea
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base font-normal text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400']) }}
            >
            </textarea>

            @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
                <x-shop::tinymce
                    :selector="'textarea#' . $attributes->get('id')"
                    :prompt="stripcslashes($attributes->get('prompt', ''))"
                    ::field="field"
                >
                </x-shop::tinymce>
            @endif
        </v-field>
        @break

    @case('date')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <x-shop::flat-picker.date {{ $attributes }}>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.date>
        </v-field>
        @break

    @case('datetime')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <x-shop::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-500 hover:border-red-500' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'mb-1.5 w-full rounded-lg border px-5 py-3 text-base text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 max-sm:px-4 max-md:py-2 max-sm:text-sm']) }}
                    autocomplete="off"
                >
            </x-shop::flat-picker.datetime>
        </v-field>
        @break

    @case('select')
        <v-field
            v-slot="{ field, errors }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
            name="{{ $name }}"
        >
            <select
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'custom-select mb-1.5 w-full rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-gray-600 transition-all hover:border-gray-400 focus-visible:outline-none max-md:py-2 max-sm:px-4 max-sm:text-sm']) }}
            >
                {{ $slot }}
            </select>
        </v-field>
        @break

    @case('multiselect')
        <v-field
            as="select"
            v-slot="{ value }"
            :class="[errors && errors['{{ $name }}'] ? 'border !border-red-500' : '']"
            {{ $attributes->except([])->merge(['class' => 'mb-1.5 w-full rounded-lg border border-zinc-200 bg-white px-5 py-3 text-base text-gray-600 transition-all hover:border-gray-400 focus-visible:outline-none max-md:py-2 max-sm:px-4 max-sm:text-sm']) }}
            name="{{ $name }}"
            multiple
        >
            {{ $slot }}
        </v-field>
        @break

    @case('checkbox')
        <v-field
            type="checkbox"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
            name="{{ $name }}"
        >
            <input
                type="checkbox"
                v-bind="field"
                class="peer sr-only"
                {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
                name="{{ $name }}"
            />
        </v-field>

        <label
            class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl peer-checked:text-navyBlue"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
        </label>
        @break

    @case('radio')
        <v-field
            type="radio"
            class="hidden"
            v-slot="{ field }"
            {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
            name="{{ $name }}"
        >
            <input
                type="radio"
                name="{{ $name }}"
                v-bind="field"
                class="peer sr-only"
                {{ $attributes->except(['rules', 'label', ':label', 'key', ':key']) }}
            />
        </v-field>

        <label
            class="icon-radio-unselect peer-checked:icon-radio-select cursor-pointer text-2xl peer-checked:text-navyBlue"
            {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
        >
        </label>
        @break

    @case('switch')
        <label class="relative inline-flex cursor-pointer items-center">
            <v-field
                type="checkbox"
                class="hidden"
                v-slot="{ field }"
                {{ $attributes->only(['name', ':name', 'value', ':value', 'v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
                name="{{ $name }}"
            >
                <input
                    type="checkbox"
                    name="{{ $name }}"
                    id="{{ $name }}"
                    class="peer sr-only"
                    v-bind="field"
                    {{ $attributes->except(['v-model', 'rules', ':rules', 'label', ':label', 'key', ':key']) }}
                />
            </v-field>

            <label
                class="rounded-lg-full after:rounded-lg-full peer h-5 w-9 cursor-pointer bg-gray-200 after:absolute after:left-0.5 after:top-0.5 after:h-4 after:w-4 after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-navyBlue peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-blue-300"
                for="{{ $name }}"
            ></label>
        </label>
        @break

    @case('image')
        <x-shop::media
            ::class="[errors && errors['{{ $name }}'] ? 'border !border-red-500' : '']"
            {{ $attributes }}
            name="{{ $name }}"
        />
        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch
