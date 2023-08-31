@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp

{{-- SEO Meta Content --}}
@push('meta')
    <meta name="description" content="{{ trim($product->meta_description) != "" ? $product->meta_description : \Illuminate\Support\Str::limit(strip_tags($product->description), 120, '') }}"/>

    <meta name="keywords" content="{{ $product->meta_keywords }}"/>

    @if (core()->getConfigData('catalog.rich_snippets.products.enable'))
        <script type="application/ld+json">
            {{ app('Webkul\Product\Helpers\SEO')->getProductJsonLd($product) }}
        </script>
    @endif

    <?php $productBaseImage = product_image()->getProductBaseImage($product); ?>

    <meta name="twitter:card" content="summary_large_image" />

    <meta name="twitter:title" content="{{ $product->name }}" />

    <meta name="twitter:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta name="twitter:image:alt" content="" />

    <meta name="twitter:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:type" content="og:product" />

    <meta property="og:title" content="{{ $product->name }}" />

    <meta property="og:image" content="{{ $productBaseImage['medium_image_url'] }}" />

    <meta property="og:description" content="{!! htmlspecialchars(trim(strip_tags($product->description))) !!}" />

    <meta property="og:url" content="{{ route('shop.product_or_category.index', $product->url_key) }}" />
@endPush

{{-- Page Layout --}}
<x-shop::layouts>
    {{-- Page Title --}}
    <x-slot:title>
        {{ trim($product->meta_title) != "" ? $product->meta_title : $product->name }}
    </x-slot>

    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    {{-- Breadcrumbs --}}
    <div class="flex justify-center max-lg:hidden">
        <x-shop::breadcrumbs name="product" :entity="$product"></x-shop::breadcrumbs>
    </div>

    {{-- Product Information Vue Component --}}
    <v-product :product-id="{{ $product->id }}">
        <x-shop::shimmer.products.view/>
    </v-product>

    {{-- Information Section --}}
    <div class="1180:mt-[80px]">
        <x-shop::tabs position="center">
            {{-- Description Tab --}}
            {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

            <x-shop::tabs.item
                class="container mt-[60px] !p-0 max-1180:hidden"
                :title="trans('shop::app.products.description')"
                :is-selected="true"
            >
                <div class="container mt-[60px] max-1180:px-[20px]">
                    <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                        {!! $product->description !!}
                    </p>
                </div>
            </x-shop::tabs.item>

            {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}


            {{-- Additional Information Tab --}}
            <x-shop::tabs.item
                class="container mt-[60px] !p-0 max-1180:hidden"
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

            {{-- Reviews Tab --}}
            <x-shop::tabs.item
                class="container mt-[60px] !p-0 max-1180:hidden"
                :title="trans('shop::app.products.reviews')"
                :is-selected="false"
            >
                @include('shop::products.view.reviews')
            </x-shop::tabs.item>
        </x-shop::tabs>
    </div>

    {{-- Information Section --}}
    <div class="container mt-[40px] max-1180:px-[20px] 1180:hidden">
        {{-- Description Accordion --}}
        <x-shop::accordion :is-active="true">
            <x-slot:header>
                <div class="flex justify-between mb-[20px] mt-[20px]">
                    <p class="text-[16px] font-medium 1180:hidden">
                        @lang('shop::app.products.description')
                    </p>
                </div>
            </x-slot:header>

            <x-slot:content>
                <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px] mb-[20px]">
                    {!! $product->description !!}
                </p>
            </x-slot:content>
        </x-shop::accordion>

        {{-- Additional Information Accordion --}}
        <x-shop::accordion :is-active="false">
            <x-slot:header>
                <div class="flex justify-between mb-[20px] mt-[20px]">
                    <p class="text-[16px] font-medium 1180:hidden">
                        @lang('shop::app.products.additional-information')
                    </p>
                </div>
            </x-slot:header>

            <x-slot:content>
                <div class="container mt-[20px] mb-[20px] max-1180:px-[20px]">
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
            </x-slot:content>
        </x-shop::accordion>

        {{-- Reviews Accordion --}}
        <x-shop::accordion :is-active="false">
            <x-slot:header>
                <div class="flex justify-between mb-[20px] mt-[20px]">
                    <p class="text-[16px] font-medium 1180:hidden">
                        @lang('shop::app.products.reviews')
                    </p>
                </div>
            </x-slot:header>

            <x-slot:content>
                @include('shop::products.view.reviews')
            </x-slot:content>
        </x-shop::accordion>
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
            <x-shop::form
                v-slot="{ meta, errors, handleSubmit }"
                as="div"
            >
                <form
                    ref="formData"
                    @submit="handleSubmit($event, addToCart)"
                >
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
                        <div class="flex gap-[40px] mt-[48px] max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-[25px]">
                            <!-- Gallery Blade Inclusion -->
                            @include('shop::products.view.gallery')

                            <!-- Details -->
                            <div class="max-w-[590px] relative max-1180:w-full max-1180:max-w-full max-1180:px-[20px]">
                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <div class="flex gap-[15px] justify-between">
                                    <h1 class="text-[30px] font-medium max-sm:text-[20px]">
                                        {{ $product->name }}
                                    </h1>

                                    <div
                                        class="flex items-center justify-center min-w-[46px] min-h-[46px] max-h-[46px] bg-white border border-black rounded-full text-[24px] transition-all hover:opacity-[0.8] cursor-pointer"
                                        :class="isWishlist ? 'icon-heart-fill' : 'icon-heart'"
                                        @click="addToWishlist"
                                    >
                                    </div>
                                </div>

                                {!! view_render_event('bagisto.shop.products.name.before', ['product' => $product]) !!}

                                <!-- Rating -->
                                {!! view_render_event('bagisto.shop.products.rating.before', ['product' => $product]) !!}

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

                                {!! view_render_event('bagisto.shop.products.rating.after', ['product' => $product]) !!}

                                <!-- Pricing -->
                                {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                                <p class="flex gap-2.5 items-center mt-[25px] text-[24px] !font-medium max-sm:mt-[15px] max-sm:text-[18px]">
                                    {!! $product->getTypeInstance()->getPriceHtml() !!}
                                </p>

                                {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                                {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                                <p class="mt-[25px] text-[18px] text-[#7D7D7D] max-sm:text-[14px] max-sm:mt-[15px]">
                                    {!! $product->short_description !!}
                                </p>

                                {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                                @include('shop::products.view.types.configurable')

                                @include('shop::products.view.types.grouped')

                                @include('shop::products.view.types.bundle')

                                @include('shop::products.view.types.downloadable')


                                <!-- Product Actions and Qunatity Box -->
                                <div class="flex gap-[15px] max-w-[470px] mt-[30px]">

                                    {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}

                                    @if ($product->getTypeInstance()->showQuantityBox())
                                        <x-shop::quantity-changer
                                            name="quantity"
                                            value="1"
                                            class="gap-x-[16px] py-[15px] px-[26px] rounded-[12px]"
                                        >
                                        </x-shop::quantity-changer>
                                    @endif

                                    {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}


                                    <!-- Add To Cart Button -->
                                    {!! view_render_event('bagisto.shop.products.view.add_to_cart.before', ['product' => $product]) !!}

                                    <button
                                        type="submit"
                                        class="bs-secondary-button w-full max-w-full"
                                    >
                                        @lang('shop::app.products.add-to-cart')
                                    </button>

                                    {!! view_render_event('bagisto.shop.products.view.add_to_cart.after', ['product' => $product]) !!}
                                </div>


                                <!-- Buy Now Button -->
                                {!! view_render_event('bagisto.shop.products.view.buy_now.before', ['product' => $product]) !!}

                                <button
                                    type="submit"
                                    class="bs-primary-button w-full max-w-[470px] mt-[20px]"
                                    {{ ! $product->isSaleable(1) ? 'disabled' : '' }}
                                >
                                    @lang('shop::app.products.buy-now')
                                </button>

                                {!! view_render_event('bagisto.shop.products.view.buy_now.after', ['product' => $product]) !!}

                                <!-- Share Buttons -->
                                <div class="flex gap-[35px] mt-[40px] max-sm:flex-wrap max-sm:justify-center">
                                    {!! view_render_event('bagisto.shop.products.view.compare.before', ['product' => $product]) !!}

                                    <div
                                        class="flex gap-[10px] justify-center items-center cursor-pointer"
                                        @click="addToCompare({{ $product->id }})"
                                    >
                                        <span class="icon-compare text-[24px]"></span>
                                        @lang('shop::app.products.compare')
                                    </div>

                                    {!! view_render_event('bagisto.shop.products.view.compare.after', ['product' => $product]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </x-shop::form>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                props: ['productId'],

                data() {
                    return {
                        isWishlist: Boolean("{{ (boolean) auth()->guard()->user()?->wishlist_items->where('channel_id', core()->getCurrentChannel()->id)->where('product_id', $product->id)->count() }}"),

                        isCustomer: '{{ auth()->guard('customer')->check() }}',
                    }
                },

                methods: {
                    addToCart(params) {
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
                                this.isWishlist = ! this.isWishlist;

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
                                    if ([400, 422].includes(error.response.status)) {
                                        this.$emitter.emit('add-flash', { type: 'warning', message: error.response.data.data.message });

                                        return;
                                    }

                                    this.$emitter.emit('add-flash', { type: 'error', message: error.response.data.message});
                                });

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

                                this.$emitter.emit('add-flash', { type: 'success', message: "{{ trans('shop::app.products.add-to-compare') }}" });
                            } else {
                                this.$emitter.emit('add-flash', { type: 'warning', message: "{{ trans('shop::app.products.already-in-compare') }}" });
                            }
                        } else {
                            this.setStorageValue(this.getCompareItemsStorageKey(), [productId]);

                            this.$emitter.emit('add-flash', { type: 'success', message: "{{ trans('shop::app.products.add-to-compare') }}" });
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
