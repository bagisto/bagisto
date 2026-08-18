<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.components.layouts.sidebar.sections')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <div class="grid gap-1.5">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.components.layouts.sidebar.sections')
            </p>

            <!--
                The screen is reached from a theme card, so it says which theme it is
                scoped to and offers a way back out to every section.
            -->
            @if ($scopedTheme)
                <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                    @lang('admin::app.appearance.sections.index.scoped-to', ['theme' => $scopedThemeName])

                    <a
                        href="{{ route('admin.appearance.sections.index') }}"
                        class="font-semibold text-blue-600 hover:underline"
                    >
                        @lang('admin::app.appearance.sections.index.show-all')
                    </a>
                </p>
            @endif
        </div>

        <div class="flex items-center gap-x-2.5">
            <div class="flex items-center gap-x-2.5">
                {!! view_render_event('bagisto.admin.appearance.sections.create.before') !!}

                <!-- Create Button -->
                <v-create-theme-form>
                    <button
                        type="button"
                        class="primary-button"
                    >
                        @lang('admin::app.appearance.sections.index.create-btn')
                    </button>  
                </v-create-theme-form>

                {!! view_render_event('bagisto.admin.appearance.sections.create.after') !!}
            </div>
        </div>
    </div>
    
{!! view_render_event('bagisto.admin.appearance.sections.list.before') !!}

    <x-admin::datagrid :src="route('admin.appearance.sections.index')" />

    {!! view_render_event('bagisto.admin.appearance.sections.list.after') !!}

    @pushOnce('scripts')
        <script
            type="text/x-template"
            id="v-create-theme-form-template"
        >
            <div>
                <!-- Theme Create Button -->
                @if (bouncer()->hasPermission('appearance.sections.create'))
                    <button
                        type="button"
                        class="primary-button"
                        @click="$refs.themeCreateModal.toggle()"
                    >
                        @lang('admin::app.appearance.sections.index.create-btn')
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
                                <p class="text-lg font-bold text-gray-800 dark:text-white">
                                    @lang('admin::app.appearance.sections.create.title')
                                </p>
                            </x-slot>

                            <!-- Modal Content -->
                            <x-slot:content>
                                <!-- Name -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.appearance.sections.create.name')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="name"
                                        rules="required"
                                        :label="trans('admin::app.appearance.sections.create.name')"
                                        :placeholder="trans('admin::app.appearance.sections.create.name')"
                                    />

                                    <x-admin::form.control-group.error control-name="name" />
                                </x-admin::form.control-group>

                                <!-- Sort Order -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.appearance.sections.create.sort-order')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="sort_order"
                                        rules="required|numeric"
                                        :label="trans('admin::app.appearance.sections.create.sort-order')"
                                        :placeholder="trans('admin::app.appearance.sections.create.sort-order')"
                                    />

                                    <x-admin::form.control-group.error control-name="sort_order" />
                                </x-admin::form.control-group>

                                <!-- Type -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.appearance.sections.create.type.title')
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
                                        @lang('admin::app.appearance.sections.edit.channels')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="channel_id"
                                        rules="required"
                                        :value="1"
                                    >
                                        @foreach (core()->getAllChannels() as $channel)
                                            <option
                                                value="{{ $channel->id }}"
                                                v-pre
                                            >
                                                {{ $channel->name }}
                                            </option>
                                        @endforeach 
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="type" />
                                </x-admin::form.control-group>

                                 <!-- Theme Selector -->
                                <x-admin::form.control-group>
                                    <x-admin::form.control-group.label class="required">
                                        @lang('admin::app.appearance.sections.create.themes')
                                    </x-admin::form.control-group.label>

                                    <x-admin::form.control-group.control
                                        type="select"
                                        id="theme_code"
                                        name="theme_code"
                                        :value="config('themes.admin-default')"
                                        :label="trans('admin::app.appearance.sections.create.themes')"
                                    >
                                        @foreach (config('themes.shop') as $themeCode => $theme)
                                            <option
                                                value="{{ $themeCode }}" {{ old('theme') == $themeCode ? 'selected' : '' }}
                                                v-pre
                                            >
                                                {{ $theme['name'] }}
                                            </option>
                                        @endforeach
                                    </x-admin::form.control-group.control>

                                    <x-admin::form.control-group.error control-name="theme" />
                                </x-admin::form.control-group>
                            </x-slot>

                             <!-- Modal Footer -->
                            <x-slot:footer>
                                <!-- Save Button -->
                                <x-admin::button
                                    button-type="submit"
                                    class="primary-button"
                                    :title="trans('admin::app.appearance.sections.create.save-btn')"
                                    ::loading="isLoading"
                                    ::disabled="isLoading"
                                />
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
                            product_carousel: "@lang('admin::app.appearance.sections.create.type.product-carousel')",
                            category_carousel: "@lang('admin::app.appearance.sections.create.type.category-carousel')",
                            static_content: "@lang('admin::app.appearance.sections.create.type.static-content')",
                            image_carousel: "@lang('admin::app.appearance.sections.create.type.image-carousel')",
                            footer_links: "@lang('admin::app.appearance.sections.create.type.footer-links')",
                            services_content: "@lang('admin::app.appearance.sections.create.type.services-content')",
                        },

                        isLoading: false,
                    };
                },

                methods: {
                    create(params, { setErrors }) {
                        this.isLoading = true;

                        this.$axios.post('{{ route('admin.appearance.sections.store') }}', params)
                            .then((response) => {
                                this.isLoading = false;

                                if (response.data.redirect_url) {
                                    window.location.href = response.data.redirect_url;
                                } 
                            })
                            .catch((error) => {
                                this.isLoading = false;

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