<x-admin::layouts>

    <v-create-sitemaps><v-create-sitemaps/>
    
    @pushOnce('scripts')
        <script 
            type="text/x-template" 
            id="v-create-sitemaps-template"
        >
            <div>
                <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
                    <p class="text-[20px] text-gray-800 font-bold">
                        @lang('admin::app.marketing.sitemaps.create.title')
                    </p>
            
                    <div class="flex gap-x-[10px] items-center">
                        <x-shop::form
                            v-slot="{ meta, errors, handleSubmit }"
                            as="div"
                        >
                            <!-- Create Sitemap form -->
                            <form @submit="handleSubmit($event, createSitemap)">
                                <x-admin::modal ref="sitemap">
                                    <x-slot:toggle>
                                        <div class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                                            @lang('admin::app.marketing.sitemaps.create.title')
                                        </div>
                                    </x-slot:toggle>
    
                                    <!-- Modal Header -->
                                    <x-slot:header>
                                        <p class="text-[18px] text-gray-800 font-bold">
                                            @lang('admin::app.marketing.sitemaps.create.general')
                                        </p>
                                    </x-slot:header>
    
                                    <!-- Modal Content -->
                                    <x-slot:content>
                                        <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                            <!-- File Name -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label class="!mt-0">
                                                    @lang('admin::app.marketing.sitemaps.create.file-name')
                                                </x-admin::form.control-group.label>
                    
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="file_name"
                                                    :value="old('file_name')"
                                                    rules="required"
                                                    label="{{ trans('admin::app.marketing.sitemaps.create.file-name') }}"
                                                    placeholder="{{ trans('admin::app.marketing.sitemaps.create.file-name') }}"
                                                >
                                                </x-admin::form.control-group.control>
                    
                                                <x-admin::form.control-group.error
                                                    class="mt-[4px]"
                                                    control-name="file_name"
                                                >
                                                </x-admin::form.control-group.error>
    
                                                <p class="mt-[8px] ml-[4px] text-[12px] text-gray-600 font-medium">
                                                    @lang('admin::app.marketing.sitemaps.create.file-name-info')
                                                </p>
    
                                            </x-admin::form.control-group>
                    
                                            <!---- File Path -->
                                            <x-admin::form.control-group class="mb-[10px]">
                                                <x-admin::form.control-group.label>
                                                    @lang('admin::app.marketing.sitemaps.create.path')
                                                </x-admin::form.control-group.label>
                    
                                                <x-admin::form.control-group.control
                                                    type="text"
                                                    name="path"
                                                    :value="old('path')"
                                                    rules="required"
                                                    label="{{ trans('admin::app.marketing.sitemaps.create.path') }}"
                                                    placeholder="{{ trans('admin::app.marketing.sitemaps.create.path') }}"
                                                >
                                                </x-admin::form.control-group.control>
                    
                                                <x-admin::form.control-group.error
                                                    class="mt-[4px]"
                                                    control-name="path"
                                                >
                                                </x-admin::form.control-group.error>
    
                                                <p class="mt-[8px] ml-[4px] text-[12px] text-gray-600 font-medium">
                                                    @lang('admin::app.marketing.sitemaps.create.path-info')
                                                </p>
                                            </x-admin::form.control-group>
                                        </div>
                                    </x-slot:content>
                                    
                                    <x-slot:footer>
                                        <!-- Save Button -->
                                        <button class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer">
                                            @lang('admin::app.marketing.sitemaps.create.save')
                                        </button>
                                    </x-slot:footer>
                                </x-admin::modal>
                            </form>
                        </x-shop::form>                      
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-create-sitemaps', {
                template: '#v-create-sitemaps-template',

                methods: {
                    createSitemap(params, { resetForm, setErrors }) {
                        this.$axios.post("{{ route('admin.sitemaps.store') }}", params )
                            .then((response) => {
                                this.$refs.sitemap.toggle();

                                resetForm();
                            })
                            .catch(error => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },
                },
            })
        </script>
    @endPushOnce
</x-admin::layouts>