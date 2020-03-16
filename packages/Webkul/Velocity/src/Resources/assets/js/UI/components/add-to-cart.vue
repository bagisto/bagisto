<template>
    <form method="POST" @submit.prevent="addToCart">
        <button
            type="submit"
            :disabled="isButtonEnable == 'false' || isButtonEnable == false"
            :class="`btn btn-add-to-cart ${addClassToBtn}`">

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
            'showCartIcon',
            'addClassToBtn',
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
                    '_token': this.csrfToken,
                    'product_id': this.productId,
                })
                .then(response => {
                    this.isButtonEnable = true;

                    if (response.data.status == 'success') {
                        this.$root.miniCartKey++;

                        window.showAlert(`alert-success`, this.__('shop.general.alert.success'), response.data.message);
                    } else {
                        window.showAlert(`alert-${response.data.status}`, response.data.label ? response.data.label : this.__('shop.general.alert.error'), response.data.message);

                        if (response.data.redirectionRoute) {
                            window.location.href = response.data.redirectionRoute;
                        }
                    }
                })
                .catch(error => {
                    console.log(this.__('error.something_went_wrong'));
                })
            }
        }
    }
</script>