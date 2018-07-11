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
                default: () => ([])
            },

            value: {
                type: [Array, String, Object],
                required: false,
                default: () => ([])
            }
        },

        data: () => ({
            finalValues: [] 
        }),
        
        computed: {
            savedValues () {
                if(!this.value)
                    return [];

                return (typeof this.value == 'string') ? JSON.parse(this.value) : this.value;
            }
        },


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
                            savedValues: this.savedValues,
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