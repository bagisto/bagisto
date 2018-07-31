<template>
    <ul class="menu-bar">
        <category-item
            v-for="(item, index) in items"
            :key="index"
            :url="url"
            :item="item">
        </category-item>
        <li>
            <img class="icon" src="vendor/webkul/shop/assets/images/offer-zone.svg" style="margin-right:5px;" />Offer Zone
        </li>
    </ul>
</template>

<script>
// define the item component
export default {
	props: {
		categories: {
            type: [Array, String, Object],
            required: false,
            default: () => ([])
        },
        url: String
    },

    created () {
        console.log(this.url)
    },

    computed: {
        items () {
            return JSON.parse(this.categories)
        }
    }
}
</script>
<style>
    .menu-bar {
        text-transform: capitalize;
        color: #242424;
    }

    .menu-bar li {
        min-width:50px;
        height:48px;
    }

    .menu-bar li a{
        display:block;
    }

    .menu-bar li inner-dropdown.ul li{
        display:block;
        height:48px;
    }

    .menu-bar li inner-dropdown.ul li a{
        display:block;
        height:48px;
    }

    .inner-dropdown {
        display:none;
    }

    .root:hover > .inner-dropdown{
        display:block;
    }
    .root:hover > .inner-dropdown > .inner-dropdown{
        display:block;
        margin-left:148px;
    }
</style>
