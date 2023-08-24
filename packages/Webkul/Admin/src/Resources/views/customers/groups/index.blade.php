<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.customers.groups.index.title')
    </x-slot:title>

    <v-create-group></v-create-group>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-group-template">
            <div>
                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 font-bold">
                        @lang('admin::app.customers.groups.index.title')
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
                            <!-- Create a new Group -->
                            @if (bouncer()->hasPermission('customers.groups.create'))
                                <button 
                                    type="button"
                                    class="text-gray-50 font-semibold px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] cursor-pointer"
                                    @click="$refs.groupCreateModal.open()"
                                >
                                    @lang('admin::app.customers.groups.index.create.create-btn')
                                </button>
                            @endif

                            <!-- Modal Form -->
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
                                                @lang('admin::app.customers.groups.index.create.title')
                                            </p>    
                                        </x-slot:header>
                        
                                        <x-slot:content>
                                            <!-- Modal Content -->
                                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                                <x-admin::form.control-group class="mb-[10px]">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('admin::app.customers.groups.index.create.code')
                                                    </x-admin::form.control-group.label>
                        
                                                    <x-admin::form.control-group.control
                                                        type="hidden"
                                                        name="id"
                                                        id="id"
                                                        :label="trans('admin::app.customers.groups.index.create.code')"
                                                        :placeholder="trans('admin::app.customers.groups.index.create.code')"
                                                    >
                                                    </x-admin::form.control-group.control>
                        
            
                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="code"
                                                        id="code"
                                                        rules="required"
                                                        :label="trans('admin::app.customers.groups.index.create.code')"
                                                        :placeholder="trans('admin::app.customers.groups.index.create.code')"
                                                    >
                                                    </x-admin::form.control-group.control>
                        
                                                    <x-admin::form.control-group.error
                                                        control-name="code"
                                                    >
                                                    </x-admin::form.control-group.error>
                                                </x-admin::form.control-group>
                        
                                                <x-admin::form.control-group class="mb-[10px]">
                                                    <x-admin::form.control-group.label class="required">
                                                        @lang('admin::app.customers.groups.index.create.name')
                                                    </x-admin::form.control-group.label>
                        
                                                    <x-admin::form.control-group.control
                                                        type="text"
                                                        name="name"
                                                        id="last_name"
                                                        rules="required"
                                                        :label="trans('admin::app.customers.groups.index.create.name')"
                                                        :placeholder="trans('admin::app.customers.groups.index.create.name')"
                                                    >
                                                    </x-admin::form.control-group.control>
                        
                                                    <x-admin::form.control-group.error
                                                        control-name="name"
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
                                                    @lang('admin::app.customers.groups.index.create.save-btn')
                                                </button>
                                            </div>
                                        </x-slot:footer>
                                    </x-admin::modal>
                                </form>
                            </x-admin::form>
                        </div>
                    </div>
                </div>

                <!-- DataGrid -->
                <x-admin::datagrid src="{{ route('admin.groups.index') }}" ref="datagrid">
                    <!-- DataGrid Header -->
                    <template #header="{ columns, records, sortPage}">
                        <div class="row grid px-[16px] py-[10px] border-b-[1px] border-gray-300 font-semibold grid-cols-4 grid-rows-1">
                            <div
                                class="cursor-pointer"
                                @click="sortPage(columns.find(column => column.index === 'id'))"
                            >
                                <div class="flex gap-[10px]">
                                    <p class="text-gray-600">ID</p>
                                </div>
                            </div>
            
                            <div
                                class="cursor-pointer"
                                @click="sortPage(columns.find(column => column.index === 'code'))"
                            >
                                <p class="text-gray-600">Code</p>
                            </div>
            
                            <div
                                class="cursor-pointer"
                                @click="sortPage(columns.find(column => column.index === 'name'))"
                            >
                                <p class="text-gray-600">Name</p>
                            </div>

                            <div class="cursor-pointer flex justify-end">
                                <p class="text-gray-600">Actions</p>
                            </div>
                        </div>
                    </template>

                    <!-- DataGrid Body -->
                    <template #body="{ columns, records }">
                        <div
                            v-for="record in records"
                            class="row grid gap-[10px] px-[16px] py-[10px] border-b-[1px] border-gray-300 text-gray-600 bg-gray-50 items-center"
                            style="grid-template-columns: repeat(4, 1fr);"
                        >
                            <!-- Id -->
                            <p v-text="record.id"></p>

                            <!-- Code -->
                            <p v-text="record.code"></p>

                            <!-- Name -->
                            <p v-text="record.name"></p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                <a @click="editModal(record)">
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
            </div>
        </script>

        <script type="module">
            app.component('v-create-group', {
                template: '#v-create-group-template',

                methods: {
                    create(params, { resetForm, setErrors  }) {
                        if (params.id) {
                            this.$axios.post("{{ route('admin.groups.update') }}", params)
                            .then((response) => {
                                this.$refs.groupCreateModal.close();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: 'Group Updated successfully' });

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                        } else {
                            this.$axios.post("{{ route('admin.groups.store') }}", params)
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
                        }

                    },

                    editModal(value) {
                        this.$refs.groupCreateModal.toggle();

                        this.$refs.modalForm.setValues(value)
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

                                this.$emitter.emit('add-flash', { type: 'success', message: 'Group Deleted successfully' });
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