<v-create-customer-form></v-create-customer-form>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-customer-form-template">
        <div>
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, create)">
                    <!-- Customer Create Modal -->
                    <x-admin::modal ref="customerCreateModal">
                        <x-slot:toggle>
                            <!-- Customer Create Button -->
                            @if (bouncer()->hasPermission('customers.customers.create'))
                                <button 
                                    type="button"
                                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                                >
                                    @lang('admin::app.customers.create.add-new-customer')
                                </button>
                            @endif
                        </x-slot:toggle>
        
                        <x-slot:header>
                            <!-- Modal Header -->
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.customers.create.create-customer')
                            </p>    
                        </x-slot:header>
        
                        <x-slot:content>
                            <!-- Modal Content -->
                            {!! view_render_event('bagisto.admin.customers.create.before') !!}

                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.create.first-name')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="first_name" 
                                        id="first_name" 
                                        :value="old('first_name')"
                                        rules="required"
                                        :label="trans('admin::app.customers.create.first-name')"
                                        :placeholder="trans('admin::app.customers.create.first-name')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="first_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
        
                                {!! view_render_event('bagisto.admin.customers.create.first_name.after') !!}
        
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.create.last-name')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="last_name" 
                                        id="last_name"
                                        :value="old('last_name')"
                                        rules="required"
                                        :label="trans('admin::app.customers.create.last-name')"
                                        :placeholder="trans('admin::app.customers.create.last-name')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="last_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
        
                                {!! view_render_event('bagisto.admin.customers.create.last_name.after') !!}
        
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.create.email')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="email"
                                        name="email"
                                        id="email"
                                        :value="old('name')"
                                        rules="required|email"
                                        :label="trans('admin::app.customers.create.email')"
                                        placeholder="email@example.com"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="email"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
        
                                {!! view_render_event('bagisto.admin.customers.create.email.after') !!}
        
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.create.contact-number')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="phone"
                                        id="phone"
                                        :value="old('phone')"
                                        rules="required|integer"
                                        :label="trans('admin::app.customers.create.contact-number')"
                                        :placeholder="trans('admin::app.customers.create.contact-number')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="phone"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
        
                                {!! view_render_event('bagisto.admin.customers.create.phone.after') !!}
            
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.create.date-of-birth')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="date_of_birth" 
                                        id="dob"
                                        :value="old('date_of_birth')"
                                        :label="trans('admin::app.customers.create.date-of-birth')"
                                        :placeholder="trans('admin::app.customers.create.date-of-birth')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="date_of_birth"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
        
                                {!! view_render_event('bagisto.admin.customers.create.date_of_birth.after') !!}
        
                                <div class="flex gap-[16px] max-sm:flex-wrap">
                                    <div class="w-full mb-[6px]">
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.customers.create.gender')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="gender"
                                                id="gender"
                                                rules="required"
                                                :label="trans('admin::app.customers.create.gender')"
                                            >
                                                <option value="">
                                                    @lang('admin::app.customers.create.select-gender')
                                                </option>

                                                <option value="Male">
                                                    @lang('admin::app.customers.create.male')
                                                </option>
                                                
                                                <option value="Female">
                                                    @lang('admin::app.customers.create.female')
                                                </option>

                                                <option value="Other">
                                                    @lang('admin::app.customers.create.other')
                                                </option>
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error
                                                control-name="gender"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
        
                                    {!! view_render_event('bagisto.admin.customers.create.gender.after') !!}
                                    
                                    <div class="w-full mb-[6px]">
                                        <x-admin::form.control-group>
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.customers.create.customer-group')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="customer_group_id"
                                                id="customerGroup"
                                                :label="trans('admin::app.customers.create.customer-group')"
                                            >
                                                <option value="">
                                                    @lang('admin::app.customers.create.select-customer-group')
                                                </option>
                                                @foreach ($groups as $group)
                                                    <option value="{{ $group->id }}"> {{ $group->name}} </option>
                                                @endforeach
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error
                                                control-name="customer_group_id"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>
                                </div>
                                {!! view_render_event('bagisto.admin.customers.create.after') !!}
                            </div>
                        </x-slot:content>
        
                        <x-slot:footer>
                            <!-- Modal Submission -->
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.customers.create.save-customer')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-create-customer-form', {
            template: '#v-create-customer-form-template',

            methods: {
                create(params, { resetForm, setErrors }) {
                   
                    this.$axios.post("{{ route('admin.customer.store') }}", params)
                        .then((response) => {
                            this.$refs.customerCreateModal.toggle();

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
