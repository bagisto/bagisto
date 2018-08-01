<template>
    <li>
        <a href="">{{ this.item['translations'][0].name }}&emsp;<i class="icon dropdown-right-icon"
        v-if="haveChildren"></i></a>
        <ul v-if="haveChildren">
            <category-item
                v-for="(child, index) in item.children"
                :key="index"
                :item="child">
            </category-item>
        </ul>
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
            return this.item.children.length ? true : false;
        }
    }
}
</script>
