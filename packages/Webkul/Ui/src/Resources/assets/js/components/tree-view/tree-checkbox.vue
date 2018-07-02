<template>
    <span class="checkbox">
        <input type="checkbox" name="permissions[]" :id="inputValue" :value="inputValue.value" @change="inputChanged($event)" :checked="isActive">
        <label class="checkbox-view" :for="inputValue"></label>
        {{ inputValue }} ======== {{ value }}
    </span>
</template>

<script>
    export default {
        name: 'tree-checkbox',

        props: ['label', 'inputValue', 'value'],

        methods: {
            inputChanged (e) {
                this.$emit('change', this.inputValue)
            },

            valueComparator (a, b) {
                if (a === b) 
                    return true

                if (a !== Object(a) || b !== Object(b)) {
                    return false
                }

                const props = Object.keys(a)

                if (props.length !== Object.keys(b).length) {
                    return false
                }

                return props.every(p => this.valueComparator(a[p], b[p]))
            }
        },

        computed: {
            isMultiple () {
                return Array.isArray(this.inputValue)
            },

            isActive () {
                const value = this.value
                const input = this.inputValue

                if (this.isMultiple) {
                    if (!Array.isArray(input)) 
                        return false

                    return input.some(item => this.valueComparator(item, value))
                }

                var isChecked = value ? this.valueComparator(value, input) : Boolean(input)

                return isChecked;
            },
        }
    }
</script>