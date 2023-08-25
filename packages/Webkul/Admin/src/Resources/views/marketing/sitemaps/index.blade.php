<x-admin::layouts>
    {{-- Title of the page --}}
    <x-slot:title>
        @lang('admin::app.marketing.sitemaps.index.title')
    </x-slot:title>

    {{-- Create Sitemap Vue Component --}}
    <v-create-sitemaps>
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <p class="text-[20px] text-gray-800 font-bold">
                @lang('admin::app.marketing.sitemaps.index.title')
            </p>

            <!-- Create Button -->
            <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                @lang('admin::app.marketing.sitemaps.index.create-btn')
            </div>
        </div>

        {{-- Added For Shimmer --}}
        <x-admin::shimmer.datagrid></x-admin::shimmer.datagrid>
    <v-create-sitemaps/>
    
    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-create-sitemaps-template"
        >
            <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                <p class="text-[20px] text-gray-800 font-bold">
                    @lang('admin::app.marketing.sitemaps.index.title')
                </p>

                <!-- Create Button -->
                <div 
                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                    @click="id=0; $refs.sitemap.toggle()"
                >
                    @lang('admin::app.marketing.sitemaps.index.create-btn')
                </div>
            </div>

            <x-admin::datagrid
                src="{{ route('admin.sitemaps.index') }}"
                ref="datagrid"
            >
                <!-- Datagrid Header -->
                <template #header="{ columns, records, sortPage }">
                    <div class="row grid grid-cols-5 grid-rows-1 gap-[10px] items-center px-[16px] py-[10px] border-b-[1px] text-gray-600 bg-gray-50 font-semibold">
                        <!-- ID -->
                        <div
                            class="flex gap-[10px] cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'id'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.marketing.sitemaps.index.datagrid.id')
                            </p>
                        </div>

                        <!-- File Name -->
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'name'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.marketing.sitemaps.index.datagrid.file-name')
                            </p>
                        </div>

                        <!-- Date -->
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'date'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.marketing.sitemaps.index.datagrid.path')
                            </p>
                        </div>

                        <!-- Actions -->
                        <div
                            class="cursor-pointer"
                            @click="sortPage(columns.find(column => column.index === 'date'))"
                        >
                            <p class="text-gray-600">
                                @lang('admin::app.marketing.sitemaps.index.datagrid.link-for-google')
                            </p>
                        </div>

                        <div class="cursor-pointer flex justify-end">
                            <p class="text-gray-600">
                                @lang('admin::app.marketing.sitemaps.index.datagrid.actions')
                            </p>
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

                        <!-- File Name -->
                        <p v-text="record.file_name"></p>

                        <!-- Path -->
                        <p v-text="record.path"></p>

                        <!-- URL -->
                        <p v-text="record.url"></p>

                        <!-- Actions -->
                        <div class="flex justify-end">
                            <a @click="id=1; editModal(record)">
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

            <!-- Model Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="modalForm"
            >
                <!-- Create Sitemap form -->
                <form @submit="handleSubmit($event, create)">
                    <x-admin::modal ref="sitemap">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                <!-- Create Modal title -->
                                <span v-if="id">
                                    @lang('admin::app.marketing.sitemaps.index.edit.title')
                                </span>

                                <!-- Edit Modal title -->
                                <span v-else>
                                    @lang('admin::app.marketing.sitemaps.index.create.title')
                                </span>
                            </p>
                        </x-slot:header>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
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

                                    <p class="mt-[8px] ml-[4px] text-[12px] text-gray-600 font-med  ium">
                                        @lang('admin::app.marketing.sitemaps.index.create.file-name-info')
                                    </p>

                                </x-admin::form.control-group>
        
                                <!---- File Path -->
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

                                    <p class="mt-[8px] ml-[4px] text-[12px] text-gray-600 font-medium">
                                        @lang('admin::app.marketing.sitemaps.index.create.path-info')
                                    </p>
                                </x-admin::form.control-group>
                            </div>
                        </x-slot:content>
                        
                        <x-slot:footer>
                            <!-- Save Button -->
                            <button class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
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
                        id: 0,
                    }
                },

                methods: {
                    create(params, { resetForm, setErrors }) {
                        if (params.id) {
                            this.$axios.post("{{ route('admin.sitemaps.update') }}", params )
                                .then((response) => {
                                    this.$emitter.emit('add-flash', { type: 'success', message: 'Sitemap Updated successfully' });

                                    this.$refs.sitemap.toggle();

                                    this.$refs.datagrid.get();

                                    resetForm();
                                })
                                .catch(error => {
                                    if (error.response.status == 422) {
                                        setErrors(error.response.data.errors);
                                    }
                                });
                        } else {
                            this.$axios.post("{{ route('admin.sitemaps.store') }}", params )
                            .then((response) => {
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                                this.$refs.sitemap.toggle();

                                this.$refs.datagrid.get();

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                        }
                    },

                    editModal(values) {
                        // this.$axios.get(`{{ route('admin.currencies.edit', '') }}/${id}`)
                        //     .then((response) => {
                        //         let values = {
                        //             id: response.data.data.id,
                        //             code: response.data.data.code,
                        //             name: response.data.data.name,
                        //             decimal: response.data.data.decimal,
                        //             symbol: response.data.data.symbol,
                        //         };

                                this.$refs.sitemap.toggle();

                                this.$refs.modalForm.setValues(values);
                            // })
                            // .catch(error => {
                            //     if (error.response.status ==422) {
                            //         setErrors(error.response.data.errors);
                            //     }
                            // });
                        
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
                },
            })
        </script>
    @endPushOnce
</x-admin::layouts>