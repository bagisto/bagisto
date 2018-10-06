<template>
    <li>

    </li>
</template>
<script>
// define the item component
export default {
	props: {
		item:  Object,
        url: String,
    },
    data(){
        return {
            items_count:0
        };
    },
    computed: {
        haveChildren() {
            console.log(this.item.name);
            return this.item.children.length ? true : false;
        }
    }
}
</script>