<?php
    $validations = [];
    $disabled = false;

    if (isset($field['validation'])) {
        array_push($validations, $field['validation']);
    }

    $validations = implode('|', array_filter($validations));

    $key = $item['key'];
    $key = explode(".", $key);
    $firstField = current($key);
    $secondField = next($key);
    $thirdField  = end($key);

    $name = $item['key'] . '.' . $field['name'];

    if (isset($field['repository'])) {
        $temp = explode("@", $field['repository']);
        $class = app(current($temp));
        $method = end($temp);
        $value = $class->$method();
    }

    $channel_locale = [];

    if (isset($field['channel_based']) && $field['channel_based'])
    {
        array_push($channel_locale, $channel);
    }

    if (isset($field['locale_based']) && $field['locale_based']) {
        array_push($channel_locale, $locale);
    }
?>

    <div class="control-group {{ $field['type'] }}" :class="[errors.has('{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]') ? 'has-error' : '']">

        <label for="{{ $name }}" {{ !isset($field['validation']) || strpos('required', $field['validation']) < 0 ? '' : 'class=required' }}>

            {{ trans($field['title']) }}

            @if (count($channel_locale))
                <span class="locale">[{{ implode(' - ', $channel_locale) }}]</span>
            @endif

        </label>

        @if ($field['type'] == 'text')

            <input type="text" v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" value="{{ old($name) ?: core()->getConfigData($name) }}" data-vv-as="&quot;{{ $field['name'] }}&quot;">

        @elseif ($field['type'] == 'textarea')

            <textarea v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" data-vv-as="&quot;{{ $field['name'] }}&quot;">{{ old($name) ?: core()->getConfigData($name) }}</textarea>

        @elseif ($field['type'] == 'select')

            <select v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" data-vv-as="&quot;{{ $field['name'] }}&quot;" >

                <?php
                    $selectedOption = core()->getConfigData($name) ?? '';
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
                            if ($option['value'] == false) {
                                $value = 0;
                            } else {
                                $value = $option['value'];
                            }
                        ?>

                        <option value="{{ $value }}" {{ $value == $selectedOption ? 'selected' : ''}}>
                            {{ trans($option['title']) }}
                        </option>
                    @endforeach
                @endif

            </select>

        @elseif ($field['type'] == 'multiselect')

            <select v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}][]" data-vv-as="&quot;{{ $field['name'] }}&quot;"  multiple>

                <?php
                    $selectedOption = core()->getConfigData($name) ?? '';
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
                            if ($option['value'] == false) {
                                $value = 0;
                            } else {
                                $value = $option['value'];
                            }
                        ?>

                        <option value="{{ $value }}" {{ in_array($option['value'], explode(',', $selectedOption)) ? 'selected' : ''}}>
                            {{ $option['title'] }}
                        </option>
                    @endforeach
                @endif

            </select>

        @elseif ($field['type'] == 'country')

            <?php
                $countryCode = core()->getConfigData($name) ?? '';
            ?>

            <country code = {{ $countryCode }}></country>

        @elseif ($field['type'] == 'state')

            <?php
                $stateCode = core()->getConfigData($name) ?? '';
            ?>

            <state code = {{ $stateCode }}></state>

        @elseif ($field['type'] == 'boolean')

            <select v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" data-vv-as="&quot;{{ $field['name'] }}&quot;">

                <?php
                    $selectedOption = core()->getConfigData($name) ?? '';
                ?>

                <option value="0" {{ $selectedOption ? '' : 'selected'}}>
                    {{ __('admin::app.configuration.no') }}
                </option>

                <option value="1" {{ $selectedOption ? 'selected' : ''}}>
                    {{ __('admin::app.configuration.yes') }}
                </option>

            </select>

        @elseif ($field['type'] == 'image')

            <?php
                $src = Storage::url(core()->getConfigData($name));
                $result = core()->getConfigData($name);
            ?>

            @if ($result)
                <a href="{{ $src }}" target="_blank">
                    <img src="{{ $src }}" class="configuration-image"/>
                </a>
            @endif

            <input type="file" v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" value="{{ old($name) ?: core()->getConfigData($name) }}" data-vv-as="&quot;{{ $field['name'] }}&quot;" style="padding-top: 5px;">

            @if ($result)
                <div class="control-group" style="margin-top: 5px;">
                    <span class="checkbox">
                        <input type="checkbox" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}][delete]"  name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}][delete]" value="1">

                        <label class="checkbox-view" for="delete"></label>
                            {{ __('admin::app.configuration.delete') }}
                    </span>
                </div>
            @endif

        @elseif ($field['type'] == 'file')

            <?php
                $result = core()->getConfigData($name);
                $src = explode("/", $result);
                $path = end($src);
            ?>

            @if ($result)
                <a href="{{ route('admin.configuration.download', [request()->route('slug'), request()->route('slug2'), $path]) }}">
                    <i class="icon sort-down-icon download"></i>
                </a>
            @endif

            <input type="file" v-validate="'{{ $validations }}'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" value="{{ old($name) ?: core()->getConfigData($name) }}" data-vv-as="&quot;{{ $field['name'] }}&quot;" style="padding-top: 5px;">

            @if ($result)
                <div class="control-group" style="margin-top: 5px;">
                    <span class="checkbox">
                        <input type="checkbox" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}][delete]"  name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}][delete]" value="1">

                        <label class="checkbox-view" for="delete"></label>
                            {{ __('admin::app.configuration.delete') }}
                    </span>
                </div>
            @endif

        @endif

        @if (isset($field['info']))
            <span class="control-info">{{ trans($field['info']) }}</span>
        @endif

        <span class="control-error" v-if="errors.has('{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]')">@{{ errors.first('{!! $firstField !!}[{!! $secondField !!}][{!! $thirdField !!}][{!! $field['name'] !!}]') }}</span>

    </div>


@push('scripts')

<script type="text/x-template" id="country-template">

    <div>
        <select type="text" v-validate="'required'" class="control" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][{{ $field['name'] }}]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][country]" v-model="country" data-vv-as="&quot;{{ __('admin::app.customers.customers.country') }}&quot;" @change="someHandler">
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

        props: ['code'],

        data: () => ({
            country: "",
        }),

        mounted() {
            this.country = this.code;
            this.someHandler()
        },

        methods: {
            someHandler() {
                this.$root.$emit('sendCountryCode', this.country)
            },
        }
    });
</script>

<script type="text/x-template" id="state-template">

    <div>
        <input type="text" v-validate="'required'" v-if="!haveStates()" class="control" v-model="state" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][state]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][state]" data-vv-as="&quot;{{ __('admin::app.customers.customers.state') }}&quot;"/>

        <select v-validate="'required'" v-if="haveStates()" class="control" v-model="state" id="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][state]" name="{{ $firstField }}[{{ $secondField }}][{{ $thirdField }}][state]" data-vv-as="&quot;{{ __('admin::app.customers.customers.state') }}&quot;" >

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

        props: ['code'],

        data: () => ({

            state: "",

            country: "",

            countryStates: @json(core()->groupedStatesByCountries())
        }),

        mounted() {
            this.state = this.code
        },

        methods: {
            haveStates() {
                this.$root.$on('sendCountryCode', (country) => {
                    this.country = country;
                })

                if (this.countryStates[this.country] && this.countryStates[this.country].length)
                    return true;

                return false;
            },
        }
    });
</script>

@endpush