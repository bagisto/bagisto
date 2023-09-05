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
                    class="primary-button"
                >
                    @lang('admin::app.settings.locales.index.create-btn')
                </button>
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid/>
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
                        class="primary-button"
                        @click="$refs.localeUpdateOrCreateModal.toggle()"
                    >
                        @lang('admin::app.settings.locales.index.create-btn')
                    </button>
                </div>
            </div>
    
            <x-admin::datagrid :src="route('admin.settings.locales.index')" ref="datagrid">
                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div class="row grid grid-cols-5 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] border-gray-300 text-gray-600 bg-gray-50 font-semibold">
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'code', 'name', 'direction']"
                        >
                            <p class="text-gray-600">
                                <span class="[&>*]:after:content-['_/_']">
                                    <span
                                        class="after:content-['/'] last:after:content-['']"
                                        :class="{
                                            'text-gray-800 font-medium': applied.sort.column == columnGroup,
                                            'cursor-pointer hover:text-gray-800': columns.find(columnTemp => columnTemp.index === columnGroup)?.sortable,
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
                                    class="ltr:ml-[5px] rtl:mr-[5px] text-[16px] text-gray-800 align-text-bottom"
                                    :class="[applied.sort.order === 'asc' ? 'icon-down-stat': 'icon-up-stat']"
                                    v-if="columnGroup.includes(applied.sort.column)"
                                ></i>
                            </p>
                        </div>

                        <!-- Actions -->
                        <p class="flex gap-[10px] justify-end">
                            @lang('admin::app.components.datagrid.table.actions')
                        </p>
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

                        <!-- Code -->
                        <p v-text="record.code"></p>

                        <!-- Name -->
                        <p v-text="record.name"></p>

                        <!-- Direction -->
                        <p v-text="record.direction"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record.id)">
                                <span
                                    :class="record.actions['0'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 max-sm:place-self-center"
                                    :title="record.actions['0'].title"
                                >
                                </span>
                            </a>

                            <a @click="deleteModal(record.actions['1']?.url)">
                                <span
                                    :class="record.actions['1'].icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 max-sm:place-self-center"
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
                <form 
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="createLocaleForm"
                >
                    <x-admin::modal ref="localeUpdateOrCreateModal">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                <span v-if="isUpdating">
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
                                    v-model="locale.id"
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
                                        v-model="locale.code"
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
                                        v-model="locale.name"
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
                                        v-model="locale.direction"
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

                                    <x-admin::media.images
                                        name="logo_path"
                                        ::uploaded-images='locale.image'
                                    >
                                    </x-admin::media.images>

                                    <x-admin::form.control-group.error
                                        control-name="logo_path"
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
                                    class="primary-button"
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
                        locale: {
                            image: [],
                        },

                        isUpdating: false,
                    }
                },
                methods: {
                    updateOrCreate(params, { resetForm, setErrors  }) {
                        let formData = new FormData(this.$refs.createLocaleForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.settings.locales.update') }}" : "{{ route('admin.settings.locales.store') }}", formData, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then((response) => {
                            this.$refs.localeUpdateOrCreateModal.close();

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                            this.isUpdating = false;

                            window.location.reload();
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                    },

                    editModal(id) {
                        this.isUpdating = true;

                        this.$axios.get(`{{ route('admin.settings.locales.edit', '') }}/${id}`)
                            .then((response) => {
                                this.locale = {
                                    ...response.data.data,
                                        image: response.data.data.logo_path
                                        ? [{ id: '', url: response.data.data.logo_url }]
                                        : [],
                                };

                                this.$refs.localeUpdateOrCreateModal.toggle();
                            })
                    },

                    deleteModal(url) {
                        if (! confirm('@lang('admin::app.settings.locales.index.delete-warning')')) {
                            return;
                        }

                        this.$axios.delete(url)
                            .then((response) => {
                                this.$refs.datagrid.get();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                } else if(error.response.status == 500) {
                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                                }
                            });
                    },
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>