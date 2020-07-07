<template>
	<span style="display: flex;align-items: center;">
		<slot>
			<input type="text" :name="name" class="control" :value="value" data-input>
		</slot>
        <button
            class="btn"
            style="height:100%;margin-left: 8px; margin-top: 5px;"
            @click.prevent="clear"
        > <span class="icon trash-icon"></span> </button>
	</span>
</template>

<script>
import Flatpickr from 'flatpickr';

export default {
		props: {
			name: String,
			value: String,
		},

		data () {
			return {
				datepicker: null
			}
		},

		mounted () {
			var this_this = this;

			var element = this.$el.getElementsByTagName('input')[0];
			this.datepicker = new Flatpickr(
				element, {
                    allowInput: true,
					altFormat: 'Y-m-d',
					dateFormat: 'Y-m-d',
                    weekNumbers: true,
					onChange: function(selectedDates, dateStr, instance) {
						this_this.$emit('onChange', dateStr)
					},
				});
		},
        methods: {
            clear() {
                this.datepicker.clear();
            }
        }
	};
</script>