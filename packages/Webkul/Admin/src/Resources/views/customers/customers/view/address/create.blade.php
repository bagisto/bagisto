<v-create-customer-address @address-created="addressCreated">
    <div class="mr-1 inline-flex w-full max-w-max cursor-pointer items-center justify-between gap-x-2 px-1 py-1.5 text-center font-semibold text-gray-600 transition-all hover:rounded-md hover:bg-gray-200 dark:text-gray-300 dark:hover:bg-gray-800">
        <span class="icon-location text-2xl"></span>

        @lang('admin::app.customers.customers.view.address.create.create-address-btn')
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
                class="flex cursor-pointer items-center justify-between gap-1.5 px-2.5 text-blue-600 transition-all hover:underline"
                @click="$refs.createAddress.toggle()"
            >
                @lang('admin::app.customers.customers.view.address.create.create-btn')
            </div>
        @endif

        {!! view_render_event('bagisto.admin.customers.addresses.create.before') !!}

        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, create)">
                {!! view_render_event('bagisto.admin.customers.addresses.create.create_form_controls.before') !!}

                <!-- Address Create Modal -->
                <x-admin::modal ref="createAddress">
                    <!-- Modal Header -->
                    <x-slot:header>
                        <p class="text-lg font-bold text-gray-800 dark:text-white">
                            @lang('admin::app.customers.customers.view.address.create.title')
                        </p>    
                    </x-slot>
    
                    <!-- Modal Content -->
                    <x-slot:content>
                        {!! view_render_event('bagisto.admin.customers.addresses.create.before') !!}

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
                                    @lang('admin::app.customers.customers.view.address.create.company-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="company_name"
                                    :label="trans('admin::app.customers.customers.view.address.create.company-name')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.company-name')"
                                />

                                <x-admin::form.control-group.error control-name="company_name" />
                            </x-admin::form.control-group>

                            <!-- Vat Id -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.view.address.create.vat-id')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="vat_id"
                                    :label="trans('admin::app.customers.customers.view.address.create.vat-id')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.vat-id')"
                                />

                                <x-admin::form.control-group.error control-name="vat_id" />
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- First Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.first-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="first_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.view.address.create.first-name')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.first-name')"
                                />

                                <x-admin::form.control-group.error control-name="first_name" />
                            </x-admin::form.control-group>

                            <!-- Last Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.last-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="last_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.view.address.create.last-name')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.last-name')"
                                />

                                <x-admin::form.control-group.error control-name="last_name" />
                            </x-admin::form.control-group>
                        </div>

                        <!-- Street Address -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.customers.customers.view.address.create.street-address')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="address[0]"
                                name="address[0]"
                                class="mb-2"
                                rules="required"
                                :label="trans('admin::app.customers.customers.view.address.create.street-address')"
                                :placeholder="trans('admin::app.customers.customers.view.address.create.street-address')"
                            />

                            <x-admin::form.control-group.error
                                class="mb-2"
                                control-name="address[0]"
                            />

                            <x-admin::form.control-group.error control-name="address[]" />

                            @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                                @for ($i = 1; $i < core()->getConfigData('customer.address.information.street_lines'); $i++)
                                    <x-admin::form.control-group.control
                                        type="text"
                                        id="address[{{ $i }}]"
                                        name="address[{{ $i }}]"
                                        :label="trans('admin::app.customers.customers.view.address.create.street-address')"
                                        :placeholder="trans('admin::app.customers.customers.view.address.create.street-address')"
                                    />

                                    <x-admin::form.control-group.error control-name="address[{{ $i }}]" />
                                @endfor
                            @endif
                        </x-admin::form.control-group>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- City -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.city')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="city"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.view.address.create.city')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.city')"
                                />

                                <x-admin::form.control-group.error control-name="city" />
                            </x-admin::form.control-group>

                            <!-- PostCode -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.post-code')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="postcode"
                                    rules="required|integer"
                                    :label="trans('admin::app.customers.customers.view.address.create.post-code')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.post-code')"
                                />

                                <x-admin::form.control-group.error control-name="postcode" />
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- Country Name -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.country')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="country"
                                    rules="required"
                                    v-model="country"
                                    :label="trans('admin::app.customers.customers.view.address.create.country')"
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
                                    @lang('admin::app.customers.customers.view.address.create.state')
                                </x-admin::form.control-group.label>

                                <template v-if="haveStates()">
                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="state"
                                        name="state"
                                        rules="required"
                                        v-model="state"
                                        :label="trans('admin::app.customers.customers.view.address.create.state')"
                                        :placeholder="trans('admin::app.customers.customers.view.address.create.state')"
                                    >
                                        <option 
                                            v-for='(state, index) in countryStates[country]'
                                            :value="state.code"
                                        >
                                            @{{ state.default_name }}
                                        </option>
                                    </x-admin::form.control-group.control>
                                </template>

                                <template v-else>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="state"
                                        rules="required"
                                        :label="trans('admin::app.customers.customers.view.address.create.state')"
                                        :placeholder="trans('admin::app.customers.customers.view.address.create.state')"
                                    />
                                </template>

                                <x-admin::form.control-group.error control-name="state" />
                            </x-admin::form.control-group>
                        </div>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!--Phone number -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.phone')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="phone"
                                    rules="required|phone"
                                    :label="trans('admin::app.customers.customers.view.address.create.phone')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.phone')"
                                />

                                <x-admin::form.control-group.error control-name="phone" />
                            </x-admin::form.control-group>

                            <!-- E-mail -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.view.address.create.email')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="email"
                                    rules="required|email"
                                    :label="trans('admin::app.customers.customers.view.address.create.email')"
                                    :placeholder="trans('admin::app.customers.customers.view.address.create.email')"
                                />

                                <x-admin::form.control-group.error control-name="email" />
                            </x-admin::form.control-group>
                        </div>

                        <!-- Default Address -->
                        <div class="w-full">
                            <x-admin::form.control-group class="!mb-0 flex items-center gap-2.5">
                                <x-admin::form.control-group.control
                                    type="checkbox"
                                    id="default_address"
                                    name="default_address"
                                    :value="1"
                                    for="default_address"
                                    :label="trans('admin::app.customers.customers.view.address.create.default-address')"
                                    :checked="false"
                                />

                                <label
                                    class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                    for="default_address"
                                >
                                    @lang('admin::app.customers.customers.view.address.create.default-address')
                                </label>
                            </x-admin::form.control-group>

                            <x-admin::form.control-group.error control-name="default_address" />
                        </div>

                        {!! view_render_event('bagisto.admin.customers.create.after') !!}
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <!-- Modal Submission -->
                        <x-admin::button
                            button-type="submit"
                            class="primary-button justify-center"
                            :title="trans('admin::app.customers.customers.view.address.create.save-btn-title')"
                            ::loading="isUpdating"
                            ::disabled="isUpdating"
                        />
                    </x-slot>
                </x-admin::modal>

                {!! view_render_event('bagisto.admin.customers.addresses.create.create_form_controls.after') !!}

            </form>
        </x-admin::form>

        {!! view_render_event('bagisto.admin.customers.addresses.create.after') !!}

    </script>

    <script type="module">
        app.component('v-create-customer-address', {
            template: '#v-create-customer-address-template',

            emits: ['address-created'],

            data() {
                return {
                    country: "",

                    state: "",

                    countryStates: @json(core()->groupedStatesByCountries()),

                    isUpdating: false,
                };
            },

            methods: {
                create(params, { resetForm, setErrors }) {
                    this.isUpdating = true;

                    params.default_address = params.default_address ?? 0;

                    this.$axios.post('{{ route('admin.customers.customers.addresses.store', $customer->id) }}', params)
                        .then((response) => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.$emit('address-created', response.data.data);

                            resetForm();

                            this.isUpdating = false;

                            this.$refs.createAddress.toggle();
                        })
                        .catch(error => {
                            this.isUpdating = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },

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