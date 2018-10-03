<template>
    <div>
        <ul class="cart-dropdown" @click="dropOrHide">
            <li class="cart-summary">
                <span class="icon cart-icon"></span>

                <span class="cart"><span class="cart-count" v-if="totalitems > 0">{{ totalitems }}</span>Products</span>

                <span class="icon arrow-down-icon"></span>
            </li>
        </ul>
        <div class="dropdown-cart" :class="{ show: toggle }">
            <div class="dropdown-header">
                <p class="heading">Cart Subtotal - $ {{ subtotal }}</p>
                <i class="icon icon-menu-close" @click="dropOrHide"></i>
            </div>

            <div class="dropdown-content">
                <div class="item" v-for="(item, index) in items" :key="index">
                    <div class="item-image" v-if="item[2]!='null'">
                        <img :src="item[2]"/>
                    </div>
                    <div class="item-image" v-else>
                        <img :src="placeholder"/>
                    </div>
                    <div class="item-details">
                        <div class="item-name">{{item[0]}}</div>
                        <div class="item-price">$ {{ item[1] }}</div>
                        <div class="item-qty">Quantity - {{ item[3] }}</div>
                    </div>
                </div>
            </div>

            <div class="dropdown-footer">
                <a href="/">View Shopping Cart</a>
                <button class="btn btn-primary btn-lg">CHECKOUT</button>
            </div>
        </div>
    </div>
</template>
<script>

// define the item component

export default {
	props: {
		items: Object,
    },

    data(){
        return {
            toggle: true,
            totalitems: parseInt(0),
            cart_items: [],
            placeholder: "http://localhost/bagisto/public/themes/default/assets/images/product/small-product-placeholder.png",
            subtotal : parseInt(0)
        };
    },

    computed: {
        makeDropdown() {

        }
    },

    mounted: function() {
        if(this.items != undefined) {
            this.initializeDropdown();
        }
    },

    methods: {
        dropOrHide: function() {
            if(this.toggle == false) {
                this.toggle = true;
            } else {
                this.toggle = false;
            }
        },

        initializeDropdown: function() {
            this.cart_items = this.items;

            var item;
            for(item in this.cart_items) {
                this.subtotal = this.subtotal + parseInt(this.cart_items[item][1]);
                this.totalitems = this.totalitems + 1;
            }
        }
    }
}
</script>