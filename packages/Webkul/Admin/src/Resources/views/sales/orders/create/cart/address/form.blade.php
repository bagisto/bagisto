@pushOnce('scripts')
    @php
        $addressChannelCode = $cart->customer?->channel?->code;

        $addressCountries = \Webkul\Customer\Facades\AddressSettings::allowedCountries($addressChannelCode)
            ->map(fn ($country) => [
                'id' => $country->id,
                'code' => $country->code,
                'name' => $country->name,
            ]);
    @endphp

    <script
        type="text/x-template"
        id="v-checkout-address-form-template"
    >
        <div class="mt-2">
            <x-admin::form.control-group class="hidden">
                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.id'"
                    ::value="address.id"
                />
            </x-admin::form.control-group>

            <!-- Company Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.sales.orders.create.cart.address.company-name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.company_name'"
                    ::value="address.company_name"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.company-name')"
                />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.company_name.after') !!}

            <!-- VatId Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label>
                    @lang('admin::app.sales.orders.create.cart.address.vat-id')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.vat_id'"
                    ::value="address.vat_id"
                    :label="trans('admin::app.sales.orders.create.cart.address.vat-id')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.vat-id')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.vat_id'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.vat_id.after') !!}

            <!-- First Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.first-name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.first_name'"
                    ::value="address.first_name"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.first-name')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.first-name')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.first_name'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.first_name.after') !!}

            <!-- Last Name -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.last-name')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.last_name'"
                    ::value="address.last_name"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.last-name')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.last-name')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.last_name'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.last_name.after') !!}

            <!-- Email -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.email')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="email"
                    ::name="controlName + '.email'"
                    ::value="address.email"
                    rules="required|email"
                    :label="trans('admin::app.sales.orders.create.cart.address.email')"
                    placeholder="email@example.com"
                />

                <x-admin::form.control-group.error ::name="controlName + '.email'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.email.after') !!}

            @php
                $streetLines = \Webkul\Customer\Facades\AddressSettings::streetLines($addressChannelCode);

                $firstLineTitle = $streetLines > 1
                    ? trans('admin::app.sales.orders.create.cart.address.street-address-line', ['line' => 1])
                    : trans('admin::app.sales.orders.create.cart.address.street-address');
            @endphp

            <!-- Street Address -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.street-address')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.address.[0]'"
                    ::value="address.address[0]"
                    rules="required|address"
                    :label="$firstLineTitle"
                    :placeholder="$firstLineTitle"
                />

                <x-admin::form.control-group.error
                    class="mb-2"
                    ::name="controlName + '.address.[0]'"
                />

                @for ($i = 1; $i < $streetLines; $i++)
                    <x-admin::form.control-group.control
                        type="text"
                        ::name="controlName + '.address.[{{ $i }}]'"
                        ::value="address.address[{{ $i }}]"
                        class="mt-2"
                        rules="address"
                        :label="trans('admin::app.sales.orders.create.cart.address.street-address-line', ['line' => $i + 1])"
                        :placeholder="trans('admin::app.sales.orders.create.cart.address.street-address-line', ['line' => $i + 1])"
                    />

                    <x-admin::form.control-group.error
                        class="mb-2"
                        ::name="controlName + '.address.[{{ $i }}]'"
                    />
                @endfor
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.address.after') !!}

            <!-- Country -->
            <x-admin::form.control-group class="mb-4!">
                <x-admin::form.control-group.label class="{{ \Webkul\Customer\Facades\AddressSettings::isCountryRequired($addressChannelCode) ? 'required' : '' }} mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.country')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="select"
                    ::name="controlName + '.country'"
                    ::value="address.country"
                    v-model="selectedCountry"
                    rules="{{ \Webkul\Customer\Facades\AddressSettings::isCountryRequired($addressChannelCode) ? 'required' : '' }}"
                    :label="trans('admin::app.sales.orders.create.cart.address.country')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.country')"
                    @change="selectedState = ''"
                >
                    <option value="">
                        @lang('admin::app.sales.orders.create.cart.address.select-country')
                    </option>

                    <option
                        v-for="country in countries"
                        :value="country.code"
                    >
                        @{{ country.name }}
                    </option>
                </x-admin::form.control-group.control>

                <x-admin::form.control-group.error ::name="controlName + '.country'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.country.after') !!}

            <!-- State -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="{{ \Webkul\Customer\Facades\AddressSettings::isStateRequired($addressChannelCode) ? 'required' : '' }} mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.state')
                </x-admin::form.control-group.label>

                <template v-if="states">
                    <template v-if="haveStates">
                        <x-admin::form.control-group.control
                            type="select"
                            ::name="controlName + '.state'"
                            ::value="address.state"
                            v-model="selectedState"
                            rules="{{ \Webkul\Customer\Facades\AddressSettings::isStateRequired($addressChannelCode) ? 'required' : '' }}"
                            :label="trans('admin::app.sales.orders.create.cart.address.state')"
                            :placeholder="trans('admin::app.sales.orders.create.cart.address.state')"
                        >
                            <option value="">
                                @lang('admin::app.sales.orders.create.cart.address.select-state')
                            </option>

                            <option
                                v-for='state in states[selectedCountry]'
                                :value="state.code"
                            >
                                @{{ state.default_name }}
                            </option>
                        </x-admin::form.control-group.control>
                    </template>

                    <template v-else>
                        <x-admin::form.control-group.control
                            type="text"
                            ::name="controlName + '.state'"
                            ::value="address.state"
                            v-model="selectedState"
                            rules="{{ \Webkul\Customer\Facades\AddressSettings::isStateRequired($addressChannelCode) ? 'required' : '' }}"
                            :label="trans('admin::app.sales.orders.create.cart.address.state')"
                            :placeholder="trans('admin::app.sales.orders.create.cart.address.state')"
                        />
                    </template>
                </template>

                <x-admin::form.control-group.error ::name="controlName + '.state'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.state.after') !!}

            <!-- City -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.city')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.city'"
                    ::value="address.city"
                    rules="required"
                    :label="trans('admin::app.sales.orders.create.cart.address.city')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.city')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.city'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.city.after') !!}

            <!-- Postcode -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="{{ \Webkul\Customer\Facades\AddressSettings::isPostCodeRequired($addressChannelCode) ? 'required' : '' }} mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.postcode')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.postcode'"
                    ::value="address.postcode"
                    rules="{{ \Webkul\Customer\Facades\AddressSettings::isPostCodeRequired($addressChannelCode) ? 'required' : '' }}|postcode"
                    :label="trans('admin::app.sales.orders.create.cart.address.postcode')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.postcode')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.postcode'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.postcode.after') !!}

            <!-- Phone Number -->
            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required mt-0!">
                    @lang('admin::app.sales.orders.create.cart.address.phone')
                </x-admin::form.control-group.label>

                <x-admin::form.control-group.control
                    type="text"
                    ::name="controlName + '.phone'"
                    ::value="address.phone"
                    rules="required|numeric"
                    :label="trans('admin::app.sales.orders.create.cart.address.phone')"
                    :placeholder="trans('admin::app.sales.orders.create.cart.address.phone')"
                />

                <x-admin::form.control-group.error ::name="controlName + '.phone'" />
            </x-admin::form.control-group>

            {!! view_render_event('bagisto.admin.sales.order.create.cart.address.form.phone.after') !!}
        </div>
    </script>

    <script type="module">
        app.component('v-checkout-address-form', {
            template: '#v-checkout-address-form-template',

            props: {
                controlName: {
                    type: String,
                    required: true,
                },

                address: {
                    type: Object,

                    default: () => ({
                        id: 0,
                        company_name: '',
                        first_name: '',
                        last_name: '',
                        email: '',
                        address: [],
                        country: '',
                        state: '',
                        city: '',
                        postcode: '',
                        phone: '',
                    }),
                },
            },

            data() {
                return {
                    selectedCountry: this.address.country,

                    selectedState: this.address.state,

                    countries: @json($addressCountries),

                    states: @json(\Webkul\Customer\Facades\AddressSettings::allowedGroupedStates($addressChannelCode)),
                }
            },

            computed: {
                haveStates() {
                    return !! this.states[this.selectedCountry]?.length;
                },
            },
        });
    </script>
@endPushOnce