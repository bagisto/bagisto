<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.marketing.search-seo.sitemaps.index.title')
    </x-slot>

    {!! view_render_event('bagisto.admin.marketing.search_seo.sitemaps.create.before') !!}

    <!-- Create Sitemap Vue Component -->
    <v-create-sitemaps>
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
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
            <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
                <p class="text-xl font-bold text-gray-800 dark:text-white">
                    @lang('admin::app.marketing.search-seo.sitemaps.index.title')
                </p>

                <!-- Create Button -->
                @if (bouncer()->hasPermission('marketing.search_seo.sitemaps.create'))
                    <div
                        class="primary-button"
                        @click="selectedSitemap=0; resetForm(); $refs.sitemapUpdateOrCreateModal.toggle()"
                    >
                        @lang('admin::app.marketing.search-seo.sitemaps.index.create-btn')
                    </div>
                @endif
            </div>

            {!! view_render_event('bagisto.admin.marketing.search_seo.sitemaps.list.before') !!}

            <x-admin::datagrid
                :src="route('admin.marketing.search_seo.sitemaps.index')"
                ref="datagrid"
            >
                <template #body="{
                    isLoading,
                    available,
                    applied,
                    selectAll,
                    sort,
                    performAction
                }">
                    <template v-if="isLoading">
                        <x-admin::shimmer.datagrid.table.body />
                    </template>

                    <template v-else>
                        <div
                            v-for="record in available.records"
                            class="row grid items-center gap-2.5 break-all border-b px-4 py-4 text-gray-600 transition-all hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-gray-950"
                            :style="`grid-template-columns: repeat(${gridsCount}, minmax(0, 1fr))`"
                        >
                            <!-- ID -->
                            <p>@{{ record.id }}</p>

                            <!-- Channel -->
                            <p>@{{ record.channel }}</p>

                            <!-- File Name -->
                            <p>@{{ record.file_name }}</p>

                            <!-- Path -->
                            <p>@{{ record.path }}</p>

                            <!-- URL -->
                            <div class="flex flex-col gap-1">
                                <a
                                    v-for="url in (Array.isArray(record.url) ? record.url : [record.url])"
                                    :key="url"
                                    :href="url"
                                    target="_blank"
                                    class="text-blue-600 hover:underline dark:text-blue-300"
                                >
                                    @{{ url }}
                                </a>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end">
                                @if (bouncer()->hasPermission('marketing.search_seo.sitemaps.edit'))
                                    <a @click="selectedSitemap=1; editModal(record.actions.find(action => action.index === 'edit')?.url)">
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
                </template>
            </x-admin::datagrid>

            {!! view_render_event('bagisto.admin.marketing.search_seo.sitemaps.list.after') !!}

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
                    <x-admin::modal ref="sitemapUpdateOrCreateModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <!-- Edit Modal title -->
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-if="selectedSitemap"
                            >
                                @lang('admin::app.marketing.search-seo.sitemaps.index.edit.title')
                            </p>

                            <!-- Create Modal title -->
                            <p
                                class="text-lg font-bold text-gray-800 dark:text-white"
                                v-else
                            >
                                @lang('admin::app.marketing.search-seo.sitemaps.index.create.title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- ID -->
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="id"
                                v-model="sitemap.id"
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
                                    v-model="sitemap.file_name"
                                    :label="trans('admin::app.marketing.search-seo.sitemaps.index.create.file-name')"
                                    :placeholder="trans('admin::app.marketing.search-seo.sitemaps.index.create.file-name')"
                                />

                                <x-admin::form.control-group.error control-name="file_name" />

                                <p class="mt-2 text-xs font-medium text-gray-600 ltr:ml-1 rtl:mr-1 dark:text-gray-300">
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
                                    v-model="sitemap.path"
                                    :label="trans('admin::app.marketing.search-seo.sitemaps.index.create.path')"
                                    :placeholder="trans('admin::app.marketing.search-seo.sitemaps.index.create.path')"
                                />

                                <x-admin::form.control-group.error control-name="path" />

                                <p class="mt-2 text-xs font-medium text-gray-600 ltr:ml-1 rtl:mr-1 dark:text-gray-300">
                                    @lang('admin::app.marketing.search-seo.sitemaps.index.create.path-info')
                                </p>
                            </x-admin::form.control-group>

                            <!-- Select Channels -->
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.marketing.search-seo.sitemaps.index.create.channels')
                            </x-admin::form.control-group.label>

                            @foreach (core()->getAllChannels() as $channel)
                                <x-admin::form.control-group class="!mb-2 flex select-none items-center gap-2.5 last:!mb-0">
                                    <x-admin::form.control-group.control
                                        type="checkbox"
                                        :id="'channels_' . $channel->id"
                                        name="channels[]"
                                        rules="required"
                                        :value="(string) $channel->id"
                                        v-model="sitemap.channels"
                                        :for="'channels_' . $channel->id"
                                        :label="trans('admin::app.marketing.search-seo.sitemaps.index.create.channels')"
                                    />

                                    <label
                                        class="cursor-pointer text-xs font-medium text-gray-600 dark:text-gray-300"
                                        for="channels_{{ $channel->id }}"
                                        v-pre
                                    >
                                        {{ core()->getChannelName($channel) }}
                                    </label>
                                </x-admin::form.control-group>
                            @endforeach

                            <x-admin::form.control-group.error control-name="channels[]" />
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-admin::button
                                button-type="submit"
                                class="primary-button"
                                :title="trans('admin::app.marketing.search-seo.sitemaps.index.create.save-btn')"
                                ::loading="isLoading"
                                ::disabled="isLoading"
                            />
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
                        sitemap: {
                            channels: [],
                        },

                        selectedSitemap: 0,

                        isLoading: false,
                    }
                },

                computed: {
                    gridsCount() {
                        let count = this.$refs.datagrid.available.columns.filter((column) => column.visibility).length;

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
                        this.isLoading = true;

                        let formData = new FormData(this.$refs.sitemapCreateForm);

                        if (params.id) {
                            formData.append('_method', 'put');
                        }

                        this.$axios.post(params.id ? "{{ route('admin.marketing.search_seo.sitemaps.update') }}" : "{{ route('admin.marketing.search_seo.sitemaps.store') }}", formData)
                            .then((response) => {
                                this.isLoading = false;

                                this.$refs.sitemapUpdateOrCreateModal.close();

                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });

                                this.$refs.datagrid.get();

                                resetForm();
                            })
                            .catch(error => {
                                this.isLoading = false;

                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },

                    editModal(url) {
                        this.$axios.get(url)
                            .then((response) => {
                                this.sitemap = {
                                    ...response.data.data,
                                    channels: (response.data.data.channels ?? []).map(String),
                                };

                                this.$refs.sitemapUpdateOrCreateModal.toggle();
                            })
                            .catch(error => {
                                console.log(error);
                            });
                    },

                    resetForm() {
                        this.sitemap = {
                            channels: [],
                        };
                    },
                },
            })
        </script>
    @endPushOnce
</x-admin::layouts>
