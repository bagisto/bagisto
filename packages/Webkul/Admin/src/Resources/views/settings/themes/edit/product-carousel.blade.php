<!-- Image-Carousel Component -->
<v-product-carousel></v-product-carousel>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-product-carousel-template"
    >
        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <div class="mb-2.5 flex items-center justify-between gap-x-2.5">
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
                                @lang('admin::app.settings.themes.edit.product-carousel')
                            </p>

                            <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
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
                            class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
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
                                class="custom-select flex min-h-[39px] w-full rounded-md border bg-white px-3 py-1.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
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
                                class="custom-select flex min-h-[39px] w-full rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
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

                    <span class="mb-4 mt-4 block w-full border-b dark:border-gray-800"></span>

                    <div class="flex items-center justify-between gap-x-2.5">
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold text-gray-800 dark:text-white">
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
                            class="flex cursor-pointer justify-between gap-2.5 py-5"
                            :class="{
                                'border-b border-slate-300 dark:border-gray-800': index < options.filters.length - 1
                            }"
                        >
                            <div class="flex gap-2.5">
                                <div class="grid place-content-start gap-1.5">
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
                            <div class="grid place-content-start gap-1 text-right">
                                <div class="flex items-center gap-x-5">
                                    <p 
                                        class="cursor-pointer text-red-600 transition-all hover:underline"
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
                        class="grid justify-center justify-items-center gap-3.5 px-2.5 py-10"
                        v-else
                    >
                        <img
                            class="h-[120px] w-[120px] p-2 dark:mix-blend-exclusion dark:invert"
                            src="{{ bagisto_asset('images/empty-placeholders/default.svg') }}"
                            alt="@lang('admin::app.settings.themes.edit.product-carousel')"
                        >
        
                        <div class="flex flex-col items-center gap-1.5">
                            <p class="text-base font-semibold text-gray-400">
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
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                <x-admin::accordion>
                    <x-slot:header>
                        <p class="p-2.5 text-base font-semibold text-gray-800 dark:text-white">
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
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
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
                                class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
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

                            <label class="relative inline-flex cursor-pointer items-center">
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
                                        class="peer sr-only"
                                        v-bind="field"
                                        :checked="{{ $theme->status }}"
                                    />
                                </v-field>
                    
                                <label
                                    class="peer-checked:bg-navyBlue peer h-5 w-9 cursor-pointer rounded-full bg-gray-200 after:absolute after:top-0.5 after:h-4 after:w-4 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 after:ltr:left-0.5 peer-checked:after:ltr:translate-x-full after:rtl:right-0.5 peer-checked:after:rtl:-translate-x-full"
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
                            <p class="text-lg font-bold text-gray-800 dark:text-white">
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
                            <div class="flex items-center gap-x-2.5">
                                <button 
                                    type="submit"
                                    class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3 py-1.5 font-semibold text-gray-50"
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