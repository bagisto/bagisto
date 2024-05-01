@php
    $dependField = $coreConfigRepository->getDependentFieldOrValue($field);

    $dependValue = $coreConfigRepository->getDependentFieldOrValue($field, 'value');

    $dependNameKey = $item['key'] . '.' . $dependField;

    $dependName = $coreConfigRepository->getNameField($dependNameKey);

    $field['options'] = $coreConfigRepository->getDependentFieldOptions($field, $field['options'] ?? null);
    // depend-options
    $selectedOption = core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? '';
    // value

    $dependSelectedOption = core()->getConfigData($dependNameKey, $currentChannel->code, $currentLocale->code) ?? '';
    // depend-selected-options
@endphp

@if (strpos($field['validation'], 'required_if') !== false)
    <v-required-if
        name="{{ $name }}"
        result="{{ $selectedOption }}"
        validations="{{ $validations }}"
        label="@lang($field['title'])"
        options="{{ json_decode($field['options']) }}"
        {{-- depend-options --}}
        info="{{ trans($field['info'] ?? '') }}"
        depend="{{ $dependName }}"
        depend-result="{{ $dependSelectedOption }}"
        {{-- depend-selected-options --}}
        channel_locale="{{ $channelLocaleInfo }}"
    >
    </v-required-if>
@else
    <v-depends
        name="{{ $name }}"
        validations="{{ $validations }}"
        options="{{ json_decode($field['options']) }}"
        depend="{{ $dependName }}"
        value="'{{ $dependValue }}'"
        label="@lang($field['title'])"
        channel_locale="{{ $channelLocaleInfo }}"
        result="{{ $selectedOption }}"
        depend-saved-value="{{ $dependSelectedOption }}"
    >
    </v-depends>
@endif

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-required-if-template"
    >
        <div>
            <div
                v-if="isRequire"
                class="mt-4 flex justify-between"
            >
                <label
                    class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white"
                    :class="{ 'required' : isRequire }"
                    :for="name"
                >
                    @{{ label }}
                </label>

                <label
                    class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white"
                    :for="name"
                >
                    @{{ channel_locale }}
                </label>
            </div>
            
            <v-field 
                :name="name"
                v-slot="{ field, errorMessage }"
                :id="name"
                v-model="value"
                :rules="appliedRules"
                :label="label"
                v-if="this.options.length"
            >
                <select 
                    v-bind="field"
                    :class="{ 'border border-red-500': errorMessage }"
                    class="w-full appearance-none rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:text-gray-300"
                >
                    <option
                        v-for='option in this.options'
                        :value="option.value"
                        :text="option.title"
                    ></option>
                </select>
            </v-field>

            <v-field 
                v-if="isRequire"
                :name="name"
                v-slot="{ field, errorMessage }"
                :id="name"
                :placeholder="info"
                :rules="appliedRules"
                v-model="value"
                :label="label"
            >
                <input 
                    type="text"
                    v-bind="field"
                    :class="{ 'border border-red-500': errorMessage }"
                    class="w-full appearance-none rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                />
            </v-field>

            <p
                v-if="isRequire"
                class="mt-1 block text-xs italic leading-5 text-gray-600 dark:text-gray-300"
            >
                @{{ info }}
            </p>

            <v-error-message
                :name="name"
                v-slot="{ message }"
            >
                <p
                    class="mt-1 text-xs italic text-red-600"
                    v-text="message"
                ></p>
            </v-error-message>
        </div>
    </script>
    
    <script type="module">
        app.component('v-required-if', {
            template: '#v-required-if-template',

            props: [
                'name',
                'label',
                'info',
                'options',
                'result',
                'validations',
                'depend',
                'dependResult',
                'channel_locale',
            ],

            data() {
                return {
                    isRequire: false,

                    appliedRules: [],

                    value: this.result,

                    dependSavedValue: parseInt(this.dependResult),
                };
            },

            mounted() {
                this.updateValidations();

                const dependElement = document.getElementById(this.depend);

                if (dependElement) {
                    dependElement.addEventListener('change', this.handleEvent);
                }

                dependElement.dispatchEvent(new Event('change'));
            },

            methods: {
                handleEvent(event) {
                    this.isRequire = 
                        event.target.type === 'checkbox' 
                        ? event.target.checked
                        : this.validations.split(',').slice(1).includes(event.target.value);

                    this.updateValidations();
                },

                updateValidations() {
                    this.appliedRules = this.validations.split('|').filter(validation => !this.validations.includes('required_if'));

                    if (this.isRequire) {
                        this.appliedRules.push('required');
                    } else {
                        this.appliedRules = this.appliedRules.filter(value => value !== 'required');
                    }

                    this.appliedRules = this.appliedRules.join('|');
                },
            },
        });
    </script>
@endPushOnce