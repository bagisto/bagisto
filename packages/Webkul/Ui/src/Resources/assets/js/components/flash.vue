<template>
    <div class="alert" v-bind:class="flash.type">
        <span class="icon white-cross-sm-icon" @click="remove"></span>
        <p>{{ flash.message }}</p>  
    </div>
</template>

<script>
    export default {
        props: ['flash'],

        created: function() {
            var this_this = this;
            setTimeout(function() {
                this_this.$emit('onRemoveFlash', this_this.flash)
            }, 1000)
        },

        methods: {
            remove: function() {
                this.$emit('onRemoveFlash', this.flash)
            }
        }
    }
</script>