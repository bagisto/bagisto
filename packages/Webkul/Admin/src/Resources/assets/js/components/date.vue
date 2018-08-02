<template>
    <Flatpickr class="control" :options="fpOptions"/>
</template>

<script>
	import VueFlatpickr from "vue-flatpickr";

	Vue.use(VueFlatpickr);

	export default {
		props: {
			default: String
		},

		data() {
			const now = new Date(this.default);
			
			return {
				fpOptions: {
					utc: false,
					enableTime: false
				}
			};
		}
	};
</script>
