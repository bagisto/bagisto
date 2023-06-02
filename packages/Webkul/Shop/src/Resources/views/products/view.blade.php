<x-shop::layouts>
    {!! view_render_event('bagisto.shop.products.view.before', ['product' => $product]) !!}

    <v-product></v-product>

    {!! view_render_event('bagisto.shop.products.view.after', ['product' => $product]) !!}

    @pushOnce('scripts')
        <script type="text/x-template" id="v-product-template">
            <div>
                <div class="container px-[60px] max-1180:px-[0px]">
                    <div class="flex mt-[48px] gap-[40px] max-1180:flex-wrap max-lg:mt-0 max-sm:gap-y-[25px]">
                        <v-gallery></v-gallery>

                        <div class="max-w-[590px] relative max-1180:px-[20px]">
                            <div class="flex justify-between gap-[15px]">
                                <h1 class="text-[30px] font-medium max-sm:text-[20px]">{{ $product->name }}</h1>
                                <div 
                                    class="flex border border-black items-center justify-center rounded-full min-w-[46px] min-h-[46px] max-h-[46px] bg-white cursor-pointer transition icon-heart text-[24px]  max-1180:absolute max-1180:-top-[82px] max-1180:right-[12px] max-1180:border-0"
                                    @click='addToWishlist()'
                                >
                                </div>
                            </div>
                            <div class="flex gap-[15px] items-center mt-[15px]">
                                <div class="flex gap-[10px]">
                                    <span class="bg-[position:-151px_-229px] bs-main-sprite w-[14px] h-[14px]"></span>
                                    <span class="bg-[position:-151px_-229px] bs-main-sprite w-[14px] h-[14px]"></span>
                                    <span class="bg-[position:-151px_-229px] bs-main-sprite w-[14px] h-[14px]"></span>
                                    <span class="bg-[position:-151px_-253px] bs-main-sprite w-[14px] h-[14px]"></span>
                                    <span class="bg-[position:-151px_-253px] bs-main-sprite w-[14px] h-[14px]"></span>
                                </div>
                                <p class="text-[#7D7D7D] text-[14px]">({{ count($product->reviews) }} reviews)</p>
                            </div>
                            <p class="text-[24px] font-medium mt-[25px] max-sm:mt-[15px] max-sm:text-[18px]">
                                {!! $product->getTypeInstance()->getPriceHtml() !!}
                            </p>
                            <p class="text-[18px] text-[#7D7D7D] mt-[25px] max-sm:text-[14px] max-sm:mt-[15px]">
                                {!! $product->short_description !!}
                            </p>
                            
                            <div class="flex gap-[15px] mt-[30px] max-w-[470px]">
                                <v-quantity-changer></v-quantity-changer>
                                
                                <button 
                                    class="rounded-[12px] border border-navyBlue py-[15px] w-full max-w-full"
                                    @click='addToCart("")'
                                >
                                    Add To Cart
                                </button>
                            </div>
                            <button
                                class="rounded-[12px] border bg-navyBlue text-white border-navyBlue py-[15px]  w-full max-w-[470px] mt-[20px]"
                                @click='addToCart("buy_now")'
                            >
                                Buy Now
                            </button>
                            <div class="flex gap-[35px] mt-[40px] max-sm:flex-wrap">
                                <div 
                                    class=" flex justify-center items-center gap-[10px] cursor-pointer" 
                                    @click='addToCompare()'
                                >
                                <span class="icon-compare text-[24px]"></span>
                                    Compare
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
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex bg-[#F5F5F5] pt-[18px] gap-[30px] justify-center mt-20 max-1180:hidden">
                    <div 
                        :class="`${ info ? 'text-black border-b-[2px]' : 'text-[#7D7D7D]' } text-[20px] font-medium pb-[18px] px-[30px] border-navyBlue cursor-pointer`" 
                        @click='if (review) review = false; info = true;'
                    >
                        Product Description
                    </div>
                    <div class="text-[20px] font-medium text-[#7D7D7D] pb-[18px] px-[30px]">Additional Information</div>
                    <div 
                        :class="`${ review ? 'text-black border-b-[2px]' : 'text-[#7D7D7D]' } border-navyBlue text-[20px] font-medium text-[#7D7D7D] pb-[18px] px-[30px] cursor-pointer`" 
                        @click='if (info) info = false; review = true;'
                    >
                        Review ({{ count($product->reviews) }})
                    </div>
                </div>

                <!-- Product Description -->
                <div class="container mt-[60px] max-1180:px-[20px]">
                    <div v-if='info'>
                        <div
                            class="flex justify-between mb-[20px] max-1180:after:content-' ' max-1180:after:bs-main-sprite max-1180:after:bg-[position:-73px_-40px] max-1180:after:w-[21px] max-1180:after:h-[20px] max-1180:after:block">
                            <p class="text-[16px] font-medium 1180:hidden">Product Description</p>
                        </div>
                        <div class="">
                            <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">{!! $product->short_description !!}</p>
                        </div>
                    </div>

                    <div v-if='review'>
                        <!-- Write Review Section -->
                        <div class="w-full">
                            <form 
                                ref='review' 
                                @submit.prevent='addToReview()' 
                                class='rounded mb-4 grid grid-cols-[auto_1fr] max-md:grid-cols-[1fr] gap-[40px] justify-center'
                            >
                                <div class="flex w-full">
                                    <label for="dropzone-file"
                                        class="flex flex-col w-[286px] h-[286px] items-center justify-center rounded-[12px] cursor-pointer bg-[#F5F5F5] hover:bg-gray-100 ">
                                        <div class="m-0 block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center">Add Image</div>
                                        <input id="dropzone-file" type="file" class="hidden" />
                                    </label>
                                </div>
                                
                                <div>
                                    <div class="">
                                        <label class="block text-gray-700 text-[12px] font-medium mb-2" for="username"> Rating </label>
                                        <div class="flex">
                                            <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#7D7D7D]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#7D7D7D]"></span>
                                        </div>
                                    </div>

                                    <div class="mb-4 mt-[15px]">
                                        <label class="block text-gray-700 text-[12px] font-medium mb-2" for="username"> Title </label>
                                        <input
                                            class="shadow text-[14px] appearance-none border rounded-[12px] w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                            id="username" type="text" placeholder="Username" name='title'>
                                    </div>

                                    <div class="mb-6">
                                        <label class="block text-gray-700 text-[12px] font-medium mb-2" for="password"> Comment </label>
                                        <textarea rows="12" class="shadow text-[14px] appearance-none border rounded-[12px] w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="comment" name='comment'></textarea>
                                    </div>

                                    <button
                                        class="m-0 ml-[0px] block mx-auto w-full bg-navyBlue text-white text-[16px] max-w-[374px] font-medium py-[16px] px-[43px] rounded-[18px] text-center"
                                        type='submit'
                                    >
                                        Submit Review
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div >
                            <div class="flex items-center justify-between gap-[15px] max-sm:flex-wrap">
                                <h3 class="font-dmserif text-[30px] max-sm:text-[22px]">Customer Reviews</h3>
                                <div class="flex gap-x-[15px] items-center rounded-[12px] border border-navyBlue px-[15px] py-[10px]"><span class="icon-pen text-[24px]"></span> Write a review</div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center gap-[15px] mt-[30px] max-w-[365px] max-sm:flex-wrap">
                            <div class="flex gap-x-[20px] items-center">
                                <p class="text-[30px] font-medium max-sm:text-[16px]">4.9</p>
                                <div class="flex">
                                    <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                    <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                    <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                    <span class="icon-star-fill text-[24px] text-[#7D7D7D]"></span>
                                    <span class="icon-star-fill text-[24px] text-[#7D7D7D]"></span>
                                </div>
                            </div>
                            <p class="text-[12px] text-[#858585]">(100 Customer Review)</p>
                        </div>

                        <div class="flex gap-y-[18px] max-w-[365px] mt-[10px] flex-wrap">
                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">5 Stars</div>
                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">4 Stars</div>
                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 80%"></div>
                                </div>
                            </div>
                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">3 Stars</div>
                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 60%"></div>
                                </div>
                            </div>
                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">2 Stars</div>
                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 40%"></div>
                                </div>
                            </div>

                        </div>
                        
                        <div class="grid grid-cols-[1fr_1fr] mt-[60px] gap-[20px] max-1060:grid-cols-[1fr]">
                            <!-- Single card review -->
                            @foreach ($product->reviews as $review)
                                <div class="flex gap-[20px] border border-[#e5e5e5] rounded-[12px] p-[25px] max-sm:flex-wrap">
                                    <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
                                        <img class="rounded-[12px]" src='{{ bagisto_asset("images/review-man.png") }}' title="" alt="">
                                    </div>

                                    <div class="">
                                        <div class="flex justify-between">
                                            <p class="text-[20px] font-medium max-sm:text-[16px]">{{ $review->title }}</p>
                                            <div class="flex items-center">
                                                {{-- For Active stars --}}
                                                @if ($review->rating)
                                                    @for($i = 1; $i <= $review->rating; $i++)
                                                        <span class="bg-[position:-151px_-229px] bs-main-sprite w-[14px] h-[14px]"></span>
                                                    @endfor
                                                @endif
                                            
                                                @php $remaining_stars = 5 - $review->rating; @endphp
                                            
                                                {{-- For Inactive stars --}}
                                                @if ($remaining_stars)
                                                    @for($i = 1; $i <= $remaining_stars; $i++)
                                                        <span class="bg-[position:-151px_-253px] bs-main-sprite w-[14px] h-[14px]"></span>
                                                    @endfor
                                                @endif
                                            </div>
                                        </div>
                                        <p class="text-[14px] font-medium mt-[10px] max-sm:text-[12px]">{{ $review->created_at->format('M d, Y') }}</p>
                                        <p class="text-[16px] text-[#7D7D7D] mt-[20px] max-sm:text-[12px]">{{ $review->comment }}</p>
                                        <div class="flex justify-between items-center mt-[20px] flex-wrap gap-[10px]">
                                            <div class="flex gap-x-[15px] item-center text-[#7D7D7D]"><span class="icon-share text-[24px] text-[#D1D1D1]"></span>Share</div>
                                            <div class="flex gap-x-[10px]">
                                                <p class="text-[16px] text-[#7D7D7D] max-sm:text-[12px]">Was This Review Helpful?</p>
                                                <div class="flex gap-[8px] text-[#7D7D7D]"><span class="icon-like text-[24px] text-[#D1D1D1]"></span>0</div>
                                                <div class="flex gap-[8px] text-[#7D7D7D]"><span class="icon-dislike text-[24px] text-[#D1D1D1]"></span>0</div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button
                            class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center">Load More
                        </button>
                    </div>
                    
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-product', {
                template: '#v-product-template',

                data() {
                    return {
                        productId: @json($product->id),

                        info: true,

                        review: false,
                    }
                },

                methods: {
                    addToCart(data) {
                        this.$axios.post('{{ route("shop.customers.cart.store") }}' ,{
                            'quantity': 1,
                            'product_id': this.productId,
                        }).then(response => {
                            alert(response.data.message);
                            if (data); //Redirect to Cart Page
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
                            'rating' : '4',
                            'title'  : this.$refs.review.title.value,
                        }).then(response => {
                            if (response.status == 200) alert(response.data.message); this.$refs.review.reset();
                        }).catch(error => {});
                    },
                }
            })
        </script>

        <script type="text/x-template" id="v-gallery-template">
            <div class="flex gap-[30px] max-1180:hidden h-max sticky top-[30px]">
				<div class="flex gap-[30px] max-w-[100px] max-h-[100px] flex-wrap">
					<img class="rounded-[12px] min-w-[100px]" src="{{ bagisto_asset('./images/graham-mans-slide.png') }}" alt="" title="">
					<img class="rounded-[12px] min-w-[100px]" src="{{ bagisto_asset('./images/graham-mans-slide.png') }}" alt="" title="">
					<img class="rounded-[12px] min-w-[100px]" src="{{ bagisto_asset('./images/graham-mans-slide.png') }}" alt="" title="">
					<img class="rounded-[12px] min-w-[100px]" src="{{ bagisto_asset('./images/graham-mans-slide.png') }}" alt="" title="">
				</div>
				<div class="max-h-[609px] max-w-[560px]">
					<img class="rounded-[12px] min-w-[450px]" src="{{ bagisto_asset('./images/graham-mans.png') }}" alt="" title="">
				</div>
			</div>
			<div class="flex gap-[30px] 1180:hidden overflow-auto scrollbar-hide">
				<img class="min-w-[450px]  max-sm:min-w-full" src="{{ bagisto_asset('./images/graham-mans.png') }}" alt="" title="">
				<img class="min-w-[450px]  max-sm:min-w-full" src="{{ bagisto_asset('./images/graham-mans.png') }}" alt="" title="">
				<img class="min-w-[450px]  max-sm:min-w-full" src="{{ bagisto_asset('./images/graham-mans.png') }}" alt="" title="">
				<img class="min-w-[450px]  max-sm:min-w-full" src="{{ bagisto_asset('./images/graham-mans.png') }}" alt="" title="">
				<img class="min-w-[450px]  max-sm:min-w-full" src="{{ bagisto_asset('./images/graham-mans.png') }}" alt="" title="">
			</div>
        </script>

        <script type="text/x-template" id="v-quantity-changer-template">
            <div class="flex gap-x-[25px] border rounded-[12px] border-navyBlue py-[15px] px-[26px] items-center">
                <span 
                    class="icon-plus text-[24px]"
                    @click='increase()'
                >
                </span>
                <p>@{{ qty }}</p>
                <span 
                    class="icon-minus text-[24px]"
                    @click='decrease()'
                >
                </span>
            </div>
        </script>
        
        <script type="module">
            app.component('v-gallery', {
                template: '#v-gallery-template',
            })
        </script>

        <script type="module">
            app.component('v-quantity-changer', {
                template: '#v-quantity-changer-template',

                data() {
                    return {
                        qty: 1
                    }
                },

                methods: {
                    decrease() {
                        if (this.qty > 1) this.qty -= 1;
                    },

                    increase() {
                        this.qty += 1;
                    }
                }
            });
        </script>
    @endPushOnce
</x-shop::layouts>