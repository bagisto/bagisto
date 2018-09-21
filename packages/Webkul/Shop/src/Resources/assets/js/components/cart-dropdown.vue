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
                <p class="heading">Cart Subtotal - $80</p>
                <i class="icon icon-menu-close" @click="dropOrHide"></i>
            </div>

            <div class="dropdown-content">
                <div class="item">
                    <div class="item-image">
                        <img />
                    </div>
                    <div class="item-details">
                        <div class="item-name">Some Item Name</div>
                        <div class="item-price">$ Some Price</div>
                        <div class="item-qty">Some Quantity</div>
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
		items: Array,
    },

    data(){
        return {
            toggle: true,
            totalitems: 0,
            cart_items: []
        };
    },

    computed: {
        makeDropdown() {

        }
    },

    mounted: function() {
        if(this.items != undefined)
            this.initializeDropdown();
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
            this.totalitems = this.items.length;
            this.cart_items = this.items;
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

    .dropdown-content {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .dropdown-content .item{
        display: flex;
        flex-direction: row;
        border-bottom: 1px solid #E8E8E8;
    }
    .dropdown-content .item img{
        height: 75px;
        width: 75px;
        margin-right: 8px;
    }

    .item-details .item-name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .item-details .item-price {
        margin-bottom: 10px;
    }

    .item-details .item-qty {
        margin-bottom: 10px;
    }

    .dropdown-footer {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-footer button {
        border-radius: 0px;
    }

</style>