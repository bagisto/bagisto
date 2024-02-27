<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.marketing.search-seo.sitemaps.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.search_seo.sitemaps.create.before') !!}

    <!-- Create Sitemap Vue Component -->
    <v-create-sitemaps>
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('admin::app.marketing.search-seo.sitemaps.index.title')
            </p>

            <!-- Create Button -->
            @if (bouncer()->hasPermission('marketing.search_seo.sitemaps.create'))
                <div class="primary-button">
                    @lang('admin::app.marketing.search-seo.sitemaps.index.create-btn')
                </div>
            @endif
        </div>

        <!-- Added For Shimmer -->
        <x-admin::shimmer.datagrid />
    </v-create-sitemaps>

    {!! view_render_event('bagisto.admin.marketing.search_seo.sitemaps.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-sitemaps-template"
        >
            <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.marketing.search-seo.sitemaps.index.title')
                </p>

                <!-- Create Button -->
                @if (bouncer()->hasPermission('marketing.search_seo.sitemaps.create'))
                    <div
                        class="primary-button"
                        @click="selectedSitemap=0; $refs.sitemap.toggle()"
                    >
                        @lang('admin::app.marketing.search-seo.sitemaps.index.create-btn')
                    </div>
                @endif
            </div>

            {!! view_render_event('admin.marketing.search_seo.sitemaps.list.before') !!}

            <x-admin::datagrid
                src="{{ route('admin.marketing.search_seo.sitemaps.index') }}"
                ref="datagrid"
            >
                <!-- DataGrid Body -->
                <template #body="{ columns, records, performAction }">
                    <div
                        v-for="record in records"
                        class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 break-all text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                        :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
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
                            @if (bouncer()->hasPermission('marketing.search_seo.sitemaps.edit'))
                                <a @click="selectedSitemap=1; editModal(record)">
                                    <span
                                        :class="record.actions.find(action => action.index === 'edit')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif

                            @if (bouncer()->hasPermission('marketing.search_seo.sitemaps.delete'))
                                <a @click="performAction(record.actions.find(action => action.index === 'delete'))">
                                    <span
                                        :class="record.actions.find(action => action.index === 'delete')?.icon"
                                        class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                    >
                                    </span>
                                </a>
                            @endif
                        </div>
                    </div>
                </template>
            </x-admin::datagrid>

            {!! view_render_event('admin.marketing.search_seo.sitemaps.list.after') !!}

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
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-if="selectedSitemap"
                            >
                                @lang('admin::app.marketing.search-seo.sitemaps.index.edit.title')
                            </p>

                            <!-- Edit Modal title -->
                            <p
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('admin::app.marketing.search-seo.sitemaps.index.create.title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Id -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            />

                            <!-- File Name -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.search-seo.sitemaps.index.create.file-name')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="file_name"
                                    rules="required"
                                    :value="old('file_name')"
                                    :label="trans('admin::app.marketing.search-seo.sitemaps.index.create.file-name')"
                                    :placeholder="trans('admin::app.marketing.search-seo.sitemaps.index.create.file-name')"
                                />

                                <x-admin::form.control-group.error control-name="file_name" />

                                <p class="mt-2 ltr:ml-1 rtl:mr-1 text-xs text-gray-600 dark:text-gray-300 font-medium">
                                    @lang('admin::app.marketing.search-seo.sitemaps.index.create.file-name-info')
                                </p>

                            </x-admin::form.control-group>

                            <!-- File Path -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.search-seo.sitemaps.index.create.path')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="path"
                                    rules="required"
                                    :value="old('path')"
                                    :label="trans('admin::app.marketing.search-seo.sitemaps.index.create.path')"
                                    :placeholder="trans('admin::app.marketing.search-seo.sitemaps.index.create.path')"
                                />

                                <x-admin::form.control-group.error control-name="path" />

                                <p class="mt-2 ltr:ml-1 rtl:mr-1 text-xs text-gray-600 dark:text-gray-300 font-medium">
                                    @lang('admin::app.marketing.search-seo.sitemaps.index.create.path-info')
                                </p>
                            </x-admin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <button class="primary-button">
                                @lang('admin::app.marketing.search-seo.sitemaps.index.create.save-btn')
                            </button>
                        </x-slot>
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
                    updateOrCreate(params, { resetForm, setErrors }) {
                        let formData = new FormData(this.$refs.sitemapCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.marketing.search_seo.sitemaps.update') }}" : "{{ route('admin.marketing.search_seo.sitemaps.store') }}", formData )
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
