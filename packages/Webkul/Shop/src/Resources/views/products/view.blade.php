@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp

<x-shop::layouts>
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <v-product :product-id="{{ $product->id }}">
        <div>
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
                                class="flex border border-black items-center justify-center rounded-full min-w-[46px] min-h-[46px] max-h-[46px] bg-white cursor-pointer transition icon-heart text-[24px]  max-1180:absolute max-1180:-top-[82px] max-1180:right-[12px] max-1180:border-0"
                                @click='addToWishlist()'
                            >
                            </div>
                        </div>

                        <div class='flex items-center'>
                            <x-shop::products.star-rating star='{{ $avgRatings }}' :is-editable=false></x-shop::products.star-rating>

                            <div class="flex gap-[15px] items-center">
                                <p class="text-[#7D7D7D] text-[14px]">({{ count($product->reviews) }} reviews)</p>
                            </div>
                        </div>

                        {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}

                        @include ('shop::products.view.stock', ['product' => $product])

                        <p class="text-[24px] flex items-center font-medium mt-[25px] max-sm:mt-[15px] max-sm:text-[18px]">
                            {!! $product->getTypeInstance()->getPriceHtml() !!}
                        </p>

                        {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                        {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                        <p class="text-[18px] text-[#7D7D7D] mt-[25px] max-sm:text-[14px] max-sm:mt-[15px]">
                            {!! $product->short_description !!}
                        </p>

                        {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}

                        <div class="flex gap-[15px] mt-[30px] max-w-[470px]">
                            <x-shop::quantity-changer
                                class="gap-x-[16px] rounded-[12px] py-[15px] px-[26px]"
                                ::default-quantity="1"
                                @change="updateItem($event)"
                            >
                            </x-shop::quantity-changer>
                            
                            <button
                                class="rounded-[12px] border border-navyBlue py-[15px] w-full max-w-full"
                                @click='addToCart("")'
                            >
                                @lang('shop::app.products.add-to-cart')
                            </button>
                        </div>

                        <button
                            class="rounded-[12px] border bg-navyBlue text-white border-navyBlue py-[15px]  w-full max-w-[470px] mt-[20px]"
                            @click='addToCart("buy_now")'
                            {{ ! $product->isSaleable(1) ? 'disabled' : '' }}
                        >
                            @lang('shop::app.products.buy-now')
                        </button>

                        <div class="flex gap-[35px] mt-[40px] max-sm:flex-wrap">
                            <div
                                class=" flex justify-center items-center gap-[10px] cursor-pointer"
                                @click='addToCompare()'
                            >
                                <span class="icon-compare text-[24px]"></span>
                                @lang('shop::app.products.compare')
                            </div>

                            <div class="flex gap-[25px] max-sm:flex-wrap">
                                <div class=" flex justify-center items-center gap-[10px] cursor-pointer"><span
                                        class="icon-share text-[24px]"></span>Share</div>
                                <div class="flex gap-[15px]">
                                    <a href="" class="bg-[position:0px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                        aria-label="Facebook"></a>
                                    <a href="" class="bg-[position:-40px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                        aria-label="Twitter"></a>
                                    <a href="" class="bg-[position:-80px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                        aria-label="Pintrest"></a>
                                    <a href="" class="bg-[position:-120px_-274px] bs-main-sprite w-[40px] h-[40px]"
                                        aria-label="Linkdln"></a>
                                </div>
                            </div>

                            {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tab Section --}}
            <x-shop::tabs position="center">
                <x-shop::tabs.item
                    class="container mt-[60px] !p-0"
                    {{-- @translations --}}
                    :title="trans('Description')"
                    :is-selected="true"
                >
                    <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                        {!! $product->description !!}
                    </p>
                </x-shop::tabs.item>

                <x-shop::tabs.item
                    class="container mt-[60px] !p-0"
                    {{-- @translations --}}
                    :title="trans('Additional Information')"
                    :is-selected="false"
                >
                    <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                        @foreach ($customAttributeValues as $values)
                            <div class="grid">
                                <p class="text-[16px] text-black">{{ $values['label'] }}</p>
                            </div>
                            <div class="grid">
                                <p class="text-[16px] text-[#7D7D7D]">{{ $values['value']??'-' }}</p>
                            </div>
                        @endforeach
                    </p>
                </x-shop::tabs.item>

                <x-shop::tabs.item
                    class="container mt-[60px] !p-0"
                    {{-- @translations --}}
                    :title="trans('Reviews')"
                    :is-selected="false"
                >
                    @include('shop::products.view.reviews')
                </x-shop::tabs.item>
            </x-shop::tabs>

            {{-- Featured Products --}}
            <x-shop::products.carousel
                {{-- @translations --}}
                :title="trans('Related Products')"
                :src="route('shop.products.related.index', ['id' => $product->id])"
                :navigation-link="route('shop.home.index')"
            >
            </x-shop::products.carousel>

            {{-- Upsell Products --}}
            <x-shop::products.carousel
                {{-- @translations --}}
                :title="trans('We found other products you might like!')"
                :src="route('shop.products.up-sell.index', ['id' => $product->id])"
                :navigation-link="route('shop.home.index')"
            >
            </x-shop::products.carousel>
        </div>
    </v-product>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-product-template">
            <slot></slot>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                props: ['productId'],

                data() {
                    return {
                        writeReview: false,

                        reviewList: false,

                        qty: 1,

                        reviews: {},

                        page: 1
                    }
                },

                mounted() {
                    this.getReviews()
                },

                methods: {
                    addToCart(buyNow) {
                        const params = {
                            'quantity': this.qty,
                            'product_id': this.productId,
                        };

                        this.$axios.post('{{ route("shop.checkout.cart.store") }}', params).then(response => {
                            alert(response.data.message);
                            if (buyNow); //Redirect to Cart Page
                        }).catch(error => {});
                    },

                    addToWishlist() {
                        this.$axios.post('{{ route("shop.customers.account.wishlist.store", $product->id) }}').then(response => {
                            alert(response.data.message);
                        }).catch(error => {});
                    },

                    addToCompare() {
                        this.$axios.get('{{ route("shop.customers.account.compare.store", $product->id) }}').then(response => {
                            alert(response.data.message);
                        }).catch(error => {});
                    },

                    addToReview() {
                        this.$axios.post('{{ route("shop.products.reviews.store", $product->id) }}', {
                            'comment': this.$refs.review.comment.value,
                            'rating' : this.$refs.review.star_rating.value,
                            'title'  : this.$refs.review.title.value,
                        }).then(response => {
                            if (response.status == 200) alert(response.data.message); this.$refs.review.reset();
                        }).catch(error => { alert('Something went wrong')});
                    },

                    updateItem(quantity) {
                        this.qty = quantity;
                    },

                    getReviews() {
                        this.$axios.get('{{ route("shop.products.reviews.index", $product->id) }}' + '?page=' + this.page).then(response => {
                            this.page++;
                            if (this.reviews.length > 0) {
                                this.reviews = this.reviews.concat(response.data.data);
                            } else {
                                this.reviews = response.data.data;
                            }
                        }).catch(error => {});
                    }
                }
            })
        </script>

    @endPushOnce
</x-shop::layouts>
