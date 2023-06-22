<v-product-list
    {{ $attributes }}
    :product="product"
>
</v-product-list>

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-list-template">
        <div class="flex gap-[15px] grid-cols-2 max-w-max relative max-sm:flex-wrap">
            <div class="relative overflow-hidden group max-w-[250px] max-h-[258px]"> 
                <a :href="`{{ route('shop.productOrCategory.index', '') }}/${product.url_key}`">
                    <img 
                        class="rounded-sm bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300" 
                        :src="product.base_image.medium_image_url"
                    >
                </a>

                <div class="action-items bg-black"> 
                    <p  v-if="product.is_new" class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]">
                        {{-- @translations --}}
                        @lang('New')
                    </p>

                    <p v-if="product.on_sale" class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]">
                        {{-- @translations --}}
                        @lang('Sale')
                    </p>

                    <div class="group-hover:bottom-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                        <span 
                            class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[20px] right-[20px] icon-heart text-[25px]"
                            @click="addToWishlist()"
                        >
                        </span> 
                        
                        <span 
                            class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[60px] right-[20px] icon-compare text-[25px]"
                            @click="addToCompare(product.id)"
                        >
                        </span>
                    </div> 
                </div> 
            </div> 

            <div class="grid gap-[15px] content-start"> 
                <p 
                    class="text-base" 
                    v-text="product.name"
                >
                </p> 

                <div class="flex gap-2.5"> 
                    <p 
                        class="text-lg font-semibold" 
                        v-html="product.price_html">
                    </p>
                </div> 

                <div class="flex gap-4"> 
                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#B5DCB4]">
                    </span> 

                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#5C5C5C]">
                    </span> 
                </div> 

                <p class="text-[14px] text-[#7D7D7D]">Be the first to review this product</p> 

                <div 
                    class="bs-primary-button px-[30px] py-[10px]"
                    @click="addToCart()"
                >
                    @lang('shop::app.components.products.add-to-cart')
                </div> 
            </div> 
        </div>
    </script>

    <script type="module">
        app.component('v-product-list', {
            template: '#v-product-list-template',

            props: ['product'],

            data() {
                return {
                    isImageLoading: true,

                    isCustomer: '{{ auth()->guard('customer')->check() }}',
                }
            },

            methods: {
                addToWishlist() {
                    this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                            product_id: this.product.id
                        })
                        .then(response => {
                            alert(response.data.data.message);
                        })
                        .catch(error => {});
                },

                addToCompare(productId) {
                    /**
                     * This will handle for customers.
                     */
                    if (this.isCustomer) {
                        this.$axios.post('{{ route("shop.api.compare.store") }}', {
                                'product_id': productId
                            })
                            .then(response => {
                                alert(response.data.data.message);
                            })
                            .catch(error => {});

                        return;
                    }

                    /**
                     * This will handle for guests.
                     */
                    let existingItems = this.getStorageValue(this.getCompareItemsStorageKey()) ?? [];

                    if (existingItems.length) {
                        if (! existingItems.includes(productId)) {
                            existingItems.push(productId);

                            this.setStorageValue(this.getCompareItemsStorageKey(), existingItems);

                            alert('Added product in compare.');
                        } else {
                            alert('Product is already added in compare.');
                        }
                    } else {
                        this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                        alert('Added product in compare.');
                    }
                },

                getCompareItemsStorageKey() {
                    return 'compare_items';
                },

                setStorageValue(key, value) {
                    window.localStorage.setItem(key, JSON.stringify(value));
                },

                getStorageValue(key) {
                    let value = window.localStorage.getItem(key);

                    if (value) {
                        value = JSON.parse(value);
                    }

                    return value;
                },

                addToCart() {
                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
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
