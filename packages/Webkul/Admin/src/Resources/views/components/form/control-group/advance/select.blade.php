@props([
    'name'        => '',
    'options'     => [],
    'value'       => '',
    'placeholder' => '',
])

<v-select
    class="block w-full"
    name="{{ $name }}"
    :options="{{ json_encode($options) }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder ?: trans('admin::app.components.datagrid.toolbar.search.title') }}"
    {{ $attributes }}
>
    <div class="shimmer h-[42px] w-full rounded-md"></div>
</v-select>

@pushOnce('scripts')
    <script
        type="text/x-template"
        id="v-select-template"
    >
        <div class="relative w-full">
            <v-field
                :name="name"
                :rules="rules"
                v-model="selectedId"
                v-slot="{ errors }"
            >
                <div
                    class="flex min-h-[42px] w-full cursor-pointer items-center gap-1.5 rounded-md border bg-white px-3 py-1.5 text-sm transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:hover:border-gray-400"
                    :class="[
                        errors.length ? 'border !border-red-600 hover:!border-red-600' : '',
                        isOpen ? 'border-gray-400 dark:border-gray-400' : '',
                    ]"
                    @click="toggleDropdown"
                >
                    <span
                        class="flex-1 truncate"
                        :class="selectedLabel ? 'text-gray-600 dark:text-gray-300' : 'text-gray-400 dark:text-gray-500'"
                    >
                        @{{ selectedLabel || placeholder }}
                    </span>

                    <span
                        class="icon-arrow-down flex items-center text-2xl text-gray-500 transition-transform dark:text-gray-400"
                        :class="{ 'rotate-180': isOpen }"
                    ></span>
                </div>

                <div
                    class="absolute left-0 z-20 mt-1 w-full overflow-hidden rounded-md border bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900"
                    v-show="isOpen"
                >
                    <div class="border-b p-2 dark:border-gray-800">
                        <div class="relative">
                            <input
                                type="text"
                                ref="searchInput"
                                class="block w-full rounded-md border bg-white py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400 ltr:pl-3 ltr:pr-9 rtl:pl-9 rtl:pr-3"
                                :placeholder="searchPlaceholder"
                                v-model="search"
                            />

                            <span class="icon-search pointer-events-none absolute top-1.5 flex items-center text-2xl text-gray-400 ltr:right-2 rtl:left-2"></span>
                        </div>
                    </div>

                    <div class="max-h-60 overflow-y-auto py-1">
                        <div
                            class="flex cursor-pointer items-center justify-between gap-2 px-3 py-2 text-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-800"
                            :class="isSelected(option.id)
                                ? 'font-semibold text-blue-600 dark:text-blue-500'
                                : 'text-gray-600 dark:text-gray-300'"
                            v-for="option in filteredOptions"
                            :key="option.id"
                            @click="select(option.id)"
                        >
                            <span class="truncate">@{{ option.label }}</span>

                            <span
                                class="icon-checked shrink-0 text-xl text-blue-600 dark:text-blue-500"
                                v-show="isSelected(option.id)"
                            ></span>
                        </div>

                        <p
                            class="px-3 py-2.5 text-sm text-gray-400 dark:text-gray-500"
                            v-if="! filteredOptions.length"
                        >
                            @lang('admin::app.components.datagrid.filters.dropdown.searchable.no-results')
                        </p>
                    </div>
                </div>
            </v-field>

            <input
                type="hidden"
                :name="name"
                :value="selectedId"
            />
        </div>
    </script>

    <script type="module">
        app.component('v-select', {
            template: '#v-select-template',

            props: {
                name: {
                    type: String,
                    required: true,
                },

                options: {
                    type: Array,
                    default: () => [],
                },

                value: {
                    type: [String, Number],
                    default: '',
                },

                rules: {
                    type: [String, Object],
                    default: '',
                },

                placeholder: {
                    type: String,
                    default: '',
                },
            },

            data() {
                return {
                    selectedId: this.value !== null && this.value !== undefined && this.value !== ''
                        ? String(this.value)
                        : '',

                    search: '',

                    isOpen: false,

                    searchPlaceholder: "@lang('admin::app.components.datagrid.toolbar.search.title')",
                };
            },

            computed: {
                selectedLabel() {
                    let option = this.options.find((option) => String(option.id) === this.selectedId);

                    return option ? option.label : '';
                },

                filteredOptions() {
                    let term = this.search.trim().toLowerCase();

                    if (! term) {
                        return this.options;
                    }

                    return this.options.filter((option) => String(option.label).toLowerCase().includes(term));
                },
            },

            mounted() {
                document.addEventListener('click', this.handleOutsideClick);
            },

            beforeUnmount() {
                document.removeEventListener('click', this.handleOutsideClick);
            },

            methods: {
                isSelected(id) {
                    return String(id) === this.selectedId;
                },

                select(id) {
                    this.selectedId = String(id);

                    this.closeDropdown();
                },

                openDropdown() {
                    this.isOpen = true;

                    this.$nextTick(() => this.$refs.searchInput?.focus());
                },

                closeDropdown() {
                    this.isOpen = false;

                    this.search = '';
                },

                toggleDropdown() {
                    this.isOpen ? this.closeDropdown() : this.openDropdown();
                },

                handleOutsideClick(event) {
                    if (! this.$el.contains(event.target)) {
                        this.closeDropdown();
                    }
                },
            },
        });
    </script>
@endPushOnce
