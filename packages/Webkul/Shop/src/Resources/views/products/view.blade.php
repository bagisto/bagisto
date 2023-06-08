@inject ('reviewHelper', 'Webkul\Product\Helpers\Review')
@inject ('productViewHelper', 'Webkul\Product\Helpers\View')

@php
    $avgRatings = round($reviewHelper->getAverageRating($product));

    $percentageRatings = $reviewHelper->getPercentageRating($product);

    $customAttributeValues = $productViewHelper->getAdditionalData($product);
@endphp

<x-shop::layouts>
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <v-product></v-product>
       
    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-product-template">
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

                            @include('shop::products.view.types.configurable')
                            
                            @include('shop::products.view.types.grouped')

                            {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}
                            
                            <div class="flex gap-[15px] mt-[30px] max-w-[470px]">

                                {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}
                                
                                @if ($product->type != 'grouped')
                                    @include('shop::products.view.quantity-changer')
                                @endif

                                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}
                                
                                @if ($product->type != 'grouped')
                                    <button 
                                        class="rounded-[12px] border border-navyBlue py-[15px] w-full max-w-full"
                                        @click='addToCart("")'
                                    >
                                        @lang('shop::app.products.add-to-cart')
                                    </button>
                                @else 
                                    <button 
                                        class="rounded-[12px] border text-navyBlue border-navyBlue py-[15px]  w-full max-w-[470px] mt-[20px]"
                                        @click='addToCart("")'
                                    >
                                        @lang('shop::app.products.add-to-cart')
                                    </button>
                                @endif
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
                        {{-- Product Details --}}
                    </div>
                </div>
                
                {{-- Tab section --}}
                <x-shop::tabs position="center">
                    <x-shop::tabs.item
                        {{-- @translations --}}
                        :title="trans('Description')"
                        :is-selected="true"
                    >   
                        <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                            {!! $product->description !!}
                        </p>
                    </x-shop::tabs.item>

                    <x-shop::tabs.item
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
                        {{-- @translations --}}
                        :title="trans('Reviews')"
                        :is-selected="false"
                    >   
                    <div>
                        <!-- Write Review Section -->
                        <div class="w-full">
                            <form 
                                ref='review' 
                                @submit.prevent='addToReview()' 
                                class='rounded mb-4 grid grid-cols-[auto_1fr] max-md:grid-cols-[1fr] gap-[40px] justify-center'
                            >
                                <div class="flex w-full">
                                    <label 
                                        for="dropzone-file"
                                        class="flex flex-col w-[286px] h-[286px] items-center justify-center rounded-[12px] cursor-pointer bg-[#F5F5F5] hover:bg-gray-100 "
                                    >
                                        <div class="m-0 block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center">
                                            @lang('shop::app.products.add-image')
                                        </div>

                                        <input id="dropzone-file" type="file" class="hidden" />
                                    </label>
                                </div>
                                
                                <div>
                                    <div class="">
                                        <label class="block text-gray-700 text-[12px] font-medium mb-2" for="username">
                                            @lang('shop::app.products.rating')
                                        </label>

                                        <x-shop::products.star-rating star='5' is-editable=true></x-shop::products.star-rating>

                                    </div>

                                    <div class="mb-4 mt-[15px]">
                                        <label 
                                            class="block text-gray-700 text-[12px] font-medium mb-2" for="username"
                                        > 
                                            @lang('shop::app.products.title') 
                                        </label>

                                        <input
                                            class="shadow text-[14px] appearance-none border rounded-[12px] w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="username" 
                                            type="text" 
                                            placeholder="Username" 
                                            name='title' 
                                            v-validate='required'
                                        >
                                    </div>

                                    <div class="mb-6">
                                        <label 
                                            class="block text-gray-700 text-[12px] font-medium mb-2" for="password"
                                        >
                                            @lang('shop::app.products.comment')
                                        </label>

                                        <textarea 
                                            rows="12" 
                                            class="shadow text-[14px] appearance-none border rounded-[12px] w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                            placeholder="comment" 
                                            v-validate='required' 
                                            name='comment'
                                        ></textarea>
                                    </div>

                                    <button
                                        class="m-0 ml-[0px] block mx-auto w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                                        type='submit'
                                    >
                                        @lang('shop::app.products.submit-review')
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- Write Review Section -->

                        <!-- Review List Section -->
                        <div>
                            <div class="flex items-center justify-between gap-[15px] max-sm:flex-wrap">
                                <h3 class="font-dmserif text-[30px] max-sm:text-[22px]">
                                    @lang('shop::app.products.customer-review')
                                </h3>

                                <div 
                                    class="flex gap-x-[15px] items-center rounded-[12px] border border-navyBlue px-[15px] py-[10px]"
                                    @click='if (info) info = false; writeReview = true;'
                                >
                                    <span class="icon-pen text-[24px]"></span> 
                                    @lang('shop::app.products.write-a-review')
                                </div>
                            </div>

                            <div class="flex justify-between items-center gap-[15px] mt-[30px] max-w-[365px] max-sm:flex-wrap">
                                <p class="text-[30px] font-medium max-sm:text-[16px]">{{ number_format($avgRatings, 1) }}</p>
                                    
                                <x-shop::products.star-rating star='{{ $avgRatings }}' editable=false></x-shop::products.star-rating>

                                <p class="text-[12px] text-[#858585]">
                                    ({{ count($product->reviews) }} @lang('shop::app.products.customer-review'))
                                </p>
                            </div>

                            <div class="flex gap-x-[20px] items-center">
                                <div class="flex gap-y-[18px] max-w-[365px] mt-[10px] flex-wrap">

                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                        <div class="text-[16px] font-medium">{{ $i }} Stars</div>
                                        <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                            <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: {{ $percentageRatings[$i] }}%"></div>
                                        </div>
                                    </div>
                                @endfor
                                
                                </div>
                            </div>

                            <div class="grid grid-cols-[1fr_1fr] mt-[60px] gap-[20px] max-1060:grid-cols-[1fr]">

                                <!-- Single card review -->
                                <div 
                                    v-for='review in reviews' 
                                    class="flex gap-[20px] border border-[#e5e5e5] rounded-[12px] p-[25px] max-sm:flex-wrap"
                                >
                                    <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
                                        <img 
                                            class="rounded-[12px]" 
                                            src='{{ bagisto_asset("images/review-man.png") }}' 
                                            title="" 
                                            alt=""
                                        >
                                    </div>

                                    <div>
                                        <div class="flex justify-between">
                                            <p class="text-[20px] font-medium max-sm:text-[16px]">
                                                @{{ review.name }}
                                            </p>

                                            <div class="flex items-center">
                                                <x-shop::products.star-rating :star="'review.name'" editable=false></x-shop::products.star-rating>
                                            </div>
                                        </div>
                                        <p class="text-[14px] font-medium mt-[10px] max-sm:text-[12px]">
                                            @{{ review.created_at }}
                                        </p>
                                        
                                        <p class="text-[16px] text-[#7D7D7D] mt-[20px] max-sm:text-[12px]">
                                            @{{ review.comment }}
                                        </p>

                                        <div class="flex justify-between items-center mt-[20px] flex-wrap gap-[10px]">
                                            <div class="flex gap-x-[10px]">
                                                <p class="text-[16px] text-[#7D7D7D] max-sm:text-[12px]">
                                                    @lang('shop::app.products.was-this-helpful')
                                                </p>

                                                <div class="flex gap-[8px] text-[#7D7D7D]">
                                                    <span class="icon-like text-[24px] text-[#D1D1D1]"></span>
                                                    0
                                                </div>

                                                <div class="flex gap-[8px] text-[#7D7D7D]">
                                                    <span class="icon-dislike text-[24px] text-[#D1D1D1]"></span>
                                                    0
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Single card review -->
                            </div>

                            <button 
                                class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center"
                                @click='getReviews()'
                                v-if='reviews.length < {{ $product->reviews->count() }}'
                            >
                                @lang('shop::app.products.load-more')
                            </button>
                        </div>
                        <!-- Review List Section -->

                    </div>
                    </x-shop::tabs.item>
                </x-shop::tabs>
                {{-- End Tab section --}}
                
                {{-- Featured Products --}}
                <x-shop::products.carousel
                    {{-- @translations --}}
                    :title="trans('Related Products')"
                    :src="route('shop.products.related.index', ['id' => $product->id])"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::products.carousel>

                {{-- Upsells Products. --}}
                <x-shop::products.carousel
                    {{-- @translations --}}
                    :title="trans('We found other products you might like!')"
                    :src="route('shop.products.up-sell.index', ['id' => $product->id])"
                    :navigation-link="route('shop.home.index')"
                >
                </x-shop::products.carousel>
            </div>
            
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        productId: @json($product->id),

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

                        this.$axios.post('{{ route("shop.customers.cart.store") }}', params).then(response => {
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

                    updateQty(qty) {
                        this.qty = qty;
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