<!-- Image-Carousel Component -->
<v-product-carousel></v-product-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-carousel-template"
    >
        <div class="flex gap-2.5 mt-3.5 max-xl:flex-wrap">
            <div class="flex flex-col gap-2 flex-1 max-xl:flex-auto">
                <div class="p-4 bg-white dark:bg-gray-900 rounded box-shadow">
                    <div class="flex gap-x-2.5 justify-between items-center mb-2.5">
                        <div class="flex flex-col gap-1">
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @lang('admin::app.settings.themes.edit.product-carousel')
                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-300 font-medium">
                                @lang('admin::app.settings.themes.edit.product-carousel-description')
                            </p>
                        </div>
                    </div>

                    <x-admin::form.control-group class="mb-2.5 pt-4">
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.themes.edit.filter-title')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="text"
                            name="{{ $currentLocale->code }}[options][title]"
                            value="{{ $theme->translate($currentLocale->code)->options['title'] ?? '' }}"
                            class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                            :class="[errors['{{ $currentLocale->code }}[options][title]'] ? 'border border-red-600 hover:border-red-600' : '']"
                            rules="required"
                            label="@lang('admin::app.settings.themes.edit.filter-title')"
                            placeholder="@lang('admin::app.settings.themes.edit.filter-title')"
                        >
                        </v-field>

                        <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[options][title]" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.themes.edit.sort')
                        </x-admin::form.control-group.label>

                        <v-field
                            name="{{ $currentLocale->code }}[options][filters][sort]"
                            v-slot="{ field }"
                            rules="required"
                            value="{{ $theme->translate($currentLocale->code)->options['filters']['sort'] ?? '' }}"
                            label="@lang('admin::app.settings.themes.edit.sort')"
                        >
                            <select
                                name="{{ $currentLocale->code }}[options][filters][sort]"
                                v-bind="field"
                                class="custom-select flex w-full min-h-[39px] py-1.5 px-3 bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400"
                                :class="[errors['{{ $currentLocale->code }}[options][filters][sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                            >
                                <option value="" selected disabled>
                                    @lang('admin::app.settings.themes.edit.select')
                                </option>
                                
                                @foreach (
                                    product_toolbar()->getAvailableOrders()->pluck('title', 'value') 
                                    as $key => $availableOrder
                                )
                                    <option value="{{ $key }}">{{ $availableOrder }}</option>
                                @endforeach
                            </select>
                        </v-field>

                        <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[options][filters][sort]" />
                    </x-admin::form.control-group>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label class="required">
                            @lang('admin::app.settings.themes.edit.limit')
                        </x-admin::form.control-group.label>

                        <v-field
                            type="select"
                            name="{{ $currentLocale->code }}[options][filters][limit]"
                            v-slot="{ field }"
                            rules="required"
                            value="{{ $theme->translate($currentLocale->code)->options['filters']['limit'] ?? '' }}"
                            label="@lang('admin::app.settings.themes.edit.limit')"
                        >
                            <select
                                name="options[filters][limit]"
                                v-bind="field"
                                class="custom-select flex w-full min-h-[39px] py-1.5 px-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-800 rounded-md text-sm text-gray-600 dark:text-gray-300 font-normal transition-all hover:border-gray-400 dark:hover:border-gray-400"
                                :class="[errors['options[filters][limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                            >
                                <option value="" selected disabled>@lang('admin::app.settings.themes.edit.select')</option>

                                @foreach (product_toolbar()->getAvailableLimits() as $availableLimit)
                                    <option value="{{ $availableLimit }}">{{ $availableLimit }}</option>
                                @endforeach
                            </select>
                        </v-field>

                        <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[options][filters][limit]" />
                    </x-admin::form.control-group>

                    <span class="block w-full mb-4 mt-4 border-b dark:border-gray-800"></span>

                    <div class="flex gap-x-2.5 justify-between items-center">
                        <div class="flex flex-col gap-1">
                            <p class="text-base text-gray-800 dark:text-white font-semibold">
                                @lang('admin::app.settings.themes.edit.filters')
                            </p>
                        </div>
        
                        <div class="flex gap-2.5">
                            <div
                                class="secondary-button"
                                @click="$refs.productFilterModal.toggle()"
                            >
                                @lang('admin::app.settings.themes.edit.add-filter-btn')
                            </div>
                        </div>
                    </div>

                    <!-- Filters Lists -->
                    <div
                        class="grid"
                        v-if="options.filters.length"
                        v-for="(filter, index) in options.filters"
                    >
                        <!-- Hidden Input -->
                        <input
                            type="hidden"
                            :name="'{{ $currentLocale->code }}[options][filters][' + filter.key +']'"
                            :value="filter.value"
                        /> 
                    
                        <!-- Details -->
                        <div 
                            class="flex gap-2.5 justify-between py-5 cursor-pointer"
                            :class="{
                                'border-b border-slate-300 dark:border-gray-800': index < options.filters.length - 1
                            }"
                        >
                            <div class="flex gap-2.5">
                                <div class="grid gap-1.5 place-content-start">
                                    <p class="text-gray-600 dark:text-gray-300">
                                        <div> 
                                            @{{ "@lang('admin::app.settings.themes.edit.key')".replace(':key', filter.key) }}
                                        </div>
                                    </p>

                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ "@lang('admin::app.settings.themes.edit.value')".replace(':value', filter.value) }}
                                    </p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="grid gap-1 place-content-start text-right">
                                <div class="flex gap-x-5 items-center">
                                    <p 
                                        class="text-red-600 cursor-pointer transition-all hover:underline"
                                        @click="remove(filter)"
                                    > 
                                        @lang('admin::app.settings.themes.edit.delete')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Filters Illustration -->
                    <div    
                        class="grid gap-3.5 justify-center justify-items-center py-10 px-2.5"
                        v-else
                    >
                        <img
                            class="w-[120px] h-[120px] p-2 dark:invert dark:mix-blend-exclusion"
                            src="{{ bagisto_asset('images/empty-placeholders/default.svg') }}"
                            alt="@lang('admin::app.settings.themes.edit.product-carousel')"
                        >
        
                        <div class="flex flex-col gap-1.5 items-center">
                            <p class="text-base text-gray-400 font-semibold">
                                @lang('admin::app.settings.themes.edit.product-carousel')
                            </p>

                            <p class="text-gray-400">
                                @lang('admin::app.settings.themes.edit.product-carousel-description')
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
                        <input type="hidden" name="type" value="product_carousel">

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('admin::app.settings.themes.edit.name')
                            </x-admin::form.control-group.label>

                            <v-field
                                type="text"
                                name="name"
                                value="{{ $theme->name }}"
                                rules="required"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                :class="[errors['name'] ? 'border border-red-600 hover:border-red-600' : '']"
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
                                rules="required|min_value:1"
                                class="flex w-full min-h-[39px] py-2 px-3 border rounded-md text-sm text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400 dark:hover:border-gray-400 focus:border-gray-400 dark:focus:border-gray-400 dark:bg-gray-900 dark:border-gray-800"
                                :class="[errors['sort_order'] ? 'border border-red-600 hover:border-red-600' : '']"
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

                        <!-- Status -->
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
                                    value="{{ $theme->status }}"
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

            <!-- For Fitler Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, addFilter)">
                    <x-admin::modal ref="productFilterModal">
                        <!-- Modal Header -->
                        <x-slot:header>
                            <p class="text-lg text-gray-800 dark:text-white font-bold">
                                @lang('admin::app.settings.themes.edit.create-filter')
                            </p>
                        </x-slot>

                        <!-- Modal Content -->
                        <x-slot:content>
                            <!-- Key -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.key-input')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="key"
                                    rules="required"
                                    :label="trans('admin::app.settings.themes.edit.key-input')"
                                    :placeholder="trans('admin::app.settings.themes.edit.key-input')"
                                />

                                <x-admin::form.control-group.error control-name="key" />
                            </x-admin::form.control-group>

                            <!-- Value -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.value-input')
                                </x-admin::form.control-group.label>

                                <x-admin::form.control-group.control
                                    type="text"
                                    name="value"
                                    rules="required"
                                    :label="trans('admin::app.settings.themes.edit.value-input')"
                                    :placeholder="trans('admin::app.settings.themes.edit.value-input')"
                                />

                                <x-admin::form.control-group.error control-name="value" />
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
        app.component('v-product-carousel', {
            template: '#v-product-carousel-template',

            props: ['errors'],

            data() {
                return {
                    options: @json($theme->translate($currentLocale->code)['options'] ?? null),
                };
            },

            created() {
                if (this.options === null) {
                    this.options = { filters: {} };
                }   
                
                if (! this.options.filters) {
                    this.options.filters = {};
                }

                this.options.filters = Object.keys(this.options.filters)
                    .filter(key => ! ['sort', 'limit', 'title'].includes(key))
                    .map(key => ({
                        key: key,
                        value: this.options.filters[key]
                    }));
            },

            methods: {
                addFilter(params) {
                    this.options.filters.push(params);

                    this.$refs.productFilterModal.toggle();
                },

                remove(filter) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.options.filters.indexOf(filter);

                            this.options.filters.splice(index, 1);
                        }
                    });
                },
            },
        });
    </script>
@endPushOnce    