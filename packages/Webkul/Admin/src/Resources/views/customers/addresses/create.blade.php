<v-create-customer-address></v-create-customer-address>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-customer-address-template">
        <div>
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- Address Create Modal -->
                    <x-admin::modal ref="addressCreateModal">
                        <x-slot:toggle>
                            <!-- Address Create Button -->
                            @if (bouncer()->hasPermission('customers.addresses.create '))
                                <div class="inline-flex gap-x-[8px] items-center justify-between w-full max-w-max px-[4px] py-[6px] text-gray-600 font-semibold text-center  cursor-pointer transition-all hover:bg-gray-200 hover:rounded-[6px]">
                                    <span class="icon-location text-[24px] "></span>
                                    Add New Address
                                </div>
                            @endif
                        </x-slot:toggle>
        
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                Create Customer's Address
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            {!! view_render_event('admin.customer.addresses.create.before') !!}

                            <x-admin::form.control-group class="mb-[10px]">
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="customer_id"
                                    :value="$customer->id"
                                >
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>

                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                    @lang('shop::app.customers.account.addresses.company-name')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="company_name"
                                                :label="trans('shop::app.customers.account.addresses.company-name')"
                                                :placeholder="trans('shop::app.customers.account.addresses.company-name')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="company_name"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.vat-id')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="vat_id"
                                                :label="trans('shop::app.customers.account.addresses.vat-id')"
                                                :placeholder="trans('shop::app.customers.account.addresses.vat-id')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="vat_id"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </div>

                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.first-name')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="first_name"
                                                rules="required"
                                                :label="trans('shop::app.customers.account.addresses.first-name')"
                                                :placeholder="trans('shop::app.customers.account.addresses.first-name')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="first_name"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.last-name')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="last_name"
                                                rules="required"
                                                :label="trans('shop::app.customers.account.addresses.last-name')"
                                                :placeholder="trans('shop::app.customers.account.addresses.last-name')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="last_name"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </div>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('shop::app.customers.account.addresses.street-address')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="address1[]"
                                        id="address_0"
                                        rules="required"
                                        :label="trans('shop::app.customers.account.addresses.street-address')"
                                        :placeholder="trans('shop::app.customers.account.addresses.street-address')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="address1[]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!--need to check this -->
                                @if (
                                    core()->getConfigData('customer.address.information.street_lines')
                                    && core()->getConfigData('customer.address.information.street_lines') > 1
                                )
                                    <div v-for="(address, index) in addressLines" :key="index">
                                        <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('shop::app.customers.account.addresses.street-address')
                                        </x-admin::form.control-group.label>
                                
                                        <x-admin::form.control-group.control
                                            type="text"
                                            :name="'address1[' + index + ']'"
                                            :id="'address_' + index"
                                            rules="required"
                                            :label="trans('shop::app.customers.account.addresses.street-address')"
                                            :placeholder="trans('shop::app.customers.account.addresses.street-address')"
                                        >
                                        </x-admin::form.control-group.control>
                                
                                        <x-admin::form.control-group.error
                                            :control-name="'address1[' + index + ']'"
                                        >
                                        </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                @endif

                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.city')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="city"
                                                rules="required"
                                                :label="trans('shop::app.customers.account.addresses.city')"
                                                :placeholder="trans('shop::app.customers.account.addresses.city')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="city"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.post-code')
                                            </x-admin::form.control-group.label>
        
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="postcode"
                                                rules="required|integer"
                                                :label="trans('shop::app.customers.account.addresses.post-code')"
                                                :placeholder="trans('shop::app.customers.account.addresses.post-code')"
                                            >
                                            </x-admin::form.control-group.control>
        
                                            <x-admin::form.control-group.error
                                                control-name="postcode"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </div>

                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.country')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="country"
                                                rules="required"
                                                :label="trans('shop::app.customers.account.addresses.country')"
                                            >
                                                <option value="">@lang('Select Country')</option>

                                                @foreach (core()->countries() as $country)
                                                    <option 
                                                        {{ $country->code === config('app.default_country') ? 'selected' : '' }}  
                                                        value="{{ $country->code }}"
                                                    >
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="country"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.state')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="state"
                                                rules="required"
                                                :label="trans('shop::app.customers.account.addresses.state')"
                                                :placeholder="trans('shop::app.customers.account.addresses.state')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="state"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </div>

                                <div class="flex gap-[16px] max-sm:flex-wrap items-center">
                                    <div class="w-full">
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('shop::app.customers.account.addresses.phone')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="phone"
                                                rules="required|integer"
                                                :label="trans('shop::app.customers.account.addresses.phone')"
                                                :placeholder="trans('shop::app.customers.account.addresses.phone')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="phone"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                    <div class="w-full">
                                        <label 
                                            class="flex gap-[4px] w-max items-center p-[6px] cursor-pointer select-none mt-[10px]"
                                            for="default_address"
                                        >
                                            <input 
                                                type="checkbox" 
                                                name="default_address" 
                                                id="default_address"
                                                value="1"
                                                class="hidden peer"
                                            >
                                
                                            <span class="icon-uncheckbox rounded-[6px] text-[24px] cursor-pointer peer-checked:icon-checked peer-checked:text-navyBlue"></span>
                                
                                            <p class="flex gap-x-[4px] items-center cursor-pointer">
                                                Default Address
                                            </p>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.admin.customers.create.after') !!}
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('Save Address')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-create-customer-address', {
            template: '#v-create-customer-address-template',

            props: {
                addressLines: {
                type: Number,
                default: 0, // Default to 0 if no data is provided
                }
            },

            methods: {

                create(params, { resetForm, setErrors }) {
                    this.$axios.post('{{ route("admin.customer.addresses.store", $customer->id) }}', params,
                        {
                            headers: {
                            'Content-Type': 'multipart/form-data'
                            }
                        }
                        )
                    
                        .then((response) => {
                            this.$refs.addressCreateModal.toggle();

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status ==422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        })
    </script>
@endPushOnce