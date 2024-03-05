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
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800']) }}
            />
        </v-field>

        @break

    @case('price')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <div
                class="flex items-center w-full border rounded-md overflow-hidden text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus-within:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
            >
                @if (isset($currency))
                    <span {{ $currency->attributes->merge(['class' => 'ltr:pl-4 rtl:pr-4 py-2.5 text-gray-500']) }}>
                        {{ $currency }}
                    </span>
                @else
                    <span class="ltr:pl-4 rtl:pr-4 py-2.5 text-gray-500">
                        {{ core()->currencySymbol(core()->getBaseCurrencyCode()) }}
                    </span>
                @endif

                <input
                    type="text"
                    name="{{ $name }}"
                    v-bind="field"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full p-2.5 text-sm text-gray-600 dark:text-gray-300  dark:bg-gray-900']) }}
                />
            </div>
        </v-field>

        @break

    @case('file')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors, handleChange, handleBlur }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', ':rules', 'label', ':label']) }}
        >
            <input
                type="{{ $type }}"
                name="{{ $name }}"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 dark:file:bg-gray-800 dark:file:dark:text-white dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800']) }}
                @change="handleChange"
                @blur="handleBlur"
            />
        </v-field>

        @break

    @case('color')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->except('class') }}
        >
            <input
                type="{{ $type }}"
                :class="[errors.length ? 'border border-red-500' : '']"
                v-bind="field"
                {{ $attributes->except(['value'])->merge(['class' => 'w-full appearance-none border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400']) }}
            >
        </v-field>
        @break

    @case('textarea')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <textarea
                type="{{ $type }}"
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800']) }}
            >
            </textarea>

            @if ($attributes->get('tinymce', false) || $attributes->get(':tinymce', false))
                <x-admin::tinymce 
                    :selector="'textarea#' . $attributes->get('id')"
                    :prompt="stripcslashes($attributes->get('prompt', ''))"
                    ::field="field"
                >
                </x-admin::tinymce>
            @endif
        </v-field>

        @break

    @case('date')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-admin::flat-picker.date>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800']) }}
                    autocomplete="off"
                />
            </x-admin::flat-picker.date>
        </v-field>

        @break

    @case('datetime')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <x-admin::flat-picker.datetime>
                <input
                    name="{{ $name }}"
                    v-bind="field"
                    :class="[errors.length ? 'border !border-red-600 hover:border-red-600' : '']"
                    {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'w-full py-2.5 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800']) }}
                    autocomplete="off"
                >
            </x-admin::flat-picker.datetime>
        </v-field>
        @break

    @case('select')
        <v-field
            name="{{ $name }}"
            v-slot="{ field, errors }"
            {{ $attributes->only(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label']) }}
        >
            <select
                name="{{ $name }}"
                v-bind="field"
                :class="[errors.length ? 'border border-red-500' : '']"
                {{ $attributes->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])->merge(['class' => 'custom-select w-full py-2.5 px-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400']) }}
            >
                {{ $slot }}
            </select>
        </v-field>

        @break

    @case('multiselect')
        <v-field
            as="select"
            name="{{ $name }}"
            :class="[errors && errors['{{ $name }}'] ? 'border !border-red-600 hover:border-red-600' : '']"
            {{ $attributes->except([])->merge(['class' => 'flex flex-col w-full py-2.5 px-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400']) }}
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

            <v-checkbox-handler
                :field="field"
                checked="{{ $attributes->get('checked') }}"
            >
            </v-checkbox-handler>
        </v-field>

        <label
             {{ 
                $attributes
                    ->except(['value', ':value', 'v-model', 'rules', ':rules', 'label', ':label'])
                    ->merge(['class' => 'icon-uncheckbox text-2xl peer-checked:icon-checked peer-checked:text-blue-600'])
                    ->merge(['class' => $attributes->get('disabled') ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer'])
            }}
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
            class="icon-radio-normal text-2xl peer-checked:icon-radio-selected peer-checked:text-blue-600 cursor-pointer"
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
                
                <v-checkbox-handler
                    class="hidden"
                    :field="field"
                    checked="{{ $attributes->get('checked') }}"
                >
                </v-checkbox-handler>
            </v-field>

            <label
                class="rounded-full w-9 h-5 bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white dark:after:bg-white after:border-gray-300 dark:after:border-white peer-checked:bg-blue-600 dark:peer-checked:bg-gray-950 peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-0.5 after:ltr:left-0.5 after:rtl:right-0.5 peer-focus:outline-none after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:bg-gray-800"
                for="{{ $name }}"
            ></label>
        </label>

        @break

    @case('image')
        <x-admin::media.images
            name="{{ $name }}"
            ::class="[errors && errors['{{ $name }}'] ? 'border !border-red-600 hover:border-red-600' : '']"
            {{ $attributes }}
        />

        @break

    @case('custom')
        <v-field {{ $attributes }}>
            {{ $slot }}
        </v-field>
@endswitch

@pushOnce('scripts')
    <script type="text/x-template" id="v-checkbox-handler-template">
    </script>

    <script type="module">
        app.component('v-checkbox-handler', {
            template: '#v-checkbox-handler-template',

            props: ['field', 'checked'],

            mounted() {
                if (this.checked == '') {
                    return;
                }

                this.field.checked = true;

                this.field.onChange();
            },
        });
    </script>
@endpushOnce