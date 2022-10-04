@php
    $dependField = $coreConfigRepository->getDependentFieldOrValue($field);
    $dependValue = $coreConfigRepository->getDependentFieldOrValue($field, 'value');

    $dependNameKey = $item['key'] . '.' . $dependField;
    $dependName = $coreConfigRepository->getNameField($dependNameKey);

    $field['options'] = $coreConfigRepository->getDependentFieldOptions($field, $value);

    $selectedOption = core()->getConfigData($nameKey, $channel, $locale) ?? '';
    $dependSelectedOption = core()->getConfigData($dependNameKey, $channel, $locale) ?? '';
@endphp

@if (strpos($field['validation'], 'required_if') !== false)
    <required-if
        name = "{{ $name }}"
        label = "{{ trans($field['title']) }}"
        :info = "'{{ trans($field['info'] ?? '') }}'"
        :options = '@json($field['options'])'
        :result = "'{{ $selectedOption }}'"
        :validations = "'{{ $validations }}'"
        :depend = "'{{ $dependName }}'"
        :depend-result= "'{{ $dependSelectedOption }}'"
        :channel_locale = "'{{ $channelLocaleInfo }}'"
    ></required-if>
@else
    <depends
        :options = '@json($field['options'])'
        name = "{{ $name }}"
        :validations = "'{{ $validations }}'"
        :depend = "'{{ $dependName }}'"
        :value = "'{{ $dependValue }}'"
        :field_name = "'{{ trans($field['title']) }}'"
        :channel_locale = "'{{ $channelLocaleInfo }}'"
        :result = "'{{ $selectedOption }}'"
        :depend-saved-value= "'{{ $dependSelectedOption }}'"
    ></depends>
@endif

@push('scripts')
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
                let self = this;

                self.savedValue = self.result;

                if (self.validations || (self.validations.indexOf("required") != -1)) {
                    self.isRequire = true;
                }

                $(document).ready(function(){
                    let dependentElement = document.getElementById(self.depend);
                    let dependValue = self.value;

                    if (dependValue == 'true') {
                        dependValue = 1;
                    } else if (dependValue == 'false') {
                        dependValue = 0;
                    }

                    $(document).on("change", "select.control", function() {
                        if (self.depend == this.name) {
                            if (self.value == this.value) {
                                self.isVisible = true;
                            } else {
                                self.isVisible = false;
                            }
                        }
                    })

                    if (dependentElement && dependentElement.value == dependValue) {
                        self.isVisible = true;
                    } else {
                        self.isVisible = false;
                    }

                    if (self.result) {
                        if (dependentElement.value == self.value) {
                            self.isVisible = true;
                        } else {
                            self.isVisible = false;
                        }
                    }
                });
            }
        });
    </script>
@endpush