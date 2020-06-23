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

	mounted() {
		var this_this = this;

		var element = this.$el.getElementsByTagName("input")[0];
		this.datepicker = new Flatpickr(element, {
			enableTime: true,
			noCalendar: true,
			dateFormat: "H:i",
			time_24hr: true,
			onChange: function(selectedDates, dateStr, instance) {
				this_this.$emit('onChange', dateStr)
			},
		});
  	}
};
</script>