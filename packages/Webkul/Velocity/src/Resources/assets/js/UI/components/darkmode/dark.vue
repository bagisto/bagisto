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

    data() {
        return {
            lightMode: 'Light Mode',
            darkMode: 'Dark Mode',
            buttonText: 'Dark Mode',
            isDarkMode: this.$root.isDarkMode,
        }
    },

    methods: {
        toggle: function () {  
            this.$root.checkThemeMode();
            this.toggleBodyClass();
        },

        toggleBodyClass: function() {
            let element = document.body;
            element.classList.toggle("dark-mode-velocity");
            
            if (this.$root.isDarkMode) {
                document.getElementById('logo_light_mode').style.display = "none";
                document.getElementById('logo_dark_mode').style.display = "block";
            } else {
                document.getElementById('logo_light_mode').style.display = "block";
                document.getElementById('logo_dark_mode').style.display = "none";
            }

            if (this.buttonText == this.darkMode) {
                this.buttonText = this.lightMode;
            } else {
                this.buttonText = this.darkMode;
            }
        }
    },
}
</script>