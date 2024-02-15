<v-customer-edit :customer="customer">
    <div type="button"
        class="flex gap-1.5 items-center justify-between px-2.5 text-blue-600 cursor-pointer transition-all hover:underline">
        @lang('admin::app.customers.customers.edit.edit-btn')
    </div>
</v-customer-edit>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-customer-edit-template"
    >
        <!-- Customer Edit Button -->
        @if (bouncer()->hasPermission('customers.customers.edit'))
            <div 
                class="flex gap-1.5 items-center justify-between px-2.5 text-blue-600 cursor-pointer transition-all hover:underline"
                @click="$refs.customerEditModal.toggle()"
            >
                @lang('admin::app.customers.customers.edit.edit-btn')
            </div>
        @endif

        {!! view_render_event('admin.customers.customers.edit.edit_form_controls.before') !!}

        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, edit)" ref="customerEditForm">
                <!-- Customer Edit Modal -->
                <x-admin::modal ref="customerEditModal">
                    <!-- Modal Header -->
                    <x-slot:header>
                        <p class="text-lg text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.customers.customers.edit.title')
                        </p>    
                    </x-slot>
    
                    <!-- Modal Content -->
                    <x-slot:content>
                        {!! view_render_event('bagisto.admin.customers.customers.edit.before') !!}

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!--First Name -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.edit.first-name')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="first_name" 
                                    id="first_name" 
                                    ::value="customer.first_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.edit.first-name')"
                                    :placeholder="trans('admin::app.customers.customers.edit.first-name')"
                                />
            
                                <x-admin::form.control-group.error control-name="first_name" />
                            </x-admin::form.control-group>
            
                            <!--Last Name -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.edit.last-name')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="last_name" 
                                    ::value="customer.last_name"
                                    id="last_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.edit.last-name')"
                                    :placeholder="trans('admin::app.customers.customers.edit.last-name')"
                                />
            
                                <x-admin::form.control-group.error control-name="last_name" />
                            </x-admin::form.control-group>
                        </div>
            
                        <!-- Email -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.customers.customers.edit.email')
                            </x-admin::form.control-group.label>
            
                            <x-admin::form.control-group.control
                                type="email"
                                name="email"
                                ::value="customer.email"
                                id="email"
                                rules="required|email"
                                :label="trans('admin::app.customers.customers.edit.email')"
                                placeholder="email@example.com"
                            />
            
                            <x-admin::form.control-group.error control-name="email" />
                        </x-admin::form.control-group>
            
                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- Phone -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.edit.contact-number')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="text"
                                    name="phone"
                                    ::value="customer.phone"
                                    id="phone"
                                    rules="integer"
                                    :label="trans('admin::app.customers.customers.edit.contact-number')"
                                    :placeholder="trans('admin::app.customers.customers.edit.contact-number')"
                                />
            
                                <x-admin::form.control-group.error control-name="phone" />
                            </x-admin::form.control-group>
            
                            <!-- Date -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.edit.date-of-birth')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="date"
                                    name="date_of_birth" 
                                    id="dob"
                                    ::value="customer.date_of_birth"
                                    :label="trans('admin::app.customers.customers.edit.date-of-birth')"
                                    :placeholder="trans('admin::app.customers.customers.edit.date-of-birth')"
                                />
                                
                                <x-admin::form.control-group.error control-name="date_of_birth" />
                            </x-admin::form.control-group>
                        </div>
                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- Gender -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.edit.gender')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="select"
                                    name="gender"
                                    ::value="customer.gender"
                                    id="gender"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.edit.gender')"
                                >
                                    <option value="Male">
                                        @lang('admin::app.customers.customers.edit.male')
                                    </option>
            
                                    <option value="Female">
                                        @lang('admin::app.customers.customers.edit.female')
                                    </option>
            
                                    <option value="Other">
                                        @lang('admin::app.customers.customers.edit.other')
                                    </option>
                                </x-admin::form.control-group.control>
            
                                <x-admin::form.control-group.error control-name="gender" />
                            </x-admin::form.control-group>
            
                            <!-- Customer Group -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.edit.customer-group')
                                </x-admin::form.control-group.label>
            
                                <x-admin::form.control-group.control
                                    type="select"
                                    name="customer_group_id"
                                    ::value="customer.customer_group_id"
                                    id="customerGroup" 
                                    :label="trans('admin::app.customers.customers.edit.customer-group')"
                                >
                                <option 
                                    v-for="group in groups" 
                                    :value="group.id"
                                    selected
                                > 
                                    @{{ group.name }} 
                                </option>
                                </x-admin::form.control-group.control>
                            </x-admin::form.control-group>
                        </div>
            
                        <div class="flex gap-60 max-sm:flex-wrap">
                            <!-- Customer Status -->
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.marketing.promotions.cart-rules.edit.status')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="status"
                                    value="0"
                                />
                                
                                <x-admin::form.control-group.control
                                    type="switch"
                                    name="status"
                                    :value="1"
                                    :label="trans('admin::app.marketing.promotions.cart-rules.edit.status')"
                                    ::checked="customer.status"
                                />
                            </x-admin::form.control-group>

                            <!-- Customer Suspended Status -->
                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.edit.suspended')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="is_suspended"
                                    value="0"
                                />
                                
                                <x-admin::form.control-group.control
                                    type="switch"
                                    name="is_suspended"
                                    :value="1"
                                    :label="trans('admin::app.customers.customers.edit.suspended')"
                                    ::checked="customer.is_suspended"
                                />
                            </x-admin::form.control-group>
                        </div>
                        
                        {!! view_render_event('bagisto.admin.customers.customers.edit.after') !!}
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <div class="flex gap-x-2.5 items-center">
                            <button 
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.customers.customers.edit.save-btn')
                            </button>
                        </div>
                    </x-slot>
                </x-admin::modal>
            </form>
        </x-admin::form>

        {!! view_render_event('admin.customers.customers.edit.edit_form_controls.after') !!}
    </script>

    <script type="module">
        app.component('v-customer-edit', {
            template: '#v-customer-edit-template',

            props: ['customer'],

            data() {
                return {
                    groups: @json($groups),
                };
            },

            methods: {
                edit(params, {resetForm, setErrors}) {
                    let formData = new FormData(this.$refs.customerEditForm);

                    formData.append('_method', 'put');

                    this.$axios.post('{{ route('admin.customers.customers.update', $customer->id) }}', formData)
                        .then((response) => {
                            this.$refs.customerEditModal.close();

                            this.$emitter.emit('add-flash', {
                                type: 'success',
                                message: response.data.message
                            });

                            this.$parent.$parent.$parent.$refs.customerDetails.get()

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            }
        })
    </script>
@endPushOnce
