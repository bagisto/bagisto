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

	},

	mounted() {
		var this_this = this;

		var element = this.$el.getElementsByTagName("input")[0];
		this.datepicker = new Flatpickr(element, {
			allowInput: true,
			altFormat: "Y-m-d H:i:S",
			dateFormat: "Y-m-d H:i:S",
			enableTime: true,
			onChange: function(selectedDates, dateStr, instance) {
				this_this.$emit('onChange', dateStr)
			},
		});
  	}
};
</script>