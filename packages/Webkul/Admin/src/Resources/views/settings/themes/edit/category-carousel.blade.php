<v-category-carousel :errors="errors">
    <x-admin::shimmer.settings.themes.category-carousel />
</v-category-carousel>

<!-- Category Carousel Vue Component -->
@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-category-carousel-template"
    >
        <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
            <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                <div class="mb-2.5 flex items-center justify-between gap-x-2.5">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.settings.themes.edit.category-carousel')
                        </p>

                        <p class="text-xs font-medium text-gray-500 dark:text-gray-300">
                            @lang('admin::app.settings.themes.edit.category-carousel-description')
                        </p>
                    </div>
                </div>

                <!-- Sort -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.themes.edit.sort')
                    </x-admin::form.control-group.label>

                    <v-field
                        name="{{ $currentLocale->code }}[options][filters][sort]"
                        value="{{ $theme->translate($currentLocale->code)->options['filters']['sort'] ?? ''}}"
                        v-slot="{ field }"
                        rules="required"
                        label="@lang('admin::app.settings.themes.edit.sort')"
                    >
                        <select
                            name="{{ $currentLocale->code }}[options][sort]"
                            v-bind="field"
                            class="custom-select flex min-h-[39px] w-full rounded-md border bg-white px-3 py-1.5 text-sm font-normal text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300"
                            :class="[errors['{{ $currentLocale->code }}[options][filters][sort]'] ? 'border border-red-600 hover:border-red-600' : '']"
                        >
                            <option value="" selected disabled>
                                @lang('admin::app.settings.themes.edit.select')
                            </option>

                            <option value="desc">
                                @lang('admin::app.settings.themes.edit.desc')
                            </option>

                            <option value="asc">
                                @lang('admin::app.settings.themes.edit.asc')
                            </option>
                        </select>
                    </v-field>

                    <x-admin::form.control-group.error control-name="{{ $currentLocale->code }}[options][sort]" />
                </x-admin::form.control-group>

                <!-- Limit -->
                <x-admin::form.control-group>
                    <x-admin::form.control-group.label class="required">
                        @lang('admin::app.settings.themes.edit.limit')
                    </x-admin::form.control-group.label>

                    <v-field
                        type="text"
                        name="{{ $currentLocale->code }}[options][filters][limit]"
                        value="{{ $theme->translate($currentLocale->code)->options['filters']['limit'] ?? '' }}"
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                        :class="[errors['{{ $currentLocale->code }}[options][filters][limit]'] ? 'border border-red-600 hover:border-red-600' : '']"
                        rules="required|min_value:1"
                        label="@lang('admin::app.settings.themes.edit.limit')"
                        placeholder="@lang('admin::app.settings.themes.edit.limit')"
                    >
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
                            @click="$refs.categoryFilterModal.toggle()"
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
                    > 
                
                    <!-- Details -->
                    <div 
                        class="flex cursor-pointer items-center justify-between gap-2.5 py-5"
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
                        class="h-40 w-40 p-2 dark:mix-blend-exclusion dark:invert"
                        src="{{ bagisto_asset('images/empty-placeholders/default.svg') }}"
                        alt="@lang('admin::app.settings.themes.edit.category-carousel')"
                    >

                    <div class="flex flex-col items-center gap-1.5">
                        <p class="text-base font-semibold text-gray-400">
                            @lang('admin::app.settings.themes.edit.category-carousel')
                        </p>

                        <p class="text-gray-400">
                            @lang('admin::app.settings.themes.edit.category-carousel-description')
                        </p>
                    </div>

                    <div 
                        class="secondary-button"
                        @click="$refs.categoryFilterModal.toggle()"
                    >
                        @lang('admin::app.settings.themes.edit.add-filter-btn')
                    </div>
                </div>
            </div>

            <!-- For Fitler Form -->
            <x-admin::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form @submit="handleSubmit($event, addFilter)">
                    <x-admin::modal ref="categoryFilterModal">
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
                                    type="select"
                                    name="key"
                                    ::value="filters.available[0].code"
                                    rules="required"
                                    :label="trans('admin::app.settings.themes.edit.key-input')"
                                    @change="handleFilter($event)"
                                >
                                    <option
                                        v-for="filter in filters.available"
                                        :value="filter.code"
                                        :text="filter.name"
                                    ></option>
                                </x-admin::form.control-group.control>

                                <x-admin::form.control-group.error control-name="key" />
                            </x-admin::form.control-group>

                            <!-- Value -->
                            <x-admin::form.control-group>
                                <x-admin::form.control-group.label class="required">
                                    @lang('admin::app.settings.themes.edit.value-input')
                                </x-admin::form.control-group.label>

                                <template v-if="filters.applied.type == 'select'">
                                    <x-admin::form.control-group.control
                                        type="select"
                                        name="value"
                                        rules="required"
                                        :label="trans('admin::app.settings.themes.edit.value-input')"
                                        :placeholder="trans('admin::app.settings.themes.edit.value-input')"
                                    >
                                        <option
                                            v-for="option in filters.applied.options"
                                            :value="option.id"
                                            :text="option.name"
                                        ></option>
                                    </x-admin::form.control-group.control>
                                </template>

                                <template v-else>
                                    <x-admin::form.control-group.control
                                        type="text"
                                        name="value"
                                        rules="required"
                                        :label="trans('admin::app.settings.themes.edit.value-input')"
                                        :placeholder="trans('admin::app.settings.themes.edit.value-input')" 
                                    />
                                </template>

                                <x-admin::form.control-group.error control-name="value" />
                            </x-admin::form.control-group>
                        </x-slot>

                        <!-- Modal Footer -->
                        <x-slot:footer>
                            <button 
                                type="submit"
                                class="cursor-pointer rounded-md border border-blue-700 bg-blue-600 px-3 py-1.5 font-semibold text-gray-50"
                            >
                                @lang('admin::app.settings.themes.edit.save-btn')
                            </button>
                        </x-slot>
                    </x-admin::modal>
                </form>
            </x-admin::form>
        </div>
    </script>

    <script type="module">
        app.component('v-category-carousel', {
            template: '#v-category-carousel-template',

            props: ['errors'],

            data() {
                return {
                    options: @json($theme->translate($currentLocale->code)['options'] ?? null),

                    filters: {
                        available: [
                            {
                                id: 'parent_id',
                                code: 'parent_id',
                                name: '@lang('admin::app.settings.themes.edit.parent-id')',
                                type: 'text',
                            },
                            {
                                id: 'name',
                                code: 'name',
                                name: '@lang('admin::app.settings.themes.edit.name')',
                                type: 'text',
                            },
                            {
                                id: 'status',
                                code: 'status',
                                name: '@lang('admin::app.settings.themes.edit.status')',
                                type: 'select',
                                options: [
                                    {
                                        id: '1',
                                        name: '@lang('admin::app.settings.themes.edit.active')',
                                    },
                                    {
                                        id: '0',
                                        name: '@lang('admin::app.settings.themes.edit.inactive')',
                                    },
                                ],
                            },
                        ],

                        applied: [],
                    },
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

                    this.$refs.categoryFilterModal.toggle();
                },

                remove(filter) {
                    this.$emitter.emit('open-confirm-modal', {
                        agree: () => {
                            let index = this.options.filters.indexOf(filter);

                            this.options.filters.splice(index, 1);
                        }
                    });
                },

                handleFilter(event) {
                    this.filters.applied = this.filters.available.find(filter => filter.code == event.target.value);
                },
            },
        });
    </script>
@endPushOnce    