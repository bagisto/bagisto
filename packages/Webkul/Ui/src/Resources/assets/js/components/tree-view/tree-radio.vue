<template>
    <span class="radio">
        <input type="radio" :id="id" :name="nameField" :value="modelValue" :checked="isActive">
        <label class="radio-view" :for="id"></label>
        <span class="" :for="id">{{ label }}</span>
    </span>
</template>

<script>
    export default {
        name: 'tree-radio',

        props: ['id', 'label', 'nameField', 'modelValue', 'value'],

        computed: {
            isActive () {
                if(this.value.length) {
                    return this.value[0] == this.modelValue ? true : false;
                }

                return false
            }
        }
    }
</script>


<script type="text/x-template" id="tree-radio">
   <span class="radio">
        <input 
            type="radio" 
            :id="id"
            :name="nameField"
            :value="modelValue"
            :checked="isActive"
        >

        <label
            class="radio-view"
            :for="id"
        >
        </label>

        <span 
            class=""
            :for="id"
            :text="label"
        >
        </span>
    </span>
</script>

<script type="module">
    app.component('tree-radio', {
        name: 'tree-radio',

        props: ['id', 'label', 'nameField', 'modelValue', 'value'],

        computed: {
            isActive () {
                if(this.value.length) {
                    return this.value[0] == this.modelValue ? true : false;
                }

                return false
            }
        }
    })
</script>