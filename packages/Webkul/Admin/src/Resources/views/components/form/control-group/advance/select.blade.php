@props([
    'name'        => '',
    'options'     => [],
    'value'       => '',
    'placeholder' => '',
    'label'       => '',
])

<v-select
    class="block w-full"
    name="{{ $name }}"
    :options="{{ json_encode($options) }}"
    value="{{ $value }}"
    placeholder="{{ $placeholder ?: trans('admin::app.components.datagrid.toolbar.search.title') }}"
    label="{{ $label }}"
    {{ $attributes }}
>
    <div class="shimmer h-10.5 w-full rounded-md"></div>
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
                :label="label"
                v-model="selectedId"
                v-slot="{ errors }"
            >
                <div
                    class="flex min-h-10.5 w-full cursor-pointer items-center gap-1.5 rounded-md border bg-white px-3 py-1.5 text-sm transition-all hover:border-gray-400 focus:border-gray-400 focus:outline-hidden dark:border-gray-800 dark:bg-gray-900 dark:hover:border-gray-400 dark:focus:border-gray-400"
                    role="combobox"
                    aria-haspopup="listbox"
                    tabindex="0"
                    ref="trigger"
                    :class="[
                        errors.length ? 'border border-red-600! hover:border-red-600!' : '',
                        isOpen ? 'border-gray-400 dark:border-gray-400' : '',
                    ]"
                    :name="name"
                    :aria-expanded="isOpen"
                    :aria-controls="listId"
                    @click="toggleDropdown"
                    @keydown.enter.prevent="toggleDropdown"
                    @keydown.esc="closeDropdown"
                >
                    <span
                        class="flex-1 truncate"
                        :class="selectedLabel ? 'text-gray-600 dark:text-gray-300' : 'text-gray-400 dark:text-gray-500'"
                    >
                        @{{ selectedLabel || placeholder }}
                    </span>

                    <span
                        class="icon-cross flex items-center text-lg text-gray-500 hover:text-red-600 dark:text-gray-400"
                        role="button"
                        v-if="clearable && selectedLabel"
                        @click.stop="clear"
                    ></span>

                    <span
                        class="icon-arrow-down flex items-center text-2xl text-gray-500 transition-transform dark:text-gray-400"
                        :class="{ 'rotate-180': isOpen }"
                    ></span>
                </div>

                <div
                    class="absolute z-20 w-full overflow-hidden rounded-md border bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900 ltr:left-0 rtl:right-0"
                    ref="panel"
                    :class="openAbove ? 'bottom-full mb-1' : 'top-full mt-1'"
                    v-show="isOpen"
                >
                    <div
                        class="border-b p-2 dark:border-gray-800"
                        v-if="searchable"
                    >
                        <div class="relative">
                            <input
                                type="text"
                                class="block w-full rounded-md border bg-white py-1.5 text-sm leading-6 text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400 ltr:pl-3 ltr:pr-9 rtl:pl-9 rtl:pr-3"
                                ref="searchInput"
                                :placeholder="searchPlaceholder"
                                v-model="search"
                                @keydown.esc="closeDropdown"
                            />

                            <span class="icon-search pointer-events-none absolute top-1.5 flex items-center text-2xl text-gray-400 ltr:right-2 rtl:left-2"></span>
                        </div>
                    </div>

                    <div
                        class="max-h-60 overflow-y-auto py-1"
                        role="listbox"
                        ref="list"
                        :id="listId"
                        :style="{ maxHeight: listMaxHeight }"
                    >
                        <div
                            class="flex cursor-pointer items-center justify-between gap-2 px-3 py-2 text-sm transition-all hover:bg-gray-100 dark:hover:bg-gray-800"
                            role="option"
                            :class="isSelected(option.id)
                                ? 'font-semibold text-blue-600 dark:text-blue-500'
                                : 'text-gray-600 dark:text-gray-300'"
                            v-for="option in filteredOptions"
                            :key="option.id"
                            :aria-selected="isSelected(option.id)"
                            :data-value="option.id"
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
                ref="input"
                :id="id || null"
                :name="name"
                :value="selectedId"
            />
        </div>
    </script>

    <script type="module">
        app.component('v-select', {
            template: '#v-select-template',

            emits: ['update:modelValue'],

            props: {
                id: {
                    type: String,
                    default: '',
                },

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

                clearable: {
                    type: Boolean,
                    default: false,
                },

                searchable: {
                    type: Boolean,
                    default: true,
                },

                label: {
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

                    openAbove: false,

                    listMaxHeight: null,

                    searchPlaceholder: "@lang('admin::app.components.datagrid.toolbar.search.title')",
                };
            },

            watch: {
                value(value) {
                    this.selectedId = value === null || value === undefined ? '' : String(value);
                },
            },

            computed: {
                /**
                 * The id of the option list, which the field points to as the list it controls.
                 */
                listId() {
                    return `${this.name}-options`;
                },

                /**
                 * The label of the chosen option, empty while nothing is chosen.
                 */
                selectedLabel() {
                    let option = this.options.find((option) => String(option.id) === this.selectedId);

                    return option ? option.label : '';
                },

                /**
                 * The options whose label holds the search term.
                 */
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
                /**
                 * Whether the option with the given id is the chosen one.
                 */
                isSelected(id) {
                    return String(id) === this.selectedId;
                },

                /**
                 * Choose the option with the given id.
                 */
                select(id) {
                    this.choose(String(id));
                },

                /**
                 * Empty the choice.
                 */
                clear() {
                    this.choose('');
                },

                /**
                 * Store the choice, announce it to v-model and to listeners of the input, then close.
                 */
                choose(id) {
                    this.selectedId = id;

                    this.$emit('update:modelValue', id);

                    this.$nextTick(() => this.$refs.input.dispatchEvent(new Event('change', { bubbles: true })));

                    this.closeDropdown();
                },

                /**
                 * Open the options when closed, and close them when open.
                 */
                toggleDropdown() {
                    this.isOpen ? this.closeDropdown() : this.openDropdown();
                },

                /**
                 * Open the options, above the field when there is not enough room below it.
                 */
                openDropdown() {
                    this.listMaxHeight = null;

                    this.isOpen = true;

                    this.$nextTick(() => {
                        this.placeDropdown();

                        this.$refs.searchInput?.focus({ preventScroll: true });
                    });
                },

                /**
                 * Close the options and forget the search term.
                 */
                closeDropdown() {
                    this.isOpen = false;

                    this.search = '';
                },

                /**
                 * Open the options on the side of the field with room for them, or with more room when
                 * neither side has enough, shortening the list to fit.
                 */
                placeDropdown() {
                    let trigger = this.$refs.trigger.getBoundingClientRect();

                    let bounds = this.visibleBounds();

                    let below = bounds.bottom - trigger.bottom - 8;

                    let above = trigger.top - bounds.top - 8;

                    let height = this.$refs.panel.offsetHeight;

                    this.openAbove = below < height
                        && above > below;

                    let room = this.openAbove ? above : below;

                    if (height > room) {
                        this.listMaxHeight = Math.max(room - (height - this.$refs.list.offsetHeight), 96) + 'px';
                    }
                },

                /**
                 * The band of the window the options can show in: inside every ancestor that clips
                 * vertically, and below the sticky admin header.
                 */
                visibleBounds() {
                    let bounds = { top: 0, bottom: window.innerHeight };

                    let header = document.querySelector('header');

                    if (
                        header
                        && ['sticky', 'fixed'].includes(getComputedStyle(header).position)
                    ) {
                        bounds.top = header.getBoundingClientRect().bottom;
                    }

                    for (let element = this.$el.parentElement; element; element = element.parentElement) {
                        let style = getComputedStyle(element);

                        if (style.overflowY === 'visible') {
                            continue;
                        }

                        let rect = element.getBoundingClientRect();

                        bounds.top = Math.max(bounds.top, rect.top);

                        bounds.bottom = Math.min(bounds.bottom, rect.bottom);
                    }

                    return bounds;
                },

                /**
                 * Close the options on a click anywhere outside the field.
                 */
                handleOutsideClick(event) {
                    if (! this.$el.contains(event.target)) {
                        this.closeDropdown();
                    }
                },
            },
        });
    </script>
@endPushOnce
