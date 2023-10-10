@pushOnce('scripts')
    {{-- v-tree-item component --}}
    <script type="module">
        app.component('v-tree-item', {
            inheritAttrs: false,
            
            props: {
                inputType: {
                    type: String,
                },

                nameField: {
                    type: String,
                },

                idField: {
                    type: String,
                },

                captionField: {
                    type: String,
                },

                childrenField: {
                    type: String,
                },

                valueField: {
                    type: String,
                },

                items: {
                    type: [Array, String, Object],
                    required: false,
                    default: null,
                },

                value: {
                    type: Array,
                    required: false,
                    default: null,
                },

                behavior: {
                    type: String,
                    required: false,
                    default: 'reactive',
                },

                savedValues: {
                    type: Array,
                    required: false,
                    default: null,
                },

                fallbackLocale: {
                    type: String,
                    required: false,
                },
            },

            data() {
                return {
                    isActive: false,
                }
            },

            created() {
                if (!this.savedValues) return;

                let found = this.savedValues.filter(
                    (value) => value == this.items[this.valueField]
                );

                if (found.length) {
                    this.value.push(this.items);
                }
            },

            computed: {
                caption() {
                    return this.items[this.captionField]
                        ? this.items[this.captionField]
                        : this.items.translations.filter(
                            (translation) =>
                                translation.locale === this.fallbackLocale
                        )[0][this.captionField];
                },

                allChildren() {
                    let leafs = [];

                    let searchTree = (items) => {
                        if (
                            !!items[this.childrenField] &&
                            this.getLength(items[this.childrenField]) > 0
                        ) {
                            if (typeof items[this.childrenField] == 'object') {
                                for (let key in items[this.childrenField]) {
                                    searchTree(items[this.childrenField][key]);
                                }
                            } else {
                                items[this.childrenField].forEach((child) =>
                                    searchTree(child)
                                );
                            }
                        } else {
                            leafs.push(items);
                        }
                    };

                    searchTree(this.items);

                    return leafs;
                },

                hasChildren() {
                    if (this.items) {
                        return (
                            !!this.items[this.childrenField] &&
                            this.getLength(this.items[this.childrenField]) > 0
                        );
                    }
                },

                hasSelection() {
                    return !!this.value && this.value.length > 0;
                },

                isAllChildrenSelected() {
                    return (
                        this.hasChildren &&
                        this.hasSelection &&
                        this.allChildren.every((leaf) =>
                            this.value.some(
                                (sel) => sel[this.idField] === leaf[this.idField]
                            )
                        )
                    );
                },

                isSomeChildrenSelected() {
                    return (
                        this.hasChildren &&
                        this.hasSelection &&
                        this.allChildren.some((leaf) =>
                            this.value.some(
                                (sel) => sel[this.idField] === leaf[this.idField]
                            )
                        )
                    );
                },
            },  

            methods: {
                getLength(items) {
                    return typeof items == 'object'
                        ? Object.keys(items).length
                        : items.length;
                },

                generateRoot() {
                    if (this.inputType == 'checkbox') {
                        if (this.behavior == 'reactive') {
                            return this.$h(this.$resolveComponent('v-tree-checkbox'), {
                                id: this.items[this.idField],
                                label: this.caption,
                                nameField: this.nameField,
                                modelValue: this.items[this.valueField],
                                inputValue: this.hasChildren
                                    ? this.isSomeChildrenSelected
                                    : this.value,
                                value: this.hasChildren
                                    ? this.isAllChildrenSelected
                                    : this.items,
                                
                                onInputChange: (selection) => {
                                    if (this.hasChildren) {
                                        if (this.isAllChildrenSelected) {
                                            this.resetChildren();
                                        } else {
                                            if (! selection) {
                                                this.allChildren.forEach((leaf) => {
                                                    this.value.forEach((item, index) => {
                                                        if (item[this.idField] == leaf[this.idField]) {
                                                            this.value.splice(index, 1);
                                                        }
                                                    });
                                                });
                                            } else {
                                                this.allChildren.forEach((leaf) => {
                                                    let exists = false;

                                                    this.value.forEach((item) => {
                                                        if (item[this.idField] == leaf[this.idField]) {
                                                            exists = true;
                                                        }
                                                    });

                                                    if (!exists) {
                                                        this.value.push(leaf);
                                                    }
                                                });
                                            }
                                        }

                                        this.$emit('input-change', this.value);
                                    } else {
                                        this.$emit('input-change', selection);
                                    }
                                },
                            });
                        } else {
                            return this.$h(this.$resolveComponent('v-tree-checkbox'), {
                                id: this.items[this.idField],
                                label: this.caption,
                                nameField: this.nameField,
                                modelValue: this.items[this.valueField],
                                inputValue: this.value,
                                value: this.items,
                            });
                        }
                    } else if (this.inputType == 'radio') {
                        return this.$h(this.$resolveComponent('v-tree-radio'), {
                            id: this.items[this.idField],
                            label: this.caption,
                            nameField: this.nameField,
                            modelValue: this.items.id,
                            value: this.savedValues,
                        });
                    }
                },

                generateChild(child) {
                    return this.$h(this.$resolveComponent('v-tree-item'), {
                        items: child,
                        value: this.value,
                        savedValues: this.savedValues,
                        nameField: this.nameField,
                        inputType: this.inputType,
                        captionField: this.captionField,
                        childrenField: this.childrenField,
                        valueField: this.valueField,
                        idField: this.idField,
                        behavior: this.behavior,
                        fallbackLocale: this.fallbackLocale,
                        onInputChange:(selection) => {
                            this.$emit('input-change', selection);
                        }
                    });
                },

                generateChildren() {
                    let childElements = [];

                    if (this.items) {
                        if (this.items[this.childrenField]) {
                            if (typeof this.items[this.childrenField] == 'object') {
                                for (let key in this.items[this.childrenField]) {
                                    childElements.push(
                                        this.generateChild(
                                            this.items[this.childrenField][key]
                                        )
                                    );
                                }
                            } else {
                                this.items[this.childrenField].forEach((child) => {
                                    childElements.push(this.generateChild(child));
                                });
                            }
                        }

                        return childElements;
                    }

                    return childElements;
                },

                generateIcon() {
                    return this.$h('i', {
                        class: [
                            this.isActive ? 'icon-sort-right' : 
                            this.hasChildren ? 'icon-sort-down' : '',
                            'text-[20px] rounded-[6px] cursor-pointer transition-all hover:bg-gray-100 dark:hover:bg-gray-950'
                        ],
                        
                        onClick: (selection) => {
                            this.$el.classList.toggle('active');

                            if (this.$el.classList.contains('has-children')) {
                                this.isActive = ! this.isActive;
                            }
                        },
                    });
                },

                generateFolderIcon() {
                    return this.$h('i', {
                        class: [
                            this.hasChildren ? 'icon-folder': 'icon-attribute',
                            'text-[24px] cursor-pointer'
                        ],
                    });
                },

                resetChildren() {
                    if (this.inputType == 'checkbox') {
                        this.allChildren.forEach((leaf) => {
                            let index = this.value.findIndex(
                                (item) => item[this.idField] === leaf[this.idField]
                            );

                            this.value.splice(index, 1);
                        });
                    }
                },
            },

            render() {
                return this.$h(
                    'div', {
                        class: [
                            'v-tree-item active inline-block w-full [&>.v-tree-item]:ltr:pl-[25px] [&>.v-tree-item]:rtl:pr-[25px] [&>.v-tree-item]:hidden [&.active>.v-tree-item]:block',
                            this.hasChildren ? 'has-children' : 'ltr:!pl-[55px] rtl:!pr-[55px]',
                        ],
                    }, [
                        this.generateIcon(),
                        this.generateFolderIcon(),
                        this.generateRoot(),
                        ...this.generateChildren(),
                    ]
                );
            },
        });
    </script>
@endPushOnce