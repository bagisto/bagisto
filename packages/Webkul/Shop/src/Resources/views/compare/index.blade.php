@php
    $attributeRepository = app('\Webkul\Attribute\Repositories\AttributeFamilyRepository');

    $comparableAttributes = $attributeRepository->getComparableAttributesBelongsToFamily()->toArray();
@endphp

<x-shop::layouts>
    <div class="flex-auto">
        <div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
            <!-- Breadcrumb -->
            <div class="flex justify-start mt-[30px] max-lg:hidden">
                <div class="flex gap-x-[14px] items-center">
                    <p class="flex items-center gap-x-[14px] text-[16px] font-medium">
                        @lang('shop::app.checkout.cart.home') 

                        <span class="icon-arrow-right text-[24px]"></span>
                    </p>

                    <p class="text-[#7D7D7D] text-[12px] flex items-center gap-x-[16px] font-medium  after:content[' '] after:bg-[position:-7px_-41px] after:bs-main-sprite after:w-[9px] after:h-[20px] after:last:hidden">
                        @lang('shop::app.compare.compare-similar-item')
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-8 mt-[30px] max-sm:mt-[20px] max-1060:grid-cols-2 max-868:grid-cols-1 max-sm:justify-items-center">
                <v-compare-item></v-compare-item>
            </div>
        </div>
    </div>

    @pushOnce('scripts')
        <script type="text/x-template" id="v-compare-item-template">
            <div class="grid gap-2.5 relative max-sm:grid-cols-1" v-for="item in compareItems" v-if="compareItems?.length">
                <div class="relative overflow-hidden group max-w-[291px] max-h-[300px]">
                    <div 
                        class="relative overflow-hidden rounded-sm min-w-[291px] min-h-[300px] bg-[#E9E9E9] shimmer"
                        v-show="isLoading"
                    > 
                        <img 
                            class="rounded-sm bg-[#F5F5F5]" 
                            src=""
                        > 
                    </div>

                    <img 
                        class="rounded-sm bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300" 
                        :src='item.product.base_image.medium_image_url'
                        @load="onResponseLoad()"
                        v-show="! isLoading"
                    >

                    <div class="action-items bg-black">
                        <p class="rounded-[44px] text-[#fff] text-[14px] px-[10px] bg-navyBlue inline-block absolute top-[20px] left-[20px]">
                            @lang('shop::app.compare.new')
                        </p>

                        <div class="group-hover:bottom-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <span 
                                class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[20px] right-[20px] icon-heart text-[25px]"
                                @click="moveToWishlist(item.product.id)"
                            >
                            </span>

                            <span 
                                class=" flex justify-center items-center w-[30px] h-[30px] bg-white rounded-md cursor-pointer absolute top-[60px] right-[20px] icon-bin text-[25px]"
                                @click="remove(item.product.id)"
                            >
                            </span>

                            <div 
                                class="rounded-xl bg-white text-navyBlue text-xs w-max font-medium py-[11px] px-[43px] cursor-pointer absolute bottom-[15px] left-[50%] -translate-x-[50%] translate-y-[54px] group-hover:translate-y-0 transition-all duration-300"
                                @click="moveToCart(item.product.id)"
                            >
                                @lang('shop::app.compare.add-to-cart')
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($comparableAttributes as $comparableAttribute)
                    @switch ($comparableAttribute['code'])
                        @case ('price')
                            <p
                                class="w-[55%] h-[24px] bg-[#E9E9E9] shimmer"
                                v-show="isLoading"    
                            >
                            </p>

                            <p
                                class="text-[14px] font-medium text-[#3A3A3A]" 
                                v-html="item.product['{{ $comparableAttribute['code'] }}'] ?? item.product.price_html"
                                @load="onResponseLoad()"
                                v-show="! isLoading"
                            >
                            </p>
                            @break
                        @default
                            @switch ($comparableAttribute['type'])
                                @case('boolean')
                                    @break
                                @default
                                    <p
                                        class="w-[55%] h-[24px] bg-[#E9E9E9] shimmer"
                                        v-show="isLoading"    
                                    >
                                    </p>

                                    <p 
                                        class="text-[14px] font-medium" 
                                        v-html="item.product['{{ $comparableAttribute['code'] }}']"
                                        @load="onResponseLoad()"
                                        v-show="! isLoading"
                                    >
                                    </p>
                            @endswitch
                        @break
                    @endswitch
                @endforeach
            </div>
        </script>

        <script type="module">
            app.component("v-compare-item", {
                template: '#v-compare-item-template',

                data() {
                    return  {
                        compareItems: [],

                        products: [],

                        isCustomer: '{{ auth()->guard('customer')->check() }}',

                        isLoading: true,
                    }
                },

                mounted() {
                    this.getcompareItems();
                },

                methods: {
                    onResponseLoad() {
                        this.isLoading = false;
                    },

                    getcompareItems() {
                        if (this.isCustomer) {
                            this.$axios.get('{{ route('shop.api.compare.index') }}')
                                .then(response => {
                                    this.compareItems = response.data.data;
                                })
                                .catch(error => {});
                            
                            return;
                        }

                        let items = this.getStorageValue('compare_items');

                        this.$axios.get('{{ route('shop.api.compare.index') }}', {
                                params: {
                                    product_ids: items,
                                },
                            })
                            .then(response => {
                                this.compareItems = response.data.data;
                            })
                            .catch(error => {});
                    },

                    remove(productId) {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route('shop.api.compare.destroy') }}', {
                                    '_method': 'DELETE',
                                    'product_id': productId,
                                })
                                .then(response => {
                                    this.compareItems = response.data.data;
                                })
                                .catch(error => {});

                            return;
                        }

                        let existingItems = this.getStorageValue('compare_items');

                        let updatedItems = existingItems.filter(item => item != productId);

                        this.setStorageValue('compare_items', updatedItems);
                        
                        location.reload();

                        alert('Selected data removed from local storage');
                    },

                    moveToCart(productId) {
                        if (this.customer) {
                            this.$axios.post('{{ route("shop.api.compare.move_to_cart") }}', {
                                    'quantity': 1,
                                    'product_id': productId,
                                })
                                .then(response => {
                                    this.compareItems = response.data.data;
                                })
                                .catch(error => {});

                            return;
                        }

                        let existingItems = this.getStorageValue('compare_items');

                        let updatedItems = existingItems.filter(item => item != productId);

                        this.$axios.post('{{ route("shop.api.compare.move_to_cart") }}', {
                                'quantity': 1,
                                'product_id': productId,
                            })
                            .then(response => {
                                this.setStorageValue('compare_items', updatedItems);
                                location.reload();
                            })
                            .catch(error => {});
                    },

                    moveToWishlist(productId) {
                        if (this.isCustomer) {
                            this.$axios.post('{{ route("shop.api.compare.move_to_wishlist") }}', {
                                    'quantity': 1,
                                    'product_id': productId,
                                })
                                .then(response => {
                                    this.compareItems = response.data.data;
                                    alert(response.data.message);
                                })
                                .catch(error => {});

                            return;
                        }

                        alert('Guest user can not move compare product to wisthlist');
                    },

                    getStorageValue(key) {
                        let value = window.localStorage.getItem(key);

                        if (value) {
                            value = JSON.parse(value);
                        }

                        return value;
                    },

                    setStorageValue(key, value) {
                        window.localStorage.setItem(key, JSON.stringify(value));

                        return true;
                    },
                }
            });
        </script>
    @endpushOnce
</x-shop::layouts>
