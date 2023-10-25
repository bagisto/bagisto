<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.customers.groups.index.title')
    </x-slot:title>

    {!! view_render_event('admin.customers.groups.create.before') !!}

    <v-create-group></v-create-group>

    {!! view_render_event('admin.customers.groups.create.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-create-group-template">
            <div>
                <div class="flex justify-between items-center">
                    <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                        @lang('admin::app.customers.groups.index.title')
                    </p>
            
                    <div class="flex gap-x-[10px] items-center">
                        <div class="flex gap-x-[10px] items-center">
                            <!-- Create a new Group -->
                            @if (bouncer()->hasPermission('customers.groups.create'))
                                <button 
                                    type="button"
                                    class="primary-button"
                                    @click="id=0; $refs.groupUpdateOrCreateModal.open()"
                                >
                                    @lang('admin::app.customers.groups.index.create.create-btn')
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {!! view_render_event('admin.customers.groups.list.before') !!}

                <!-- DataGrid -->
                <x-admin::datagrid src="{{ route('admin.customers.groups.index') }}" ref="datagrid">
                    @php
                        $hasPermission = bouncer()->hasPermission('customers.groups.edit') || bouncer()->hasPermission('customers.groups.delete');
                    @endphp

                    <!-- DataGrid Header -->
                    <template #header="{ columns, records, sortPage, applied}">
                        <div class="row grid grid-cols-{{ $hasPermission ? '4' : '3' }} grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold">
                            <div
                                class="flex gap-[10px] cursor-pointer"
                                v-for="(columnGroup, index) in ['id', 'code', 'name']"
                            >
                                <p class="text-gray-600 dark:text-gray-300">
                                    <span class="[&>*]:after:content-['_/_']">
                                        <span
                                            class="after:content-['/'] last:after:content-['']"
                                            :class="{
                                                'text-gray-800 dark:text-white font-medium': applied.sort.column == columnGroup,
                                                'cursor-pointer hover:text-gray-800 dark:hover:text-white': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
                                            }"
                                            @click="
                                                columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable ? sortPage(columns.find(columnTemp => columnTemp.index === columnGroup)): {}
                                            "
                                        >
                                            @{{ columns.find(columnTemp => columnTemp.index === columnGroup)?.label }}
                                        </span>
                                    </span>

                                    <!-- Filter Arrow Icon -->
                                    <i
                                        class="ltr:ml-[5px] rtl:mr-[5px] text-[16px] text-gray-800 dark:text-white align-text-bottom"
                                        :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                        v-if="columnGroup.includes(applied.sort.column)"
                                    ></i>
                                </p>
                            </div>
    
                            <!-- Actions -->
                            @if ($hasPermission)
                                <p class="flex gap-[10px] justify-end">
                                    @lang('admin::app.components.datagrid.table.actions')
                                </p>
                            @endif
                        </div>
                    </template>

                    <!-- DataGrid Body -->
                    <template #body="{ columns, records, performAction }">
                        <div
                            v-for="record in records"
                            class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                            :style="'grid-template-columns: repeat(' + (record.actions.length ? 4 : 3) + ', 1fr);'"
                        >
                            <!-- Id -->
                            <p v-text="record.id"></p>

                            <!-- Code -->
                            <p v-text="record.code"></p>

                            <!-- Name -->
                            <p v-text="record.name"></p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                <a @click="id=1; editModal(record)">
                                    <span
                                        :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :title="record.actions.find(action => action.title === 'Edit')?.title"
                                    >
                                    </span>
                                </a>

                                <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                    <span
                                        :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                        class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                        :title="record.actions.find(action => action.method === 'DELETE')?.title"
                                    >
                                    </span>
                                </a>
                            </div>
                        </div>
                    </template>
                </x-admin::datagrid>

                {!! view_render_event('admin.customers.groups.list.after') !!}

                <!-- Modal Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                    ref="modalForm"
                >
                    <form
                        @submit="handleSubmit($event, updateOrCreate)"
                        ref="groupCreateForm"
                    >
                        <!-- Create Group Modal -->
                        <x-admin::modal ref="groupUpdateOrCreateModal">          
                            <x-slot:header>
                                <!-- Modal Header -->
                                <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                    <span v-if="id">
                                        @lang('admin::app.customers.groups.index.edit.title')
                                    </span>
                                    <span v-else>
                                        @lang('admin::app.customers.groups.index.create.title')
                                    </span>
                                        
                                </p>    
                            </x-slot:header>
            
                            <x-slot:content>
                                <!-- Modal Content -->
                                <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                    <x-admin::form.control-group class="mb-[10px]">
                                        <x-admin::form.control-group.label class="required">
                                            @lang('admin::app.customers.groups.index.create.code')
                                        </x-admin::form.control-group.label>
            
                                        <x-admin::form.control-group.control
                                            type="hidden"
                                            name="id"
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
                                        class="primary-button"
                                    >
                                        @lang('admin::app.customers.groups.index.create.save-btn')
                                    </button>
                                </div>
                            </x-slot:footer>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-group', {
                template: '#v-create-group-template',

                data() {
                    return {
                        id: 0,
                    }
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors  }) {
                        let formData = new FormData(this.$refs.groupCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.customers.groups.update') }}" : "{{ route('admin.customers.groups.store') }}", formData)
                            .then((response) => {
                                this.$refs.groupUpdateOrCreateModal.close();

                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(value) {
                        this.$refs.groupUpdateOrCreateModal.toggle();

                        this.$refs.modalForm.setValues(value);
                    },
                }
            })
        </script>
    @endPushOnce

</x-admin::layouts>