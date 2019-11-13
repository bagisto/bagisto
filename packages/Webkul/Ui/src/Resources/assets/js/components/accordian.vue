<template>
    <div class="accordian" :class="[isActive ? 'active' : '', className]" :id="id">
        <div class="accordian-header" @click="toggleAccordion()">
            <slot name="header">
                {{ title }}
                <i class="icon" :class="iconClass"></i>
            </slot>
        </div>

        <div class="accordian-content">
            <slot name="body">
            </slot>
        </div>
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

        inject: ['$validator'],

        data: function() {
            return {
                isActive: false,
                imageData: '',
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
                    'accordian-down-icon': ! this.isActive,
                    'accordian-up-icon': this.isActive,
                };
            }
        }
    }
</script>