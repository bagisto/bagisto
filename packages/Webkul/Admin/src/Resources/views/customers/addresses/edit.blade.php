<v-edit-customer-address address="{{ $address }}"></v-edit-customer-address>

@pushOnce('scripts')
    <!-- Customer Address Form -->
    <script type="text/x-template" id="v-edit-customer-address-template">
        <div>
            <!-- Address Edit Button -->
            @if (bouncer()->hasPermission('customers.addresses.edit'))
                <p 
                    class="text-blue-600 cursor-pointer transition-all hover:underline"
                    @click="$refs.CustomerAddressEdit.toggle()"
                >
                    @lang('admin::app.customers.customers.view.edit')
                </p>
            @endif

            {!! view_render_event('admin.customers.addresses.edit.before') !!}

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"     
            >

                {!! view_render_event('admin.customers.addresses.edit.edit_form_controls.before') !!}

                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="customer_id"
                        :value="$customer->id"
                    >
                    </x-admin::form.control-group.control>
                </x-admin::form.control-group>

                <x-admin::form.control-group class="mb-[10px]">
                    <x-admin::form.control-group.control
                        type="hidden"
                        name="address_id"
                        v-model="addressData.id"
                    >
                    </x-admin::form.control-group.control>
                </x-admin::form.control-group>

                <form
                    @submit="handleSubmit($event, update)"
                    ref="addressCreateForm"
                >
                    <!-- Address Edit Modal -->
                    <x-admin::modal ref="CustomerAddressEdit">

                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.customers.addresses.edit.title')
                            </p>    
                        </x-slot:header>

                        <x-slot:content>
                            <!-- Modal Content -->
                            {!! view_render_event('admin.customer.addresses.edit.before') !!}
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800  ">
                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <!-- Company Name -->
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.addresses.edit.company-name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="company_name"
                                            :label="trans('admin::app.customers.addresses.edit.company-name')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.company-name')"
                                            v-model="addressData.company_name"
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
                                            @lang('admin::app.customers.addresses.edit.vat-id')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="vat_id"
                                            v-model="addressData.vat_id"
                                            :label="trans('admin::app.customers.addresses.edit.vat-id')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.vat-id')"
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
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.addresses.edit.first-name')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="first_name"
                                            v-model="addressData.first_name"
                                            rules="required"
                                            :label="trans('admin::app.customers.addresses.edit.first-name')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.first-name')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="first_name"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Last Name -->
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.addresses.edit.last-name')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="last_name"
                                            v-model="addressData.last_name"
                                            rules="required"
                                            :label="trans('admin::app.customers.addresses.edit.last-name')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.last-name')"
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
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.addresses.edit.street-address')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="address1[]"
                                        id="address_0"
                                        v-model="addressData.address1"
                                        rules="required"
                                        :label="trans('admin::app.customers.addresses.edit.street-address')"
                                        :placeholder="trans('admin::app.customers.addresses.edit.street-address')"
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
                                            v-model="addressData.address{{ $i }}"
                                        >
                                        </x-shop::form.control-group.control>
                                    @endfor
                                @endif

                                <!--need to check this -->
                                <div
                                    v-if="streetLineCount && streetLineCount > 1"
                                    v-for="index in streetLineCount"
                                >
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.control
                                            type="text"
                                            ::name="'address1[' + index + ']'"
                                            ::id="'address_' + index"
                                            v-model="addressData.address1[' + index + ']"
                                            :label="trans('admin::app.customers.addresses.edit.street-address')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.street-address')"
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
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.addresses.edit.city')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="city"
                                            v-model="addressData.city"
                                            rules="required"
                                            :label="trans('admin::app.customers.addresses.edit.city')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.city')"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.error
                                            control-name="city"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- PostCode -->
                                    <x-admin::form.control-group class="w-full mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.addresses.edit.post-code')
                                        </x-admin::form.control-group.label>
    
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="postcode"
                                            v-model="addressData.postcode"
                                            rules="required|integer"
                                            :label="trans('admin::app.customers.addresses.edit.post-code')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.post-code')"
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
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.addresses.edit.country')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="country"
                                            v-model="addressData.country"
                                            rules="required"
                                            :label="trans('admin::app.customers.addresses.edit.country')"
                                        >
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
                                                ::value="addressData.state"
                                                rules="required"
                                                :label="trans('admin::app.customers.addresses.create.state')"
                                                :placeholder="trans('admin::app.customers.addresses.create.state')"
                                                v-model="addressData.state"
                                            >
                                                <option 
                                                    v-for='(state, index) in countryStates[addressData.country]'
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
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.addresses.edit.phone')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="phone"
                                            v-model="addressData.phone"
                                            rules="required|integer"
                                            :label="trans('admin::app.customers.addresses.edit.phone')"
                                            :placeholder="trans('admin::app.customers.addresses.edit.phone')"
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
                                            id="default_address"
                                            v-model="addressData.default_address"
                                            :label="trans('admin::app.customers.addresses.edit.default-address')"
                                            ::checked="addressData.default_address"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.label 
                                            for="default_address"
                                            class="text-gray-600 dark:text-gray-300 font-semibold cursor-pointer" 
                                        >
                                            @lang('admin::app.customers.addresses.edit.default-address')
                                        </x-admin::form.control-group.label>

                                        <x-admin::form.control-group.error
                                            control-name="default_address"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.admin.customers.edit.after') !!}
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="primary-button"
                                >
                                    @lang('admin::app.customers.addresses.edit.save-btn-title') 
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>

                {!! view_render_event('admin.customers.addresses.edit.edit_form_controls.after') !!}

            </x-admin::form>

            {!! view_render_event('admin.customers.addresses.edit.after') !!}

        </div>
    </script>

    <script type="module">
        app.component('v-edit-customer-address', {
            template: '#v-edit-customer-address-template',

            props: {
                address: {
                    type: String,
                }
            },

            data() {
                return {
                    addressData: {},
                    countryStates: @json(core()->groupedStatesByCountries()),
                    streetLineCount: 0,
                };
            },

            mounted() {
                this.addressData = JSON.parse(this.address);
            },

            methods: {
                update(params, { resetForm, setErrors }) {
                    if (! params.default_address) {
                        delete params.default_address;
                    }

                    let formData = new FormData(this.$refs.addressCreateForm);

                    formData.append('_method', 'put');

                    this.$axios.post(`{{ route('admin.customers.customers.addresses.update', '') }}/${params?.address_id}`, formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((response) => {
                        this.$refs.CustomerAddressEdit.toggle();
                        
                        this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                        window.location.reload();

                        resetForm();
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            setErrors(error.response.data.errors);
                        }
                    });
                },

                haveStates() {
                    return !!this.countryStates[this.addressData.country]?.length;
                }
            }
        });
    </script>
@endPushOnce
