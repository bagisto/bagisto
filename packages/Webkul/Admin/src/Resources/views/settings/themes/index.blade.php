<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.settings.themes.index.title')
    </x-slot>
   
    <div class="flex justify-between items-center">
        <p class="text-xl text-gray-800 dark:text-white font-bold">
            @lang('admin::app.settings.themes.index.title')
        </p>
        
        <div class="flex gap-x-2.5 items-center">
            <div class="flex gap-x-2.5 items-center">
                {!! view_render_event('bagisto.admin.settings.themes.create.before') !!}

                <!-- Create Button -->
                <v-create-theme-form>
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.settings.themes.index.create-btn')
                    </button>  
                </v-create-theme-form>

                {!! view_render_event('bagisto.admin.settings.themes.create.after') !!}
            </div>
        </div>
    </div>
    
    {!! view_render_event('bagisto.admin.settings.themes.list.before') !!}

    <x-admin::datagrid :src="route('admin.settings.themes.index')" />

    {!! view_render_event('bagisto.admin.settings.themes.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-theme-form-template"
        >
            <div>
                <!-- Theme Create Button -->
                @if (bouncer()->hasPermission('settings.themes.create'))
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.themeCreateModal.toggle()"
                    >
                        @lang('admin::app.settings.themes.index.create-btn')
                    </button>
                @endif

                <!-- Modal Form -->
                <x-admin::form
                    v-slot="{ meta, errors, handleSubmit }"
                    as="div"
                >
                    <form @submit="handleSubmit($event, create)">
                        <!-- Customer Create Modal -->
                        <x-admin::modal ref="themeCreateModal">
                            <!-- Modal Header -->
                            <x-slot:header>
                                <p class="text-lg text-gray-800 dark:text-white font-bold">
                                    @lang('admin::app.settings.themes.create.title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <!-- Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('admin::app.settings.themes.create.name')"
                                        :placeholder="trans('admin::app.settings.themes.create.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>

                                <!-- Sort Order -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.sort-order')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="sort_order"
                                        rules="required|numeric"
                                        :label="trans('admin::app.settings.themes.create.sort-order')"
                                        :placeholder="trans('admin::app.settings.themes.create.sort-order')"
                                    />

                                    <x-admin::form.control-group.error control-name="sort_order" />
                                </x-admin::form.control-group>

                                <!-- Type -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.create.type.title')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="type"
                                        rules="required"
                                        value="product_carousel"
                                    >
                                        <option 
                                            v-for="(type, key) in themeTypes"
                                            :value="key"
                                            :text="type"
                                        >
                                        </option>
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="type" />
                                </x-admin::form.control-group>

                                <!-- Channels -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.settings.themes.edit.channels')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="channel_id"
                                        rules="required"
                                        :value="1"
                                    >
                                        @foreach (core()->getAllChannels() as $channel)
                                            <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                        @endforeach 
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="type" />
                                </x-admin::form.control-group>
                            </x-slot>

                            <x-slot:footer>
                                <!-- Modal Submission -->
                                <div class="flex gap-x-2.5 items-center">
                                    <button
                                        type="submit"
                                        class="primary-button"
                                    >
                                        @lang('admin::app.settings.themes.create.save-btn')
                                    </button>
                                </div>
                            </x-slot>
                        </x-admin::modal>
                    </form>
                </x-admin::form>
            </div>
        </script>

        <script type="module">
            app.component('v-create-theme-form', {
                template: '#v-create-theme-form-template',

                data() {
                    return {
                        themeTypes: {
                            product_carousel: "@lang('admin::app.settings.themes.create.type.product-carousel')",
                            category_carousel: "@lang('admin::app.settings.themes.create.type.category-carousel')",
                            static_content: "@lang('admin::app.settings.themes.create.type.static-content')",
                            image_carousel: "@lang('admin::app.settings.themes.create.type.image-carousel')",
                            footer_links: "@lang('admin::app.settings.themes.create.type.footer-links')",
                            services_content: "@lang('admin::app.settings.themes.create.type.services-content')",
                        }
                    };
                },

                methods: {
                    create(params, { setErrors }) {
                        this.$axios.post('{{ route('admin.settings.themes.store') }}', params)
                            .then((response) => {
                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } 
                            })
                            .catch((error) => {
                                if (error.response.status == 422) {
                                    setErrors(error.response.data.errors);
                                }
                            });
                    },
                },
            });
        </script>
    @endPushOnce
    
</x-admin::layouts>