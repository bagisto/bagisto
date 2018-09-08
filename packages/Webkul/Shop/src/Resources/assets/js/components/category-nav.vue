<template>
    <ul class="nav">

        <category-item
            v-for="(item, index) in items"
            :key="index"
            :url="url"
            :item="item"
            :parent="index">
        </category-item>
        <li>
            <img src="http://localhost/bagisto/public/vendor/webkul/shop/assets/images/offer-zone.svg"/>
            <span>Offer Zone</span>
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

    data(){
        return {
            items_count:0
        };
    },

    computed: {
        items () {
            return JSON.parse(this.categories)
        }
    },
}
</script>
