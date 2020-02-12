<template>
    <form method="POST" @submit.prevent="addToCart">
        <button
            type="submit"
            :disabled="isButtonEnable == 'false'"
            :class="`btn btn-add-to-cart ${addClassToBtn}`">

            <i class="material-icons text-down-3" v-if="showCartIcon">shopping_cart</i>

            <span class="fs14 fw6 text-uppercase text-up-4" v-text="btnText"></span>
        </button>
    </form>
</template>

<script type="text/javascript">
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
                'qtyText': this.__('checkout.qty'),
                'isButtonEnable': this.isEnable,
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

                    if (response.data.status) {
                        response.data.addedItems.forEach(item => {
                            let cartItemHTML = `<div class="row small-card-container">
                                <div class="col-3 product-image-container mr15">
                                    <a href="${this.$root.baseUrl}/checkout/cart/remove/${item.itemId}">
                                        <span class="rango-close"></span>
                                    </a>

                                    <a
                                        href="${this.$root.baseUrl}/${item.url_key}"
                                        class="unset">

                                        <div class="product-image"
                                            style="background-image: url('${item.images['small_image_url']}');">
                                        </div>
                                    </a>
                                </div>
                                <div class="col-9 no-padding card-body align-vertical-top">
                                    <div class="no-padding">
                                        <div class="fs16 text-nowrap fw6">${item.name}</div>
                                        <div class="fs18 card-current-price fw6">
                                            <div class="display-inbl">
                                                <label class="fw5">${this.qtyText}</label>

                                                <input type="text" disabled value="${item.quantity}" class="ml5" />
                                            </div>
                                            <span class="card-total-price fw6">${item.baseTotal}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                            $('#cart-modal-content .mini-cart-container').append(cartItemHTML);
                            $('.mini-cart-container .badge').text(response.data.totalCartItems);
                        });
                    }
                })
                .catch(error => {
                    console.log("something went wrong");
                })
            }
        }
    }
</script>