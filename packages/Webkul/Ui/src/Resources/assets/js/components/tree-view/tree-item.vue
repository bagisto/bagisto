<script>

    export default {
        name: 'tree-view',

        inheritAttrs: false,

        props: {
            inputType: String,

            nameField: String,

            idField: String,

            captionField: String,

            childrenField: String,

            valueField: String,

            items: {
                type: [Array, String, Object],
                required: false,
                default: null
            },

            value: {
                type: Array,
                required: false,
                default: null
            },

            behavior: {
                type: String,
                required: false,
                default: 'reactive'
            },

            savedValues: {
                type: Array,
                required: false,
                default: null
            }
        },

        created () {
            let index = this.savedValues.indexOf(this.items[this.valueField])
            if(index !== -1) {
                this.value.push(this.items);
            }
        },

        computed: {
            caption () {
                return this.items[this.captionField]
            },

            allChildren () {
                let leafs = [];
                let searchTree = items => {
                    if(!! items[this.childrenField] && this.getLength(items[this.childrenField]) > 0) {
                        if(typeof items[this.childrenField] == 'object') {
                            for(let key in items[this.childrenField]) {
                                searchTree(items[this.childrenField][key])
                            }
                        } else {
                            items[this.childrenField].forEach(child => searchTree(child))
                        }
                    } else {
                        leafs.push(items)
                    }
                }

                searchTree(this.items)
                
                return leafs;
            },

            hasChildren () {
                return !! this.items[this.childrenField] && this.getLength(this.items[this.childrenField]) > 0;
            },

            hasSelection () {
                return !! this.value && this.value.length > 0;
            },

            isAllChildrenSelected () {
                return this.hasChildren && this.hasSelection && this.allChildren.every(leaf => this.value.some(sel => sel[this.idField] === leaf[this.idField]))
            },

            isSomeChildrenSelected () {
                return this.hasChildren && this.hasSelection && this.allChildren.some(leaf => this.value.some(sel => sel[this.idField] === leaf[this.idField]))
            }
        },

        methods: {
            getLength (items) {
                if(typeof items == 'object') {
                    let length = 0;

                    for(let item in items) {
                        length++;
                    }

                    return length;
                }

                return items.length;
            },

            generateRoot () {
                if(this.inputType == 'checkbox') {
                    if(this.behavior == 'reactive') {
                        return this.$createElement('tree-checkbox', {
                            props: {
                                id: this.items[this.idField],
                                label: this.caption,
                                nameField: this.nameField,
                                modelValue: this.items[this.valueField],
                                inputValue: this.hasChildren ? this.isSomeChildrenSelected : this.value,
                                value: this.hasChildren ? this.isAllChildrenSelected : this.items
                            },
                            on: {
                                change: selection => {
                                    if(this.hasChildren) {
                                        if(this.isAllChildrenSelected) {
                                            this.allChildren.forEach(leaf => {
                                                let index = this.value.indexOf(leaf)
                                                this.value.splice(index, 1)
                                            })
                                        } else {
                                            this.allChildren.forEach(leaf => {
                                                let exists = false;
                                                this.value.forEach(item => {
                                                    if(item['key'] == leaf['key']) {
                                                        exists = true;
                                                    }
                                                })

                                                if(!exists) {
                                                    this.value.push(leaf);
                                                }
                                            })
                                        }

                                        this.$emit('input', this.value)
                                    } else {
                                        this.$emit('input', selection);
                                    }
                                }
                            }
                        })
                    } else {
                        return this.$createElement('tree-checkbox', {
                            props: {
                                id: this.items[this.idField],
                                label: this.caption,
                                nameField: this.nameField,
                                modelValue: this.items[this.valueField],
                                inputValue: this.value,
                                value: this.items
                            }
                        })
                    }
                } else if(this.inputType == 'radio') {
                    return this.$createElement('tree-radio', {
                        props: {
                            id: this.items[this.idField],
                            label: this.caption,
                            nameField: this.nameField,
                            modelValue: this.items[this.valueField],
                            value: this.savedValues
                        }
                    })
                }
            },

            generateChild (child) {
                return this.$createElement('tree-item', {
                    on: {
                        input: selection => {
                            this.$emit('input', selection)
                        }
                    },
                    props: {
                        items: child,
                        value: this.value,
                        savedValues: this.savedValues,
                        nameField: this.nameField,
                        inputType: this.inputType,
                        captionField: this.captionField,
                        childrenField: this.childrenField,
                        valueField: this.valueField,
                        idField: this.idField,
                        behavior: this.behavior
                    }
                })
            },

            generateChildren () {
                let childElements = [];
                if(this.items[this.childrenField]) {
                    if(typeof this.items[this.childrenField] == 'object') {
                        for(let key in this.items[this.childrenField]) {
                            childElements.push(this.generateChild(this.items[this.childrenField][key]));
                        }
                    } else {
                        this.items[this.childrenField].forEach(child => {
                            childElements.push(this.generateChild(child));
                        })
                    }
                }

                return childElements;
            },

            generateIcon () {
                return this.$createElement('i', {
                    class: ['expand-icon'],
                    on: {
                        click: selection => {
                            this.$el.classList.toggle("active")
                        }
                    }
                })
            },

            generateFolderIcon () {
                return this.$createElement('i', {
                    class: ['icon', 'folder-icon']
                })
            }
        },

        render (createElement) {
            return createElement('div', {
                    class: [
                        'tree-item',
                        'active',
                        this.hasChildren ? 'has-children' : ''
                    ]
                }, [
                    this.generateIcon(), this.generateFolderIcon(), this.generateRoot(), ... this.generateChildren()
                ]
            )
        }
    }
</script>