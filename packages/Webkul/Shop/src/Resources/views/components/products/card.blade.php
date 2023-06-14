<product-card
    {{ $attributes }}
    :product="product"
>
</product-card>

@pushOnce('scripts')
    <script type="text/x-template" id="product-card-template">
        <div class='grid gap-2.5 content-start relative {{ $attributes["class"] }}'>
            <div class="relative overflow-hidden  group max-w-[291px] max-h-[300px]">
                <div
                    class="relative overflow-hidden rounded-sm  min-w-[291px] min-h-[300px] bg-[#E9E9E9] shimmer"
                    v-show="isImageLoading"
                >
                    <img class="rounded-sm bg-[#F5F5F5]" src="">
                </div>

                <img
                    class="rounded-sm bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300"
                    :src="product.base_image.medium_image_url"
                    width="291"
                    height="300"
                    @load="onImageLoad()"
                    v-show="! isImageLoading"
                >

                <div class="action-items bg-black">
                    <p
                        class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]"
                        v-if="product.is_new"
                    >
                        {{-- @translations --}}
                        @lang('New')
                    </p>

                    <div class="group-hover:bottom-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <a
                            class="flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[20px] right-[20px] icon-heart text-[25px]"
                            @click="addToWishlist()"
                        >
                        </a>

                        <a
                            class="flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[60px] right-[20px] icon-compare text-[25px]"
                            @click="addToCompare(product.id)"
                        >
                        </a>

                        <a
                            class="rounded-xl bg-white text-navyBlue text-xs w-max font-medium py-[11px] px-[43px] cursor-pointer absolute bottom-[15px] left-[50%] -translate-x-[50%] translate-y-[54px] group-hover:translate-y-0 transition-all duration-300"
                            @click="addToCart()"
                        >
                            @lang('shop::app.components.products.add-to-cart')
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-2.5 content-start">
                <p class="text-base" v-text="product.name"></p>

                <div
                    class="flex gap-2.5 text-lg"
                    v-html="product.price_html"
                >
                </div>

                <div class="flex gap-4 mt-[8px]">
                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#B5DCB4]"></span>

                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#5C5C5C]"></span>
                </div>
            </div>
        </div>
    </script>

    <script type="module">
        app.component('product-card', {
            template: '#product-card-template',

            props: ['product'],

            data() {
                return {
                    isImageLoading: true,

                    customer: '{{ auth()->guard('customer')->check() }}',
                }
            },

            methods: {
                onImageLoad() {
                    this.isImageLoading = false;
                },

                addToWishlist() {
                    this.$axios.post(`{{ route('shop.customers.account.wishlist.store') }}`, {
                            product_id: this.product.id
                        })
                        .then(response => {
                            alert(response.data.data.message);
                        })
                        .catch(error => {});
                },

                addToCompare(productId) {
                    if (this.customer) {
                        this.$axios.post("{{ route('shop.api.compare.store') }}", {
                                'product_id': this.product.id,
                            })
                            .then(response => {
                                alert(response.data.data.message);
                            })
                            .catch(error => {});
                    } else {
                        let updatedItems = [productId];

                        let existingItems = this.getStorageValue('compare_items');

                        if (existingItems) {
                            if (! existingItems.includes(productId)) {
                                if (existingItems.indexOf(this.productId) == -1) {
                                    updatedItems = existingItems.concat(updatedItems);

                                    this.setStorageValue('compare_items', updatedItems);

                                    alert('Added product in compare for guest');
                                }
                            } else {
                                alert('Product is already added in compare.');
                            }
                        } else {
                            this.setStorageValue('compare_items', updatedItems);
                        }

                    }
                },

                setStorageValue(key, value) {
                    window.localStorage.setItem(key, JSON.stringify(value));

                    return true;
                },

                getStorageValue(key) {
                    let value = window.localStorage.getItem(key);

                    if (value) {
                        value = JSON.parse(value);
                    }

                    return value;
                },

                addToCart() {
                    this.$axios.post('{{ route("shop.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            alert(response.data.message);
                        })
                        .catch(error => {});
                },
            },
        });
    </script>
@endpushOnce
