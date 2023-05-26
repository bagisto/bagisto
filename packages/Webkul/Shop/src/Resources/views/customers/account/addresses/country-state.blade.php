<country-state></country-state>

@push('scripts')
    <script type="text/x-template" id="country-state-template">
        <div>
            <div class="control-group" :class="[errors.has('country') ? 'has-error' : '']">
                <label for="country" class="{{ core()->isCountryRequired() ? 'required' : '' }}">
                    {{ __('shop::app.customer.account.address.create.country') }}
                </label>

                <select
                    class="control"
                    id="country"
                    type="text"
                    name="country"
                    v-model="country"
                    v-validate="'{{ core()->isCountryRequired() ? 'required' : '' }}'"
                    data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.country') }}&quot;">
                    <option value=""></option>

                    @foreach (core()->countries() as $country)
                        <option {{ $country->code === $defaultCountry ? 'selected' : '' }}  value="{{ $country->code }}">{{ $country->name }}</option>
                    @endforeach
                </select>

                <span
                    class="control-error"
                    v-text="errors.first('country')"
                    v-if="errors.has('country')">
                </span>
            </div>

            <div class="control-group" :class="[errors.has('state') ? 'has-error' : '']">
                <label for="state" class="{{ core()->isStateRequired() ? 'required' : '' }}">
                    {{ __('shop::app.customer.account.address.create.state') }}
                </label>

                <input
                    class="control"
                    id="state"
                    type="text"
                    name="state"
                    v-model="state"
                    v-validate="'{{ core()->isStateRequired() ? 'required' : '' }}'"
                    data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.state') }}&quot;"
                    v-if="! haveStates()"/>

                <select
                    class="control"
                    id="state"
                    name="state"
                    v-model="state"
                    v-validate="'{{ core()->isStateRequired() ? 'required' : '' }}'"
                    data-vv-as="&quot;{{ __('shop::app.customer.account.address.create.state') }}&quot;"
                    v-if="haveStates()">
                    <option value="">{{ __('shop::app.customer.account.address.create.select-state') }}</option>

                    <option v-for='(state, index) in countryStates[country]' :value="state.code">
                        @{{ state.default_name }}
                    </option>
                </select>

                <span class="control-error" v-if="errors.has('state')">
                    @{{ errors.first('state') }}
                </span>
            </div>
        </div>
    </script>

    <script>
        Vue.component('country-state', {
            template: '#country-state-template',

            inject: ['$validator'],

            data() {
                return {
                    country: "{{ $countryCode ?? $defaultCountry }}",

                    state: "{{ $stateCode }}",

                    countryStates: @json(core()->groupedStatesByCountries())
                }
            },

            methods: {
                haveStates() {
                    if (this.countryStates[this.country] && this.countryStates[this.country].length)
                        return true;

                    return false;
                },
            }
        });
    </script>
@endpush
