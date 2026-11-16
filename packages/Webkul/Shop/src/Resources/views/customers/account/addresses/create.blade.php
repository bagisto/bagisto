<x-shop::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('shop::app.customers.account.addresses.create.add-address')
    </x-slot>

    <!-- Breadcrumbs -->
    @if ((core()->getConfigData('general.general.breadcrumbs.shop')))
        @section('breadcrumbs')
            <x-shop::breadcrumbs name="addresses.create" />
        @endSection
    @endif

    <div class="max-md:hidden">
        <x-shop::layouts.account.navigation />
    </div>

    <div class="mx-4 flex-auto max-md:mx-6 max-sm:mx-4">
        <div class="mb-8 flex items-center max-md:mb-5">
            <!-- Back Button -->
            <a
                class="grid md:hidden"
                href="{{ route('shop.customers.account.addresses.index') }}"
            >
                <span class="icon-arrow-left rtl:icon-arrow-right text-2xl"></span>
            </a>

            <h2 class="text-2xl font-medium max-md:text-xl max-sm:text-base ltr:ml-2.5 md:ltr:ml-0 rtl:mr-2.5 md:rtl:mr-0">
                @lang('shop::app.customers.account.addresses.create.add-address')
            </h2>
        </div>

        <v-create-customer-address>
            <!--Address Shimmer-->
            <x-shop::shimmer.form.control-group :count="10" />
        </v-create-customer-address>

    </div>

    @push('scripts')
        <script
            type="text/x-template"
            id="v-create-customer-address-template"
        >
            <div>
                <x-shop::form :action="route('shop.customers.account.addresses.store')">
                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.before') !!}

                    <!--Company Name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.account.addresses.create.company-name')
                        </x-shop::form.control-group.label>
            
                        <x-shop::form.control-group.control
                            type="text"
                            name="company_name"
                            :value="old('company_name')"
                            :label="trans('shop::app.customers.account.addresses.create.company-name')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.company-name')"
                        />
            
                        <x-shop::form.control-group.error control-name="company_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.company_name.after') !!}

                    <!-- First Name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.account.addresses.create.first-name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="first_name"
                            rules="required"
                            :value="old('first_name')"
                            :label="trans('shop::app.customers.account.addresses.create.first-name')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.first-name')"
                        />

                        <x-shop::form.control-group.error control-name="first_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.first_name.after') !!}

                    <!-- Last Name  -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.account.addresses.create.last-name')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="last_name"
                            rules="required"
                            :value="old('last_name')"
                            :label="trans('shop::app.customers.account.addresses.create.last-name')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.last-name')"
                        />

                        <x-shop::form.control-group.error control-name="last_name" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.last_name.after') !!}

                    <!-- E-mail -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.account.addresses.create.email')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="email"
                            name="email"
                            rules="required|email"
                            :value="old('email')"
                            :label="trans('shop::app.customers.account.addresses.create.email')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.email')"
                        />

                        <x-shop::form.control-group.error control-name="email" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.email.after') !!}

                    <!-- Vat Id -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label>
                            @lang('shop::app.customers.account.addresses.create.vat-id')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="vat_id"
                            :value="old('vat_id')"
                            :label="trans('shop::app.customers.account.addresses.create.vat-id')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.vat-id')"
                        />

                        <x-shop::form.control-group.error control-name="vat_id" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.vat_id.after') !!}

                    @php
                        $streetLines = \Webkul\Customer\Facades\AddressSettings::streetLines();

                        $firstLineTitle = $streetLines > 1
                            ? trans('shop::app.customers.account.addresses.create.street-address-line', ['line' => 1])
                            : trans('shop::app.customers.account.addresses.create.street-address');
                    @endphp

                    <!-- Street Address -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.account.addresses.create.street-address')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="address[]"
                            rules="required|address"
                            :value="collect(old('address'))->first()"
                            :label="$firstLineTitle"
                            :placeholder="$firstLineTitle"
                        />

                        <x-shop::form.control-group.error
                            class="mb-2"
                            control-name="address[]"
                        />

                        @for ($i = 1; $i < $streetLines; $i++)
                            <x-shop::form.control-group.control
                                type="text"
                                name="address[{{ $i }}]"
                                :value="old('address.'.$i)"
                                rules="address"
                                :label="trans('shop::app.customers.account.addresses.create.street-address-line', ['line' => $i + 1])"
                                :placeholder="trans('shop::app.customers.account.addresses.create.street-address-line', ['line' => $i + 1])"
                            />

                            <x-shop::form.control-group.error
                                class="mb-2"
                                name="address[{{ $i }}]"
                            />
                        @endfor
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.street_address.after') !!}

                    <!-- Country List-->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="{{ \Webkul\Customer\Facades\AddressSettings::isCountryRequired() ? 'required' : '' }}">
                            @lang('shop::app.customers.account.addresses.create.country')
                        </x-shop::form.control-group.label>
            
                        <x-shop::form.control-group.control
                            type="select"
                            name="country"
                            rules="{{ \Webkul\Customer\Facades\AddressSettings::isCountryRequired() ? 'required' : '' }}"
                            v-model="country"
                            :aria-label="trans('shop::app.customers.account.addresses.create.country')"
                            :label="trans('shop::app.customers.account.addresses.create.country')"
                            @change="state = ''"
                        >
                            <option value="">
                                @lang('shop::app.customers.account.addresses.create.select-country')
                            </option>
            
                            @foreach (\Webkul\Customer\Facades\AddressSettings::allowedCountries() as $country)
                                <option value="{{ $country->code }}">{{ $country->name }}</option>
                            @endforeach
                        </x-shop::form.control-group.control>
            
                        <x-shop::form.control-group.error control-name="country" />
                    </x-shop::form.control-group>
        
                    <!-- State Name -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="{{ \Webkul\Customer\Facades\AddressSettings::isStateRequired() ? 'required' : '' }}">
                            @lang('shop::app.customers.account.addresses.create.state')
                        </x-shop::form.control-group.label>
        
                        <template v-if="haveStates()">
                            <x-shop::form.control-group.control
                                type="select"
                                id="state"
                                name="state"
                                rules="{{ \Webkul\Customer\Facades\AddressSettings::isStateRequired() ? 'required' : '' }}"
                                v-model="state"
                                :label="trans('shop::app.customers.account.addresses.create.state')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.state')"
                            >
                                <option 
                                    v-for='(state, index) in countryStates[country]'
                                    :value="state.code"
                                >
                                    @{{ state.default_name }}
                                </option>
                            </x-shop::form.control-group.control>
                        </template>
        
                        <template v-else>
                            <x-shop::form.control-group.control
                                type="text"
                                name="state"
                                :value="old('state')"
                                v-model="state"
                                rules="{{ \Webkul\Customer\Facades\AddressSettings::isStateRequired() ? 'required' : '' }}"
                                :label="trans('shop::app.customers.account.addresses.create.state')"
                                :placeholder="trans('shop::app.customers.account.addresses.create.state')"
                            />
                        </template>
        
                        <x-shop::form.control-group.error control-name="state" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.state.after') !!}

                    <!-- City -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.account.addresses.create.city')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="city"
                            rules="required"
                            :value="old('city')"
                            :label="trans('shop::app.customers.account.addresses.create.city')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.city')"
                        />

                        <x-shop::form.control-group.error control-name="city" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.city.after') !!}

                    <!-- Post Code -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="{{ \Webkul\Customer\Facades\AddressSettings::isPostCodeRequired() ? 'required' : '' }}">
                            @lang('shop::app.customers.account.addresses.create.post-code')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="postcode"
                            rules="{{ \Webkul\Customer\Facades\AddressSettings::isPostCodeRequired() ? 'required' : '' }}|postcode"
                            :value="old('postcode')"
                            :label="trans('shop::app.customers.account.addresses.create.post-code')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.post-code')"
                        />

                        <x-shop::form.control-group.error control-name="postcode" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.postcode.after') !!}

                    <!-- Contact -->
                    <x-shop::form.control-group>
                        <x-shop::form.control-group.label class="required">
                            @lang('shop::app.customers.account.addresses.create.phone')
                        </x-shop::form.control-group.label>

                        <x-shop::form.control-group.control
                            type="text"
                            name="phone"
                            rules="required|phone"
                            :value="old('phone')"
                            :label="trans('shop::app.customers.account.addresses.create.phone')"
                            :placeholder="trans('shop::app.customers.account.addresses.create.phone')"
                        />

                        <x-shop::form.control-group.error control-name="phone" />
                    </x-shop::form.control-group>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.phone.after') !!}

                    <!-- Set As Default -->
                    <div class="text-md mb-4 flex select-none items-center gap-x-1.5 text-zinc-500">
                        <input
                            type="checkbox"
                            name="default_address"
                            value="1"
                            id="default_address"
                            class="peer sr-only cursor-pointer"
                        >

                        <label
                            class="icon-uncheck peer-checked:icon-check-box cursor-pointer text-2xl text-navyBlue peer-checked:text-navyBlue peer-focus-visible:ring-2 peer-focus-visible:ring-navyBlue peer-focus-visible:ring-offset-2 peer-focus-visible:outline-hidden rounded-sm"
                            for="default_address"
                        >
                        </label>

                        <label 
                            class="block cursor-pointer text-base max-md:text-sm"
                            for="default_address"
                        >
                            @lang('shop::app.customers.account.addresses.create.set-as-default')
                        </label>
                    </div>

                    <button
                        type="submit"
                        class="primary-button m-0 block rounded-2xl px-11 py-3 text-center text-base max-md:w-full max-md:max-w-full max-md:rounded-lg max-md:py-2 max-sm:py-1.5"
                    >
                        @lang('shop::app.customers.account.addresses.create.save')
                    </button>

                    {!! view_render_event('bagisto.shop.customers.account.addresses.create_form_controls.after') !!}
                </x-shop::form>
                {!! view_render_event('bagisto.shop.customers.account.address.create.after') !!}
            </div>
        </script>
    
        <script type="module">
            app.component('v-create-customer-address', {
                template: '#v-create-customer-address-template',
    
                data() {
                    return {
                        country: "{{ old('country') }}",

                        state: "{{ old('state') }}",

                        countryStates: @json(\Webkul\Customer\Facades\AddressSettings::allowedGroupedStates()),
                    }
                },
    
                methods: {
                    /**
                     * Whether the selected country has states.
                     */
                    haveStates() {
                        return !!this.countryStates[this.country]?.length;
                    },
                }
            });
        </script>
    @endpush

</x-shop::layouts.account>
