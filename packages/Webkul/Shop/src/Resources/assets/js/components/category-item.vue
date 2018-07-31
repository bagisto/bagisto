<template>
    <li class="root">
        <a href="">{{ item.name }}</a>
        <!-- replace the below inner-child class with dropdown that -->
        <ul class="inner-dropdown" v-if="haveChildren">
            <category-item
                class="inner-item"
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
<style>
    .inner-dropdown {
        position:absolute;
        background:white;
        border: 1px solid red;
        color:black;
        margin-top:35px;
        padding:5px;
        width:148px;
    }
</style>
