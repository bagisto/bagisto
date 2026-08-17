@props([
    'inputType'     => 'checkbox',
    'selectionType' => 'hierarchical',
])

@if ($inputType == 'checkbox')
    <!-- Tree Checkbox Component -->
    <x-admin::tree.checkbox />
@else
    <!-- Tree Radio Component -->
    <x-admin::tree.radio />
@endif

@pushOnce('styles')
    <style>
        /**
         * Clean tree: no folder/file icons, subtle connector guides.
         */
        .v-tree-container .v-tree-row {
            display: flex;
            align-items: center;
            padding: 2px 0;
        }

        .v-tree-toggle {
            display: inline-flex;
            width: 20px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            line-height: 1;
            color: #9ca3af;
            cursor: pointer;
            border-radius: 4px;
            transition: color .15s ease;
        }

        .v-tree-toggle:hover {
            color: #4b5563;
        }

        .dark .v-tree-toggle {
            color: #6b7280;
        }

        .dark .v-tree-toggle:hover {
            color: #e5e7eb;
        }

        .v-tree-gutter {
            display: inline-block;
            width: 20px;
            flex-shrink: 0;
        }

        /**
         * Indent every level by one step (root included). Kept here rather than as Tailwind
         * arbitrary variants so the whole tree ships in this view without an asset rebuild.
         * padding-inline-start resolves to left/right automatically per document direction.
         */
        .v-tree-item-wrapper > .v-tree-item,
        .v-tree-container .v-tree-item > .v-tree-item {
            padding-inline-start: 20px;
        }

        /**
         * Guide lines: a vertical rail per level (root included) with a tick to each node.
         */
        .v-tree-container .v-tree-item {
            position: relative;
        }

        .v-tree-container .v-tree-item::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 9px;
            border-left: 1px solid #e5e7eb;
        }

        /**
         * The last node at a level ends its rail at its own tick, so nothing dangles below.
         */
        .v-tree-container .v-tree-item:last-child::before {
            bottom: auto;
            height: 19px;
        }

        .v-tree-container .v-tree-item::after {
            content: '';
            position: absolute;
            top: 19px;
            left: 9px;
            width: 11px;
            border-top: 1px solid #e5e7eb;
        }

        /**
         * Leaf nodes have no chevron, so run the tick across the empty slot up to the checkbox.
         */
        .v-tree-container .v-tree-leaf::after {
            width: 33px;
        }

        .dark .v-tree-container .v-tree-item::before,
        .dark .v-tree-container .v-tree-item::after {
            border-color: #374151;
        }
    </style>
@endPushOnce

<v-tree-view
    {{ $attributes->except(['input-type', 'selection-type']) }}
    input-type="{{ $inputType }}"
    selection-type="{{ $selectionType }}"
>
    <x-admin::shimmer.tree />
</v-tree-view>

@pushOnce('scripts')
    <script type="module">
        app.component('v-tree-view', {
            name: 'v-tree-view',

            inheritAttrs: false,

            props: {
                inputType: {
                    type: String,
                    required: false,
                    default: 'checkbox'
                },

                selectionType: {
                    type: String,
                    required: false,
                    default: 'hierarchical'
                },

                nameField: {
                    type: String,
                    required: false,
                    default: 'permissions'
                },

                valueField: {
                    type: String,
                    required: false,
                    default: 'value'
                },

                idField: {
                    type: String,
                    required: false,
                    default: 'id'
                },

                labelField: {
                    type: String,
                    required: false,
                    default: 'name'
                },

                childrenField: {
                    type: String,
                    required: false,
                    default: 'children'
                },

                items: {
                    type: [Array, String, Object],
                    required: false,
                    default: () => ([])
                },

                value: {
                    type: [Array, String, Object],
                    required: false,
                    default: () => ([])
                },

                fallbackLocale: {
                    type: String,
                    required: 'en',
                },

                collapse: {
                    type: Boolean,
                    required: false,
                    default: false
                },

                searchable: {
                    type: [Boolean, String],
                    required: false,
                    default: false,
                },

                searchPlaceholder: {
                    type: String,
                    required: false,
                    default: 'Search',
                },
            },

            data() {
                return {
                    formattedItems: null,

                    formattedValues: null,

                    searchTerm: '',
                };
            },

            computed: {
                /**
                 * Whether the tree is being filtered. `searchable` may arrive as the string "true"
                 * from the Blade attribute, so both forms are accepted.
                 */
                isSearchable() {
                    return this.searchable === true || this.searchable === 'true';
                },
            },

            created() {
                this.formattedItems = this.getInitialFormattedItems();

                this.formattedValues = this.getInitialFormattedValues();
            },

            methods: {
                getInitialFormattedItems() {
                    return (typeof this.items == 'string')
                        ? JSON.parse(this.items)
                        : this.items;
                },

                getInitialFormattedValues() {
                    if (this.inputType == 'radio') {
                        if (typeof this.value == 'array') {
                            return this.value;
                        } else {
                            return [this.value];
                        }
                    }

                    return (typeof this.value == 'string')
                        ? JSON.parse(this.value)
                        : this.value;
                },

                getId(item) {
                    const timestamp = new Date().getTime().toString(36);

                    const id = item[this.idField];

                    return `${timestamp}_${id}`
                },

                getLabel(item) {
                    return item[this.labelField]
                        ? item[this.labelField]
                        : item.translations.filter((translation) => translation.locale === this.fallbackLocale)[0][this.labelField];
                },

                /**
                 * Whether an item, or any of its descendants, matches the current search term. Used to
                 * keep a node visible when the match is deeper in its branch. Nodes that do not match
                 * are only hidden (display:none), never removed, so their inputs stay in the form and
                 * previously-selected-but-filtered-out values are preserved on submit.
                 */
                subtreeMatches(item) {
                    const term = (this.searchTerm || '').trim().toLowerCase();

                    if (! term) {
                        return true;
                    }

                    if (this.getLabel(item).toLowerCase().includes(term)) {
                        return true;
                    }

                    const children = item[this.childrenField] || {};

                    for (let key in children) {
                        if (this.subtreeMatches(children[key])) {
                            return true;
                        }
                    }

                    return false;
                },

                generateToggleIconComponent(hasChildren) {
                    /**
                     * Leaf nodes get a plain spacer so their checkbox lines up with the sibling
                     * parents' checkboxes; parents get a clickable chevron that collapses the branch.
                     */
                    if (! hasChildren) {
                        return this.$h('i', { class: ['v-tree-gutter'] });
                    }

                    return this.$h('i', {
                        class: ['icon-sort-down v-tree-toggle'],

                        onClick: (selection) => {
                            const item = selection.srcElement.closest('.v-tree-item');

                            item.classList.toggle('active');

                            selection.srcElement.classList.toggle('icon-sort-down', !selection.srcElement.classList.contains('icon-sort-down'));
                            selection.srcElement.classList.toggle('icon-sort-right', !selection.srcElement.classList.contains('icon-sort-right'));
                        },
                    });
                },

                generateCheckboxComponent(props) {
                    return this.$h(this.$resolveComponent('v-tree-checkbox'), {
                        ...props,

                        onChangeInput: (item) => {
                            this.handleCheckbox(item.value);

                            this.$emit('change-input', this.formattedValues);
                        },
                    });
                },

                generateRadioComponent(props) {
                    return this.$h(this.$resolveComponent('v-tree-radio'), {
                        ...props,

                        onChangeInput: (item) => {
                            this.$emit('change-input', this.formattedValues[0]);
                        },
                    });
                },

                generateInputComponent(props) {
                    switch (this.inputType) {
                        case 'checkbox':
                            return this.generateCheckboxComponent(props);

                        case 'radio':
                            return this.generateRadioComponent(props);

                        default:
                            return this.generateCheckboxComponent(props);
                    }
                },

                generateTreeItemComponents(items, level = 1) {
                    let treeItems = [];

                    for (let key in items) {
                        let hasChildren = Object.entries(items[key][this.childrenField]).length > 0;

                        /**
                         * Hide (never remove) a node whose whole branch has no search match, so its
                         * input stays in the DOM and any prior selection still submits.
                         */
                        let hidden = this.isSearchable && ! this.subtreeMatches(items[key]);

                        treeItems.push(
                            this.$h(
                                'div', {
                                    class: [
                                        this.collapse ? '' : 'active',
                                        'v-tree-item w-full [&>.v-tree-item]:hidden [&.active>.v-tree-item]:block',
                                        hasChildren ? '' : 'v-tree-leaf',
                                    ],
                                    style: hidden ? 'display: none' : null,
                                }, [
                                    this.$h('div', {
                                        class: ['v-tree-row'],
                                    }, [
                                        this.generateToggleIconComponent(hasChildren),

                                        this.generateInputComponent({
                                            id: this.getId(items[key]),
                                            label: this.getLabel(items[key]),
                                            name: this.nameField,
                                            value: items[key][this.valueField],
                                        }),
                                    ]),

                                    this.generateTreeItemComponents(items[key][this.childrenField], level + 1),
                                ]
                            )
                        );
                    }

                    return treeItems;
                },

                generateTree() {
                    return this.$h(
                        'div', {
                            class: [
                                'v-tree-item-wrapper',
                            ],
                        }, [
                            this.generateTreeItemComponents(this.formattedItems),
                        ]
                    );
                },

                searchInTree(items, value, ancestors = []) {
                    for (let key in items) {
                        if (items[key][this.valueField] === value) {
                            return Object.assign(items[key], { ancestors: ancestors.reverse() });
                        }

                        const result = this.searchInTree(items[key][this.childrenField], value, [...ancestors, items[key]]);

                        if (result !== undefined) {
                            return result;
                        }
                    }

                    return undefined;
                },

                has(key) {
                    let foundValues = this.formattedValues.filter(value => value == key);

                    return foundValues.length > 0;
                },

                select(key) {
                    if (! this.has(key)) {
                        this.formattedValues.push(key);
                    }
                },

                unSelect(key) {
                    this.formattedValues = this.formattedValues.filter((savedKey) => savedKey !== key);
                },

                toggle(key) {
                    this.has(key) ? this.unSelect(key) : this.select(key);
                },

                handleCheckbox(key) {
                    let item = this.searchInTree(this.formattedItems, key);

                    switch (this.selectionType) {
                        case 'individual':
                            this.handleIndividualSelectionType(item);

                            break;

                        case 'hierarchical':
                            this.handleHierarchicalSelectionType(item);

                            break;

                        default:
                            this.handleHierarchicalSelectionType(item);

                            break;
                    }
                },

                handleIndividualSelectionType(item) {
                    this.handleCurrent(item);
                },

                handleHierarchicalSelectionType(item) {
                    this.handleAncestors(item);

                    this.handleCurrent(item);

                    this.handleChildren(item);

                    if (! this.has(item[this.valueField])) {
                        this.unSelectAllChildren(item);
                    }
                },

                handleAncestors(item) {
                    if (item.ancestors.length) {
                        item.ancestors.forEach((ancestor) => {
                            this.select(ancestor[this.valueField]);
                        });
                    }
                },

                handleCurrent(item) {
                    this.toggle(item[this.valueField]);
                },

                handleChildren(item) {
                    let selectedChildrenCount = this.countSelectedChildren(item);

                    selectedChildrenCount ? this.unSelectAllChildren(item) : this.selectAllChildren(item);
                },

                countSelectedChildren(item, selectedCount = 0) {
                    if (typeof item[this.childrenField] === 'object') {
                        for (let childKey in item[this.childrenField]) {
                            if (this.has(item[this.childrenField][childKey][this.valueField])) {
                                ++selectedCount;
                            }

                            this.countSelectedChildren(item[this.childrenField][childKey], selectedCount);
                        }
                    }

                    return selectedCount;
                },

                selectAllChildren(item) {
                    if (typeof item[this.childrenField] === 'object') {
                        for (let childKey in item[this.childrenField]) {
                            this.select(item[this.childrenField][childKey][this.valueField]);

                            this.selectAllChildren(item[this.childrenField][childKey]);
                        }
                    }
                },

                unSelectAllChildren(item) {
                    if (typeof item[this.childrenField] === 'object') {
                        for (let childKey in item[this.childrenField]) {
                            this.unSelect(item[this.childrenField][childKey][this.valueField]);

                            this.unSelectAllChildren(item[this.childrenField][childKey]);
                        }
                    }
                },
            },

            render() {
                const children = [];

                /**
                 * Opt-in search box. Filters the tree live; matches keep their ancestors visible for
                 * context, non-matches are hidden but retained in the DOM (see subtreeMatches).
                 */
                if (this.isSearchable) {
                    children.push(
                        this.$h('div', {
                            class: ['relative mb-3'],
                        }, [
                            this.$h('input', {
                                type: 'text',
                                value: this.searchTerm,
                                placeholder: this.searchPlaceholder,
                                class: 'block h-10 w-full rounded-lg border bg-white text-sm text-gray-600 transition-all hover:border-gray-400 focus:border-gray-400 ltr:pl-3 ltr:pr-10 rtl:pl-10 rtl:pr-3 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300',
                                onInput: (event) => { this.searchTerm = event.target.value; },
                            }),

                            this.$h('span', {
                                class: ['icon-search pointer-events-none absolute top-2 flex items-center text-2xl text-gray-500 ltr:right-2 rtl:left-2'],
                            }),
                        ])
                    );
                }

                children.push(this.generateTree());

                return this.$h('div', {
                    class: ['v-tree-container'],
                }, children);
            }
        });
    </script>
@endPushOnce
