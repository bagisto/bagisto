@inject('coreConfigRepository', 'Webkul\Core\Repositories\CoreConfigRepository')

@php
    $nameKey = $item['key'] . '.' . $field['name'];

    $name = $coreConfigRepository->getNameField($nameKey);

    $value = $coreConfigRepository->getValueByRepository($field);

    $validations = $coreConfigRepository->getValidations($field);

    $isRequired = Str::contains($validations, 'required') ? 'required' : '';

    $channelLocaleInfo = $coreConfigRepository->getChannelLocaleInfo($field, $currentChannel->code, $currentLocale->code);
@endphp

<x-admin::form.control-group>
    @if ($field['type'] == 'depends')
        @include('admin::configuration.dependent-field-type')
    @else
        {{-- Title of the input field --}}
        <div class="flex justify-between">
            <x-admin::form.control-group.label
                :for="$name" :class="$isRequired"
            >
                @lang($field['title'])
            </x-admin::form.control-group.label>

            <x-admin::form.control-group.label
                :for="$name"
            >
                <span class="flex">{{ $channelLocaleInfo }}</span>
            </x-admin::form.control-group.label>
        </div>

        {{-- Text input --}}
        @if ($field['type'] == 'text')
            <x-admin::form.control-group.control
                type="text"
                :name="$name"
                :value="old($nameKey) ?? (core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ? core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) : ($field['default_value'] ?? ''))"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
            >
            </x-admin::form.control-group.control>

        {{-- Password input --}}
        @elseif ($field['type'] == 'password')
            <x-admin::form.control-group.control
                type="password"
                :name="$name"
                :value="old($nameKey) ?? core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
            >
            </x-admin::form.control-group.control>

        {{-- Number input --}}
        @elseif ($field['type'] == 'number')
            <x-admin::form.control-group.control
                type="number"
                :name="$name"
                :value="old($nameKey) ?? core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
                :min="$field['name'] == 'minimum_order_amount'"
            >
            </x-admin::form.control-group.control>

        {{-- Color Input --}}
        @elseif ($field['type'] == 'color')
            <x-admin::form.control-group.control
                type="color"
                :name="$name"
                :value="old($nameKey) ?? core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
            >
            </x-admin::form.control-group.control>

        {{-- Textarea Input --}}
        @elseif ($field['type'] == 'textarea')
            <x-admin::form.control-group.control
                type="textarea"
                :name="$name"
                :value="old($nameKey) ?: core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?: (isset($field['default_value']) ? $field['default_value'] : '')"
                class="text-gray-600 dark:text-gray-300"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
            >
            </x-admin::form.control-group.control>

        {{-- Textarea Input --}}
        @elseif ($field['type'] == 'editor')
            {{-- (@suraj-webkul) TODO Change textarea to tiny mce --}}
            <x-admin::form.control-group.control
                type="textarea"
                :name="$name"
                :value="old($nameKey) ?: core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?: (isset($field['default_value']) ? $field['default_value'] : '')"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
            >
            </x-admin::form.control-group.control>

        {{-- Select input --}}
        @elseif ($field['type'] == 'select')
            @php $selectedOption = core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? ''; @endphp

            <x-admin::form.control-group.control
                type="select"
                :name="$name"
                :value="$selectedOption"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
            >
                @if (isset($field['repository']))
                    @foreach ($value as $key => $option)
                        <option
                            value="{{ $key }}"
                            {{ $key == $selectedOption ? 'selected' : ''}}
                        >
                            @lang($option)
                        </option>
                    @endforeach
                @else
                    @foreach ($field['options'] as $option)
                        @php
                            $value = ! isset($option['value']) ? null : ( $value = ! $option['value'] ? 0 : $option['value'] );
                        @endphp

                        <option
                            value="{{ $value }}"
                            {{ $value == $selectedOption ? 'selected' : ''}}
                        >
                            @lang($option['title'])
                        </option>
                    @endforeach
                @endif
            </x-admin::form.control-group.control>

        {{-- Multiselect Input --}}
        @elseif ($field['type'] == 'multiselect')
            @php $selectedOption = core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? ''; @endphp

            <x-admin::form.control-group.control
                type="select"
                :name="$name"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
                multiple
            >
                @if (isset($field['repository']))
                    @foreach ($value as $key => $option)

                        <option value="{{ $key }}" {{ in_array($key, explode(',', $selectedOption)) ? 'selected' : ''}}>
                            @lang($value[$key])
                        </option>

                    @endforeach
                @else
                    @foreach ($field['options'] as $option)
                        @php
                            $value = ! isset($option['value']) ? null : ( $value = ! $option['value'] ? 0 : $option['value'] );
                        @endphp

                        <option value="{{ $value }}" {{ in_array($option['value'], explode(',', $selectedOption)) ? 'selected' : ''}}>
                            @lang($option['title'])
                        </option>
                    @endforeach
                @endif
            </x-admin::form.control-group.control>

        {{-- Boolean/Switch input --}}
        @elseif ($field['type'] == 'boolean')
            @php
                $selectedOption = core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? ($field['default_value'] ?? '');
            @endphp

            <!-- Hidden Fild for unseleted Switch button -->
            <x-admin::form.control-group.control
                type="hidden"
                :name="$name"
                value="0"
            >
            </x-admin::form.control-group.control>

            <x-admin::form.control-group.control
                type="switch"
                :name="$name"
                :value="(bool) $selectedOption"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
                :checked="(bool) $selectedOption"
            >
            </x-admin::form.control-group.control>

        @elseif ($field['type'] == 'image')

            @php
                $src = Storage::url(core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code));
                $result = core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code);
            @endphp

            <div class="flex justify-center items-center">
                @if ($result)
                    <a
                        href="{{ $src }}"
                        target="_blank"
                    >
                        <img
                            src="{{ $src }}"
                            class="relative mr-5 h-[33px] w-[33px] top-15 rounded-3 border-3 border-gray-500"
                        />
                    </a>
                @endif

                <x-admin::form.control-group.control
                    type="file"
                    :name="$name"
                    :id="$name"
                    :rules="$validations"
                    :label="trans($field['title'])"
                >
                </x-admin::form.control-group.control>
            </div>

            @if ($result)
                <div class="flex gap-[10px] cursor-pointer">
                    <x-admin::form.control-group.control
                        type="checkbox"
                        :name="$name.'[delete]'"
                        :id="$name.'[delete]'"
                        value="1"
                        class="hidden peer"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.label
                        class="cursor-pointer"
                        :for="$name.'[delete]'"
                    >
                        @lang('admin::app.configuration.index.delete')
                    </x-admin::form.control-group.label>
                </div>
            @endif

        @elseif ($field['type'] == 'file')
            @php
                $result = core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code);
                $src = explode("/", $result);
                $path = end($src);
            @endphp

            @if ($result)
                <a
                    href="{{ route('admin.configuration.download', [request()->route('slug'), request()->route('slug2'), $path]) }}"
                >
                    <i class="icon sort-down-icon download"></i>
                </a>
            @endif

            <x-admin::form.control-group.control
                type="file"
                :name="$name"
                :id="$name"
                :rules="$validations"
                :label="trans($field['title'])"
            >
            </x-admin::form.control-group.control>

            @if ($result)
                <div class="flex gap-[10px] cursor-pointer">
                    <x-admin::form.control-group.control
                        type="checkbox"
                        :name="$name.'[delete]'"
                        :id="$name.'[delete]'"
                        value="1"
                        class="hidden peer"
                    >
                    </x-admin::form.control-group.control>

                    <x-admin::form.control-group.label
                        class="cursor-pointer"
                        :for="$name.'[delete]'"
                    >
                        @lang('admin::app.configuration.index.delete')
                    </x-admin::form.control-group.label>
                </div>
            @endif

        {{-- Country select Vue component --}}
        @elseif ($field['type'] == 'country')
            <v-country ref="countryRef">
                <template v-slot:default="{ changeCountry }">
                    <x-admin::form.control-group class="flex">
                        <x-admin::form.control-group.control
                            type="select"
                            :name="$name"
                            :value="core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)"
                            :id="$name"
                            :rules="$validations"
                            :label="trans($field['title'])"
                            @change="changeCountry($event.target.value)"
                        >
                            @foreach (core()->countries() as $country)
                                <option value="{{ $country->code }}">{{ $country->name }}</option>
                            @endforeach
                        </x-admin::form.control-group.control>
                    </x-admin::form.control-group>
                </template>
            </v-country>

        {{-- State select Vue component --}}
        @elseif ($field['type'] == 'state')
            <v-state ref="stateRef">
                <template
                    v-slot:default="{ countryStates, country, haveStates, isStateComponenetLoaded }"
                >
                    <div v-if="isStateComponenetLoaded">
                        <template v-if="haveStates()">
                            <x-admin::form.control-group class="flex">
                                <x-admin::form.control-group.control
                                    type="select"
                                    :name="$name"
                                    :value="core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)"
                                    :id="$name"
                                    :rules="$validations"
                                    :label="trans($field['title'])"
                                >
                                    <option
                                        v-for='(state, index) in countryStates[country]'
                                        :value="state.code"
                                        v-text="state.default_name"
                                    >
                                    </option>
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
                        </template>

                        <template v-else>
                            <x-admin::form.control-group class="flex">
                                <x-admin::form.control-group.control
                                    type="text"
                                    :name="$name"
                                    :value="core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code)"
                                    :id="$name"
                                    :rules="$validations"
                                    :label="trans($field['title'])"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
                        </template>
                    </div>
                </template>
            </v-state>
        @endif

    @endif

    @if (isset($field['info']))
        <label
            class="block leading-[20px] text-[12px] text-gray-600 dark:text-gray-300 font-medium"
        >
            {!! trans($field['info']) !!}
        </label>
    @endif

    {{-- Input field validaitons error message --}}
    <x-admin::form.control-group.error
        :control-name="$name"
    >
    </x-admin::form.control-group.error>
</x-admin::form.control-group>

@if ($field['type'] == 'country')
    @pushOnce('scripts')
        <script type="text/x-template" id="v-country-template">
            <div>
                <slot
                    :changeCountry="changeCountry"
                >
                </slot>
            </div>
        </script>

        <script type="module">
            app.component('v-country', {
                template: '#v-country-template',

                data() {
                    return {
                        country: "{{ core()->getConfigData($nameKey, $currentChannel->code, $currentLocale->code) ?? '' }}",
                    }
                },

                mounted() {
                    this.$root.$refs.stateRef.country = this.country;
                },

                methods: {
                    changeCountry(selectedCountryCode) {
                        this.$root.$refs.stateRef.country = selectedCountryCode;
                    },
                },
            });
        </script>

        <script type="text/x-template" id="v-state-template">
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
            app.component('v-state', {
                template: '#v-state-template',

                data() {
                    return {
                        country: "",

                        isStateComponenetLoaded: false,

                        countryStates: @json(core()->groupedStatesByCountries())
                    }
                },

                created() {
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
@endif
