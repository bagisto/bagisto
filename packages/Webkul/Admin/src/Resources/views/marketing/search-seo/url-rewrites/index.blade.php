<x-admin::layouts>
    <!-- Title of the page -->
    <x-slot:title>
        @lang('admin::app.marketing.search-seo.url-rewrites.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.create.before') !!}

    <!-- Create Sitemap Vue Component -->
    <v-create-sitemaps>
        <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
            <p class="text-xl text-gray-800 dark:text-white font-bold">
                @lang('admin::app.marketing.search-seo.url-rewrites.index.title')
            </p>

            <!-- Create Button -->
            @if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.create'))
                <div class="primary-button">
                    @lang('admin::app.marketing.search-seo.url-rewrites.index.create-btn')
                </div>
            @endif
        </div>

        <!-- Added For Shimmer -->
        <x-admin::shimmer.datagrid />
    </v-create-sitemaps>

    {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.create.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-sitemaps-template"
        >
            <div class="flex gap-4 justify-between items-center max-sm:flex-wrap">
                <p class="text-xl text-gray-800 dark:text-white font-bold">
                    @lang('admin::app.marketing.search-seo.url-rewrites.index.title')
                </p>

                <!-- Create Button -->
                @if (bouncer()->hasPermission('marketing.search_seo.url_rewrites.create'))
                    <div
                        class="primary-button"
                        @click="selectedSitemap=0; $refs.sitemap.toggle()"
                    >
                        @lang('admin::app.marketing.search-seo.url-rewrites.index.create-btn')
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.list.before') !!}

            <x-admin::datagrid
                src="{{ route('admin.marketing.search_seo.url_rewrites.index') }}"
                ref="datagrid"
            >
                <!-- DataGrid Body -->
                <template #body="{ columns, records, setCurrentSelectionMode, performAction, available, applied, isLoading }">
                    <template v-if="! isLoading">
                        <div
                            v-for="record in records"
                            class="row grid gap-2.5 items-center px-4 py-4 border-b dark:border-gray-800 text-gray-600 dark:text-gray-300 transition-all hover:bg-gray-50 dark:hover:bg-gray-950"
                                :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- Mass Actions -->
                            <p v-if="available.massActions.length">
                                <label :for="`mass_action_select_record_${record[available.meta.primary_column]}`">
                                    <input
                                        type="checkbox"
                                        class="peer hidden"
                                        :name="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                        :value="record[available.meta.primary_column]"
                                        :id="`mass_action_select_record_${record[available.meta.primary_column]}`"
                                        v-model="applied.massActions.indices"
                                        @change="setCurrentSelectionMode"
                                    >

                                    <span class="icon-uncheckbox peer-checked:icon-checked peer-checked:text-blue-600 cursor-pointer rounded-md text-2xl">
                                    </span>
                                </label>
                            </p>

                            <!-- Id -->
                            <p v-text="record.id"></p>

                            <!-- For -->
                            <p v-text="record.entity_type"></p>

                            <!-- Request Path -->
                            <p v-text="record.request_path"></p>

                            <!-- Target Path -->
                            <p v-text="record.target_path"></p>

                            <!-- Redirect Type -->
                            <p v-text="record.redirect_type"></p>

                            <!-- Locale -->
                            <p v-text="record.locale"></p>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                @if (bouncer()->hasPermission('marketing.url_rewrites.edit'))
                                    <a @click="selectedSitemap=1; editModal(record)">
                                        <span
                                            :class="record.actions.find(action => action.index === 'edit')?.icon"
                                            class="cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950 max-sm:place-self-center"
                                        >
                                        </span>
                                    </a>
                                @endif

                                @if (bouncer()->hasPermission('marketing.url_rewrites.delete'))
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

                    <!-- Datagrid Body Shimmer -->
                    <template v-else>
                        <x-admin::shimmer.datagrid.table.body />
                    </template>
                </template>
            </x-admin::datagrid>

            {!! view_render_event('bagisto.admin.marketing.search_seo.url_rewrites.list.after') !!}

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
                                @lang('admin::app.marketing.search-seo.url-rewrites.index.edit.title')
                            </p>

                            <!-- Edit Modal title -->
                            <p
                                class="text-lg text-gray-800 dark:text-white font-bold"
                                v-else
                            >
                                @lang('admin::app.marketing.search-seo.url-rewrites.index.create.title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Id -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                            />

                            <!-- Entity Type -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.search-seo.url-rewrites.index.create.for')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="entity_type"
                                    rules="required"
                                    :label="trans('admin::app.marketing.search-seo.url-rewrites.index.create.for')"
                                >
                                    <option value="product">
                                        @lang('admin::app.marketing.search-seo.url-rewrites.index.create.product')
                                    </option>

                                    <option value="category">
                                        @lang('admin::app.marketing.search-seo.url-rewrites.index.create.category')
                                    </option>

                                    <option value="cms_page">
                                        @lang('admin::app.marketing.search-seo.url-rewrites.index.create.cms-page')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="entity_type" />
                            </x-admin::form.control-group>

                            <!-- Request Path -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.search-seo.url-rewrites.index.create.request-path')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="request_path"
                                    rules="required"
                                    :label="trans('admin::app.marketing.search-seo.url-rewrites.index.create.request-path')"
                                    :placeholder="trans('admin::app.marketing.search-seo.url-rewrites.index.create.request-path')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="request_path" />
                            </x-admin::form.control-group>

                            <!-- Target Path -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.search-seo.url-rewrites.index.create.target-path')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="target_path"
                                    rules="required"
                                    :label="trans('admin::app.marketing.search-seo.url-rewrites.index.create.target-path')"
                                    :placeholder="trans('admin::app.marketing.search-seo.url-rewrites.index.create.target-path')"
                                >
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="target_path" />
                            </x-admin::form.control-group>

                            <!-- Redirect Type -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.search-seo.url-rewrites.index.create.redirect-type')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="redirect_type"
                                    rules="required"
                                    :label="trans('admin::app.marketing.search-seo.url-rewrites.index.create.redirect-type')"
                                >
                                    <option value="302">
                                        @lang('admin::app.marketing.search-seo.url-rewrites.index.create.temporary-redirect')
                                    </option>

                                    <option value="301">
                                        @lang('admin::app.marketing.search-seo.url-rewrites.index.create.permanent-redirect')
                                    </option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="redirect_type" />
                            </x-admin::form.control-group>

                            <!-- Locales -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.marketing.search-seo.url-rewrites.index.create.locale')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="locale"
                                    rules="required"
                                    :label="trans('admin::app.marketing.search-seo.url-rewrites.index.create.locale')"
                                >
                                    @foreach (core()->getAllLocales() as $locale)
                                        <option value="{{ $locale->code }}">{{ $locale->name }}</option>
                                    @endforeach 
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="locale" />
                            </x-admin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <button class="primary-button">
                                @lang('admin::app.marketing.search-seo.url-rewrites.index.create.save-btn')
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

                        this.$axios.post(params.id ? "{{ route('admin.marketing.search_seo.url_rewrites.update') }}" : "{{ route('admin.marketing.search_seo.url_rewrites.store') }}", formData )
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
