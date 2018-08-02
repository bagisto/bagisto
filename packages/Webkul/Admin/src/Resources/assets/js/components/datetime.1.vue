<template>
	<div class="control-group" :class="[errors.has(name) ? 'has-error' : '']">
		<label :for="name" :class="[is_required ? 'required' : '']">
			{{ label }}
		</label>

		<flat-pickr v-model="finalvalue" class="control" v-validate="'required'" :config="config" :name="name" @on-open="open()"></flat-pickr>

		<span class="control-error" v-if="errors.has(name)">{{ errors.first(name) }}</span>
	</div>
</template>

<script>
	import flatPickr from 'vue-flatpickr-component';

	// Vue.use(flatPickr);

	export default {
		props: {
			label: String,
			name: String,
			required: String,
			value: String,
		},

		computed: {
			is_required () {
				return Number(this.required)
			}
		},

		methods: {
			open () {
				console.log(this.$validator)
			}
		},

		data () {
			return {
				finalvalue: this.value,

				date: new Date(),

				config: {
					allowInput: true,
					altFormat: 'Y-m-d H:i:s',
					dateFormat: 'Y-m-d H:i:s',
					enableTime: true
				},                
			}
		}
	};
</script>