<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.customers.index.title')
    </x-slot:title>

    <div class="flex justify-between items-center">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.customers.index.title')
        </p>

        <div class="flex gap-x-[10px] items-center">
            <!-- Dropdown -->
            <x-admin::dropdown position="bottom-right">
                <x-slot:toggle>
                    <span class="icon-setting p-[6px] rounded-[6px] text-[24px]  cursor-pointer transition-all hover:bg-gray-100"></span>
                </x-slot:toggle>

                <x-slot:content class="w-[174px] max-w-full !p-[8PX] border border-gray-300 rounded-[4px] z-10 bg-white shadow-[0px_8px_10px_0px_rgba(0,_0,_0,_0.2)]">
                    <div class="grid gap-[2px]">
                        <!-- Current Channel -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Channel - {{ core()->getCurrentChannel()->name }}
                            </p>
                        </div>

                        <!-- Current Locale -->
                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <p class="text-gray-600 font-semibold leading-[24px]">
                                Language - {{ core()->getCurrentLocale()->name }}
                            </p>
                        </div>

                        <div class="p-[6px] items-center cursor-pointer transition-all hover:bg-gray-100 hover:rounded-[6px]">
                            <!-- Export Modal -->
                            <x-admin::modal ref="exportModal">
                                <x-slot:toggle>
                                    <p class="text-gray-600 font-semibold leading-[24px]">
                                        Export                                            
                                    </p>
                                </x-slot:toggle>

                                <x-slot:header>
                                    <p class="text-[18px] text-gray-800 font-bold">
                                        @lang('Download')
                                    </p>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="p-[16px]">
                                        <x-admin::form action="">
                                            <x-admin::form.control-group>
                                                <x-admin::form.control-group.control
                                                    type="select"
                                                    name="format"
                                                    id="format"
                                                >
                                                    <option value="xls">XLS</option>
                                                    <option value="csv">CLS</option>
                                                </x-admin::form.control-group.control>
                                            </x-admin::form.control-group>
                                        </x-admin::form>
                                    </div>
                                </x-slot:content>
                                <x-slot:footer>
                                    <!-- Save Button -->
                                    <button
                                        type="submit" 
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('Export')
                                    </button>
                                </x-slot:footer>
                            </x-admin::modal>
                        </div>
                    </div>
                </x-slot:content>
            </x-admin::dropdown>

            <div class="flex gap-x-[10px] items-center">
                {{-- Customer Create Vue Component --}}
                <v-create-customer-form></v-create-customer-form>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-customer-form-template">
            <div>
                <!-- Create Button -->
                @if (bouncer()->hasPermission('customers.customers.create'))
                    <button
                        type="button"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        @click="$refs.customerCreateModal.toggle()"
                    >
                        @lang('admin::app.customers.index.create.create-btn')
                    </button>
                @endif

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Customer Create Modal -->
                        <x-admin::modal ref="customerCreateModal">
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.customers.index.create.title')
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                {!! view_render_event('bagisto.admin.customers.create.before') !!}

                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <!-- First Name -->
                                        <x-admin::form.control-group class="w-full mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.customers.index.create.first-name')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="first_name" 
                                                id="first_name" 
                                                rules="required"
                                                :label="trans('admin::app.customers.index.create.first-name')"
                                                :placeholder="trans('admin::app.customers.index.create.first-name')"
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
                                                @lang('admin::app.customers.index.create.last-name')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="last_name" 
                                                id="last_name"
                                                rules="required"
                                                :label="trans('admin::app.customers.index.create.last-name')"
                                                :placeholder="trans('admin::app.customers.index.create.last-name')"
                                            >
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error
                                                control-name="last_name"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                                    </div>

                                    <!-- Email -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.index.create.email')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="email"
                                            name="email"
                                            id="email"
                                            rules="required|email"
                                            :label="trans('admin::app.customers.index.create.email')"
                                            placeholder="email@example.com"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="email"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Contact Number -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.index.create.contact-number')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="phone"
                                            id="phone"
                                            rules="required|integer"
                                            :label="trans('admin::app.customers.index.create.contact-number')"
                                            :placeholder="trans('admin::app.customers.index.create.contact-number')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="phone"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
            
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label>
                                            @lang('admin::app.customers.index.create.date-of-birth')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="date"
                                            name="date_of_birth" 
                                            id="dob"
                                            :label="trans('admin::app.customers.index.create.date-of-birth')"
                                            :placeholder="trans('admin::app.customers.index.create.date-of-birth')"
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="date_of_birth"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
            
                                    <div class="flex gap-[16px] max-sm:flex-wrap">
                                        <!-- Gender -->
                                        <x-admin::form.control-group class="w-full">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.customers.index.create.gender')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="gender"
                                                id="gender"
                                                rules="required"
                                                :label="trans('admin::app.customers.index.create.gender')"
                                            >
                                                <option value="Male">
                                                    @lang('admin::app.customers.index.create.male')
                                                </option>
                                                
                                                <option value="Female">
                                                    @lang('admin::app.customers.index.create.female')
                                                </option>

                                                <option value="Other">
                                                    @lang('admin::app.customers.index.create.other')
                                                </option>
                                            </x-admin::form.control-group.control>
                
                                            <x-admin::form.control-group.error
                                                control-name="gender"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <!-- Customer Group -->
                                        <x-admin::form.control-group class="w-full">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.customers.index.create.customer-group')
                                            </x-admin::form.control-group.label>
                
                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="customer_group_id"
                                                id="customerGroup"
                                                :label="trans('admin::app.customers.index.create.customer-group')"
                                            >
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

                                    {!! view_render_event('bagisto.admin.customers.create.after') !!}

                                </div>
                            </x-slot:content>
            
                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-[10px] items-center">
                                    <!-- Save Button -->
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.customers.index.create.save-btn')
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
                                this.$refs.customerCreateModal.close();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

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
</x-admin::layouts>