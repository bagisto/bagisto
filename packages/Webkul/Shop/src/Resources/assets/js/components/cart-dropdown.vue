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
<style>
    .show {
        display: none;
    }

    .dropdown-cart {
        position: absolute;
        background: #FFFFFF;
        border: 1px solid #E8E8E8;
        box-shadow: 1px 3px 6px 0 rgba(0,0,0,0.40);
        color: #242424;
        padding: 20px;
        border-radius: 1px;
        right: 10%;
        top: 75px;
        width: 387px;
        z-index: 5;
    }
    .dropdown-cart > .dropdown-header {
        width: 100%;
    }

    .dropdown-cart > .dropdown-header p{
        display: inline;
        line-height: 25px;
    }

    .dropdown-cart > .dropdown-header i{
        cursor: pointer;
        float: right;
        height: 22px;
        width: 22px;
    }

    .dropdown-cart > .dropdown-header p.heading {
        font-weight: lighter;
    }

    .dropdown-content {
        padding-top: 10px;
        padding-bottom: 10px;
        margin-top: 7px;
    }

    .dropdown-content .item{
        display: flex;
        flex-direction: row;
        border-bottom: 1px solid #E8E8E8;
        padding-top: 9px;
        padding-bottom: 9px;
    }

    .dropdown-content .item img{
        height: 75px;
        width: 75px;
        margin-right: 8px;
    }

    .dropdown-content .item-details{
        height: 75px;
    }

    .item-details .item-name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 9px;
    }

    .item-details .item-price {
        margin-bottom: 9px;
    }

    .item-details .item-qty {
        font-weight: lighter;
        margin-bottom: 9px;
    }

    .dropdown-footer {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }

    .dropdown-footer button {
        border-radius: 0px;
    }

</style>