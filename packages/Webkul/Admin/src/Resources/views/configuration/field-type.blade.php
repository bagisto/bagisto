@php
    $value = system_config()->getConfigData($field->getNameKey(), $currentChannel->code, $currentLocale->code);

    $holdsFile = in_array($field->getType(), ['image', 'file'])
        && is_string($value)
        && $value !== '';

    $source = $holdsFile ? Storage::url($value) : '';
@endphp

<input
    type="hidden"
    name="keys[]"
    value="{{ json_encode($child) }}"
/>

<div class="mb-4 last:mb-0!">
    <v-configurable
        name="{{ $field->getNameField() }}"
        value="{{ $value }}"
        label="{{ trans($field->getTitle()) }}"
        info="{{ trans($field->getInfo()) }}"
        validations="{{ $field->getValidations() }}"
        is-require="{{ $field->isRequired() }}"
        depend-name="{{ $field->getDependFieldName() }}"
        depend-value="{{ $field->getDependFieldValue() }}"
        placeholder="{{ $field->getPlaceholder() }}"
        src="{{ $source }}"
        field-data="{{ json_encode($field) }}"
        channel-count="{{ $channels->count() }}"
        current-channel="{{ $currentChannel }}"
        current-locale="{{ $currentLocale }}"
    >
        <div class="shimmer mb-1.5 h-4 w-24"></div>

        @if (in_array($field->getType(), ['image', 'file']))
            <div class="shimmer h-30 w-30 rounded-sm"></div>
        @else
            <div class="shimmer flex h-10.5 w-full rounded-md"></div>
        @endif
    </v-configurable>
</div>

@once
    <!-- Loads the advanced select controls the fields render with, without rendering one. -->
    <x-admin::form.control-group.advance.select
        name="configuration-select"
        v-if="false"
    />

    <x-admin::form.control-group.advance.multiselect
        name="configuration-multiselect"
        v-if="false"
    />
@endonce

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-configurable-template"
    >
        <x-admin::form.control-group class="last:mb-0!">
            <!-- Field Title -->
            <div
                class="flex justify-between"
                v-if="field.is_visible"
            >
                <x-admin::form.control-group.label ::for="name">
                    @{{ label }} <span :class="isRequire"></span>

                    <span
                        v-if="field['channel_based'] && channelCount"
                        class="rounded-sm border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                        v-text="JSON.parse(currentChannel).name"
                    >
                    </span>

                    <span
                        v-if="field['locale_based']"
                        class="rounded-sm border border-gray-200 bg-gray-100 px-1 py-0.5 text-[10px] font-semibold leading-normal text-gray-600"
                        v-text="JSON.parse(currentLocale).name"
                    >
                    </span>
                </x-admin::form.control-group.label>
            </div>

            <!-- Text Input -->
            <template v-if="field.type == 'text' && field.is_visible">
                <x-admin::form.control-group.control
                    type="text"
                    ::id="name"
                    ::name="name"
                    ::value="value"
                    ::rules="validations"
                    ::label="label"
                    ::placeholder="placeholder"
                />
            </template>

            <!-- Password Input -->
            <template v-if="field.type == 'password' && field.is_visible">
                <x-admin::form.control-group.control
                    type="password"
                    ::id="name"
                    ::name="name"
                    ::value="value"
                    ::rules="validations"
                    ::label="label"
                    ::placeholder="placeholder"
                />
            </template>

            <!-- Number Input -->
            <template v-if="field.type == 'number' && field.is_visible">
                <x-admin::form.control-group.control
                    type="number"
                    ::id="name"
                    ::name="name"
                    ::rules="validations"
                    ::value="value"
                    ::label="label"
                    ::min="field.name == 'minimum_order_amount'"
                    ::placeholder="placeholder"
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
                    ::placeholder="placeholder"
                />
            </template>

            <!-- Rich Text Editor -->
            <template v-if="field.type == 'editor' && field.is_visible">
                <v-field
                    v-slot="{ field, errors }"
                    :name="name"
                    :value="value"
                >
                    <textarea
                        :name="name"
                        :id="name.replaceAll('[', '_').replaceAll(']', '_').replaceAll('[]', '_')"
                        v-bind="field"
                        :class="[errors.length ? 'border border-red-600! hover:border-red-600' : '']"
                        class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                    ></textarea>

                    <x-admin::tinymce
                        ::selector="`textarea#${name.replaceAll('[', '_').replaceAll(']', '_').replaceAll('[]', '_')}`"
                        ::field="field"
                    />
                </v-field>
            </template>

            <!-- Select Input -->
            <template v-if="field.type == 'select' && field.is_visible">
                <v-select
                    :id="name"
                    :name="name"
                    :options="choices"
                    :value="chosenValue"
                    :rules="validations"
                    :label="label"
                    :placeholder="placeholder"
                    :searchable="searchable"
                >
                </v-select>
            </template>

            <!-- Multiselect Input -->
            <template v-if="field.type == 'multiselect' && field.is_visible">
                <!-- Posted when nothing is chosen, so clearing every option saves an empty value. -->
                <input
                    type="hidden"
                    :name="name"
                    value=""
                />

                <v-multiselect
                    :name="`${name}[]`"
                    :options="choices"
                    :value="savedSelections"
                    :rules="validations"
                    :label="label"
                    :placeholder="placeholder || selectOptionsPlaceholder"
                    :searchable="searchable"
                >
                </v-multiselect>
            </template>

            <!-- Boolean Switch -->
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

                    <div class="peer h-5 w-9 cursor-pointer rounded-full bg-gray-200 after:absolute after:top-0.5 after:h-4 after:w-4 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:border-white peer-focus:outline-hidden peer-focus:ring-blue-300 ltr:after:left-0.5 ltr:peer-checked:after:translate-x-full rtl:after:right-0.5 rtl:peer-checked:after:-translate-x-full dark:bg-gray-800 dark:after:border-white dark:after:bg-white dark:peer-checked:bg-blue-600"></div>
                </label>
            </template>

            <!-- Image And File Input -->
            <template v-if="isMediaField && field.is_visible">
                <x-admin::media.upload
                    ::name="name"
                    ::type="field.type"
                    ::value="value"
                    ::src="src"
                    ::download-url="mediaDownloadUrl"
                    ::rules="validations"
                    ::label="label"
                />
            </template>

            <!-- Country Select -->
            <template v-if="field.type == 'country' && field.is_visible">
                <v-country :selected-country="value">
                    <template v-slot:default="{ changeCountry }">
                        <v-select
                            placeholder="@lang('admin::app.configuration.index.select-country')"
                            clearable
                            :id="name"
                            :name="name"
                            :options="countries"
                            :value="value"
                            :rules="validations"
                            :label="label"
                            @update:model-value="changeCountry"
                        >
                        </v-select>
                    </template>
                </v-country>
            </template>

            <!-- State Select -->
            <template v-if="field.type == 'state' && field.is_visible">
                <v-state>
                    <template v-slot:default="{ countryStates, country, haveStates, isStateComponenetLoaded }">
                        <div v-if="isStateComponenetLoaded">
                            <template v-if="haveStates()">
                                <v-select
                                    placeholder="@lang('admin::app.configuration.index.select-state')"
                                    clearable
                                    :id="name"
                                    :name="name"
                                    :options="stateChoices(countryStates[country])"
                                    :value="value"
                                    :rules="validations"
                                    :label="label"
                                >
                                </v-select>
                            </template>

                            <template v-else>
                                <x-admin::form.control-group.control
                                    type="text"
                                    ::id="name"
                                    ::name="name"
                                    ::rules="validations"
                                    ::value="value"
                                    ::label="label"
                                />
                            </template>
                        </div>
                    </template>
                </v-state>
            </template>

            <!-- Field Info -->
            <p
                class="mt-1 block text-xs italic leading-5 text-gray-600 dark:text-gray-300"
                v-if="field.info && field.is_visible"
                v-text="info"
            >
            </p>

            <!-- Validation Message -->
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
        const configurationCountries = @json(core()->countries()->map(fn ($country) => ['id' => $country->code, 'label' => $country->name])->values());

        app.component('v-configurable', {
            template: '#v-configurable-template',

            props: [
                'channelCount',
                'currentChannel',
                'currentLocale',
                'dependName',
                'dependValue',
                'fieldData',
                'info',
                'isRequire',
                'label',
                'name',
                'src',
                'validations',
                'value',
                'placeholder',
            ],

            data() {
                return {
                    field: JSON.parse(this.fieldData),

                    selectOptionsPlaceholder: @json(trans('admin::app.configuration.index.select-options')),
                };
            },

            computed: {
                /**
                 * The field's options in the shape the advanced select controls take.
                 */
                choices() {
                    return (this.field.options ?? []).map((option) => ({
                        id: String(option.value),
                        label: option.title,
                    }));
                },

                /**
                 * The stored option of a select, or its first option when the stored value is none of them.
                 */
                chosenValue() {
                    return this.choices.some((choice) => choice.id === String(this.value ?? ''))
                        ? String(this.value)
                        : (this.choices[0]?.id ?? '');
                },

                /**
                 * The stored comma separated value of a multiselect, as the array it binds to.
                 */
                savedSelections() {
                    return this.value ? this.value.split(',') : [];
                },

                /**
                 * Whether the field has options enough to need a search box.
                 */
                searchable() {
                    return this.choices.length > 6;
                },

                /**
                 * Every country, for the country field.
                 */
                countries() {
                    return configurationCountries;
                },

                /**
                 * Whether the field holds an uploaded image or file.
                 */
                isMediaField() {
                    return ['image', 'file'].includes(this.field.type);
                },

                /**
                 * The link a stored file is downloaded from, empty while there is nothing stored.
                 */
                mediaDownloadUrl() {
                    if (
                        this.field.type != 'file'
                        || ! this.value
                    ) {
                        return '';
                    }

                    return "{{ route('admin.configuration.download', [request()->route('slug'), request()->route('slug2'), ':path']) }}"
                        .replace(':path', this.value.split('/')[1]);
                },
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
                    const value =
                        event.target.type === 'checkbox'
                        ? (event.target.checked ? '1' : '0')
                        : event.target.value;

                    this.field['is_visible'] = (this.dependValue ?? '')
                        .split(',')
                        .map((expected) => ({ true: '1', false: '0' })[expected] ?? expected)
                        .includes(value);
                });

                dependElement.dispatchEvent(new Event('change'));
            },

            methods: {
                /**
                 * A country's states for the state field, led by the wildcard that matches all of them.
                 */
                stateChoices(states) {
                    return [
                        { id: '*', label: '*' },
                        ...(states ?? []).map((state) => ({ id: state.code, label: state.default_name })),
                    ];
                },
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
                /**
                 * Tell the state field which country was chosen.
                 */
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
                /**
                 * Whether the chosen country has states to choose from.
                 */
                haveStates() {
                    return !!this.countryStates[this.country]?.length;
                },
            },
        });
    </script>
@endPushOnce