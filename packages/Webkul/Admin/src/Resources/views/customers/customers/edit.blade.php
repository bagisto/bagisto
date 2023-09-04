<v-customer-edit-form></v-customer-edit-form>

@pushOnce('scripts')
    <script type="text/x-template" id="v-customer-edit-form-template">
        <!-- Customer Edit Button -->
        @if (bouncer()->hasPermission('customers.customers.edit'))
            <button 
                type="button"
                class="flex gap-[6px] items-center justify-between px-[10px] text-blue-600 cursor-pointer transition-all hover:underline"
                @click="$refs.customerEditModal.toggle()"
                >
                @lang('admin::app.customers.edit.edit-btn')
            </button>
        @endif

        <!-- Modal Form -->
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form
                @submit="handleSubmit($event, update)"
                ref="customerCreateForm"
            >
                <!-- Customer Edit Modal -->
                <x-admin::modal ref="customerEditModal">
                    <x-slot:header>
                        <!-- Modal Header -->
                        <p class="text-[18px] text-gray-800 font-bold">
                            @lang('admin::app.customers.edit.title')
                        </p>    
                    </x-slot:header>

                    <x-slot:content>
                        <!-- Modal Content -->
                        {!! view_render_event('bagisto.admin.customers.edit.before') !!}

                        <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>

                                <!--First Name -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.edit.first-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="first_name" 
                                        id="first_name" 
                                        :value="$customer->first_name"
                                        rules="required"
                                        :label="trans('admin::app.customers.edit.first-name')"
                                        :placeholder="trans('admin::app.customers.edit.first-name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="first_name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!--Last Name -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.edit.last-name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="last_name" 
                                        :value="$customer->last_name"
                                        id="last_name"
                                        rules="required"
                                        :label="trans('admin::app.customers.edit.last-name')"
                                        :placeholder="trans('admin::app.customers.edit.last-name')"
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
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.edit.email')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="email"
                                    name="email"
                                    :value="$customer->email"
                                    id="email"
                                    rules="required|email"
                                    :label="trans('admin::app.customers.edit.email')"
                                    placeholder="email@example.com"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error
                                    control-name="email"
                                >
                                </x-admin::form.control-group.error>
                            </x-admin::form.control-group>

                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <!-- Phone -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.edit.contact-number')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="phone"
                                        :value="$customer->phone"
                                        id="phone"
                                        rules="required|integer"
                                        :label="trans('admin::app.customers.edit.contact-number')"
                                        :placeholder="trans('admin::app.customers.edit.contact-number')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="phone"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Date -->
                                <x-admin::form.control-group class="w-full mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.customers.edit.date-of-birth')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="date"
                                        name="date_of_birth" 
                                        :value="$customer->date_of_birth"
                                        id="dob"
                                        :label="trans('admin::app.customers.edit.date-of-birth')"
                                        :placeholder="trans('admin::app.customers.edit.date-of-birth')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="date_of_birth"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <!-- Gender -->
                                <x-admin::form.control-group class="w-full">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.customers.edit.gender')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="gender"
                                        :value="$customer->gender"
                                        id="gender"
                                        rules="required"
                                        :label="trans('admin::app.customers.edit.gender')"
                                    >
                                        <option value="Male">
                                            @lang('admin::app.customers.edit.male')
                                        </option>

                                        <option value="Female">
                                            @lang('admin::app.customers.edit.female')
                                        </option>

                                        <option value="Other">
                                            @lang('admin::app.customers.edit.other')
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
                                        @lang('admin::app.customers.edit.customer-group')
                                    </x-admin::form.control-group.label>

                                    @php
                                        $selectedCustomerOption = !is_null($customer->customer_group_id) ? $customer->group->id : '';
                                    @endphp

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="customer_group_id"
                                        :value="$selectedCustomerOption"
                                        id="customerGroup" 
                                        :label="trans('admin::app.customers.edit.customer-group')"
                                    >
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">
                                                {{ $group->name}}
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="customer_group_id"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            <div class="flex gap-[16px] max-sm:flex-wrap">
                                <!-- Customer Status -->
                                <x-admin::form.control-group class="flex gap-[10px] w-full mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="status"
                                        :value="$customer->status"
                                        id="status"
                                        for="status"
                                        :checked="(bool)$customer->status"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label 
                                        for="status"
                                        class="text-gray-600 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.customers.edit.status')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.error
                                        control-name="status"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <!-- Customer Suspended Status -->
                                <x-admin::form.control-group class="flex gap-[10px] w-full mb-[10px]">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        name="is_suspended"
                                        :value="$customer->is_suspended"
                                        id="isSuspended"
                                        for="isSuspended"
                                        :checked="(bool)$customer->is_suspended"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.label 
                                        for="isSuspended"
                                        class="text-gray-600 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.customers.edit.suspended')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.error
                                        control-name="is_suspended"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                            </div>

                            {!! view_render_event('bagisto.admin.customers.edit.after') !!}

                        </div>
                    </x-slot:content>

                    <x-slot:footer>
                        <!-- Modal Submission -->
                        <div class="flex gap-x-[10px] items-center">
                            <button 
                                type="submit"
                                class="primary-button"
                            >
                                @lang('admin::app.customers.edit.save-btn')
                            </button>
                        </div>
                    </x-slot:footer>
                </x-admin::modal>
            </form>
        </x-admin::form>
    </script>

    <script type="module">
        app.component('v-customer-edit-form', {
            template: '#v-customer-edit-form-template',

            methods: {
                update(params, { resetForm, setErrors }) {
                    let formData = new FormData(this.$refs.customerCreateForm);

                    formData.append('_method', 'put');

                    this.$axios.post("{{ route('admin.customers.customers.update', $customer->id) }}", formData)
                        .then((response) => {
                            this.$refs.customerEditModal.toggle();

                            window.location.reload();

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