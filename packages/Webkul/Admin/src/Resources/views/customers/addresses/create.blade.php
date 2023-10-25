<v-create-customer-address>
    <div class="inline-flex gap-x-[8px] mr-[4px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]">
        <span class="icon-location text-[24px]"></span>

        @lang('admin::app.customers.addresses.create.create-address-btn')
    </div>
</v-create-customer-address>

{{-- Customer Address Modal --}}
@pushOnce('scripts')
    <!-- Customer Address Form -->
    <script
        type="text/x-template"
        id="v-create-customer-address-template"
    >
        <!-- Address Create Button -->
        @if (bouncer()->hasPermission('customers.addresses.create'))
            <div 
                class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 dark:text-gray-300 font-semibold text-center cursor-pointer transition-all hover:bg-gray-200 dark:hover:bg-gray-800 hover:rounded-[6px]"
                @click="$refs.CustomerAddress.toggle()"
            >
                <span class="icon-location text-[24px]"></span>

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
                    <x-slot:header>
                        <!-- Modal Header -->
                        <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.customers.addresses.create.title')
                        </p>    
                    </x-slot:header>
    
                    <x-slot:content>
                        <!-- Modal Content -->
                        {!! view_render_event('admin.customers.addresses.create.before') !!}

                        <x-admin::form.control-group class="mb-[10px]">
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="customer_id"
                                :value="$customer->id"
                            >
                            </x-admin::form.control-group.control>
                        </x-admin::form.control-group>

                        <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <!-- Company Name -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.addresses.create.company-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="company_name"
                                        :label="trans('admin::app.customers.addresses.create.company-name')"
                                        :placeholder="trans('admin::app.customers.addresses.create.company-name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="company_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Vat Id -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.addresses.create.vat-id')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="vat_id"
                                        :label="trans('admin::app.customers.addresses.create.vat-id')"
                                        :placeholder="trans('admin::app.customers.addresses.create.vat-id')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="vat_id"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <!-- First Name -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.addresses.create.first-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="first_name"
                                        rules="required"
                                        :label="trans('admin::app.customers.addresses.create.first-name')"
                                        :placeholder="trans('admin::app.customers.addresses.create.first-name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="first_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Last Name -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.addresses.create.last-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="last_name"
                                        rules="required"
                                        :label="trans('admin::app.customers.addresses.create.last-name')"
                                        :placeholder="trans('admin::app.customers.addresses.create.last-name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="last_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <!-- Street Address -->
                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.addresses.create.street-address')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="address1[]"
                                    id="address1[]"
                                    rules="required"
                                    :label="trans('admin::app.customers.addresses.create.street-address')"
                                    :placeholder="trans('admin::app.customers.addresses.create.street-address')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="address1[]"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            @if (core()->getConfigData('customer.address.information.street_lines') > 1)
                                @for ($i = 2; $i <= core()->getConfigData('customer.address.information.street_lines'); $i++)
                                    <x-shop::form.control-group.control
                                        type="text"
                                        name="address{{ $i }}[]"
                                        id="address{{ $i }}[]"
                                        :label="trans('admin::app.customers.addresses.create.street-address')"
                                        :placeholder="trans('admin::app.customers.addresses.create.street-address')"
                                    >
                                    </x-shop::form.control-group.control>
                                @endfor
                            @endif

                            <!--need to check this -->
                            <div v-if="streetLineCount && streetLineCount > 1" v-for="index in streetLineCount">
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="text"
                                        ::name="'address1[' + index + ']'"
                                        ::id="'address_' + index"
                                        :label="trans('admin::app.customers.addresses.create.street-address')"
                                        :placeholder="trans('admin::app.customers.addresses.create.street-address')"
                                    >
                                    </x-admin::form.control-group.control>
                            
                                    <x-admin::form.control-group.error
                                        ::control-name="'address1[' + index + ']'"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <!-- City -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.addresses.create.city')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="city"
                                        rules="required"
                                        :label="trans('admin::app.customers.addresses.create.city')"
                                        :placeholder="trans('admin::app.customers.addresses.create.city')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="city"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- PostCode -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.addresses.create.post-code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="postcode"
                                        rules="required|integer"
                                        :label="trans('admin::app.customers.addresses.create.post-code')"
                                        :placeholder="trans('admin::app.customers.addresses.create.post-code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="postcode"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <!-- Country Name -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.addresses.create.country')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="country"
                                        rules="required"
                                        :label="trans('admin::app.customers.addresses.create.country')"
                                        v-model="country"
                                    >
                                        @foreach (core()->countries() as $country)
                                            <option value="{{ $country->code }}">{{ $country->name }}</option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="country"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- State Name -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.addresses.create.state')
                                    </x-admin::form.control-group.label>

                                    <template v-if="haveStates()">
                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="state"
                                            id="state"
                                            rules="required"
                                            :label="trans('admin::app.customers.addresses.create.state')"
                                            :placeholder="trans('admin::app.customers.addresses.create.state')"
                                            v-model="state"
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
                                        >
                                        </x-admin::form.control-group.control>
                                    </template>

                                    <x-admin::form.control-group.error
                                        control-name="state"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex gap-[16px] max-sm:flex-wrap items-center">
                                <!--Phone number -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.addresses.create.phone')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="phone"
                                        rules="required|integer"
                                        :label="trans('admin::app.customers.addresses.create.phone')"
                                        :placeholder="trans('admin::app.customers.addresses.create.phone')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="phone"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                
                                <!-- Default Address -->
                                <x-admin::form.control-group class="flex gap-[10px] w-full mt-[20px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="default_address"
                                        :value="1"
                                        id="default_address"
                                        for="default_address"
                                        :label="trans('admin::app.customers.addresses.create.default-address')"
                                        :checked="false"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label 
                                        for="default_address"
                                        class="text-gray-600 dark:text-gray-300 font-semibold cursor-pointer" 
                                    >
                                        @lang('admin::app.customers.addresses.create.default-address')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.error
                                        control-name="default_address"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>
                        </div>

                        {!! view_render_event('bagisto.admin.customers.create.after') !!}
                    </x-slot:content>
    
                    <x-slot:footer>
                        <!-- Modal Submission -->
                        <div class="flex gap-x-[10px] items-center">
                            <button 
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.customers.addresses.create.save-btn-title') 
                            </button>
                        </div>
                    </x-slot:footer>
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