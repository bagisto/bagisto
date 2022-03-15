<template>
    <div :class="`dropdown ${cartItems.length > 0 ? '' : 'disable-active'}`">
        <div class="dropdown-toggle btn btn-link" id="mini-cart" :class="{'cursor-not-allowed': ! cartItems.length}">
            <div class="mini-cart-content">
                <i class="material-icons-outlined">shopping_cart</i>
                <div class="badge-container">
                    <span class="badge" v-text="cartItems.length" v-if="cartItems.length != 0"></span>
                </div>
                <span class="fs18 fw6 cart-text" v-text="cartText"></span>
            </div>

            <div class="down-arrow-container">
                <span class="rango-arrow-down"></span>
            </div>
        </div>

        <div
            id="cart-modal-content"
            class="modal-content dropdown-list sensitive-modal cart-modal-content"
            :class="{hide: ! cartItems.length}"
        >
            <div class="mini-cart-container">
                <div
                    class="row small-card-container"
                    :key="index"
                    v-for="(item, index) in cartItems"
                >
                    <div class="col-3 product-image-container mr15">
                        <span class="remove-item" @click="removeProduct(item.id)">
                            <span class="rango-close"></span>
                        </span>

                        <a
                            class="unset"
                            :href="`${$root.baseUrl}/${item.url_key}`"
                        >
                            <div
                                class="product-image"
                                :style="
                                    `background-image: url(${item.images.medium_image_url});`
                                "
                            ></div>
                        </a>
                    </div>
                    <div class="col-9 no-padding card-body align-vertical-top">
                        <div class="no-padding">
                            <div
                                class="fs16 text-nowrap fw6 product-name"
                                v-html="item.name"
                            ></div>

                            <div class="fs18 card-current-price fw6">
                                <div class="display-inbl">
                                    <label class="fw5">{{
                                        __('checkout.qty')
                                    }}</label>
                                    <input
                                        type="text"
                                        disabled
                                        :value="item.quantity"
                                        class="ml5"
                                    />
                                </div>
                                <span class="card-total-price fw6">
                                    {{
                                        isTaxInclusive == '1'
                                            ? item.base_total_with_tax
                                            : item.base_total
                                    }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <h5 class="col-6 text-left fw6">
                    {{ subtotalText }}
                </h5>

                <h5 class="col-6 text-right fw6 no-padding">
                    {{
                        isTaxInclusive == '1'
                            ? cartInformation.base_grand_total
                            : cartInformation.base_sub_total
                    }}
                </h5>
            </div>

            <div class="modal-footer">
                <a
                    class="col text-left fs16 link-color remove-decoration"
                    :href="viewCartRoute"
                    >{{ viewCartText }}</a
                >

                <div class="col text-right no-padding">
                    <a :href="checkoutRoute" @click="checkMinimumOrder($event)">
                        <button type="button" class="theme-btn fs16 fw6">
                            {{ checkoutText }}
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.hide {
    display: none !important;
}
</style>

<script>
export default {
    props: [
        'isTaxInclusive',
        'viewCartRoute',
        'checkoutRoute',
        'checkMinimumOrderRoute',
        'cartText',
        'viewCartText',
        'checkoutText',
        'subtotalText'
    ],

    data: function() {
        return {
            cartItems: [],
            cartInformation: []
        };
    },

    mounted: function() {
        this.getMiniCartDetails();
    },

    watch: {
        '$root.miniCartKey': function() {
            this.getMiniCartDetails();
        }
    },

    methods: {
        getMiniCartDetails: function() {
            this.$http
                .get(`${this.$root.baseUrl}/mini-cart`)
                .then(response => {
                    if (response.data.status) {
                        this.cartItems = response.data.mini_cart.cart_items;
                        this.cartInformation =
                            response.data.mini_cart.cart_details;
                    }
                })
                .catch(exception => {
                    console.log(this.__('error.something_went_wrong'));
                });
        },

        removeProduct: function(productId) {
            this.$http
                .delete(`${this.$root.baseUrl}/cart/remove/${productId}`)
                .then(response => {
                    this.cartItems = this.cartItems.filter(
                        item => item.id != productId
                    );
                    this.$root.miniCartKey++;

                    window.showAlert(
                        `alert-${response.data.status}`,
                        response.data.label,
                        response.data.message
                    );
                })
                .catch(exception => {
                    console.log(this.__('error.something_went_wrong'));
                });
        },

        checkMinimumOrder: function(e) {
            e.preventDefault();

            this.$http.post(this.checkMinimumOrderRoute).then(({ data }) => {
                if (!data.status) {
                    window.showAlert(`alert-warning`, 'Warning', data.message);
                } else {
                    window.location.href = this.checkoutRoute;
                }
            });
        }
    }
};
</script>
