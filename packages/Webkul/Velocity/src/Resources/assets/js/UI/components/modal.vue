<template>
    <div class="modal-parent scrollable" v-if="isModalOpen">
        <div class="modal-container">
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
    </div>
</template>

<script>
    export default {
        props: ['id', 'isOpen'],

        data: function () {
            return {}
        },

        computed: {
            isModalOpen: function () {
                this.addClassToBody();

                return this.isOpen;
            }
        },

        methods: {
            closeModal: function () {
                this.$root.$set(this.$root.modalIds, this.id, false);
            },

            addClassToBody: function () {
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