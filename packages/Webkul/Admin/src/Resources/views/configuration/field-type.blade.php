@php
    $value = system_config()->getConfigData($field->getNameKey(), $currentChannel->code, $currentLocale->code);
@endphp

<input
    type="hidden"
    name="keys[]"
    value="{{ json_encode($child) }}"
/>

<div class="mb-4 last:!mb-0">
    <v-configurable
        name="{{ $field->getNameField() }}"
        value="{{ $value }}"
        label="{{ trans($field->getTitle()) }}"
        info="{{ trans($field->getInfo()) }}"
        validations="{{ $field->getValidations() }}"
        is-require="{{ $field->isRequired() }}"
        depend-name="{{ $field->getDependFieldName() }}"
        src="{{ Storage::url($value) }}"
        field-data="{{ json_encode($field) }}"
        channel-count="{{ $channels->count() }}"
        current-channel="{{ $currentChannel }}"
        current-locale="{{ $currentLocale }}"
    >
        <div class="shimmer mb-1.5 h-4 w-24"></div>

        <div class="shimmer flex h-[42px] w-full rounded-md"></div>
    </v-configurable>
</div>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-configurable-template"
    >
        <x-admin::form.control-group class="last:!mb-0">
            <!-- Title of the input field -->
            <div    
                v-if="field.is_visible"
                class="flex justify-between"
            >
                <x-admin::form.control-group.label ::for="name">
                    @{{ label }} <span :class="isRequire"></span>

                    <span
                        v-if="field['channel_based'] && channelCount"
                        class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                        v-text="JSON.parse(currentChannel).name"
                    >
                    </span>
        
                    <span
                        v-if="field['locale_based']"
                        class="rounded border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                        v-text="JSON.parse(currentLocale).name"
                    >
                    </span>
                </x-admin::form.control-group.label>
            </div>
        
            <!-- Text input -->
            <template v-if="field.type == 'text' && field.is_visible">
                <x-admin::form.control-group.control
                    type="text"
                    ::id="name"
                    ::name="name"
                    ::value="value"
                    ::rules="validations"
                    ::label="label"
                />
            </template>
        
            <!-- Password input -->
            <template v-if="field.type == 'password' && field.is_visible">
                <x-admin::form.control-group.control
                    type="password"
                    ::id="name"
                    ::name="name"
                    ::value="value"
                    ::rules="validations"
                    ::label="label"
                />
            </template>
        
            <!-- Number input -->
            <template v-if="field.type == 'number' && field.is_visible">
                <x-admin::form.control-group.control
                    type="number"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::value="value"
                    ::label="label"
                    ::min="field.name == 'minimum_order_amount'"
                />
            </template>

            <!-- Color Input -->
            <template v-if="field.type == 'color' && field.is_visible">
                <v-field
                    v-slot="{ field, errors }"
                    :id="name"
                    :name="name"
                    :value="value != '' ? value : '#ffffff'"
                    :label="label"
                    :rules="validations"
                >
                    <input
                        type="color"
                        v-bind="field"
                        :class="[errors.length ? 'border border-red-500' : '']"
                        class="w-full appearance-none rounded-md border text-sm text-gray-600 transition-all hover:border-gray-400 dark:text-gray-300 dark:hover:border-gray-400"
                    />
                </v-field>
            </template>
        
            <!-- Textarea Input -->
            <template v-if="field.type == 'textarea' && field.is_visible">
                <x-admin::form.control-group.control
                    type="textarea"
                    class="text-gray-600 dark:text-gray-300"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::value="value"
                    ::label="label"
                />
            </template>

            <!-- Textarea with tinymce -->
            <template v-if="field.type == 'editor' && field.is_visible">
                <x-admin::form.control-group.control
                    type="textarea"
                    class="text-gray-600 dark:text-gray-300"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::value="value"
                    ::label="label"
                />
            </template>
        
            <!-- Select input -->
            <template v-if="field.type == 'select' && field.is_visible">
                <v-field
                    v-slot="data"
                    :id="name"
                    :name="name"
                    :rules="validations"
                    :value="value"
                    :label="label"
                >
                    <select
                        :id="name"
                        :name="name"
                        v-bind="data.field"
                        :class="[data.errors.length ? 'border border-red-500' : '']"
                        class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                    >
                        <option
                            v-for="option in field.options"
                            :value="option.value"
                            v-text="option.title"
                        >
                        </option>
                    </select>
                </v-field>
            </template>

            <!-- Multiselect Input -->
            <template v-if="field.type == 'multiselect' && field.is_visible">
                <v-field
                    v-slot="data"
                    :id="name"
                    :name="`${name}[]`"
                    :rules="validations"
                    :value="value"
                    :label="label"
                    multiple
                >
                    <select
                        :name="`${name}[]`"
                        v-bind="data.field"
                        :class="[data.errors.length ? 'border border-red-500' : '']"
                        class="custom-select w-full rounded-md border bg-white px-3 py-2.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        multiple
                    >
                        <option
                            v-for="option in field.options"
                            :value="option.value"
                            v-text="option.title"
                        >
                        </option>
                    </select>
                </v-field>
            </template>
           
            <!-- Boolean/Switch input -->
            <template v-if="field.type == 'boolean' && field.is_visible">
                <input
                    type="hidden"
                    :name="name"
                    :value="0"
                />
        
                <label class="relative inline-flex cursor-pointer items-center">
                    <input  
                        type="checkbox"
                        :name="name"
                        :value="1"
                        :id="name"
                        class="peer sr-only"
                        :checked="parseInt(value || 0)"
                    >

                    <div class="peer h-5 w-9 cursor-pointer rounded-full bg-gray-200 after:absolute after:top-0.5 after:h-4 after:w-4 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-blue-300 dark:bg-gray-800 dark:after:border-white dark:after:bg-white dark:peer-checked:bg-gray-950 after:ltr:left-0.5 peer-checked:after:ltr:translate-x-full after:rtl:right-0.5 peer-checked:after:rtl:-translate-x-full"></div>
                </label>
            </template>
        
            <template v-if="field.type == 'image' && field.is_visible">
                <div class="flex items-center justify-center">
                    <a
                        :href="src"
                        target="_blank"
                        v-if="value"
                    >
                        <img
                            :src="src"
                            :alt="name"
                            class="top-15 rounded-3 border-3 relative h-[33px] w-[33px] border-gray-500 ltr:mr-5 rtl:ml-5"
                        />
                    </a>
                    
                    <x-admin::form.control-group.control
                        type="file"
                        ::name="name"
                        ::id="name"
                        ::rules="validations"
                        ::label="label"
                    />
                </div>
        
                <template v-if="value">
                    <x-admin::form.control-group class="mt-1.5 flex w-max cursor-pointer select-none items-center gap-1.5">
                        <x-admin::form.control-group.control
                            type="checkbox"
                            ::id="`${name}[delete]`"
                            ::name="`${name}[delete]`"
                            value="1"
                            ::for="`${name}[delete]`"
                        />
        
                        <label
                            :for="`${name}[delete]`"
                            class="cursor-pointer !text-sm !font-semibold !text-gray-600 dark:!text-gray-300"
                        >
                            @lang('admin::app.configuration.index.delete')
                        </label>
                    </x-admin::form.control-group>
                </template>
            </template>

            <template v-if="field.type == 'file' && field.is_visible">
                <a
                    v-if="value"
                    :href="`{{ route('admin.configuration.download', [request()->route('slug'), request()->route('slug2'), '']) }}/${value.split('/')[1]}`"
                >
                    <div class="mb-1 inline-flex w-full max-w-max cursor-pointer appearance-none items-center justify-between gap-x-1 rounded-md border border-transparent p-1.5 text-center text-gray-600 transition-all marker:shadow hover:bg-gray-200 active:border-gray-300 dark:text-gray-300 dark:hover:bg-gray-800">
                        <i class="icon-down-stat text-2xl"></i>
                    </div>
                </a>
        
                <x-admin::form.control-group.control
                    type="file"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::label="label"
                />
        
                <template v-if="value">
                    <div class="flex cursor-pointer gap-2.5">
                        <x-admin::form.control-group.control
                            type="checkbox"
                            ::id="`${name}[delete]`"
                            ::name="`${name}[delete]`"
                            value="1"
                        />
        
                        <label
                            class="cursor-pointer"
                            ::for="`${name}[delete]`"
                        >
                            @lang('admin::app.configuration.index.delete')
                        </label>
                    </div>
                </template>
            </template>

            <template v-if="field.type == 'country' && field.is_visible">
                <v-country :selected-country="value">
                    <template v-slot:default="{ changeCountry }">
                        <x-admin::form.control-group class="flex">
                            <x-admin::form.control-group.control
                                type="select"
                                ::id="name"
                                ::name="name"
                                ::rules="validations"
                                ::value="value"
                                ::label="label"
                                @change="changeCountry($event.target.value)"
                            >
                                <option value="">
                                    @lang('admin::app.configuration.index.select-country')
                                </option>
        
                                @foreach (core()->countries() as $country)
                                    <option value="{{ $country->code }}">
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>
                    </template>
                </v-country>
            </template>
        
            <!-- State select Vue component -->
            <template v-if="field.type == 'state' && field.is_visible">
                <v-state>
                    <template v-slot:default="{ countryStates, country, haveStates, isStateComponenetLoaded }">
                        <div v-if="isStateComponenetLoaded">
                            <template v-if="haveStates()">
                                <x-admin::form.control-group class="flex">
                                    <x-admin::form.control-group.control
                                        type="select"
                                        ::id="name"
                                        ::name="name"
                                        ::rules="validations"
                                        ::value="value"
                                        ::label="label"
                                    >
                                        <option value="">
                                            @lang('admin::app.configuration.index.select-state')
                                        </option>
                                        
                                        <option value="*">
                                            *
                                        </option>
                                        
                                        <option
                                            v-for='(state, index) in countryStates[country]'
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-admin::form.control-group.control>
                                </x-admin::form.control-group>
                            </template>
        
                            <template v-else>
                                <x-admin::form.control-group class="flex">
                                    <x-admin::form.control-group.control
                                        type="text"
                                        ::id="name"
                                        ::name="name"
                                        ::rules="validations"
                                        ::value="value"
                                        ::label="label"
                                    />
                                </x-admin::form.control-group>
                            </template>
                        </div>
                    </template>
                </v-state>
            </template>
        
            <p
                v-if="field.info && field.is_visible"
                class="mt-1 block text-xs italic leading-5 text-gray-600 dark:text-gray-300"
                v-text="info"
            >
            </p>
     
            <!-- validaiton message -->
            <v-error-message
                :name="name"
                v-slot="{ message }"
            >
                <p
                    class="mt-1 text-xs italic text-red-600"
                    v-text="message"
                >
                </p>
            </v-error-message>
        </x-admin::form.control-group>
    </script>

    <script
        type="text/x-template"
        id="v-country-template"
    >
        <div>
            <slot :changeCountry="changeCountry"></slot>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-state-template"
    >
        <div>
            <slot
                :country="country"
                :country-states="countryStates"
                :have-states="haveStates"
                :is-state-componenet-loaded="isStateComponenetLoaded"
            >
            </slot>
        </div>
    </script>

    <script type="module">
        app.component('v-configurable', {
            template: '#v-configurable-template',

            props: [
                'channelCount',
                'currentChannel',
                'currentLocale',
                'dependName',
                'fieldData',
                'info',
                'isRequire',
                'label',
                'name',
                'src',
                'validations',
                'value',
            ],

            data() {
                return {
                    field: JSON.parse(this.fieldData),
                };
            },

            mounted() {
                if (! this.dependName) {
                    return;
                }

                const dependElement = document.getElementById(this.dependName);

                if (! dependElement) {
                    return;
                }

                dependElement.addEventListener('change', (event) => {
                    this.field['is_visible'] = 
                        event.target.type === 'checkbox' 
                        ? event.target.checked
                        : this.validations.split(',').slice(1).includes(event.target.value);
                });

                dependElement.dispatchEvent(new Event('change'));
            },
        });

        app.component('v-country', {
            template: '#v-country-template',

            props: ['selectedCountry'],

            data() {
                return {
                    country: this.selectedCountry,
                };
            },

            mounted() {
                this.$emitter.emit('country-changed', this.country);
            },

            methods: {
                changeCountry(selectedCountryCode) {
                    this.$emitter.emit('country-changed', selectedCountryCode);
                },
            },
        });

        app.component('v-state', {
            template: '#v-state-template',

            data() {
                return {
                    country: "",

                    isStateComponenetLoaded: false,

                    countryStates: @json(core()->groupedStatesByCountries())
                };
            },

            created() {
                this.$emitter.on('country-changed', (value) => this.country = value);

                setTimeout(() => {
                    this.isStateComponenetLoaded = true;
                }, 0);
            },

            methods: {
                haveStates() {
                    /*
                    * The double negation operator is used to convert the value to a boolean.
                    * It ensures that the final result is a boolean value,
                    * true if the array has a length greater than 0, and otherwise false.
                    */
                    return !!this.countryStates[this.country]?.length;
                },
            },
        });
    </script>
@endPushOnce