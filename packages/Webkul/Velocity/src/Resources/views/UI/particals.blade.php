<script type="text/x-template" id="star-ratings-template">
    <div :class="`stars mr10 fs${size ? size : '16'} ${pushClass ? pushClass : ''}`">
        <input
            v-if="editable"
            type="number"
            :value="showFilled"
            name="rating"
            class="hidden" />

        <span
            :class="`rango-star-fill ${editable ? 'cursor-pointer' : ''}`"
            v-for="(rating, index) in parseInt(showFilled ? showFilled : 3)"
            :key="`${index}${Math.random()}`"
            @click="updateRating(index + 1)">
        </span>

        <template v-if="!hideBlank">
            <span
                :class="`rango-star ${editable ? 'cursor-pointer' : ''}`"
                v-for="(blankStar, index) in (5 - (showFilled ? showFilled : 3))"
                :key="`${index}${Math.random()}`"
                @click="updateRating(showFilled + index + 1)">
            </span>
        </template>
    </div>
</script>

<script type="text/x-template" id="cart-btn-template">
    <button
        :class="`btn btn-link disable-box-shadow ${parseInt(itemCount) ? 'dropdown-icon-custom' : ''}`"
        type="button"
        id="mini-cart"
        @click="toggleMiniCart">

        <div class="mini-cart-content">
            <i class="rango-cart-1 fs24"></i>
            <span class="badge" v-text="itemCount"></span>
            <span class="fs18 fw6 cart-text">{{ __('velocity::app.minicart.cart') }}</span>
        </div>
    </button>
</script>

<script type="text/x-template" id="close-btn-template">
    <button type="button" class="close disable-box-shadow">
        <span class="white-text fs20" @click="togglePopup">×</span>
    </button>
</script>

<script type="text/x-template" id="quantity-changer-template">
    <div class="quantity control-group" :class="[errors.has(controlName) ? 'has-error' : '']">
        <label class="required">{{ __('shop::app.products.quantity') }}</label>

        <button type="button" class="decrease" @click="decreaseQty()">-</button>

        <input :name="controlName" class="control" :value="qty" :v-validate="validations" data-vv-as="&quot;{{ __('shop::app.products.quantity') }}&quot;" readonly>

        <button type="button" class="increase" @click="increaseQty()">+</button>

        <span class="control-error" v-if="errors.has(controlName)">@{{ errors.first(controlName) }}</span>
    </div>
</script>

<script type="text/javascript">
    (() => {
        Vue.component('star-ratings', {
            props: [
                'ratings',
                'size',
                'hideBlank',
                'pushClass',
                'editable'
            ],

            template: '#star-ratings-template',

            data: function () {
                return {
                    showFilled: this.ratings,
                }
            },

            methods: {
                updateRating: function (index) {
                    index = Math.abs(index);

                    this.editable ? this.showFilled = index : '';

                    this.showFilled
                }
            },
        })

        Vue.component('cart-btn', {
            template: '#cart-btn-template',

            props: ['itemCount'],

            methods: {
                toggleMiniCart: function () {
                    $('#cart-modal-content').toggle();
                }
            }
        })

        Vue.component('close-btn', {
            template: '#close-btn-template',

            methods: {
                togglePopup: function () {
                    $('#cart-modal-content').hide();
                }
            }
        });

        Vue.component('quantity-changer', {
            template: '#quantity-changer-template',

            inject: ['$validator'],

            props: {
                controlName: {
                    type: String,
                    default: 'quantity'
                },

                quantity: {
                    type: [Number, String],
                    default: 1
                },

                minQuantity: {
                    type: [Number, String],
                    default: 1
                },

                validations: {
                    type: String,
                    default: 'required|numeric|min_value:1'
                }
            },

            data: function() {
                return {
                    qty: this.quantity
                }
            },

            watch: {
                quantity: function (val) {
                    this.qty = val;

                    this.$emit('onQtyUpdated', this.qty)
                }
            },

            methods: {
                decreaseQty: function() {
                    if (this.qty > this.minQuantity)
                        this.qty = parseInt(this.qty) - 1;

                    this.$emit('onQtyUpdated', this.qty)
                },

                increaseQty: function() {
                    this.qty = parseInt(this.qty) + 1;

                    this.$emit('onQtyUpdated', this.qty)
                }
            }
        });
    })()
</script>
