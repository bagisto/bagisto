<template>
    <span>
        <slot>
            <input
                class="control"
                type="text"
                :name="name"
                :value="value"
                data-input
            />
        </slot>

        <span
            class="icon cross-icon"
            v-if="!hideRemoveButton"
            @click.prevent="clear"
        >
        </span>
    </span>
</template>

<script>
import Flatpickr from "flatpickr";

export default {
    props: {
        name: String,

        value: String,

        disable: Array,

        minDate: String,

        maxDate: String,

        hideRemoveButton: [Number, String]
    },

    data: function() {
        return {
            datepicker: null
        };
    },

    mounted: function() {
        let options = this.setOptions();

        this.activate(options);
    },

    methods: {
        setOptions: function() {
            let self = this;

            return {
                allowInput: true,
                disable: this.disable ?? [],
                minDate: this.minDate ?? '',
                maxDate: this.maxDate ?? '',
                altFormat: "Y-m-d H:i:S",
                dateFormat: "Y-m-d H:i:S",
                enableTime: true,
                time_24hr: true,
                weekNumbers: true,
                onChange: function(selectedDates, dateStr, instance) {
                    self.$emit("onChange", dateStr);
                }
            };
        },

        activate: function(options) {
            let element = this.$el.getElementsByTagName("input")[0];
            this.datepicker = new Flatpickr(element, options);
        },

        clear: function() {
            this.datepicker.clear();
        }
    }
};
</script>
