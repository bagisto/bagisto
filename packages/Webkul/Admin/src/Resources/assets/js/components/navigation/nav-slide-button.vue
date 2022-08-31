<template>   
     <div class="menubar-bottom" @click="toggle">
        <i class="icon" :class="iconClass"></i>
    </div>
</template>

<script>
export default {  

    data(){
        return {
            iconClass : 'accordian-right-icon',
            leftIconClass : 'accordian-left-icon',
            rightIconClass : 'accordian-right-icon'            
        }
    },

    methods: {
        toggle: function () {
            this.$root.toggleMenu();

            if (
                this.$root.isMenuOpen 
                && this.iconClass == 'accordian-right-icon'
            ) {
                this.iconClass = this.leftIconClass;
            } else {
                this.iconClass = this.rightIconClass;
            }
        },
    }
}
</script>