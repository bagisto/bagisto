<v-product-card
    {{ $attributes }}
    :product="product"
>
</v-product-card>

@pushOnce('scripts')
    <script type="text/x-template" id="v-product-card-template">
        <div
            class='grid gap-2.5 content-start relative {{ $attributes["class"] }}'
            v-if="mode != 'list'"
        >
            <div class="relative overflow-hidden group max-w-[291px] max-h-[300px]">
                <div
                    class="relative overflow-hidden rounded-sm  min-w-[291px] min-h-[300px] bg-[#E9E9E9] shimmer"
                    v-show="isImageLoading"
                >
                    <img class="rounded-sm bg-[#F5F5F5]" src="">
                </div>

                <a :href="`{{ route('shop.productOrCategory.index', '') }}/${product.url_key}`">
                    <img
                        class="rounded-sm bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300"
                        :src="product.base_image.medium_image_url"
                        width="291"
                        height="300"
                        @load="onImageLoad()"
                        v-show="! isImageLoading"
                    >
                </a>   
                
                <div class="action-items bg-black">
                    <p
                        class="rounded-[44px] text-[#fff] text-[14px] px-[10px]  bg-red-700 inline-block absolute top-[20px] left-[20px]"
                        v-if="product.on_sale"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </p>

                    <p
                        class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]"
                        v-else-if="product.is_new"
                    >
                        @lang('shop::app.components.products.card.new')
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
                    class="flex font-semibold gap-2.5 text-lg"
                    v-html="product.price_html"
                >
                </div>

                <div class="flex gap-4 mt-[8px]">
                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#B5DCB4]"></span>

                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#5C5C5C]"></span>
                </div>
            </div>
        </div>

        <div
            class="flex gap-[15px] grid-cols-2 max-w-max relative max-sm:flex-wrap"
            v-else
        >
            <div class="relative overflow-hidden group max-w-[250px] max-h-[258px]"> 
                <a :href="`{{ route('shop.productOrCategory.index', '') }}/${product.url_key}`">
                    <img 
                        class="rounded-sm bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300" 
                        :src="product.base_image.medium_image_url"
                        width="250"
                        height="258"
                    >
                </a>
            
                <div class="action-items bg-black"> 
                    <p
                        class="rounded-[44px] text-[#fff] text-[14px] px-[10px]  bg-red-700 inline-block absolute top-[20px] left-[20px]"
                        v-if="product.on_sale"
                    >
                        @lang('shop::app.components.products.card.sale')
                    </p>

                    <p
                        class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]"
                        v-else-if="product.is_new"
                    >
                        @lang('shop::app.components.products.card.new')
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

                <div 
                    class="flex gap-2.5 text-lg font-semibold"
                    v-html="product.price_html"
                >   
                </div> 

                <div class="flex gap-4"> 
                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#B5DCB4]">
                    </span> 

                    <span class="rounded-full w-[30px] h-[30px] block cursor-pointer bg-[#5C5C5C]">
                    </span> 
                </div> 
                
                <p class="text-[14px] text-[#7D7D7D]" v-if="! product.avg_ratings">
                    @lang('shop::app.components.products.card.review-description')
                </p>
            
                <p v-else class="text-[14px] text-[#7D7D7D]">
                    <x-shop::products.star-rating 
                        ::value="product && product.avg_ratings ? product.avg_ratings : 0"
                        :is-editable=false
                    >
                    </x-shop::products.star-rating>
                </p>
            
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
        app.component('v-product-card', {
            template: '#v-product-card-template',

            props: ['mode', 'product'],

            data() {
                return {
                    isImageLoading: true,

                    isCustomer: '{{ auth()->guard('customer')->check() }}',
                }
            },

            methods: {
                onImageLoad() {
                    this.isImageLoading = false;
                },

                addToWishlist() {
                    this.$axios.post(`{{ route('shop.api.customers.account.wishlist.store') }}`, {
                            product_id: this.product.id
                        })
                        .then(response => {
                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
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
                                this.$emitter.emit('add-flash', { type: 'success', message: response.data.data.message });
                            })
                            .catch(error => {
                                this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.data.message});
                            });

                        return;
                    }

                    /**
                     * This will handle for guests.
                     */
                    let items = this.getStorageValue() ?? [];

                    if (items.length) {
                        if (! items.includes(productId)) {
                            items.push(productId);

                            localStorage.setItem('compare_items', JSON.stringify(items));

                            this.$emitter.emit('add-flash', { type: 'success', message: '@lang('Item added successfully to the compare list')' });
                        } else {
                            this.$emitter.emit('add-flash', { type: 'error', message: '@lang('Item is already added to compare list')' });
                        }
                    } else {
                        localStorage.setItem('compare_items', JSON.stringify([productId]));
                            
                        this.$emitter.emit('add-flash', { type: 'success', message: '@lang('Item added successfully to the compare list')' });

                    }
                },

                getStorageValue(key) {
                    let value = localStorage.getItem('compare_items');

                    if (! value) {
                        return [];
                    }

                    return JSON.parse(value);
                },

                addToCart() {
                    this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', {
                            'quantity': 1,
                            'product_id': this.product.id,
                        })
                        .then(response => {
                            if (response.data.redirect) {
                                window.location.href = response.data.data;
                            }

                            this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                        })
                        .catch(error => {});
                },
            },
        });
    </script>
@endpushOnce
