<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.sitemaps.index.title')
    </x-slot:title>

    {!! view_render_event('bagisto.admin.marketing.sitemaps.create.before') !!}

    {{-- Create Sitemap Vue Component --}}
    <v-create-sitemaps>
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                @lang('admin::app.marketing.sitemaps.index.title')
            </p>

            <!-- Create Button -->
            @if (bouncer()->hasPermission('marketing.sitemaps.create'))
                <div class="primary-button">
                    @lang('admin::app.marketing.sitemaps.index.create-btn')
                </div>
            @endif
        </div>

        {{-- Added For Shimmer --}}
        <x-admin::shimmer.datagrid/>
    </v-create-sitemaps>

    {!! view_render_event('bagisto.admin.marketing.sitemaps.create.after') !!}
    
    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-create-sitemaps-template"
        >
            <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.marketing.sitemaps.index.title')
                </p>

                <!-- Create Button -->
                @if (bouncer()->hasPermission('marketing.sitemaps.create'))
                    <div 
                        class="primary-button"
                        @click="selectedSitemap=0; $refs.sitemap.toggle()"
                    >
                        @lang('admin::app.marketing.sitemaps.index.create-btn')
                    </div>
                @endif
            </div>

            {!! view_render_event('admin.marketing.sitemaps.list.before') !!}

            <x-admin::datagrid
                src="{{ route('admin.marketing.promotions.sitemaps.index') }}"
                ref="datagrid"
            >
                @php
                    $hasPermission = bouncer()->hasPermission('marketing.sitemaps.edit') || bouncer()->hasPermission('marketing.sitemaps.delete');
                @endphp

                <!-- Datagrid Header -->
                <template #header="{ columns, records, sortPage, applied }">
                    <div
                        class="row grid grid-cols-{{ $hasPermission ? '5' : '4' }} grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] dark:border-gray-800 text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900 font-semibold"
                        :style="'grid-template-columns: repeat({{ $hasPermission ? '5' : '4' }}, 1fr);'"
                    >
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            v-for="(columnGroup, index) in ['id', 'file_name', 'path', 'url']"
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
                            <p class="col-start-[none]">
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

                        <!-- File Name -->
                        <p v-text="record.file_name"></p>

                        <!-- Path -->
                        <p v-text="record.path"></p>

                        <!-- URL -->
                        <p>
                            <a :href="record.url" target="_blank">
                                @{{ record.url}}
                            </a>
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="selectedSitemap=1; editModal(record)">
                                <span
                                    :class="record.actions.find(action => action.title === 'Edit')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                >
                                </span>
                            </a>

                            <a @click="performAction(record.actions.find(action => action.method === 'DELETE'))">
                                <span
                                    :class="record.actions.find(action => action.method === 'DELETE')?.icon"
                                    class="cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                >
                                </span>
                            </a>
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>

            {!! view_render_event('admin.marketing.sitemaps.list.after') !!}

            <!-- Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <!-- Create Sitemap form -->
                <form
                    @submit="handleSubmit($event, updateOrCreate)"
                    ref="sitemapCreateForm"
                >
                    <x-admin::modal ref="sitemap">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <!-- Create Modal title -->
                            <p
                                class="text-[18px] text-gray-800 dark:text-white font-bold"
                                v-if="selectedSitemap"
                            >
                                @lang('admin::app.marketing.sitemaps.index.edit.title')
                            </p>

                            <!-- Edit Modal title -->
                            <p 
                                class="text-[18px] text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('admin::app.marketing.sitemaps.index.create.title')
                            </p>
                        </x-slot:header>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] dark:border-gray-800">
                                <!-- Id -->
                                <x-admin::form.control-group.control
                                    type="hidden"
                                    name="id"
                                >
                                </x-admin::form.control-group.control>

                                <!-- File Name -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.sitemaps.index.create.file-name')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="file_name"
                                        :value="old('file_name')"
                                        rules="required"
                                        :label="trans('admin::app.marketing.sitemaps.index.create.file-name')"
                                        :placeholder="trans('admin::app.marketing.sitemaps.index.create.file-name')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="file_name"
                                    >
                                    </x-admin::form.control-group.error>

                                    <p class="mt-[8px] ltr:ml-[4px] rtl:mr-[4px] text-[12px] text-gray-600 dark:text-gray-300 font-medium">
                                        @lang('admin::app.marketing.sitemaps.index.create.file-name-info')
                                    </p>

                                </x-admin::form.control-group>
        
                                <!-- File Path -->
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.marketing.sitemaps.index.create.path')
                                    </x-admin::form.control-group.label>
        
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="path"
                                        :value="old('path')"
                                        rules="required"
                                        :label="trans('admin::app.marketing.sitemaps.index.create.path')"
                                        :placeholder="trans('admin::app.marketing.sitemaps.index.create.path')"
                                    >
                                    </x-admin::form.control-group.control>
        
                                    <x-admin::form.control-group.error
                                        control-name="path"
                                    >
                                    </x-admin::form.control-group.error>

                                    <p class="mt-[8px] ltr:ml-[4px] rtl:mr-[4px] text-[12px] text-gray-600 dark:text-gray-300 font-medium">
                                        @lang('admin::app.marketing.sitemaps.index.create.path-info')
                                    </p>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
                        
                        <x-slot:footer>
                            <!-- Save Button -->
                            <button class="primary-button">
                                @lang('admin::app.marketing.sitemaps.index.create.save-btn')
                            </button>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </script>

        <script type="module">
            app.component('v-create-sitemaps', {
                template: '#v-create-sitemaps-template',

                data() {
                    return {
                        selectedSitemap: 0,
                    }
                },

                methods: {
                    updateOrCreate(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.sitemapCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.marketing.promotions.sitemaps.update') }}" : "{{ route('admin.marketing.promotions.sitemaps.store') }}", formData )
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.$refs.sitemap.toggle();

                                this.$refs.datagrid.get();

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(values) {
                        this.$refs.sitemap.toggle();

                        this.$refs.modalForm.setValues(values);
                    },
                },
            })
        </script>
    @endPushOnce
</x-admin::layouts>