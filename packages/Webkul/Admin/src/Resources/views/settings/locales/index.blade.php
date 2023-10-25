<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.locales.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.settings.locales.create.before') !!}

    <v-locales>
        <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.settings.locales.index.title')
            </p>

            <div class="flex gap-x-[10px] items-center">
                @if (bouncer()->hasPermission('settings.locales.create'))
                    <button 
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.locales.index.create-btn')
                    </button>
                @endif
            </div>
        </div>

        {{-- DataGrid Shimmer --}}
        <x-admin::shimmer.datagrid/>
    </v-locales>

    {!! view_render_event('bagisto.admin.settings.locales.create.after') !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-locales-template">
            <div class="flex  gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.settings.locales.index.title')
                </p>

                <div class="flex gap-x-[10px] items-center">
                    <!-- Locale Create Button -->
                    @if (bouncer()->hasPermission('settings.locales.create'))
                        <button
                            type="button"
                            class="primary-button"
                            @click="resetForm();$refs.localeUpdateOrCreateModal.toggle()"
                        >
                            @lang('admin::app.settings.locales.index.create-btn')
                        </button>
                    @endif
                </div>
            </div>
    
            <x-admin::datagrid :src="route('admin.settings.locales.index')" ref="datagrid">
                @php
                    $hasPermission = bouncer()->hasPermission('settings.locales.edit') || bouncer()->hasPermission('settings.locales.delete');
                @endphp

                <!-- DataGrid Header -->
                <template #header="{ columns, records, sortPage, applied}">
                    <div
                        class="row grid grid-cols-{{ $hasPermission ? '5' : '4' }} grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold"
                        :style="'grid-template-columns: repeat({{ $hasPermission ? '5' : '4' }} , 1fr);'"
                    >
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'code', 'name', 'direction']"
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
                        :style="'grid-template-columns: repeat(' + (record.actions.length ? 5 : 4) + ', 1fr);'"
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
                            <a @click="id=1; editModal(record.actions.find(action => action.title === 'Edit')?.url)">
                                <span
                                    :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
                                >
                                </span>
                            </a>

                            <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                <span
                                    :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"
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

                    {!! view_render_event('admin.settings.locales.create_form_controls.before') !!}

                    <x-admin::modal ref="localeUpdateOrCreateModal">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 dark:text-white font-bold">
                                <span v-if="isUpdating">
                                    @lang('admin::app.settings.locales.index.edit.title')
                                </span>

                                <span v-else>
                                    @lang('admin::app.settings.locales.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
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
                                        <!-- Default Option -->
                                        <option value="">
                                            @lang('admin::app.settings.locales.index.create.select-direction')
                                        </option>

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

                                    <div class="hidden">
                                        <x-admin::media.images
                                            name="logo_path"
                                            ::uploaded-images='locale.image'
                                        >
                                        </x-admin::media.images>
                                    </div>

                                    <v-media-images
                                        name="logo_path"
                                        :uploaded-images='locale.image'
                                    >
                                    </v-media-images>

                                    <x-admin::form.control-group.error
                                        control-name="logo_path"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                                
                                <p class="text-[12px] text-gray-600 dark:text-gray-300">
                                    @lang('admin::app.settings.locales.index.logo-size')
                                </p>

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

                    {!! view_render_event('admin.settings.locales.create_form_controls.after') !!}

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

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                            this.isUpdating = false;
                            
                            this.$refs.datagrid.get();

                            resetForm();
                        })
                        .catch(error => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                    },

                    editModal(url) {
                        this.isUpdating = true;

                        this.$axios.get(url)
                            .then((response) => {
                                this.locale = {
                                    ...response.data.data,
                                        image: response.data.data.logo_path
                                        ? [{ id: 'logo_url', url: response.data.data.logo_url }]
                                        : [],
                                };

                                this.$refs.localeUpdateOrCreateModal.toggle();
                            })
                    },

                    resetForm() {
                        this.locale = {
                            image: [],
                        };
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>