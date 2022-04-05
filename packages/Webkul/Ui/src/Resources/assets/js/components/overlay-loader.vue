<template>
    <div class="overlay-loader" v-if="isLoaderOpen">
        <div id="loader" class="cp-spinner cp-round"></div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'isOpen'],

        computed: {
            isLoaderOpen () {
                this.addClassToBody();

                return this.isOpen;
            }
        },

        methods: {
            addClassToBody () {
                var body = document.querySelector("body");

                if (this.isOpen) {
                    body.classList.add("loader-open");
                } else {
                    body.classList.remove("loader-open");
                }
            }
        }
    }
</script>