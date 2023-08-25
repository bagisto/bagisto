<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.email-marketing.newsletters.index.title')
    </x-slot:title>

    <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
        <p class="text-[20px] text-gray-800 font-bold">
            @lang('admin::app.marketing.email-marketing.newsletters.index.title')
        </p>
    </div>

    <v-subscribers>
        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid></x-admin::shimmer.datagrid>
    </v-subscribers>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-subscribers-template">
            <div>
                <!-- DataGrid -->
                <x-admin::datagrid
                    src="{{ route('admin.customers.subscribers.index') }}"
                    ref="datagrid"
                >
                    <!-- DataGrid Header -->
                    <template #header="{ columns, records, sortPage}">
                        <div class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] text-gray-600 bg-gray-50 font-semibold">
                            <!-- Id -->
                            <div
                                class="flex gap-[10px] cursor-pointer"
                                @click="sortPage(columns.find(column => column.index === 'id'))"
                            >
                                <p class="text-gray-600">
                                    @lang('admin::app.marketing.email-marketing.newsletters.index.datagrid.id')
                                </p>
                            </div>
            
                            <!-- Status -->
                            <div
                                class="cursor-pointer"
                                @click="sortPage(columns.find(column => column.index === 'status'))"
                            >
                                <p class="text-gray-600">
                                    @lang('admin::app.marketing.email-marketing.newsletters.index.datagrid.subscribed')
                                </p>
                            </div>

                            <!-- Email -->
                            <div
                                class="cursor-pointer"
                                @click="sortPage(columns.find(column => column.index === 'email'))"
                            >
                                <p class="text-gray-600">
                                    @lang('admin::app.marketing.email-marketing.newsletters.index.datagrid.email')
                                </p>
                            </div>

                            <!-- Actions -->
                            <div class="cursor-pointer flex justify-end">
                                <p class="text-gray-600">
                                    @lang('admin::app.marketing.email-marketing.newsletters.index.datagrid.actions')
                                </p>
                            </div>
                        </div>
                    </template>
        
                    <!-- DataGrid Body -->
                    <template #body="{ columns, records }">
                        <div
                            v-for="record in records"
                            class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all hover:bg-gray-100"
                            style="grid-template-columns: repeat(4, 1fr);"
                        >
                            <!-- Id -->
                            <p v-text="record.id"></p>
            
                            <!-- Status -->
                            <p v-text="record.status"></p>
            
                            <!-- Email -->
                            <p v-text="record.email"></p>
            
                            <!-- Actions -->
                            <div class="flex justify-end">
                                <a @click="editModal(record.id)">
                                    <span
                                        :class="record.actions['0'].icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                        :title="record.actions['0'].title"
                                    >
                                    </span>
                                </a>
            
                                <a @click="deleteModal(record.actions['1']?.url)">
                                    <span
                                        :class="record.actions['1'].icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 max-sm:place-self-center"
                                        :title="record.actions['1'].title"
                                    >
                                    </span>
                                </a>
                            </div>
                        </div>
                    </template>
                </x-admin::datagrid>

                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="modalForm"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Create Group Modal -->
                        <x-admin::modal ref="groupCreateModal">          
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 font-bold">
                                    @lang('admin::app.marketing.email-marketing.newsletters.index.edit.title')
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                    <!-- Email -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.email-marketing.newsletters.index.edit.email')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
                                            id="id"
                                        >
                                        </x-admin::form.control-group.control>

                                        <x-admin::form.control-group.control
                                            type="text"
                                            name="email"
                                            :value="old('email')"
                                            rules="required"
                                            class="mb-1 cursor-not-allowed"
                                            :label="trans('admin::app.marketing.email-marketing.newsletters.index.edit.email')"
                                            disabled
                                        >
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="email"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>

                                    <!-- Subscribed -->
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.marketing.email-marketing.newsletters.index.edit.subscribed')
                                        </x-admin::form.control-group.label>

                                        @php 
                                            $selectedOption = old('status');
                                        @endphp

                                        <x-admin::form.control-group.control
                                            type="select"
                                            name="status"
                                            class="cursor-pointer mb-1"
                                            rules="required"
                                            label="{{ trans('admin::app.marketing.email-marketing.newsletters.index.edit.subscribed') }}"
                                        >
                                            @foreach (['true', 'false'] as $state)
                                                <option 
                                                    value="{{ $state == 'true' ? 1 : 0 }}"
                                                >
                                                    @lang('admin::app.marketing.email-marketing.newsletters.index.edit.' . $state)
                                                </option>
                                            @endforeach
                                        </x-admin::form.control-group.control>
            
                                        <x-admin::form.control-group.error
                                            control-name="status"
                                        >
                                        </x-admin::form.control-group.error>
                                    </x-admin::form.control-group>
                                </div>
                            </x-slot:content>
            
                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-[10px] items-center">
                                    <button 
                                        type="submit"
                                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                    >
                                        @lang('admin::app.marketing.email-marketing.newsletters.index.edit.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-subscribers', {
                template: '#v-subscribers-template',

                methods: {
                    create(params, { resetForm, setErrors  }) {
                        this.$axios.post("{{ route('admin.customers.subscribers.update') }}", params)
                        .then((response) => {
                            this.$refs.groupCreateModal.close();

                            this.$refs.datagrid.get();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status ==422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                    },

                    editModal(id) {

                        this.$axios.get(`{{ route('admin.customers.subscribers.edit', '') }}/${id}`)
                            .then((response) => {
                                let values = {
                                    id: response.data.data.id,
                                    email: response.data.data.email,
                                    status: response.data.data.is_subscribed,
                                };

                                this.$refs.groupCreateModal.toggle();

                                this.$refs.modalForm.setValues(values);
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    deleteModal(url) {
                        if (! confirm('Are you sure, you want to perform this action?')) {
                            return;
                        }

                        this.$axios.post(url, {
                            '_method': 'DELETE'
                        })
                            .then((response) => {
                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
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