@props([
    'inputType' => 'checkbox',
    'selectionType' => 'hierarchical',
])

@if ($inputType == 'checkbox')
    <!-- Tree Checkbox Component -->
    <x-admin::tree.checkbox />
@else
    <!-- Tree Radio Component -->
    <x-admin::tree.radio />
@endif

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
            },

            data() {
                return {
                    formattedItems: null,

                    formattedValues: null,
                };
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

                generateToggleIconComponent(props) {
                    return this.$h('i', {
                        ...props,

                        onClick: (selection) => {
                            selection.srcElement.parentElement.classList.toggle('active');

                            selection.srcElement.classList.toggle('icon-sort-down', !selection.srcElement.classList.contains('icon-sort-down'));
                            selection.srcElement.classList.toggle('icon-sort-right', !selection.srcElement.classList.contains('icon-sort-right'));
                        },
                    });
                },

                generateFolderIconComponent(props) {
                    return this.$h('i', {
                        ...props,
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

                        treeItems.push(
                            this.$h(
                                'div', {
                                    class: [
                                        'v-tree-item active inline-block w-full [&>.v-tree-item]:ltr:pl-6 [&>.v-tree-item]:rtl:pr-6 [&>.v-tree-item]:hidden [&.active>.v-tree-item]:block',
                                        level === 1 && ! hasChildren
                                            ? 'ltr:!pl-5 rtl:!pr-5'
                                            : level > 1 && ! hasChildren
                                            ? 'ltr:!pl-14 rtl:!pr-14'
                                            : '',
                                    ],
                                }, [
                                    this.generateToggleIconComponent({
                                        class: [
                                            hasChildren ? 'icon-sort-down' : '',
                                            'text-xl rounded-md cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950'
                                        ],
                                    }),

                                    this.generateFolderIconComponent({
                                        class: [
                                            hasChildren ? 'icon-folder' : 'icon-attribute',
                                            'text-2xl cursor-pointer'
                                        ],
                                    }),

                                    this.generateInputComponent({
                                        id: this.getId(items[key]),
                                        label: this.getLabel(items[key]),
                                        name: this.nameField,
                                        value: items[key][this.valueField],
                                    }),

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
                return this.$h('div', {
                    class: ['v-tree-container']
                }, [this.generateTree()]);
            }
        });
    </script>
@endPushOnce
