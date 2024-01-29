<v-create-customer-address>
    <div class="inline-flex gap-x-2 mr-1 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md">
        <span class="icon-location text-2xl"></span>

        @lang('admin::app.customers.addresses.create.create-address-btn')
    </div>
</v-create-customer-address>

<!-- Customer Address Modal -->
@pushOnce('scripts')
    <!-- Customer Address Form -->
    <script
        type="text/x-template"
        id="v-create-customer-address-template"
    >
        <!-- Address Create Button -->
        @if (bouncer()->hasPermission('customers.addresses.create'))
            <div 
                class="inline-flex gap-x-2 items-center justify-between w-full max-w-max px-1 py-1.5 text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-md"
                @click="$refs.CustomerAddress.toggle()"
            >
                <span class="icon-location text-2xl"></span>

                @lang('admin::app.customers.addresses.create.create-address-btn')
            </div>
        @endif

        {!! view_render_event('admin.customers.addresses.create.before') !!}

        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, create)">

                {!! view_render_event('admin.customers.addresses.create.create_form_controls.before') !!}

                <!-- Address Create Modal -->
                <x-admin::modal ref="CustomerAddress">
                    <!-- Modal Header -->
                    <x-slot:header>
                        <p class="text-lg text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.customers.addresses.create.title')
                        </p>    
                    </x-slot>
    
                    <!-- Modal Content -->
                    <x-slot:content>
                        {!! view_render_event('admin.customers.addresses.create.before') !!}

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="customer_id"
                                :value="$customer->id"
                            />
                        </x-admin::form.control-group>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- Company Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.addresses.create.company-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="company_name"
                                    :label="trans('admin::app.customers.addresses.create.company-name')"
                                    :placeholder="trans('admin::app.customers.addresses.create.company-name')"
                                />

                                <x-admin::form.control-group.error control-name="company_name" />
                            </x-admin::form.control-group>

                            <!-- Vat Id -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.addresses.create.vat-id')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="vat_id"
                                    :label="trans('admin::app.customers.addresses.create.vat-id')"
                                    :placeholder="trans('admin::app.customers.addresses.create.vat-id')"
                                />

                                <x-admin::form.control-group.error control-name="vat_id" />
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- First Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.first-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="first_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.addresses.create.first-name')"
                                    :placeholder="trans('admin::app.customers.addresses.create.first-name')"
                                />

                                <x-admin::form.control-group.error control-name="first_name" />
                            </x-admin::form.control-group>

                            <!-- Last Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.last-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="last_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.addresses.create.last-name')"
                                    :placeholder="trans('admin::app.customers.addresses.create.last-name')"
                                />

                                <x-admin::form.control-group.error control-name="last_name" />
                            </x-admin::form.control-group>
                        </div>

                        <!-- Street Address -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.customers.addresses.create.street-address')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="address1[]"
                                name="address1[]"
                                rules="required"
                                :label="trans('admin::app.customers.addresses.create.street-address')"
                                :placeholder="trans('admin::app.customers.addresses.create.street-address')"
                            />

                            <x-admin::form.control-group.error control-name="address1[]" />
                        </x-admin::form.control-group>

                        @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                            @for ($i = 2; $i <= core()->getConfigData('customer.address.information.street_lines'); $i++)
                                <x-admin::form.control-group.control
                                    type="text"
                                    id="address{{ $i }}[]"
                                    name="address{{ $i }}[]"
                                    :label="trans('admin::app.customers.addresses.create.street-address')"
                                    :placeholder="trans('admin::app.customers.addresses.create.street-address')"
                                />
                            @endfor
                        @endif

                        <!--need to check this -->
                        <div v-if="streetLineCount && streetLineCount > 1" v-for="index in streetLineCount">
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.control
                                    type="text"
                                    ::id="'address_' + index"
                                    ::name="'address1[' + index + ']'"
                                    :label="trans('admin::app.customers.addresses.create.street-address')"
                                    :placeholder="trans('admin::app.customers.addresses.create.street-address')"
                                />
                        
                                <x-admin::form.control-group.error ::control-name="'address1[' + index + ']'" />
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- City -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.city')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="city"
                                    rules="required"
                                    :label="trans('admin::app.customers.addresses.create.city')"
                                    :placeholder="trans('admin::app.customers.addresses.create.city')"
                                />

                                <x-admin::form.control-group.error control-name="city" />
                            </x-admin::form.control-group>

                            <!-- PostCode -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.post-code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="postcode"
                                    rules="required|integer"
                                    :label="trans('admin::app.customers.addresses.create.post-code')"
                                    :placeholder="trans('admin::app.customers.addresses.create.post-code')"
                                />

                                <x-admin::form.control-group.error control-name="postcode" />
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- Country Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.country')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="country"
                                    rules="required"
                                    v-model="country"
                                    :label="trans('admin::app.customers.addresses.create.country')"
                                >
                                    @foreach (core()->countries() as $country)
                                        <option value="{{ $country->code }}">{{ $country->name }}</option>
                                    @endforeach
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="country" />
                            </x-admin::form.control-group>

                            <!-- State Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.state')
                                </x-admin::form.control-group.label>

                                <template v-if="haveStates()">
                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="state"
                                        name="state"
                                        rules="required"
                                        v-model="state"
                                        :label="trans('admin::app.customers.addresses.create.state')"
                                        :placeholder="trans('admin::app.customers.addresses.create.state')"
                                    >
                                        <option 
                                            v-for='(state, index) in countryStates[country]'
                                            :value="state.code"
                                            v-text="state.default_name"
                                        >
                                        </option>
                                    </x-admin::form.control-group.control>
                                </template>

                                <template v-else>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="state"
                                        rules="required"
                                        :label="trans('admin::app.customers.addresses.create.state')"
                                        :placeholder="trans('admin::app.customers.addresses.create.state')"
                                    />
                                </template>

                                <x-admin::form.control-group.error control-name="state" />
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!--Phone number -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.phone')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="phone"
                                    rules="required|integer"
                                    :label="trans('admin::app.customers.addresses.create.phone')"
                                    :placeholder="trans('admin::app.customers.addresses.create.phone')"
                                />

                                <x-admin::form.control-group.error control-name="phone" />
                            </x-admin::form.control-group>

                            <!-- Default Address -->
                            <div class="w-full mt-8">
                                <x-admin::form.control-group class="flex gap-2.5 items-center !mb-0">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        id="default_address"
                                        name="default_address"
                                        :value="1"
                                        for="default_address"
                                        :label="trans('admin::app.customers.addresses.create.default-address')"
                                        :checked="false"
                                    />

                                    <label
                                        class="text-xs text-gray-600 dark:text-gray-300 font-medium cursor-pointer"
                                        for="default_address"
                                    >
                                        @lang('admin::app.customers.addresses.create.default-address')
                                    </label>
                                </x-admin::form.control-group>
                            </div>
                        </div>

                        {!! view_render_event('bagisto.admin.customers.create.after') !!}
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <!-- Modal Submission -->
                        <div class="flex gap-x-2.5 items-center">
                            <button 
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.customers.addresses.create.save-btn-title') 
                            </button>
                        </div>
                    </x-slot>
                </x-admin::modal>

                {!! view_render_event('admin.customers.addresses.create.create_form_controls.after') !!}

            </form>
        </x-admin::form>

        {!! view_render_event('admin.customers.addresses.create.after') !!}

    </script>

    <script type="module">
        app.component('v-create-customer-address', {
            template: '#v-create-customer-address-template',

            data: function () {
                return {
                    country: "",

                    state: "",

                    countryStates: @json(core()->groupedStatesByCountries()),
                    
                    streetLineCount: 0,
                }
            },

            methods: {
                create(params, { resetForm, setErrors }) {
                    this.$axios.post('{{ route("admin.customers.customers.addresses.store", $customer->id) }}', params,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                    
                        .then((response) => {
                            this.$refs.CustomerAddress.toggle();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            window.location.reload();

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status ==422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

                haveStates: function () {
                    /*
                    * The double negation operator is used to convert the value to a boolean.
                    * It ensures that the final result is a boolean value,
                    * true if the array has a length greater than 0, and otherwise false.
                    */
                    return !!this.countryStates[this.country]?.length;
                },
            }
        })
    </script>
@endPushOnce