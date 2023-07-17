<v-create></v-create>

@pushOnce('scripts')
    <script type="text/x-template" id="v-create-template">
        <div>
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, store)">
                    <x-admin::modal ref="localeModal">
                        <x-slot:toggle>
                            <button 
                                type="button"
                                class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                            >
                                @lang('admin::app.settings.locales.add-title')
                            </button>
                        </x-slot:toggle>

                        <x-slot:header>
                            @lang('admin::app.settings.locales.add-title')
                        </x-slot:header>

                        <x-slot:content>
                            <div class="flex gap-[10px] max-xl:flex-wrap">
                                <div class="flex flex-col gap-[8px] flex-1 max-xl:flex-auto">
                                    <div class="p-[16px] rounded-[4px]">
                                        {!! view_render_event('bagisto.admin.settings.locale.create.before') !!}

                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.settings.locales.code')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="code"
                                                id="code"
                                                rules="required"
                                                :label="trans('admin::app.settings.locales.code')"
                                                :placeholder="trans('admin::app.settings.locales.code')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="code"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.settings.locales.name')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="text"
                                                name="name"
                                                id="name"
                                                rules="required"
                                                :label="trans('admin::app.settings.locales.name')"
                                                :placeholder="trans('admin::app.settings.locales.name')"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="name"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>
                            
                                        <x-admin::form.control-group class="mb-[10px]">
                                            <x-admin::form.control-group.label>
                                                @lang('admin::app.settings.locales.direction')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="select"
                                                name="direction"
                                                id="direction"
                                                rules="required"
                                                :label="trans('admin::app.settings.locales.direction')"
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
                                                @lang('admin::app.settings.locales.locale-logo')
                                            </x-admin::form.control-group.label>

                                            <x-admin::form.control-group.control
                                                type="image"
                                                name="logo_path[image_1]"
                                                id="direction"
                                                :label="trans('Logo Path')"
                                                accepted-types="image/*"
                                            >
                                            </x-admin::form.control-group.control>

                                            <x-admin::form.control-group.error
                                                control-name="logo_path[image_1]"
                                            >
                                            </x-admin::form.control-group.error>
                                        </x-admin::form.control-group>

                                        {!! view_render_event('bagisto.admin.settings.locale.create.after') !!}
                                    </div>
                                </div>
                            </div>
                        </x-slot:content>

                        <x-slot:footer>
                            <div class="flex gap-x-[10px] items-center">
                                <button 
                                    type="submit"
                                    class="px-[12px] py-[6px] bg-blue-600 border border-blue-700 rounded-[6px] text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.settings.locales.save-btn-title')
                                </button>
                            </div>
                        </x-slot:footer>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-create', {
            template: '#v-create-template',
            methods: {
                store(params, { resetForm }) {
                    this.$axios.post('{{ route('admin.locales.store') }}', params , {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        })
                        .then((response) => {
                            alert(response.data.data.message);

                            this.$refs.localeModal.toggle();

                            resetForm();
                        }).catch((error) => console.log(error));
                },
            },
        });
    </script>
@endPushOnce