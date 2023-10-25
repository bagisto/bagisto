@php
    $dependField = $coreConfigRepository->getDependentFieldOrValue($field);

    $dependValue = $coreConfigRepository->getDependentFieldOrValue($field, 'value');

    $dependNameKey = $item['key'] . '.' . $dependField;

    $dependName = $coreConfigRepository->getNameField($dependNameKey);

    $field['options'] = $coreConfigRepository->getDependentFieldOptions($field, $value);

    $selectedOption = core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? '';

    $dependSelectedOption = core()->getConfigData($dependNameKey, $currentChannel->code, $currentLocale->code) ?? '';
@endphp

@if (strpos($field['validation'], 'required_if') !== false)
    <v-required-if
        name="{{ $name }}"
        result="{{ $selectedOption }}"
        validations="{{ $validations }}"
        label="@lang($field['title'])"
        options="{{ json_decode($field['options']) }}"
        info="{{ json_encode($field['info'] ?? '') }}"
        depend="{{ $dependName }}"
        depend-result="{{ $dependSelectedOption }}"
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
        id="v-depends-template"
    >
        <div>
            <div class="flex justify-between">
                <label
                    class="block leading-[24px] text-[12px] text-gray-800 dark:text-white font-medium"
                    :class="{ 'required' : isRequire }"
                    :for="name"
                >
                    @{{ label }}
                </label>

                <label
                    class="block leading-[24px] text-[12px] text-gray-800 dark:text-white font-medium"
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
                :rules="validations"
                :label="field_name"
                v-if="this.isVisible"
            >
                <select 
                    v-bind="field"
                    :class="{ 'border border-red-500': errorMessage }"
                    class="w-full py-2 px-3 appearance-none border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                >
                    <option 
                        v-for='(option, index) in this.options' 
                        :value="option.value"
                        :text="option.title"
                    > 
                    </option>
                </select>
            </v-field>

            <v-field 
                :name="name"
                v-slot="{ field, errorMessage }"
                :id="name"
                :placeholder="info"
                :rules="validations"
                v-model="value"
                :label="field_name"
                v-else
            >
                <input 
                    type="text"
                    v-bind="field"
                    :class="{ 'border border-red-500': errorMessage }"
                    class="w-full py-2 px-3 appearance-none border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                />
            </v-field>
        </div>
    </script>

    <script
        type="text/x-template"
        id="v-required-if-template"
    >
        <div>
            <div class="flex justify-between">
                <label
                    class="block leading-[24px] text-[12px] text-gray-800 dark:text-white font-medium"
                    :class="{ 'required' : isRequire }"
                    :for="name"
                >
                    @{{ label }}
                </label>

                <label
                    class="block leading-[24px] text-[12px] text-gray-800 dark:text-white font-medium"
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
                    class="w-full py-2 px-3 appearance-none border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                >
                    <option
                        v-for='option in this.options'
                        :value="option.value"
                        :text="option.title"
                    >
                    </option>
                </select>
            </v-field>

            <v-field 
                :name="name"
                v-slot="{ field, errorMessage }"
                :id="name"
                :placeholder="info"
                :rules="appliedRules"
                v-model="value"
                :label="label"
                v-else
            >
                <input 
                    type="text"
                    v-bind="field"
                    :class="{ 'border border-red-500': errorMessage }"
                    class="w-full appearance-none py-2 px-3 border rounded-[6px] text-[14px] text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                />
            </v-field>
        </div>
    </script>
    
    <script type="module">
        app.component('v-depends', {
            template: '#v-depends-template',

            props: [
                'options',
                'name',
                'validations',
                'depend',
                'value',
                'label',
                'channel_locale',
                'repository',
                'result'
            ],

            data() {
                return {
                    isRequire: false,
                    isVisible: false,
                    value: this.result,
                };
            },

            mounted() {
                if (this.validations || this.validations.indexOf("required") !== -1) {
                    this.isRequire = true;
                }

                let dependentElement = document.getElementById(this.depend);

                let dependValue = this.value;

                if (dependValue === 'true') {
                    dependValue = 1;
                } else if (dependValue === 'false') {
                    dependValue = 0;
                }

                document.addEventListener("change", (event) => {
                    if (this.depend === event.target.name) {
                        this.isVisible = this.value === event.target.value;
                    }
                });

                if (dependentElement && dependentElement.value == dependValue) {
                    this.isVisible = true;
                } else {
                    this.isVisible = false;
                }

                if (this.result) {
                    if (dependentElement && dependentElement.value == this.value) {
                        this.isVisible = true;
                    } else {
                        this.isVisible = false;
                    }
                }
            },
        });
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

                    dependSavedValue: parseInt(this.dependResult)
                };
            },

            mounted() {
                this.isRequire = this.dependSavedValue ? true : false;

                this.updateValidations();

                const dependElement = document.getElementById(this.depend);

                if (dependElement) {
                    dependElement.addEventListener('change', (e) => {
                        this.dependSavedValue = !this.dependSavedValue;
                        this.dependSavedValue = this.dependSavedValue ? 1 : 0;
                        this.isRequire = this.dependSavedValue ? true : false;

                        this.updateValidations();
                    });
                }
            },

            methods: {
                updateValidations() {
                    this.appliedRules = this.validations.split('|').filter(validation => !this.validations.includes('required_if'));

                    if (this.isRequire) {
                        this.appliedRules.push('required');
                    } else {
                        this.appliedRules = this.appliedRules.filter((value) => {
                            return value !== 'required';
                        });
                    }

                    this.appliedRules = this.appliedRules.join('|');
                },
            },
        });
    </script>
@endPushOnce