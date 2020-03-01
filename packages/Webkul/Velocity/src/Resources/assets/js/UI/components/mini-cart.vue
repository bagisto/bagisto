<template>
    <div
        id="cart-modal-content"
        class="modal-content sensitive-modal cart-modal-content hide">

        <!--Body-->
        <div class="mini-cart-container">
            <div class="row small-card-container" :key="index" v-for="(item, index) in cartItems">
                <div class="col-3 product-image-container mr15">
                    <a :href="removeUrl">
                        <span class="rango-close"></span>
                    </a>

                    <a
                        class="unset"
                        :href="`${$root.baseUrl}/${item.url_key}`">

                        <div
                            class="product-image"
                            :style="`background-image: url(${item.images.medium_image_url});`">
                        </div>
                    </a>
                </div>
                <div class="col-9 no-padding card-body align-vertical-top">
                    <div class="no-padding">
                        <div class="fs16 text-nowrap fw6" v-html="item.name"></div>

                        <div class="fs18 card-current-price fw6">
                            <div class="display-inbl">
                                <label class="fw5">{{ __('checkout.qty') }}</label>
                                <input type="text" disabled :value="item.quantity" class="ml5" />
                            </div>
                            <span class="card-total-price fw6">
                                {{ item.base_total }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--Footer-->
        <div class="modal-footer">
            <h2 class="col-6 text-left fw6">
                {{ subtotalText }}
            </h2>

            <h2 class="col-6 text-right fw6 no-padding">{{ cartInformation.base_sub_total }}</h2>
        </div>

        <div class="modal-footer">
            <a class="col text-left fs16 link-color remove-decoration" :href="checkoutUrl">{{ cartText }}</a>

            <div class="col text-right no-padding">
                <a :href="viewCart">
                    <button
                        type="button"
                        class="theme-btn fs16 fw6">
                        {{ checkoutText }}
                    </button>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            'items',
            'cartText',
            'viewCart',
            'removeUrl',
            'checkoutUrl',
            'cartDetails',
            'checkoutText',
            'subtotalText',
        ],
        data: function () {
            return {
                cartItems: JSON.parse(this.items),
                cartInformation: JSON.parse(this.cartDetails),
            }
        }
    }
</script>
