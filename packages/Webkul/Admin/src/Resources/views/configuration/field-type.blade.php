@php
    $value = system_config()->getConfigData($field->getNameKey(), $currentChannel->code, $currentLocale->code);
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
        src="{{ Storage::url($value) }}"
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

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-configurable-template"
    >
        <x-admin::form.control-group class="last:mb-0!">
            <!-- Title of the input field -->
            <div    
                v-if="field.is_visible"
                class="flex justify-between"
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
        
            <!-- Text input -->
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
        
            <!-- Password input -->
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

            <!-- Textarea with tinymce -->
            <template v-if="field.type == 'editor' && field.is_visible">
                <v-field
                    v-slot="{ field, errors }"
                    :name="name"
                >
                    <textarea
                        :name="name"
                        :id="name.replaceAll('[', '_').replaceAll(']', '_').replaceAll('[]', '_')"
                        :value="value"
                        v-bind="{field, errors}"
                        :class="[errors.length ? 'border border-red-600! hover:border-red-600' : '']"
                        class="w-full rounded-md border px-3 py-2.5 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                    ></textarea>

                    <x-admin::tinymce
                        ::selector="`textarea#${name.replaceAll('[', '_').replaceAll(']', '_').replaceAll('[]', '_')}`"
                        ::field="field"
                    />
                </v-field>
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
                    :value="savedSelections"
                    :label="label"
                >
                    <select
                        :name="`${name}[]`"
                        v-bind="data.field"
                        v-model="data.value"
                        :class="['custom-select', 'w-full', 'rounded-md', 'border', 'bg-white', 'px-3', 'py-2.5', 'text-sm', 'font-normal', 'text-gray-600', 'transition-all', 'hover:border-gray-400', 'dark:border-gray-800', 'dark:bg-gray-900', 'dark:text-gray-300', 'dark:hover:border-gray-400', data.errors.length && 'border-red-500']"
                        multiple
                    >
                        <option
                            v-for="option in field.options"
                            :key="option.value"
                            :value="option.value"
                        >
                            @{{ option.title }}
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

                    <div class="peer h-5 w-9 cursor-pointer rounded-full bg-gray-200 after:absolute after:top-0.5 after:h-4 after:w-4 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:border-white peer-focus:outline-hidden peer-focus:ring-blue-300 ltr:after:left-0.5 ltr:peer-checked:after:translate-x-full rtl:after:right-0.5 rtl:peer-checked:after:-translate-x-full dark:bg-gray-800 dark:after:border-white dark:after:bg-white dark:peer-checked:bg-gray-950"></div>
                </label>
            </template>
        
            <!-- Image And File Input -->
            <template v-if="isMediaField && field.is_visible">
                <v-field
                    v-slot="{ field: mediaField, errors, handleChange, handleBlur }"
                    :name="name"
                    :rules="validations"
                    :label="label"
                >
                    <div class="flex">
                        <!-- Uploaded Media -->
                        <div
                            v-if="hasMedia"
                            class="group relative flex h-30 w-30 items-center justify-center overflow-hidden rounded-sm border border-gray-300 dark:border-gray-800"
                        >
                            <img
                                v-if="mediaPreview"
                                class="max-h-full max-w-full"
                                :src="mediaPreview"
                                :alt="mediaFileName"
                            />

                            <div
                                v-else
                                class="flex flex-col items-center gap-1 px-2"
                            >
                                <span class="icon-folder text-2xl text-gray-600 dark:text-gray-300"></span>

                                <p
                                    class="line-clamp-2 break-all text-center text-xs text-gray-600 dark:text-gray-300"
                                    v-text="mediaFileName"
                                >
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="invisible absolute bottom-0 flex w-full justify-center gap-1 bg-white/90 p-1 transition-all group-hover:visible dark:bg-gray-900/90">
                                <label
                                    class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                                    :for="name"
                                    title="@lang('admin::app.configuration.index.replace')"
                                ></label>

                                <a
                                    v-if="mediaDownloadUrl"
                                    class="icon-down-stat rounded-md p-1.5 text-2xl text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                                    :href="mediaDownloadUrl"
                                    title="@lang('admin::app.configuration.index.download')"
                                ></a>

                                <span
                                    class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl text-gray-600 hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800"
                                    title="@lang('admin::app.configuration.index.delete')"
                                    @click="removeMedia"
                                ></span>
                            </div>
                        </div>

                        <!-- Upload Button -->
                        <label
                            v-else
                            class="flex h-30 w-30 cursor-pointer flex-col items-center justify-center gap-1 overflow-hidden rounded-sm border border-dashed px-2 transition-all hover:border-gray-400 dark:hover:border-gray-400"
                            :class="errors.length ? 'border-red-600' : 'border-gray-300 dark:border-gray-800'"
                            :for="name"
                        >
                            <span
                                v-if="field.type == 'image'"
                                class="icon-image text-2xl text-gray-600 dark:text-gray-300"
                            ></span>

                            <span
                                v-else
                                class="icon-folder text-2xl text-gray-600 dark:text-gray-300"
                            ></span>

                            <p
                                v-if="field.type == 'image'"
                                class="text-center text-sm font-semibold text-gray-600 dark:text-gray-300"
                            >
                                @lang('admin::app.configuration.index.add-image')
                            </p>

                            <p
                                v-else
                                class="text-center text-sm font-semibold text-gray-600 dark:text-gray-300"
                            >
                                @lang('admin::app.configuration.index.add-file')
                            </p>

                            <p
                                v-if="mediaHint"
                                class="text-center text-xs leading-tight text-gray-500 dark:text-gray-300"
                                v-text="mediaHint"
                            >
                            </p>
                        </label>

                        <input
                            type="file"
                            class="hidden"
                            ref="mediaInput"
                            :id="name"
                            :name="mediaField.name"
                            :accept="mediaAccept"
                            @change="handleChange($event); stageMedia($event)"
                            @blur="handleBlur"
                        />
                    </div>
                </v-field>

                <input
                    v-if="media.isDeleted"
                    type="hidden"
                    :name="`${name}[delete]`"
                    value="1"
                />
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
     
            <!-- validation message -->
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

                    media: {
                        file: null,

                        preview: '',

                        isDeleted: false,
                    },
                };
            },

            computed: {
                /**
                 * The stored comma separated value of a multiselect, as the array it binds to.
                 */
                savedSelections() {
                    return this.value ? this.value.split(',') : [];
                },

                /**
                 * Whether the field holds an uploaded image or file.
                 */
                isMediaField() {
                    return ['image', 'file'].includes(this.field.type);
                },

                /**
                 * The extensions the field's mimes rule accepts.
                 */
                mediaTypes() {
                    const rule = (this.validations ?? '')
                        .split('|')
                        .find((rule) => rule.startsWith('mimes:'));

                    return rule
                        ? rule.split(':')[1].split(',').filter((type) => type)
                        : [];
                },

                /**
                 * The accept attribute of the file input, narrowed to the extensions the field allows.
                 */
                mediaAccept() {
                    if (this.mediaTypes.length) {
                        return this.mediaTypes.map((type) => `.${type}`).join(',');
                    }

                    return this.field.type == 'image' ? 'image/*' : '';
                },

                /**
                 * The accepted extensions, shown as the hint under the upload button.
                 */
                mediaHint() {
                    return this.mediaTypes.join(', ');
                },

                /**
                 * Whether there is a file to show: one just picked, or a stored one still kept.
                 */
                hasMedia() {
                    return !! this.media.file
                        || (!! this.value && ! this.media.isDeleted);
                },

                /**
                 * The image to preview, empty for a field holding something that is not one.
                 */
                mediaPreview() {
                    if (this.field.type != 'image') {
                        return '';
                    }

                    if (this.media.file) {
                        return this.media.preview;
                    }

                    return this.value && ! this.media.isDeleted ? this.src : '';
                },

                /**
                 * The name of the picked file, or of the stored one.
                 */
                mediaFileName() {
                    if (this.media.file) {
                        return this.media.file.name;
                    }

                    return this.value ? this.value.split('/').pop() : '';
                },

                /**
                 * The link a stored file is downloaded from, empty while there is nothing stored.
                 */
                mediaDownloadUrl() {
                    if (
                        this.field.type != 'file'
                        || this.media.file
                        || ! this.value
                        || this.media.isDeleted
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
                    this.field['is_visible'] =
                        event.target.type === 'checkbox'
                        ? event.target.checked
                        : (this.dependValue ?? '').split(',').includes(event.target.value);
                });

                dependElement.dispatchEvent(new Event('change'));
            },

            methods: {
                /**
                 * Stage the picked file for preview, over whatever the field already held.
                 */
                stageMedia(event) {
                    const file = event.target.files[0];

                    if (! file) {
                        return;
                    }

                    this.media.file = file;

                    this.media.preview = '';

                    this.media.isDeleted = false;

                    if (! file.type.startsWith('image/')) {
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = (event) => this.media.preview = event.target.result;

                    reader.readAsDataURL(file);
                },

                /**
                 * Drop the picked file, or mark the stored one to be removed on save. The cleared
                 * input is dispatched so the validator lets go of the file it was holding too.
                 */
                removeMedia() {
                    this.$refs.mediaInput.value = '';

                    this.$refs.mediaInput.dispatchEvent(new Event('change'));

                    if (this.media.file) {
                        this.media.file = null;

                        this.media.preview = '';

                        return;
                    }

                    this.media.isDeleted = true;
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