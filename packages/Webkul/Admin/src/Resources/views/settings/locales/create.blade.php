<v-locale-form>
    <button 
        type="button"
        class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
        @click="$refs.localeModal.toggle()"
    >
        @lang('admin::app.settings.locales.index.create-btn')
    </button>
</v-locale-form>

@pushOnce('scripts')
    <script type="text/x-template" id="v-locale-form-template">
        <div>
            <button 
                type="button"
                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                @click="$refs.localeModal.toggle()"
            >
                @lang('admin::app.settings.locales.index.create-btn')
            </button>

            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, store)">
                    <x-admin::modal ref="localeModal">
                        <x-slot:header>
                            <p class="text-[18px] text-gray-800 font-bold">
                                @lang('admin::app.settings.locales.index.create-btn')
                            </p>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="px-[16px] py-[10px] border-b-[1px] border-gray-300">
                                {!! view_render_event('bagisto.admin.settings.locale.create.before') !!}

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.locales.index.code')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="code"
                                        id="code"
                                        rules="required"
                                        :label="trans('admin::app.settings.locales.index.code')"
                                        :placeholder="trans('admin::app.settings.locales.index.code')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="code"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>

                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.locales.index.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        id="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.locales.index.name')"
                                        :placeholder="trans('admin::app.settings.locales.index.name')"
                                    >
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error
                                        control-name="name"
                                    >
                                    </x-admin::form.control-group.error>
                                </x-admin::form.control-group>
                    
                                <x-admin::form.control-group class="mb-[10px]">
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.locales.index.direction')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="direction"
                                        id="direction"
                                        rules="required"
                                        :label="trans('admin::app.settings.locales.index.direction')"
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
                                        @lang('admin::app.settings.locales.index.locale-logo')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="image"
                                        name="logo_path[image_1]"
                                        id="direction"
                                        :label="trans('Logo Path')"
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
                                    @lang('admin::app.settings.locales.index.save-btn-title')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-locale-form', {
            template: '#v-locale-form-template',

            methods: {
                store(params, { resetForm, setErrors }) {
                    this.$axios.post('{{ route('admin.locales.store') }}', params , {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then((response) => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });

                            this.$refs.localeModal.close();
                            
                            resetForm();
                            
                            this.$refs.image.uploadedFiles = [];
                        }).catch((error) => {
                            if (error.response.status == 422) {
                                setErrors(error.response.data.errors);
                            }
                        });
                },
            },
        });
    </script>
@endPushOnce