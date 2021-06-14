<?php
    $name = $item['key'] . '.' . $field['name'];

    $coreConfigRepository = app('Webkul\Core\Repositories\CoreConfigRepository');

    $nameField = $coreConfigRepository->getNameField($name);

    $validations = $coreConfigRepository->getValidations($field);

    $channelLocaleInfo = $coreConfigRepository->getChannelLocaleInfo($field, $channel, $locale);

    if (isset($field['repository'])) {
        $temp = explode("@", $field['repository']);
        $class = app(current($temp));
        $method = end($temp);
        $value = $class->$method();
    }

    $key = $item['key'];
    $key = explode(".", $key);
    $firstField = current($key);
    $secondField = next($key);
    $thirdField  = end($key);
?>

    @if ($field['type'] == 'depends')

        <?php
            $depends = explode(":", $field['depend']);
            $dependField = current($depends);

            $dependValue = end($depends);

            if (isset($value) && $value) {
                $i = 0;
                foreach ($value as $key => $result) {
                    $data['title'] = $result;
                    $data['value'] = $key;
                    $options[$i] = $data;
                    $i++;
                }
                $field['options'] = $options;
            }

            if (! isset($field['options'])) {
                $field['options'] = '';
            }

            $selectedOption = core()->getConfigData($name, $channel, $locale) ?? '';
            $dependSelectedOption = core()->getConfigData(implode('.', [$firstField, $secondField, $thirdField, $dependField]), $channel, $locale) ?? '';
        ?>

        @if (strpos($field['validation'], 'required_if') !== false)
            <required-if
                :name = "'{{ $nameField }}'"
                :label = "'{{ trans($field['title']) }}'"
                :info = "'{{ trans(isset($field['info']) ? $field['info'] : '') }}'"
                :options = '@json($field['options'])'
                :result = "'{{ $selectedOption }}'"
                :validations = "'{{ $validations }}'"
                :depend = "'{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $dependField }}]'"
                :depend-result= "'{{ $dependSelectedOption }}'"
                :channel_locale = "'{{ $channelLocaleInfo }}'"
            ></required-if>
        @else
            <depends
                :options = '@json($field['options'])'
                :name = "'{{ $nameField }}'"
                :validations = "'{{ $validations }}'"
                :depend = "'{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $dependField }}]'"
                :value = "'{{ $dependValue }}'"
                :field_name = "'{{ trans($field['title']) }}'"
                :channel_locale = "'{{ $channelLocaleInfo }}'"
                :result = "'{{ $selectedOption }}'"
                :depend-saved-value= "'{{ $dependSelectedOption }}'"
            ></depends>
        @endif

    @else

        <div class="control-group {{ $field['type'] }}" @if ($field['type'] == 'multiselect') :class="[errors.has('{{ $nameField }}[]') ? 'has-error' : '']" @else :class="[errors.has('{{ $nameField }}') ? 'has-error' : '']" @endif>

            <label for="{{ $nameField }}" {{ !isset($field['validation']) || preg_match('/\brequired\b/', $field['validation']) == false ? '' : 'class=required' }}>

                {{ trans($field['title']) }}

                <span class="locale">{{ $channelLocaleInfo }}</span>

            </label>

            @if ($field['type'] == 'text')

                <input type="text" v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" value="{{ old($name) ?: (core()->getConfigData($name, $channel, $locale) ? core()->getConfigData($name, $channel, $locale) : (isset($field['default_value']) ? $field['default_value'] : '')) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">

            @elseif ($field['type'] == 'password')

                <input type="password" v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" value="{{ old($name) ?: core()->getConfigData($name, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">

            @elseif ($field['type'] == 'number')

                <input type="number" min="0" v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" value="{{ old($name) ?: core()->getConfigData($name, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">

            @elseif ($field['type'] == 'color')

                <input type="color" v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" value="{{ old($name) ?: core()->getConfigData($name, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">


            @elseif ($field['type'] == 'textarea')

                <textarea v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">{{ old($name) ?: core()->getConfigData($name, $channel, $locale) ?: (isset($field['default_value']) ? $field['default_value'] : '') }}</textarea>

            @elseif ($field['type'] == 'select')

                <select v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;" >

                    <?php
                        $selectedOption = core()->getConfigData($name, $channel, $locale) ?? '';
                    ?>

                    @if (isset($field['repository']))
                        @foreach ($value as $key => $option)

                            <option value="{{ $key }}" {{ $key == $selectedOption ? 'selected' : ''}}>
                            {{ trans($option) }}
                            </option>

                        @endforeach
                    @else
                        @foreach ($field['options'] as $option)
                            <?php
                                if (! isset($option['value'])) {
                                    $value = null;
                                } else {
                                    $value = $option['value'];

                                    if (! $value) {
                                        $value = 0;
                                    }
                                }
                            ?>

                            <option value="{{ $value }}" {{ $value == $selectedOption ? 'selected' : ''}}>
                                {{ trans($option['title']) }}
                            </option>
                        @endforeach
                    @endif

                </select>

            @elseif ($field['type'] == 'multiselect')

                <select v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}[]" data-vv-as="&quot;{{ trans($field['title']) }}&quot;"  multiple>

                    <?php
                        $selectedOption = core()->getConfigData($name, $channel, $locale) ?? '';
                    ?>

                    @if (isset($field['repository']))
                        @foreach ($value as $key => $option)

                            <option value="{{ $key }}" {{ in_array($key, explode(',', $selectedOption)) ? 'selected' : ''}}>
                                {{ trans($value[$key]) }}
                            </option>

                        @endforeach
                    @else
                        @foreach ($field['options'] as $option)
                            <?php
                                if (! isset($option['value'])) {
                                    $value = null;
                                } else {
                                    $value = $option['value'];

                                    if (! $value) {
                                        $value = 0;
                                    }
                                }
                            ?>

                            <option value="{{ $value }}" {{ in_array($option['value'], explode(',', $selectedOption)) ? 'selected' : ''}}>
                                {{ trans($option['title']) }}
                            </option>
                        @endforeach
                    @endif

                </select>

            @elseif ($field['type'] == 'country')

                <?php
                    $countryCode = core()->getConfigData($name, $channel, $locale) ?? '';
                ?>

                <country
                    :country_code = "'{{ $countryCode }}'"
                    :validations = "'{{ $validations }}'"
                    :name = "'{{ $nameField }}'"
                ></country>

            @elseif ($field['type'] == 'state')

                <?php
                    $stateCode = core()->getConfigData($name, $channel, $locale) ?? '';
                ?>

                <state
                    :state_code = "'{{ $stateCode }}'"
                    :validations = "'{{ $validations }}'"
                    :name = "'{{ $nameField }}'"
                ></state>

            @elseif ($field['type'] == 'boolean')

                <?php $selectedOption = core()->getConfigData($name, $channel, $locale) ?? (isset($field['default_value']) ? $field['default_value'] : ''); ?>

                <label class="switch">
                    <input type="hidden" name="{{ $nameField }}" value="0" />
                    <input type="checkbox" id="{{ $nameField }}" name="{{ $nameField }}" value="1" {{ $selectedOption ? 'checked' : '' }}>
                    <span class="slider round"></span>
                </label>

            @elseif ($field['type'] == 'image')

                <?php
                    $src = Storage::url(core()->getConfigData($name, $channel, $locale));
                    $result = core()->getConfigData($name, $channel, $locale);
                ?>

                @if ($result)
                    <a href="{{ $src }}" target="_blank">
                        <img src="{{ $src }}" class="configuration-image"/>
                    </a>
                @endif

                <input type="file" v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" value="{{ old($name) ?: core()->getConfigData($name, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;" style="padding-top: 5px;">

                @if ($result)
                    <div class="control-group" style="margin-top: 5px;">
                        <span class="checkbox">
                            <input type="checkbox" id="{{ $nameField }}[delete]"  name="{{ $nameField }}[delete]" value="1">

                            <label class="checkbox-view" for="delete"></label>
                                {{ __('admin::app.configuration.delete') }}
                        </span>
                    </div>
                @endif

            @elseif ($field['type'] == 'file')

                <?php
                    $result = core()->getConfigData($name, $channel, $locale);
                    $src = explode("/", $result);
                    $path = end($src);
                ?>

                @if ($result)
                    <a href="{{ route('admin.configuration.download', [request()->route('slug'), request()->route('slug2'), $path]) }}">
                        <i class="icon sort-down-icon download"></i>
                    </a>
                @endif

                <input type="file" v-validate="'{{ $validations }}'" class="control" id="{{ $nameField }}" name="{{ $nameField }}" value="{{ old($name) ?: core()->getConfigData($name, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;" style="padding-top: 5px;">

                @if ($result)
                    <div class="control-group" style="margin-top: 5px;">
                        <span class="checkbox">
                            <input type="checkbox" id="{{ $nameField }}[delete]"  name="{{ $nameField }}[delete]" value="1">

                            <label class="checkbox-view" for="delete"></label>
                                {{ __('admin::app.configuration.delete') }}
                        </span>
                    </div>
                @endif

            @endif

            @if (isset($field['info']))
                <span class="control-info mt-10">{{ trans($field['info']) }}</span>
            @endif

            <span class="control-error" @if ($field['type'] == 'multiselect')  v-if="errors.has('{{ $nameField }}[]')" @else  v-if="errors.has('{{ $nameField }}')" @endif
            >
            @if ($field['type'] == 'multiselect')
                @{{ errors.first('{!! $firstField !!}[{!! $secondField !!}][{!! $thirdField !!}][{!! $field['name'] !!}][]') }}
            @else
                @{{ errors.first('{!! $firstField !!}[{!! $secondField !!}][{!! $thirdField !!}][{!! $field['name'] !!}]') }}
            @endif
            </span>

        </div>

    @endif

@push('scripts')

<?php if ($field['type'] == 'country'): ?>

<script type="text/x-template" id="country-template">

    <div>
        <select type="text" v-validate="validations" class="control" :id="name" :name="name" v-model="country" data-vv-as="&quot;{{ __('admin::app.customers.customers.country') }}&quot;" @change="sendCountryCode">
            <option value=""></option>

            @foreach (core()->countries() as $country)

                <option value="{{ $country->code }}">{{ $country->name }}</option>

            @endforeach
        </select>
    </div>

</script>

<script>
    Vue.component('country', {

        template: '#country-template',

        inject: ['$validator'],

        props: ['country_code', 'name', 'validations'],

        data: function () {
            return {
                country: "",
            }
        },

        mounted: function () {
            this.country = this.country_code;
            this.sendCountryCode()
        },

        methods: {
            sendCountryCode: function () {
                this.$root.$emit('countryCode', this.country)
            },
        }
    });
</script>

<script type="text/x-template" id="state-template">

    <div>
        <input type="text" v-validate="validations" v-if="!haveStates()" class="control" v-model="state" :id="name" :name="name" data-vv-as="&quot;{{ __('admin::app.customers.customers.state') }}&quot;"/>

        <select v-validate="validations" v-if="haveStates()" class="control" v-model="state" :id="name" :name="name" data-vv-as="&quot;{{ __('admin::app.customers.customers.state') }}&quot;" >

            <option value="">{{ __('admin::app.customers.customers.select-state') }}</option>

            <option v-for='(state, index) in countryStates[country]' :value="state.code">
                @{{ state.default_name }}
            </option>

        </select>

    </div>

</script>

<script>
    Vue.component('state', {

        template: '#state-template',

        inject: ['$validator'],

        props: ['state_code', 'name', 'validations'],

        data: function () {
            return {
                state: "",

                country: "",

                countryStates: @json(core()->groupedStatesByCountries())
            }
        },

        mounted: function () {
            this.state = this.state_code
        },

        methods: {
            haveStates: function () {
                var this_this = this;

                this_this.$root.$on('countryCode', function (country) {
                    this_this.country = country;
                });

                if (this.countryStates[this.country] && this.countryStates[this.country].length)
                    return true;

                return false;
            },
        }
    });
</script>

<?php endif; ?>

<script type="text/x-template" id="depends-template">

    <div class="control-group"  :class="[errors.has(name) ? 'has-error' : '']" v-if="this.isVisible">
        <label :for="name" :class="[ isRequire ? 'required' : '']">
            @{{ field_name }}
            <span class="locale"> @{{ channel_locale }} </span>
        </label>

        <select v-if="this.options.length" v-validate= "validations" class="control" :id = "name" :name = "name" v-model="savedValue"
        :data-vv-as="field_name">
            <option v-for='(option, index) in this.options' :value="option.value"> @{{ option.title }} </option>
        </select>

        <input v-else type="text"  class="control" v-validate= "validations" :id = "name" :name = "name" v-model="savedValue"
        :data-vv-as="field_name">

        <span class="control-error" v-if="errors.has(name)">
            @{{ errors.first(name) }}
        </span>
    </div>

</script>

<script>
    Vue.component('depends', {

        template: '#depends-template',

        inject: ['$validator'],

        props: ['options', 'name', 'validations', 'depend', 'value', 'field_name', 'channel_locale', 'repository', 'result'],

        data: function() {
            return {
                isRequire: false,
                isVisible: false,
                savedValue: "",
            }
        },

        mounted: function () {
            var this_this = this;

            this_this.savedValue = this_this.result;

            if (this_this.validations || (this_this.validations.indexOf("required") != -1)) {
                this_this.isRequire = true;
            }

            $(document).ready(function(){
                var dependentElement = document.getElementById(this_this.depend);
                var dependValue = this_this.value;

                if (dependValue == 'true') {
                    dependValue = 1;
                } else if (dependValue == 'false') {
                    dependValue = 0;
                }

                $(document).on("change", "select.control", function() {
                    if (this_this.depend == this.name) {
                        if (this_this.value == this.value) {
                            this_this.isVisible = true;
                        } else {
                            this_this.isVisible = false;
                        }
                    }
                })

                if (dependentElement && dependentElement.value == dependValue) {
                    this_this.isVisible = true;
                } else {
                    this_this.isVisible = false;
                }

                if (this_this.result) {
                    if (dependentElement.value == this_this.value) {
                        this_this.isVisible = true;
                    } else {
                        this_this.isVisible = false;
                    }
                }
            });
        }
    });
</script>

@endpush
