@props([
    'inputType' => 'checkbox'
])

<v-tree-view 
    {{ $attributes->except('input-type') }}
    input-type="{{ $inputType }}"
>
    <x-admin::shimmer.tree/>
</v-tree-view>

@pushOnce('scripts')
    {{-- v-tree-view component --}}
    <script type="module">
        app.component('v-tree-view',{
            name: 'v-tree-view',

            inheritAttrs: false,

            props: {
                inputType: {
                    type: String,
                    required: false,
                    default: 'checkbox'
                },

                nameField: {
                    type: String,
                    required: false,
                    default: 'permissions'
                },

                idField: {
                    type: String,
                    required: false,
                    default: 'id'
                },

                valueField: {
                    type: String,
                    required: false,
                    default: 'value'
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

                items: {
                    type: [Array, String, Object],
                    required: false,
                    default: () => ([])
                },

                behavior: {
                    type: String,
                    required: false,
                    default: 'reactive'
                },

                value: {
                    type: [Array, String, Object],
                    required: false,
                    default: () => ([])
                },

                fallbackLocale: {
                    type: String,
                    required: false
                },
            },

            data() {
                return {
                    finalValues: []
                }
            },

            computed: {
                savedValues () {
                    if(! this.value)
                        return [];

                    if(this.inputType == 'radio')
                        return [this.value];

                    return (typeof this.value == 'string') ? JSON.parse(this.value) : this.value;
                }
            },


            methods: {
                generateChildren () {
                    let childElements = [];

                    let items = (typeof this.items == 'string') ? JSON.parse(this.items) : this.items;

                    for (let key in items) {
                        childElements.push(this.generateTreeItem(items[key]));
                    }

                    return childElements;
                },

                generateTreeItem(item) {
                    return this.$h(this.$resolveComponent('v-tree-item'), {
                            items: item,
                            value: this.finalValues,
                            savedValues: this.savedValues,
                            nameField: this.nameField,
                            inputType: this.inputType,
                            captionField: this.captionField,
                            childrenField: this.childrenField,
                            valueField: this.valueField,
                            idField: this.idField,
                            behavior: this.behavior,
                            fallbackLocale: this.fallbackLocale,
                            onInputChange: (selection) => {
                                this.finalValues = selection;
                            },
                        })
                }
            },

            render () {
                return this.$h('div', {
                        class: [
                            'v-tree-container group',
                        ]
                    }, [this.generateChildren()]
                )
            }
        });
    </script>
@endPushOnce

{{-- Tree Item Component --}}
<x-admin::tree.item></x-admin::tree.item>

@if ($inputType == 'checkbox')
    {{-- Tree Checkbox Component --}}
    <x-admin::tree.checkbox></x-admin::tree.checkbox>
@else 
    {{-- Tree Radio component --}}
    <x-admin::tree.radio></x-admin::tree.radio>
@endif
