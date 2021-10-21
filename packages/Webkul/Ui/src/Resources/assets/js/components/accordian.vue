<template>
    <div
        :class="[
            'accordian',
            isActive ? 'active' : '',
            className,
            !isActive && hasError ? 'error' : ''
        ]"
        :id="id"
    >
        <div class="accordian-header" @click="toggleAccordian()">
            <slot name="header">
                {{ title }}
                <i :class="['icon', iconClass]"></i>
            </slot>
        </div>

        <div class="accordian-content" ref="controls">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: String,
        id: String,
        className: String,
        active: Boolean,
        downIconClass: {
            type: String,
            default: "accordian-down-icon"
        },
        upIconClass: {
            type: String,
            default: "accordian-up-icon"
        }
    },

    inject: ["$validator"],

    data: function() {
        return {
            isActive: false,

            imageData: "",

            hasError: false
        };
    },

    mounted: function() {
        this.addHasErrorClass();

        eventBus.$on("onFormError", this.addHasErrorClass);

        this.isActive = this.active;
    },

    methods: {
        toggleAccordian: function() {
            this.isActive = !this.isActive;
        },

        addHasErrorClass: function() {
            let self = this;

            setTimeout(function() {
                $(self.$el)
                    .find(".control-group")
                    .each(function(index, element) {
                        if ($(element).hasClass("has-error")) {
                            self.hasError = true;
                        }
                    });
            }, 0);
        }
    },

    computed: {
        iconClass: function() {
            return {
                [this.downIconClass]: !this.isActive,
                [this.upIconClass]: this.isActive
            };
        }
    }
};
</script>
