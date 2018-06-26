<template>
    <transition-group
        tag='div'
        name="flash-wrapper"
        class='alert-wrapper'
    >
        <flash
            v-for='(flash, index) in flashes'
            :key='flash.uid'
            :flash="flash"
            @onRemoveFlash="removeFlash($event)"
        ></flash>
    </transition-group>
</template>

<script>
    export default {
        data: function() {
            return {
                uid: 1,

                flashes: []
            }
        },

        methods: {
            addFlash: function(flash) {
                flash.uid = this.uid++;
                this.flashes.push(flash)
            },

            removeFlash: function(flash) {
                let index = this.flashes.indexOf(flash)

                this.flashes.splice(index, 1)
            }
        }
    }
</script>