<script>

    export default {
        name: 'tree-view',

        inheritAttrs: false,

        props: {
            idField: {
                type: String,
                required: false,
                default: 'id'
            },

            captionField: {
                type: String,
                required: false,
                default: 'name'
            },

            childrenField: {
                type: String,
                required: false,
                default: 'children'
            },

            valueField: {
                type: String,
                required: false,
                default: 'value'
            },

            items: {
                type: [Array, String, Object],
                required: false,
                default: () => ([{
                        "name": "Dashboard",
                        "value": "1",
                    }, {
                        "name": "Root",
                        "value": "2",
                        "children": [{
                                "name": "First Child",
                                "value": "3",
                            }, {
                                "name": "Second Child",
                                "value": "4",
                                "children": [{
                                    "name": "GrandChild 1",
                                    "value": "5",
                                }, {
                                    "name": "GrandChild 2",
                                    "value": "6",
                                }, {
                                    "name":"GrandChild 3",
                                    "value": "7",
                                }]
                            }]
                    }])
            },

            value: {
                type: Array,
                required: false,
                default: () => ([])
            }
        },

        created() {
            this.finalValues = this.value;
        },

        data: () => ({
            finalValues: [] 
        }),


        methods: {
            generateChildren () {
                let childElements = [];

                let items = (typeof this.items == 'string') ? JSON.parse(this.items) : this.items;

                for(let key in items) {
                    childElements.push(this.generateTreeItem(items[key]));
                }

                return childElements;
            },
            
            generateTreeItem(item) {
                return this.$createElement('tree-item', {
                        props: {
                            items: item,
                            value: this.finalValues,
                            captionField: this.captionField,
                            childrenField: this.childrenField,
                            valueField: this.valueField,
                            idField: this.idField
                        },
                        on: {
                            input: selection => {
                                this.finalValues = selection;
                            }
                        },
                    })
            }
        },

        render (createElement) {
            return createElement('div', {
                    class: [
                        'tree-container',
                    ]
                }, [this.generateChildren()]
            )
        }
    }
</script>