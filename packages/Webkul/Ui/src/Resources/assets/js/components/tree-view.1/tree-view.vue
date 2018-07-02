<script>

    export default {
        name: 'tree-view',

        inheritAttrs: false,

        props: {
            items: {
                type: Object,
                required: false,
                default: () => ({
                    "name": "Root",
                    "value": "1",
                    "children": [{
                        "name": "First Child",
                        "value": "2",
                    }, {
                        "name": "Second Child",
                        "value": "3",
                        "children": [{
                            "name": "GrandChild 1",
                            "value": "4",
                        }, {
                            "name": "GrandChild 2",
                            "value": "5",
                        }, {
                            "name":"GrandChild 3",
                            "value": "6",
                        }]
                    }]
                })
            },

            value: {
                type: Array,
                required: false,
                default: () => ([])
            }
        },

        computed: {
            allChildren () {
                let leafs = [];
                let searchTree = items => {
                    if(!! items['children'] && items['children'].length > 0) {
                        items['children'].forEach(child => searchTree(child))
                    } else {
                        leafs.push(items)
                    }
                }

                searchTree(this.items)

                return leafs;
            },

            hasChildren () {
                return !! this.items['children'] && this.items['children'].length > 0;
            },

            hasSelection () {
                return !! this.value && this.value.length > 0;
            },

            isAllChildrenSelected () {
                return this.hasChildren && this.hasSelection && this.allChildren.every(leaf => this.value.some(sel => sel === leaf.value))
            },

            isSomeChildrenSelected () {
                return this.hasSelection && this.allChildren.some(leaf => this.value.some(sel => sel === leaf.value))
            },

            isChecked () {
                return this.hasChildren ? this.isSomeChildrenSelected : (this.value.indexOf(this.items['value']) === -1 ? false : true);
            }
        },

        methods: {
            generateRoot () {
                return this.$createElement('tree-checkbox', {
                    props: {
                        label: this.items['name'],
                        inputValue: this.items['value'],
                        isChecked: this.isChecked
                    },
                    on: {
                        change: selection => {
                            if(this.hasChildren) {
                                if(this.isAllChildrenSelected) {
                                    this.allChildren.forEach(leaf => {
                                        let index = this.value.indexOf(leaf.value)
                                        this.value.splice(index, 1)
                                    })
                                } else {
                                    this.allChildren.forEach(leaf => {
                                        let index = this.value.indexOf(leaf.value)
                                        if(index === -1) {
                                            this.value.push(leaf.value);
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
            },

            generateChildren () {
                let childElements = [];
                if(this.items['children']) {
                    this.items['children'].forEach(child => {
                        childElements.push(this.generateChild(child));
                    })
                }

                return childElements;
            },

            generateChild (child) {
                return this.$createElement('tree-view', {
                    class: 'tree-item',
                    on: {
                        input: selection => {
                            // Main Turning Point
                            
                            console.log(this.items)
                            this.$emit('input', selection)
                        }
                    },
                    props: {
                        items: child,
                        value: this.value
                    }
                })
            }
        },

        render (createElement) {
            return createElement('div', {}, [this.generateRoot(), ... this.generateChildren()])
        }
    }
</script>