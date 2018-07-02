<template>
    <span class="checkbox">
        <input type="checkbox" name="permissions[]" :id="inputValue" v-model="inputValue" @change="inputChanged($event)" :checked="isChecked">
        <label class="checkbox-view" :for="inputValue"></label>
        {{ label }}
    </span>
</template>

<script>
    export default {
        name: 'tree-checkbox',

        props: ['label', 'inputValue', 'isChecked'],

        methods: {
            inputChanged (e) {
                this.$emit('change', this.inputValue)
            }
        }
    }
</script>