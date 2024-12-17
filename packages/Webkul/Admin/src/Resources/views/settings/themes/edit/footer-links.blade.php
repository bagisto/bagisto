<v-footer-links :errors="errors">
    <x-admin::shimmer.settings.themes.footer-links />
</v-footer-links>

<!-- Footer Link Vue Component -->
@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-footer-links-template"
    >
        <!-- Left Panel -->
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <!-- Add Links-->
                <div class="mb-2.5 flex items-center justify-between gap-x-2.5">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.themes.edit.footer-link')
                        </p>

                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.themes.edit.footer-link-description')
                        </p>
                    </div>

                    <!-- Add Link Button -->
                    <div
                        class="secondary-button"
                        @click="isUpdating=false;$refs.addLinksModal.toggle()"
                    >
                        @lang('admin::app.settings.themes.edit.add-link')
                    </div>
                </div>

                <!-- Footer Links -->
                <div
                    v-if="Object.keys(footerLinks).length"
                    v-for="(footerLink, index) in footerLinks"
                >
                    <!-- Information -->
                    <div
                        class="grid border-b border-slate-300 last:border-b-0 dark:border-gray-800"
                        v-for="(link, key) in footerLink"
                    >
                        <!-- Hidden Input -->
                        <input
                            type="hidden"
                            :name="'{{ $currentLocale->code }}[options][' + link.column + '][' + key + ']'"
                            :value="link.column"
                        />

                        <input
                            type="hidden"
                            :name="'{{ $currentLocale->code }}[options][' + link.column + '][' + key + '][url]'"
                            :value="link.url"
                        />

                        <input
                            type="hidden"
                            :name="'{{ $currentLocale->code }}[options][' + link.column + '][' + key + '][title]'"
                            :value="link.title"
                        />

                        <input
                            type="hidden"
                            :name="'{{ $currentLocale->code }}[options][' + link.column + '][' + key + '][sort_order]'"
                            :value="link.sort_order"
                        />

                        <div class="flex cursor-pointer justify-between gap-2.5 py-5">
                            <div class="flex gap-2.5">
                                <div class="grid place-content-start gap-1.5">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.themes.edit.column'): 

                                        <span class="text-gray-600 transition-all dark:text-gray-300">
                                            @{{ link.column }}
                                        </span>
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.themes.edit.url'):

                                        <a
                                            :href="link.url"
                                            target="_blank"
                                            class="text-blue-600 transition-all hover:underline"
                                        >
                                            @{{ link.url }}
                                        </a>
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.themes.edit.filter-title'):

                                        <span class="text-gray-600 transition-all dark:text-gray-300">
                                            @{{ link.title }}
                                        </span>
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @lang('admin::app.settings.themes.edit.sort-order'):

                                        <span
                                            class="text-gray-600 transition-all dark:text-gray-300"
                                            v-text="link.sort_order"
                                        >
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid place-content-start gap-1 text-right">
                                <div class="flex items-center gap-x-5">
                                    <p 
                                        class="cursor-pointer text-blue-600 transition-all hover:underline"
                                        @click="edit(link, key)"
                                    > 
                                        @lang('admin::app.settings.themes.edit.edit')
                                    </p>

                                    <p 
                                        class="cursor-pointer text-red-600 transition-all hover:underline"
                                        @click="remove(link, key)"
                                    > 
                                        @lang('admin::app.settings.themes.edit.delete')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    v-if="isFooterLinksEmpty"
                    class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                >
                    <img
                        class="h-[120px] w-[120px] p-2 dark:mix-blend-exclusion dark:invert"
                        src="{{ bagisto_asset('images/empty-placeholders/default.svg') }}"
                        alt="@lang('admin::app.settings.themes.edit.footer-link')"
                    >
    
                    <div class="flex flex-col items-center gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.settings.themes.edit.footer-link')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.settings.themes.edit.footer-link-description')
                        </p>
                    </div>
                </div>
            </div>

            <!-- For Filter Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="footerLinkUpdateOrCreateModal"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    <x-admin::modal ref="addLinksModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.themes.edit.footer-link-form-title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="key"
                            />

                            <!-- Column Select -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.column')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="column"
                                    rules="required"
                                    :label="trans('admin::app.settings.themes.edit.column')"
                                    :placeholder="trans('admin::app.settings.themes.edit.column')"
                                    ::disabled="isUpdating"
                                >
                                    <option value="column_1">1</option>
                                    <option value="column_2">2</option>
                                    <option value="column_3">3</option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="column" />
                            </x-admin::form.control-group>

                            <!-- Title -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.footer-title')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="title"
                                    rules="required"
                                    :label="trans('admin::app.settings.themes.edit.footer-title')"
                                    :placeholder="trans('admin::app.settings.themes.edit.footer-title')"
                                />

                                <x-admin::form.control-group.error control-name="title" />
                            </x-admin::form.control-group>

                            <!-- URL -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.url')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="url"
                                    rules="required|url"
                                    :label="trans('admin::app.settings.themes.edit.url')"
                                    :placeholder="trans('admin::app.settings.themes.edit.url')"
                                />

                                <x-admin::form.control-group.error control-name="url" />
                            </x-admin::form.control-group>

                            <!-- Sort Order -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="sort_order"
                                    rules="required|numeric"
                                    :label="trans('admin::app.settings.themes.edit.sort-order')"
                                    :placeholder="trans('admin::app.settings.themes.edit.sort-order')"
                                />

                                <x-admin::form.control-group.error control-name="sort_order" />
                            </x-admin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <!-- Save Button -->
                            <x-admin::button
                                button-type="submit"
                                class="primary-button justify-center"
                                :title="trans('admin::app.settings.themes.edit.save-btn')"
                            />
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-footer-links', {
            template: '#v-footer-links-template',

            props: ['errors'],

            data() {
                return {
                    footerLinks: @json($theme->translate($currentLocale->code)['options'] ?? null),

                    isUpdating: false,
                };
            },

            computed: {
                isFooterLinksEmpty() {
                    return Object.values(this.footerLinks).every(column => column.length === 0);
                },
            },

            created() {
                if (this.footerLinks === null) {
                    this.footerLinks = {};
                }

                for (let i = 1; i <= 3; i++) {
                    if (!this.footerLinks.hasOwnProperty(`column_${i}`)) {
                        this.footerLinks[`column_${i}`] = [];
                    }
                }

                Object.keys(this.footerLinks).forEach(key => {
                    this.footerLinks[key] = this.footerLinks[key].map(item => ({
                        ...item,
                        column: key
                    }));
                });
            },

            methods: {
                updateOrCreate(params) {
                    if (params.key != null) {
                        Object.keys(this.footerLinks).forEach(key => {
                            this.footerLinks[params.column][params.key] = params;
                        });
                    } else {
                        this.footerLinks[params.column].push(params);
                    }

                    this.$refs.addLinksModal.toggle();
                },

                remove(footerLink, key) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            this.footerLinks[footerLink.column].splice(key, 1);
                        }
                    });
                },

                edit(footerLink, key) {
                    this.isUpdating = true;

                    this.$refs.footerLinkUpdateOrCreateModal.setValues({
                        ...footerLink, 
                        key,
                    });

                    this.$refs.addLinksModal.toggle();
                },
            },
        });
    </script>
@endPushOnce    