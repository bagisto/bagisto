<x-admin::layouts>
    <!-- Title of the page -->
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
                                    @click="selectedGroups=0; $refs.groupUpdateOrCreateModal.open()"
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
                    <!-- DataGrid Body -->
                    <template #body="{ columns, records, performAction }">
                        <div
                            v-for="record in records"
                            class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Id -->
                            <p v-text="record.id"></p>

                            <!-- Code -->
                            <p v-text="record.code"></p>

                            <!-- Name -->
                            <p v-text="record.name"></p>

                            <!-- Actions -->
                            @if (
                                bouncer()->hasPermission('customers.groups.edit') 
                                || bouncer()->hasPermission('customers.groups.delete')
                            )
                                <div class="flex justify-end">
                                    @if (bouncer()->hasPermission('customers.groups.edit'))
                                        <a @click="selectedGroups=1; editModal(record)">
                                            <span
                                                :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                                class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                :title="record.actions.find(action => action.title === 'Edit')?.title"
                                            >
                                            </span>
                                        </a>
                                    @endif

                                    @if (bouncer()->hasPermission('customers.groups.delete'))
                                        <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                            <span
                                                :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                                class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                                :title="record.actions.find(action => action.method === 'DELETE')?.title"
                                            >
                                            </span>
                                        </a>
                                    @endif
                                </div>
                            @endif
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
                                    <span v-if="selectedGroups">
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
                        selectedGroups: 0,
                    }
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.length;

                        if (this.$refs.datagrid.available.actions.length) {
                            ++count;
                        }

                        if (this.$refs.datagrid.available.massActions.length) {
                            ++count;
                        }

                        return count;
                    },
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
