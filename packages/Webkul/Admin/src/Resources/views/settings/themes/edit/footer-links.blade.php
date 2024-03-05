<!-- Todays Details Vue Component -->
<v-footer-links></v-footer-links>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-footer-links-template"
    >
        <div>
            <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
                <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                    <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                        <!-- Add Links-->
                        <div class="flex gap-x-2.5 justify-between items-center mb-2.5">
                            <div class="flex flex-col gap-1">
                                <p class="text-base text-gray-800 dark:text-white font-semibold">
                                    @lang('admin::app.settings.themes.edit.footer-link')
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                                    @lang('admin::app.settings.themes.edit.footer-link-description')
                                </p>
                            </div>
            
                            <div class="flex gap-2.5">
                                <div
                                    class="secondary-button"
                                    @click="isUpdating=false;$refs.addLinksModal.toggle()"
                                >
                                    @lang('admin::app.settings.themes.edit.add-link')
                                </div>
                            </div>
                        </div>

                        <!-- Footer Links -->
                        <div
                            v-if="Object.keys(footerLinks).length"
                            v-for="(footerLink, index) in footerLinks"
                        >
                            <!-- Information -->
                            <div 
                                class="grid border-b last:border-b-0 border-slate-300 dark:border-gray-800"
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
 
                                <div class="flex gap-2.5 justify-between py-5 cursor-pointer">
                                    <div class="flex gap-2.5">
                                        <div class="grid gap-1.5 place-content-start">
                                            <p class="text-gray-600 dark:text-gray-300">
                                                <div> 
                                                    @lang('admin::app.settings.themes.edit.column'): 

                                                    <span class="text-gray-600 dark:text-gray-300 transition-all">
                                                        @{{ link.column }}
                                                    </span>
                                                </div>
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                <div> 
                                                    @lang('admin::app.settings.themes.edit.url'):

                                                    <a
                                                        :href="link.url"
                                                        target="_blank"
                                                        class="text-blue-600 transition-all hover:underline"
                                                        v-text="link.url"
                                                    >
                                                    </a>
                                                </div>
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                <div> 
                                                    @lang('admin::app.settings.themes.edit.filter-title'):

                                                    <span
                                                        class="text-gray-600 dark:text-gray-300 transition-all"
                                                        v-text="link.title"
                                                    >
                                                    </span>
                                                </div>
                                            </p>

                                            <p class="text-gray-600 dark:text-gray-300">
                                                <div> 
                                                    @lang('admin::app.settings.themes.edit.sort-order'):

                                                    <span
                                                        class="text-gray-600 dark:text-gray-300 transition-all"
                                                        v-text="link.sort_order"
                                                    >
                                                    </span>
                                                </div>
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="grid gap-1 place-content-start text-right">
                                        <div class="flex gap-x-5 items-center">
                                            <p 
                                                class="text-blue-600 cursor-pointer transition-all hover:underline"
                                                @click="edit(link, key)"
                                            > 
                                                @lang('admin::app.settings.themes.edit.edit')
                                            </p>

                                            <p 
                                                class="text-red-600 cursor-pointer transition-all hover:underline"
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
                            class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                        >
                            <img
                                class="w-[120px] h-[120px] p-2 dark:invert dark:mix-blend-exclusion"
                                src="{{ bagisto_asset('images/empty-placeholders/default.svg') }}"
                                alt="@lang('admin::app.settings.themes.edit.footer-link')"
                            >
            
                            <div class="flex flex-col gap-1.5 items-center">
                                <p class="text-base text-gray-400 font-semibold">
                                    @lang('admin::app.settings.themes.edit.footer-link')
                                    
                                </p>

                                <p class="text-gray-400">
                                    @lang('admin::app.settings.themes.edit.footer-link-description')
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- General -->
                <div class="flex flex-col gap-2 w-[360px] max-w-full max-sm:w-full">
                    <x-admin::accordion>
                        <x-slot:header>
                            <p class="p-2.5 text-gray-800 dark:text-white text-base font-semibold">
                                @lang('admin::app.settings.themes.edit.general')
                            </p>
                        </x-slot>
                    
                        <x-slot:content>
                            <input type="hidden" name="type" value="footer_links">

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.name')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="name"
                                    value="{{ $theme->name }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('admin::app.settings.themes.edit.name')"
                                    placeholder="@lang('admin::app.settings.themes.edit.name')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error control-name="name" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.sort-order')
                                </x-admin::form.control-group.label>

                                <v-field
                                    type="text"
                                    name="sort_order"
                                    value="{{ $theme->sort_order }}"
                                    class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                    :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
                                    rules="required"
                                    label="@lang('admin::app.settings.themes.edit.sort-order')"
                                    placeholder="@lang('admin::app.settings.themes.edit.sort-order')"
                                >
                                </v-field>

                                <x-admin::form.control-group.error control-name="sort_order" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.channels')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="select"
                                    name="channel_id"
                                    rules="required"
                                    :value="$theme->channel_id"
                                >
                                    @foreach($channels as $channel)
                                        <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                                    @endforeach 
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="channel_id" />
                            </x-admin::form.control-group>

                            <x-admin::form.control-group class="!mb-0">
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.status')
                                </x-admin::form.control-group.label>

                                <label class="relative inline-flex items-center cursor-pointer">
                                    <v-field
                                        type="checkbox"
                                        name="status"
                                        class="hidden"
                                        v-slot="{ field }"
                                        :value="{{ $theme->status }}"
                                    >
                                        <input
                                            type="checkbox"
                                            name="status"
                                            id="status"
                                            class="sr-only peer"
                                            v-bind="field"
                                            :checked="{{ $theme->status }}"
                                        />
                                    </v-field>
                        
                                    <label
                                        class="rounded-full dark:peer-focus:ring-blue-800 peer-checked:bg-blue-600 w-9 h-5 bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white after:border-gray-300 peer-checked:bg-navyBlue peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-0.5 after:ltr:left-0.5 after:rtl:right-0.5 peer-focus:outline-none after:border after:rounded-full after:h-4 after:w-4 after:transition-all"
                                        for="status"
                                    ></label>
                                </label>

                                <x-admin::form.control-group.error control-name="status" />
                            </x-admin::form.control-group>
                        </x-slot>
                    </x-admin::accordion>
                </div>
            </div>

            <!-- For Fitler Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
                ref="footerLinkUpdateOrCreateModal"
            >
                <form @submit="handleSubmit($event, updateOrCreate)">
                    <x-admin::modal ref="addLinksModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.settings.themes.edit.footer-link-form-title')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <x-admin::form.control-group.control
                                type="hidden"
                                name="key"
                            />
                            
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
                            <div class="flex gap-x-2.5 items-center">
                                <button 
                                    type="submit"
                                    class="px-3 py-1.5 bg-blue-600 border border-blue-700 rounded-md text-gray-50 font-semibold cursor-pointer"
                                >
                                    @lang('admin::app.settings.themes.edit.save-btn')
                                </button>
                            </div>
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