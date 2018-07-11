<template>
    <div class="accordian" :class="[isActive ? 'active' : '', className]" :id="id">
        <div class="accordian-header" @click="toggleAccordion()">
            {{ title }}
            <i class="icon" :class="iconClass"></i>
        </div>
        <slot></slot>
    </div>
</template>
<script>
    export default {
        props: {
            title: String,
            id: String,
            className: String,
            active: Boolean
        },

        data: function() {
            return {
                isActive: false
            }
        },

        mounted: function() {
            this.isActive = this.active;
        },

        methods: {
            toggleAccordion: function() {
                this.isActive = !this.isActive;
            }
        },

        computed: {
            iconClass() {
                return {
                    'accordian-down-icon': !this.isActive,
                    'accordian-up-icon': this.isActive,
                };
            }
        }
    }
</script>