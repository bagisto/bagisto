<template>
    <div class="modal-container" v-if="isModalOpen">
        <div class="modal-header">
            <slot name="header">
                Default header
            </slot>
            <i class="icon remove-icon" @click="closeModal"></i>
        </div>

        <div class="modal-body">
            <slot name="body">
                Default body
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'isOpen'],

        data: function () {
            return {}
        },

        created () {
            this.closeModal();
        },

        computed: {
            isModalOpen () {
                this.addClassToBody();

                return this.isOpen;
            }
        },

        methods: {
            closeModal () {
                this.$root.$set(this.$root.modalIds, this.id, true);
            },

            addClassToBody () {
                var body = document.querySelector("body");
                if(this.isOpen) {
                    body.classList.add("modal-open");
                } else {
                    body.classList.remove("modal-open");
                }
            }
        }
    }
</script>