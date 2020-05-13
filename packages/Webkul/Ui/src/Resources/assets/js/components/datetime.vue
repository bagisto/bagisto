<template>
	<span>
		<slot>
			<input type="text" :name="name" class="control" :value="value" data-input>
		</slot>
	</span>
</template>

<script>
    import Flatpickr from "flatpickr";

    export default {
        props: {
            name: String,
            value: String
        },

        data() {
            return {
                datepicker: null
            };
        },

        created() {
            function splitDateTime(value) {
                let dateTimeParts = value.split(' ')
                let dateParts = dateTimeParts[0].split('-')
                let timeParts = dateTimeParts[1].split(':')

                return {
                    "year": dateParts[0],
                    "month": dateParts[1],
                    "day": dateParts[2],
                    "hour": timeParts[0],
                    "minute": timeParts[1],
                }
            }

            function validateDate(value) {
                let valueDate = new Date(value)
                if (valueDate.getTime() !== valueDate.getTime()) {
                    return false
                }

                let inputValue = splitDateTime(value)

                if (inputValue.year.length !== 4
                    || inputValue.month.length !== 2
                    || inputValue.day.length !== 2
                    || inputValue.hour.length !== 2
                    || inputValue.minute.length !== 2
                ) {
                    return false
                }

                if (inputValue.month.parseInt < 1 || inputValue.month.parseInt > 12) {
                    return false
                }

                let daysPerMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
                if ((!(inputValue.year.parseInt % 4) && inputValue.year.parseInt % 100) || !(inputValue.year.parseInt % 400)) {
                    daysPerMonth[1] = 29 // it is a leap year
                }

                if (inputValue.day > daysPerMonth[inputValue.month - 1]) {
                    return false
                }

                if (inputValue.hour.parseInt < 0 || inputValue.hour.parseInt > 23) {
                    return false
                }

                if (inputValue.minute.parseInt < 0 || inputValue.hour.parseInt > 59) {
                    return false
                }
                return true
            }

            this.$validator.extend('date_format_rule', {
                getMessage(field, val) {
                    return 'The date must be in the format yyyy-MM-dd HH:ii:ss.'
                },
                validate(value, field) {
                    return validateDate(value)
                }
            })
        },

        mounted() {
            var this_this = this;

            var element = this.$el.getElementsByTagName("input")[0];
            this.datepicker = new Flatpickr(element, {
                allowInput: true,
                altFormat: "Y-m-d H:i:S",
                dateFormat: "Y-m-d H:i:S",
                enableTime: true,
                enableSeconds: true,
                onChange: function (selectedDates, dateStr, instance) {
                    this_this.$emit('onChange', dateStr)
                },
            });
        },

        methods: {}
    };
</script>