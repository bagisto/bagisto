@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp

<x-shop::layouts>
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <div class="flex justify-center max-lg:hidden">
        <x-shop::breadcrumbs name="product" :entity="$product"></x-shop::breadcrumbs>
    </div>

    <v-product :product-id="{{ $product->id }}">
        <x-shop::shimmer.products.view></x-shop::shimmer.products.view>
    </v-product>

    {{-- Information Section --}}
    <div class="mt-[80px]">
        <x-shop::tabs position="center">
            <x-shop::tabs.item
                class="container mt-[60px] !p-0"
                :title="trans('shop::app.products.description')"
                :is-selected="true"
            >
                <div class="container mt-[60px] max-1180:px-[20px]">
                    <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                        {!! $product->description !!}
                    </p>
                </div>
            </x-shop::tabs.item>

            <x-shop::tabs.item
                class="container mt-[60px] !p-0"
                :title="trans('shop::app.products.additional-information')"
                :is-selected="false"
            >
                <div class="container mt-[60px] max-1180:px-[20px]">
                    <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                        @foreach ($customAttributeValues as $customAttributeValue)
                            <div class="grid">
                                <p class="text-[16px] text-black">
                                    {{ $customAttributeValue['label'] }}
                                </p>
                            </div>

                            <div class="grid">
                                <p class="text-[16px] text-[#7D7D7D]">
                                    {{ $customAttributeValue['value']??'-' }}
                                </p>
                            </div>
                        @endforeach
                    </p>
                </div>
            </x-shop::tabs.item>

            <x-shop::tabs.item
                class="container mt-[60px] !p-0"
                :title="trans('shop::app.products.reviews')"
                :is-selected="false"
            >
                @include('shop::products.view.reviews')
            </x-shop::tabs.item>
        </x-shop::tabs>
    </div>

    {{-- Featured Products --}}
    <x-shop::products.carousel
        :title="trans('shop::app.products.related-product-title')"
        :src="route('shop.api.products.related.index', ['id' => $product->id])"
    >
    </x-shop::products.carousel>

    {{-- Upsell Products --}}
    <x-shop::products.carousel
        :title="trans('shop::app.products.up-sell-title')"
        :src="route('shop.api.products.up-sell.index', ['id' => $product->id])"
    >
    </x-shop::products.carousel>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-product-template">
            <form ref="formData">
                <input 
                    type="hidden" 
                    name="product_id" 
                    value="{{ $product->id }}"
                >
                
                <input 
                    type="hidden" 
                    name="quantity" 
                    :value="qty"
                >

                <div class="container px-[60px] max-1180:px-[0px]">
                    <div class="flex mt-[48px] gap-[40px] max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-[25px]">
                        @include('shop::products.view.gallery')

                        {{-- Product Details --}}
                        <div class="max-w-[590px] relative max-1180:px-[20px]">
                            <div class="flex justify-between gap-[15px]">
                                <h1 class="text-[30px] font-medium max-sm:text-[20px]">
                                    {{ $product->name }}
                                </h1>

                                <div
                                    class="flex border border-black items-center justify-center rounded-full min-w-[46px] min-h-[46px] max-h-[46px] bg-white cursor-pointer transition icon-heart text-[24px]"
                                    @click="addToWishlist"
                                >
                                </div>
                            </div>

                            <div class="flex gap-[15px] items-center mt-[15px]">
                                <x-shop::products.star-rating 
                                    :value="$avgRatings"
                                    :is-editable=false
                                >
                                </x-shop::products.star-rating>

                                <div class="flex gap-[15px] items-center">
                                    <p class="text-[#7D7D7D] text-[14px]">
                                        ({{ $product->approvedReviews->count() }} @lang('reviews'))
                                    </p>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                            <p class="text-[24px] !font-medium flex gap-2.5 items-center mt-[25px] max-sm:mt-[15px] max-sm:text-[18px]">
                                {!! $product->getTypeInstance()->getPriceHtml() !!}
                            </p>

                            {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                            {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                            <p class="text-[18px] text-[#7D7D7D] mt-[25px] max-sm:text-[14px] max-sm:mt-[15px]">
                                {!! $product->short_description !!}
                            </p>

                            {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                            @include('shop::products.view.types.configurable')

                            @include('shop::products.view.types.grouped')

                            @include('shop::products.view.types.bundle')

                            @include('shop::products.view.types.downloadable')

                            <div class="flex gap-[15px] mt-[30px] max-w-[470px]">

                                {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                @if ($product->getTypeInstance()->showQuantityBox())
                                    <x-shop::quantity-changer
                                        name="quantity"
                                        value="1"
                                        class="gap-x-[16px] rounded-[12px] py-[15px] px-[26px]"
                                    >
                                    </x-shop::quantity-changer>
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}

                                <button
                                    type="button"
                                    class="rounded-[12px] border border-navyBlue py-[15px] w-full max-w-full"
                                    @click="addToCart"
                                >
                                    @lang('shop::app.products.add-to-cart')
                                </button>
                            </div>

                            <button
                                type="button"
                                class="rounded-[12px] border bg-navyBlue text-white border-navyBlue py-[15px] w-full max-w-[470px] mt-[20px]"
                                {{-- To Do @(suraj-webkul) handle buy now option with another endpoint/method --}}
                                @click="addToCart"
                                {{ ! $product->isSaleable(1) ? 'disabled' : '' }}
                            >
                                @lang('shop::app.products.buy-now')
                            </button>

                            <div class="flex gap-[35px] mt-[40px] max-sm:flex-wrap">
                                <div
                                    class=" flex justify-center items-center gap-[10px] cursor-pointer"
                                    @click="addToCompare({{ $product->id }})"
                                >
                                    <span class="icon-compare text-[24px]"></span>
                                    @lang('shop::app.products.compare')
                                </div>

                                <div class="flex gap-[25px] max-sm:flex-wrap">
                                    <div class=" flex justify-center items-center gap-[10px] cursor-pointer">
                                        <span class="icon-share text-[24px]"></span>
                                        Share
                                    </div>
                                    
                                    {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                props: ['productId'],

                data() {
                    return {
                        isCustomer: '{{ auth()->guard('customer')->check() }}',
                    }
                },

                methods: {
                    addToCart() {
                        let formData = new FormData(this.$refs.formData);

                        this.$axios.post('{{ route("shop.api.checkout.cart.store") }}', formData, {
                                headers: {
                                    'Content-Type': 'multipart/form-data'
                                }
                            })
                            .then(response => {
                                if (response.data.message) {
                                    this.$emitter.emit('update-mini-cart', response.data.data);

                                    this.$emitter.emit('add-flash', { type: 'success', message: response.data.message });
                                } else {
                                    this.$emitter.emit('add-flash', { type: 'warning', message: response.data.data.message });
                                }
                            })
                            .catch(error => {});
                    },

                    addToWishlist() {
                        this.$axios.post('{{ route('shop.api.customers.account.wishlist.store') }}', {
                                product_id: "{{ $product->id }}"
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
                        localStorage.setItem(key, JSON.stringify(value));
                    },

                    getStorageValue(key) {
                        let value = localStorage.getItem(key);

                        if (value) {
                            value = JSON.parse(value);
                        }

                        return value;
                    },
                },
            });
        </script>
    @endPushOnce
</x-shop::layouts>