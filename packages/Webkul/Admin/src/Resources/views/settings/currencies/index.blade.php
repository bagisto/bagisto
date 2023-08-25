<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.currencies.index.title')
    </x-slot:title>

    <v-currencies>
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.currencies.index.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <!-- Craete currency Button -->
                <button 
                    type="button"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.currencies.index.create-btn')
                </button>
            </div>
        </div>

        {{-- Added For Shimmer --}}
        <x-admin::datagrid></x-admin::datagrid>
    </v-currencies>

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-currencies-template"
        >
            <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.settings.currencies.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <!-- Craete currency Button -->
                    <button 
                        type="button"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        @click="id=0; $refs.currencyModal.toggle()"
                    >
                        @lang('admin::app.settings.currencies.index.create-btn')
                    </button>
                </div>
            </div>
    
            <x-admin::datagrid
                src="{{ route('admin.currencies.index') }}"
                ref="datagrid"
            >
                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage}">
                    <div class="row grid grid-cols-4 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] text-gray-600 bg-gray-50 font-semibold">
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
                        class="row grid gap-[10px] items-center px-[16px] py-[16px] border-b-[1px] border-gray-300 text-gray-600 transition-all hover:bg-gray-100"
                        style="grid-template-columns: repeat(4, 1fr);"
                    >
                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Status -->
                        <p v-text="record.name"></p>

                        <!-- Email -->
                        <p v-text="record.code"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.id)">
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

            <!-- Modal Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, create)">
                    <x-admin::modal ref="currencyModal">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                <span v-if="id">
                                    @lang('admin::app.settings.currencies.index.edit.title')
                                </span>
                                <span v-else>
                                    @lang('admin::app.settings.currencies.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                {!! view_render_event('bagisto.admin.settings.currencies.create.before') !!}

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.currencies.index.create.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        :value="old('code')"
                                        id="code"
                                        rules="required"
                                        :label="trans('admin::app.settings.currencies.index.create.code')"
                                        :placeholder="trans('admin::app.settings.currencies.index.create.code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required"> 
                                        @lang('admin::app.settings.currencies.index.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.currencies.index.create.name')"
                                        :placeholder="trans('admin::app.settings.currencies.index.create.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.currencies.index.create.symbol')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="symbol"
                                        :value="old('symbol')"
                                        id="symbol"
                                        :label="trans('admin::app.settings.currencies.index.create.symbol')"
                                        :placeholder="trans('admin::app.settings.currencies.index.create.symbol')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="symbol"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.currencies.index.create.decimal')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="decimal"
                                        :value="old('decimal')"
                                        id="decimal"
                                        :label="trans('admin::app.settings.currencies.index.create.decimal')"
                                        :placeholder="trans('admin::app.settings.currencies.index.create.decimal')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="decimal"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                {!! view_render_event('bagisto.admin.settings.currencies.create.after') !!}
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex gap-x-[10px] items-center">
                               <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.settings.currencies.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-currencies', {
                template: '#v-currencies-template',

                data() {
                    return {
                        id: 0,
                    }
                },

                methods: {
                    create(params, { resetForm, setErrors  }) {
                        if (params.id) {
                            this.$axios.post("{{ route('admin.currencies.update') }}", params)
                            .then((response) => {
                                this.$refs.currencyModal.close();
    
                                this.$refs.datagrid.get();
    
                                this.$emitter.emit('add-flash', { type: 'success', message: 'Currencies Updated successfully' });
    
                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                        } else {
                            this.$axios.post('{{ route('admin.currencies.store') }}', params)
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                    this.$refs.currencyModal.close();

                                    this.$refs.datagrid.get();

                                    resetForm();
                                }).catch((error) =>{
                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        }
                    },

                    editModal(id) {
                        this.$axios.get(`{{ route('admin.currencies.edit', '') }}/${id}`)
                            .then((response) => {
                                let values = {
                                    id: response.data.data.id,
                                    code: response.data.data.code,
                                    name: response.data.data.name,
                                    decimal: response.data.data.decimal,
                                    symbol: response.data.data.symbol,
                                };

                                this.$refs.currencyModal.toggle();

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

                                this.$emitter.emit('add-flash', { type: 'success', message: 'Currencies Deleted successfully' });
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