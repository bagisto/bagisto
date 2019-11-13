<template>
	<span>
		<swatches v-model="colorModel" v-bind="$attrs"></swatches>
		<input type="hidden" :name="inputName" :value="colorModel"/>
	</span>
</template>

<script>
	import Swatches from 'vue-swatches';

	export default {
		components: { Swatches },

        props: ['inputName', 'color'],

		data () {
			return {
				colorModel: this.color
			}
		}
	};
</script>