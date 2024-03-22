@pushOnce('scripts')
    <script type="text/x-template" id="v-create-customer-form-template">
        <x-admin::form
            v-slot="{ meta, errors, handleSubmit }"
            as="div"
        >
            <form @submit="handleSubmit($event, create)">
                <!-- Customer Create Modal -->
                <x-admin::modal ref="customerCreateModal">
                    <!-- Modal Header -->
                    <x-slot:header>
                        <p class="text-lg text-gray-800 dark:text-white font-bold">
                            @lang('admin::app.customers.customers.index.create.title')
                        </p>
                    </x-slot>

                    <!-- Modal Content -->
                    <x-slot:content>
                        {!! view_render_event('bagisto.admin.customers.create.before') !!}

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- First Name -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.index.create.first-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.index.create.first-name')"
                                    :placeholder="trans('admin::app.customers.customers.index.create.first-name')"
                                />

                                <x-admin::form.control-group.error control-name="first_name" />
                            </x-admin::form.control-group>

                            <!-- Last Name -->
                            <x-admin::form.control-group class="w-full mb-2.5">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.index.create.last-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.index.create.last-name')"
                                    :placeholder="trans('admin::app.customers.customers.index.create.last-name')"
                                />

                                <x-admin::form.control-group.error control-name="last_name" />
                            </x-admin::form.control-group>
                        </div>

                        <!-- Email -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.customers.customers.index.create.email')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="email"
                                id="email"
                                name="email"
                                rules="required|email"
                                :label="trans('admin::app.customers.customers.index.create.email')"
                                placeholder="email@example.com"
                            />

                            <x-admin::form.control-group.error control-name="email" />
                        </x-admin::form.control-group>

                        <!-- Contact Number -->
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.customers.customers.index.create.contact-number')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                id="phone"
                                name="phone"
                                rules="integer"
                                :label="trans('admin::app.customers.customers.index.create.contact-number')"
                                :placeholder="trans('admin::app.customers.customers.index.create.contact-number')"
                            />

                            <x-admin::form.control-group.error control-name="phone" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label>
                                @lang('admin::app.customers.customers.index.create.date-of-birth')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="date"
                                id="dob"
                                name="date_of_birth"
                                :label="trans('admin::app.customers.customers.index.create.date-of-birth')"
                                :placeholder="trans('admin::app.customers.customers.index.create.date-of-birth')"
                            />

                            <x-admin::form.control-group.error control-name="date_of_birth" />
                        </x-admin::form.control-group>

                        <div class="flex gap-4 max-sm:flex-wrap">
                            <!-- Gender -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.customers.customers.index.create.gender')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="gender"
                                    name="gender"
                                    rules="required"
                                    :label="trans('admin::app.customers.customers.index.create.gender')"
                                >
                                    <option value="">
                                        @lang('admin::app.customers.customers.index.create.select-gender')
                                    </option>

                                    <option value="Male">
                                        @lang('admin::app.customers.customers.index.create.male')
                                    </option>

                                    <option value="Female">
                                        @lang('admin::app.customers.customers.index.create.female')
                                    </option>

                                    <option value="Other">
                                        @lang('admin::app.customers.customers.index.create.other')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="gender" />
                            </x-admin::form.control-group>

                            <!-- Customer Group -->
                            <x-admin::form.control-group class="w-full">
                                <x-admin::form.control-group.label>
                                    @lang('admin::app.customers.customers.index.create.customer-group')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    id="customerGroup"
                                    name="customer_group_id"
                                    :label="trans('admin::app.customers.customers.index.create.customer-group')"
                                    ::value="groups[0]?.id"
                                >
                                    <option 
                                        v-for="group in groups" 
                                        :value="group.id"
                                        selected
                                    > 
                                        @{{ group.name }} 
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="customer_group_id" />
                            </x-admin::form.control-group>
                        </div>

                        {!! view_render_event('bagisto.admin.customers.create.after') !!}
                    </x-slot>

                    <!-- Modal Footer -->
                    <x-slot:footer>
                        <!-- Modal Submission -->
                        <div class="flex gap-x-2.5 items-center">
                            <!-- Save Button -->
                            <x-admin::button
                                button-type="submit"
                                class="primary-button justify-center"
                                :title="trans('admin::app.customers.customers.index.create.save-btn')"
                                ::loading="isStoring"
                                ::disabled="isStoring"
                            />
                        </div>
                    </x-slot>
                </x-admin::modal>
            </form>
        </x-admin::form>
    </script>

    <script type="module">
        app.component('v-create-customer-form', {
            template: '#v-create-customer-form-template',

            data() {
                return {
                    groups: @json($groups),

                    isStoring: false,
                };
            },

            methods: {
                openModal() {
                    this.$refs.customerCreateModal.open();
                },

                create(params, { resetForm, setErrors }) {
                    this.isStoring = true;

                    this.$axios.post("{{ route('admin.customers.customers.store') }}", params)
                        .then((response) => {
                            this.$refs.customerCreateModal.close();

                            this.$emit('customer-created', response.data.data);

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            resetForm();

                            this.isStoring = false;
                        })
                        .catch(error => {
                            console.log(error);
                            
                            this.isStoring = false;

                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                }
            }
        })
    </script>
@endPushOnce