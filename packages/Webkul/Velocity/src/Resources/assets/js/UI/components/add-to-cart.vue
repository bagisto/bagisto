<template>
    <form method="POST" @submit.prevent="addToCart">

        <!-- for move to cart from wishlist -->
        <a
            :href="wishlistMoveRoute"
            :disabled="isButtonEnable == 'false' || isButtonEnable == false"
            :class="`btn btn-add-to-cart ${addClassToBtn}`"
            v-if="moveToCart"
            >

            <i class="material-icons text-down-3" v-if="showCartIcon">shopping_cart</i>

            <span class="fs14 fw6 text-uppercase text-up-4" v-text="btnText"></span>
        </a>

        <!-- for add to cart -->
        <button
            type="submit"
            :disabled="isButtonEnable == 'false' || isButtonEnable == false"
            :class="`btn btn-add-to-cart ${addClassToBtn}`"
            v-else
            >

            <i class="material-icons text-down-3" v-if="showCartIcon">shopping_cart</i>

            <span class="fs14 fw6 text-uppercase text-up-4" v-text="btnText"></span>
        </button>

    </form>
</template>

<script>
    export default {
        props: [
            'form',
            'btnText',
            'isEnable',
            'csrfToken',
            'productId',
            'reloadPage',
            'moveToCart',
            'wishlistMoveRoute',
            'showCartIcon',
            'addClassToBtn',
            'productFlatId',
        ],

        data: function () {
            return {
                'isButtonEnable': this.isEnable,
                'qtyText': this.__('checkout.qty'),
            }
        },

        methods: {
            'addToCart': function () {
                this.isButtonEnable = false;
                let url = `${this.$root.baseUrl}/cart/add`;

                this.$http.post(url, {
                    'quantity': 1,
                    'product_id': this.productId,
                    '_token': this.csrfToken.split("&#039;").join(""),
                })
                .then(response => {
                    this.isButtonEnable = true;

                    if (response.data.status == 'success') {
                        this.$root.miniCartKey++;

                        window.showAlert(`alert-success`, this.__('shop.general.alert.success'), response.data.message);

                        if (this.reloadPage == "1") {
                            window.location.reload();
                        }
                    } else {

                        if (response.data.redirectionRoute) {
                            window.location.href = response.data.redirectionRoute;
                        }
                    }
                })
                .catch(error => {
                    console.log(this.__('error.something_went_wrong'));
                })
            },
        }
    }
</script>