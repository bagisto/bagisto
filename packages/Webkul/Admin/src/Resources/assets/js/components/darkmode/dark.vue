<template>   
    <div>
        <div class="control-group">                            
            <label class="switch">
                <input id="dark" class="control" type="checkbox" :checked="isDarkMode" @click="toggle">
                <span class="slider round switch-dark"></span>
            </label>
        </div>
    </div>  
</template>

<script>
export default {  

    data(){
        return{
            lightMode: 'Light Mode',
            darkMode: 'Dark Mode',
            buttonText: 'Dark Mode',
            isDarkMode: this.$root.isDarkMode,
        }
    },

    methods: {

        toggle: function () {  

            this.$root.checkMode();

            this.toggleBodyClass();
        },

        toggleBodyClass: function(){
            
            let element = document.body;

            element.classList.toggle("dark-mode"); 

            if(this.buttonText == this.darkMode){
                this.buttonText = this.lightMode;
            }else{
                this.buttonText = this.darkMode;
            }
        }
    },
}
</script>