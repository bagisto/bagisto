<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.locales.index.title')
    </x-slot:title>

    <v-locales>
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.settings.locales.index.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                <button 
                    type="button"
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                >
                    @lang('admin::app.settings.locales.index.create-btn')
                </button>
            </div>
        </div>

        <x-admin::datagrid></x-admin::datagrid>
    </v-locales>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-locales-template">
            <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.settings.locales.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <button 
                        type="button"
                        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                        @click="id=0; $refs.localeModal.toggle()"
                    >
                        @lang('admin::app.settings.locales.index.create-btn')
                    </button>
                </div>
            </div>
    
            <x-admin::datagrid :src="route('admin.locales.index')" ref="datagrid">
                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage}">
                    <div class="row grid grid-cols-5 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] text-gray-600 bg-gray-50 font-semibold">
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

                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'direction'))"
                        >
                            <p class="text-gray-600">Direction</p>
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
                        style="grid-template-columns: repeat(5, 1fr);"
                    >
                        <!-- Id -->
                        <p v-text="record.id"></p>

                        <!-- Status -->
                        <p v-text="record.code"></p>

                        <!-- Email -->
                        <p v-text="record.name"></p>

                        <!-- Email -->
                        <p v-text="record.direction"></p>

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


            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <form @submit="handleSubmit($event, create)">
                    <x-admin::modal ref="localeModal">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                <span v-if="id">
                                    @lang('admin::app.settings.locales.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.settings.locales.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                {!! view_render_event('bagisto.admin.settings.locale.create.before') !!}

                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.locales.index.create.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        id="code"
                                        rules="required"
                                        :label="trans('admin::app.settings.locales.index.create.code')"
                                        :placeholder="trans('admin::app.settings.locales.index.create.code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.locales.index.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.locales.index.create.name')"
                                        :placeholder="trans('admin::app.settings.locales.index.create.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.locales.index.create.direction')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="direction"
                                        id="direction"
                                        rules="required"
                                        :label="trans('admin::app.settings.locales.index.create.direction')"
                                    >
                                        <option value="ltr" selected title="Text direction left to right">LTR</option>
                    
                                        <option value="rtl" title="Text direction right to left">RTL</option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="direction"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label>
                                        @lang('admin::app.settings.locales.index.create.locale-logo')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="logo_path[image_1]"
                                        id="direction"
                                        :label="trans('admin::app.settings.locales.index.create.locale-logo')"
                                        accepted-types="image/*"
                                        ref="image"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="logo_path[image_1]"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                {!! view_render_event('bagisto.admin.settings.locale.create.after') !!}
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.settings.locales.index.create.save-btn')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-locales', {
                template: '#v-locales-template',

                data() {
                    return {
                        id: 0,

                        image: {},
                    }
                },
                methods: {
                    create(params, { resetForm, setErrors  }) {
                        if (params.id) {
                            this.$axios.post('{{ route('admin.locales.update') }}', params, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then((response) => {
                                this.$refs.localeModal.close();
    
                                this.$refs.datagrid.get();
    
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
    
                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status ==422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                        } else {
                            this.$axios.post('{{ route('admin.locales.store') }}', params , {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then((response) => {
                                this.$refs.localeModal.close();
                                
                                this.$refs.datagrid.get();
                                
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                resetForm();
                            }).catch((error) => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                        }
                    },

                    editModal(id) {
                        this.$axios.get(`{{ route('admin.locales.edit', '') }}/${id}`)
                            .then((response) => {
                                let values = {
                                    id: response.data.data.id,
                                    code: response.data.data.code,
                                    name: response.data.data.name,
                                    direction: response.data.data.direction,
                                    logo_path: response.data.data.logo_url,
                                };

                                this.image = {
                                    id: response.data.data.id,
                                    src: response.data.data.logo_url
                                };

                                this.$refs.localeModal.toggle();

                                this.$refs.modalForm.setValues(values);
                            })
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