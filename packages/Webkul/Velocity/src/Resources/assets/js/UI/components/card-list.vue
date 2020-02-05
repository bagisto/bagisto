<template>
    <div class="row flex-nowrap" :class="rowClass">

        <product-quick-view v-if="quickViewDetails" :quick-view-details="quickViewDetails"></product-quick-view>

        <div class="col-2 lg-card-container" v-for="(card, index) in cardDetails" :key="index">
            <div @click="redirect(card.route)" class="card padding-10">

                <template v-if="card.price > card.max_price">
                    <button type="button" class="sale-btn card-sale-btn fw6">Sale</button>
                </template>

                <div class="card-product-image-container">
                    <div
                        class="background-image-group"
                        :style="`background-image: url(${card['product-image']});`">
                    </div>

                    <div class="quick-view-btn-container" @click="openQuickView({details: card, event: $event})">
                        <span class="rango-zoom-plus"></span>

                        <button type="button">Quick View</button>
                    </div>
                </div>

                <div class="card-bottom-container">

                    <div class="card-body no-padding">
                        <span class="fs16 hide-text">{{ card.name }}</span>
                        <div class="fs18 card-price-container">

                            <span class="card-current-price fw6 mr10">
                                {{ card['currency-icon'] }} {{ card.max_price }}
                            </span>

                            <template v-if="card.price > card.max_price">
                                <span class="card-actual-price mr10">
                                    {{ card['currency-icon'] }} {{ card.price }}
                                </span>

                                <span class="card-discount">
                                    {{ ((card.price - card.max_price) * 100) / card.price }}%
                                </span>
                            </template>
                        </div>
                    </div>

                    <template v-if="card['review-count']">
                        <star-ratings :ratings="card['star-rating']"></star-ratings>
                    </template>

                    <template v-else>
                        <div class="mt10">
                            <span class="fs14">Be the first to write a review</span>
                        </div>
                    </template>

                    <div class="button-row mt10 card-bottom-container">
                        <add-to-cart-btn></add-to-cart-btn>
                        <span class="rango-heart"></span>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/javascript">
    export default {
        mixins: ['myMixin'],
        props: ['cardDetails', 'rowClass'],

        data: function () {
            return {
                quickView: null,
                quickViewDetails: false,
            }
        },

        mounted: function () {
            this.quickView = $('.cd-quick-view');

            // this.openQuickView({details: this.cardDetails[0]});
        },

        methods: {
            openQuickView: function ({details, event}) {
                if (event) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                // this.quickView

                // .velocity({

                //     'width': '734px',
                //     'left': '200px',
                //     'top': '50px',
                // }, 1000, [ 500, 20 ])

                // .velocity({
                //     'width': '734px',
                //     'left': '200px',
                // }, 3000, 'ease');

                this.quickViewDetails = details;
            },
        }
    }
</script>