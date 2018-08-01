<template>
    <li>
        <a href="">{{ item.name }}</a>
        <!-- replace the below inner-child class with dropdown -->
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
        url: String
    },

    methods:{
        change: function () {
            this.isActive = true;
        }
    },

    created() {
        // console.log(this.item)
    },

    computed: {
        haveChildren() {
            return this.item.children.length ? true : false;
        }
    }
}
</script>
