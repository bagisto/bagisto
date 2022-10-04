@inject('coreConfigRepository', 'Webkul\Core\Repositories\CoreConfigRepository')

@php
    $nameKey = $item['key'] . '.' . $field['name'];

    $name = $coreConfigRepository->getNameField($nameKey);

    $value = $coreConfigRepository->getValueByRepository($field);

    $validations = $coreConfigRepository->getValidations($field);

    $channelLocaleInfo = $coreConfigRepository->getChannelLocaleInfo($field, $channel, $locale);
@endphp

@if ($field['type'] == 'depends')

    @include('admin::configuration.dependent-field-type')

@else
    <div class="control-group {{ $field['type'] }}" @if ($field['type'] == 'multiselect') :class="[errors.has('{{ $name }}[]') ? 'has-error' : '']" @else :class="[errors.has('{{ $name }}') ? 'has-error' : '']" @endif>

        <label for="{{ $name }}" {{ !isset($field['validation']) || preg_match('/\brequired\b/', $field['validation']) == false ? '' : 'class=required' }}>

            {{ trans($field['title']) }}

            <span class="locale">{{ $channelLocaleInfo }}</span>

        </label>

        @if ($field['type'] == 'text')

            <input type="text" v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($nameKey) ?: (core()->getConfigData($nameKey, $channel, $locale) ? core()->getConfigData($nameKey, $channel, $locale) : ($field['default_value'] ?? '')) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">

        @elseif ($field['type'] == 'password')

            <input type="password" v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($nameKey) ?: core()->getConfigData($nameKey, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">

        @elseif ($field['type'] == 'number')

            <input type="number" min="{{ $field['name'] == 'minimum_order_amount' ? 1 : 0 }}" v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($nameKey) ?: core()->getConfigData($nameKey, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">

        @elseif ($field['type'] == 'color')

            <input type="color" v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($nameKey) ?: core()->getConfigData($nameKey, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">

        @elseif ($field['type'] == 'textarea')

            <textarea v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">{{ old($nameKey) ?: core()->getConfigData($nameKey, $channel, $locale) ?: (isset($field['default_value']) ? $field['default_value'] : '') }}</textarea>

        @elseif ($field['type'] == 'editor')

            <textarea v-validate="'{{ $validations }}'" class="editor control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;">{{ old($nameKey) ?: core()->getConfigData($nameKey, $channel, $locale) ?: (isset($field['default_value']) ? $field['default_value'] : '') }}</textarea>

        @elseif ($field['type'] == 'select')

            <select v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;" >

                @php $selectedOption = core()->getConfigData($nameKey, $channel, $locale) ?? ''; @endphp

                @if (isset($field['repository']))
                    @foreach ($value as $key => $option)

                        <option value="{{ $key }}" {{ $key == $selectedOption ? 'selected' : ''}}>
                        {{ trans($option) }}
                        </option>

                    @endforeach
                @else
                    @foreach ($field['options'] as $option)
                        @php
                            $value = ! isset($option['value']) ? null : ( $value = ! $option['value'] ? 0 : $option['value'] );
                        @endphp

                        <option value="{{ $value }}" {{ $value == $selectedOption ? 'selected' : ''}}>
                            {{ trans($option['title']) }}
                        </option>
                    @endforeach
                @endif

            </select>

        @elseif ($field['type'] == 'multiselect')

            <select v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}[]" data-vv-as="&quot;{{ trans($field['title']) }}&quot;"  multiple>

                @php $selectedOption = core()->getConfigData($nameKey, $channel, $locale) ?? ''; @endphp

                @if (isset($field['repository']))
                    @foreach ($value as $key => $option)

                        <option value="{{ $key }}" {{ in_array($key, explode(',', $selectedOption)) ? 'selected' : ''}}>
                            {{ trans($value[$key]) }}
                        </option>

                    @endforeach
                @else
                    @foreach ($field['options'] as $option)
                        @php
                            $value = ! isset($option['value']) ? null : ( $value = ! $option['value'] ? 0 : $option['value'] );
                        @endphp

                        <option value="{{ $value }}" {{ in_array($option['value'], explode(',', $selectedOption)) ? 'selected' : ''}}>
                            {{ trans($option['title']) }}
                        </option>
                    @endforeach
                @endif

            </select>

        @elseif ($field['type'] == 'country')

            @php $countryCode = core()->getConfigData($nameKey, $channel, $locale) ?? ''; @endphp

            <country
                :country_code = "'{{ $countryCode }}'"
                :validations = "'{{ $validations }}'"
                :name = "'{{ $name }}'"
            ></country>

        @elseif ($field['type'] == 'state')

            @php $stateCode = core()->getConfigData($nameKey, $channel, $locale) ?? ''; @endphp

            <state
                :state_code = "'{{ $stateCode }}'"
                :validations = "'{{ $validations }}'"
                :name = "'{{ $name }}'"
            ></state>

        @elseif ($field['type'] == 'boolean')

            @php $selectedOption = core()->getConfigData($nameKey, $channel, $locale) ?? ($field['default_value'] ?? ''); @endphp

            <label class="switch">
                <input type="hidden" name="{{ $name }}" value="0" />
                <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="1" {{ $selectedOption ? 'checked' : '' }}>
                <span class="slider round"></span>
            </label>

        @elseif ($field['type'] == 'image')

            @php
                $src = Storage::url(core()->getConfigData($nameKey, $channel, $locale));
                $result = core()->getConfigData($nameKey, $channel, $locale);
            @endphp

            @if ($result)
                <a href="{{ $src }}" target="_blank">
                    <img src="{{ $src }}" class="configuration-image"/>
                </a>
            @endif

            <input type="file" v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($nameKey) ?: core()->getConfigData($nameKey, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;" style="padding-top: 5px;">

            @if ($result)
                <div class="control-group" style="margin-top: 5px;">
                    <span class="checkbox">
                        <input type="checkbox" id="{{ $name }}[delete]"  name="{{ $name }}[delete]" value="1">

                        <label class="checkbox-view" for="delete"></label>
                            {{ __('admin::app.configuration.delete') }}
                    </span>
                </div>
            @endif

        @elseif ($field['type'] == 'file')

            @php
                $result = core()->getConfigData($nameKey, $channel, $locale);
                $src = explode("/", $result);
                $path = end($src);
            @endphp

            @if ($result)
                <a href="{{ route('admin.configuration.download', [request()->route('slug'), request()->route('slug2'), $path]) }}">
                    <i class="icon sort-down-icon download"></i>
                </a>
            @endif

            <input type="file" v-validate="'{{ $validations }}'" class="control" id="{{ $name }}" name="{{ $name }}" value="{{ old($nameKey) ?: core()->getConfigData($nameKey, $channel, $locale) }}" data-vv-as="&quot;{{ trans($field['title']) }}&quot;" style="padding-top: 5px;">

            @if ($result)
                <div class="control-group" style="margin-top: 5px;">
                    <span class="checkbox">
                        <input type="checkbox" id="{{ $name }}[delete]"  name="{{ $name }}[delete]" value="1">

                        <label class="checkbox-view" for="delete"></label>
                            {{ __('admin::app.configuration.delete') }}
                    </span>
                </div>
            @endif

        @endif

        @if (isset($field['info']))
            <span class="control-info mt-10">{{!! trans($field['info']) !!}}</span>
        @endif

        <span class="control-error" @if ($field['type'] == 'multiselect')  v-if="errors.has('{{ $name }}[]')" @else  v-if="errors.has('{{ $name }}')" @endif>
            @if ($field['type'] == 'multiselect')
                @{{ errors.first('{!! $name !!}[]') }}
            @else
                @{{ errors.first('{!! $name !!}') }}
            @endif
        </span>

    </div>

@endif

@push('scripts')
    @if ($field['type'] == 'country')
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
                        let self = this;

                        self.$root.$on('countryCode', function (country) {
                            self.country = country;
                        });

                        if (this.countryStates[this.country] && this.countryStates[this.country].length)
                            return true;

                        return false;
                    },
                }
            });
        </script>
    @endif
@endpush


@pushonce('scripts')
    @include('admin::layouts.tinymce')

    <script>
        $(document).ready(function () {
            tinyMCEHelper.initTinyMCE({
                selector: 'textarea.editor',
                height: 200,
                width: "100%",
                plugins: 'image imagetools media wordcount save fullscreen code table lists link hr',
                toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor link hr | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent  | removeformat | code | table',
                image_advtab: true,
            });
        });
    </script>
@endpushonce
