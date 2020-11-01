<template>
    <span>
        <slot>
            <input
                type="text"
                :name="name"
                class="control"
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

        minDate: String,

        maxDate: String,

        hideRemoveButton: [Number, String],
    },

    data() {
        return {
            datepicker: null,
        };
    },

    mounted() {
        var this_this = this;
        var options = {
            allowInput: true,
            altFormat: "Y-m-d",
            dateFormat: "Y-m-d",
            weekNumbers: true,
            onChange: function(selectedDates, dateStr, instance) {
                this_this.$emit("onChange", dateStr);
            },
        };

        if (this.minDate) {
            options.minDate = this.minDate;
        }

        if (this.maxDate) {
            options.maxDate = this.maxDate;
        }

        var element = this.$el.getElementsByTagName("input")[0];
        this.datepicker = new Flatpickr(element, options);
    },
    methods: {
        clear() {
            this.datepicker.clear();
        },
    },
};
</script>
