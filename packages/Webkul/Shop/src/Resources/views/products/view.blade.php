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
                                <h1 class="text-[30px] font-medium max-sm:text-[20px]">
                                    {{ $product->name }}
                                </h1>

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
                            
                            {!! view_render_event('bagisto.shop.products.price.before', ['product' => $product]) !!}
                            
                            <p class="text-[24px] font-medium mt-[25px] max-sm:mt-[15px] max-sm:text-[18px]">
                                {!! $product->getTypeInstance()->getPriceHtml() !!}
                            </p>

                            {!! view_render_event('bagisto.shop.products.price.after', ['product' => $product]) !!}

                            {!! view_render_event('bagisto.shop.products.short_description.before', ['product' => $product]) !!}

                            <p class="text-[18px] text-[#7D7D7D] mt-[25px] max-sm:text-[14px] max-sm:mt-[15px]">
                                {!! $product->short_description !!}
                            </p>

                            {!! view_render_event('bagisto.shop.products.short_description.after', ['product' => $product]) !!}
                            
                            <div class="flex gap-[15px] mt-[30px] max-w-[470px]">

                                {!! view_render_event('bagisto.shop.products.view.quantity.before', ['product' => $product]) !!}
                                
                                @include('shop::products.view.quantity-changer')

                                {!! view_render_event('bagisto.shop.products.view.quantity.after', ['product' => $product]) !!}
                                
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

                <div class="flex bg-[#F5F5F5] pt-[18px] gap-[30px] justify-center mt-20 max-1180:hidden">
                    <div 
                        :class="`${ info ? 'text-black border-b-[2px]' : 'text-[#7D7D7D]' } text-[20px] font-medium pb-[18px] px-[30px] border-navyBlue cursor-pointer`" 
                        @click='if (review) review = false; info = true;'
                    >
                        @lang('shop::app.products.product-description')
                    </div>

                    <div class="text-[20px] font-medium text-[#7D7D7D] pb-[18px] px-[30px]">
                        @lang('shop::app.products.additional-information')
                    </div>

                    <div 
                        :class="`${ review ? 'text-black border-b-[2px]' : 'text-[#7D7D7D]' } border-navyBlue text-[20px] font-medium text-[#7D7D7D] pb-[18px] px-[30px] cursor-pointer`" 
                        @click='if (info) info = false; review = true;'
                    >
                        @lang('shop::app.products.review') ({{ count($product->reviews) }})
                    </div>
                </div>

                <!-- Product Description -->
                <div class="container mt-[60px] max-1180:px-[20px]">

                    {!! view_render_event('bagisto.shop.products.view.description.before', ['product' => $product]) !!}

                    <div v-if='info'>
                        <div class="flex justify-between mb-[20px] max-1180:after:content-' ' max-1180:after:bs-main-sprite max-1180:after:bg-[position:-73px_-40px] max-1180:after:w-[21px] max-1180:after:h-[20px] max-1180:after:block">

                            <p class="text-[16px] font-medium 1180:hidden">
                                @lang('shop::app.product-description')
                            </p>
                        </div>

                        <div>
                            <p class="text-[#7D7D7D] text-[18px] max-1180:text-[14px]">
                                {!! $product->description !!}
                            </p>
                        </div>
                    </div>

                    {!! view_render_event('bagisto.shop.products.view.description.after', ['product' => $product]) !!}

                    <div v-if='review'>
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
                                        
                                        <div class="flex">
                                            <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#ffb600]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#7D7D7D]"></span>
                                            <span class="icon-star-fill text-[24px] text-[#7D7D7D]"></span>
                                        </div>
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

                        <div >
                            <div class="flex items-center justify-between gap-[15px] max-sm:flex-wrap">
                                <h3 class="font-dmserif text-[30px] max-sm:text-[22px]">
                                    @lang('shop::app.products.customer-review')
                                </h3>

                                <div class="flex gap-x-[15px] items-center rounded-[12px] border border-navyBlue px-[15px] py-[10px]">
                                    <span class="icon-pen text-[24px]"></span> 
                                    @lang('shop::app.products.write-a-review')
                                </div>
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

                            <p class="text-[12px] text-[#858585]">
                                ({{ count($product->reviews) }} @lang('shop::app.products.customer-review'))
                            </p>
                        </div>

                        <!-- <div class="flex gap-y-[18px] max-w-[365px] mt-[10px] flex-wrap">
                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">
                                    5 star
                                </div>

                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 100%"></div>
                                </div>
                            </div>

                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">
                                    4 star
                                </div>

                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 80%"></div>
                                </div>
                            </div>

                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">
                                    3 star
                                </div>

                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 60%"></div>
                                </div>
                            </div>

                            <div class="flex gap-x-[25px] items-center max-sm:flex-wrap">
                                <div class="text-[16px] font-medium">
                                    2 star
                                </div>

                                <div class="h-[16px] w-[275px] max-w-full bg-[#E5E5E5] rounded-[2px]">
                                    <div class="h-[16px] bg-[#FEA82B] rounded-[2px]" style="width: 40%"></div>
                                </div>
                            </div>
                        </div> -->
                        
                        <div class="grid grid-cols-[1fr_1fr] mt-[60px] gap-[20px] max-1060:grid-cols-[1fr]">
                            <!-- Single card review -->
                            @foreach ($product->reviews as $review)
                                <div class="flex gap-[20px] border border-[#e5e5e5] rounded-[12px] p-[25px] max-sm:flex-wrap">
                                    <div class="min-h-[100px] min-w-[100px] max-sm:hidden">
                                        <img 
                                            class="rounded-[12px]" 
                                            src='{{ bagisto_asset("images/review-man.png") }}' 
                                            title="" 
                                            alt=""
                                        >
                                    </div>

                                    <div class="">
                                        <div class="flex justify-between">
                                            <p class="text-[20px] font-medium max-sm:text-[16px]">
                                                {{ $review->title }}
                                            </p>

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
                                        <p class="text-[14px] font-medium mt-[10px] max-sm:text-[12px]">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </p>
                                        
                                        <p class="text-[16px] text-[#7D7D7D] mt-[20px] max-sm:text-[12px]">
                                            {{ $review->comment }}
                                        </p>

                                        <div class="flex justify-between items-center mt-[20px] flex-wrap gap-[10px]">
                                            <div class="flex gap-x-[15px] item-center text-[#7D7D7D]">
                                                <span class="icon-share text-[24px] text-[#D1D1D1]"></span>
                                                @lang('shop::app.products.share')
                                            </div>

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
                            @endforeach
                        </div>

                        <button class="block mx-auto text-navyBlue text-base w-max font-medium py-[11px] px-[43px] border rounded-[18px] border-navyBlue bg-white mt-[60px] text-center">
                            @lang('shop::app.load-more')
                        </button>
                    </div>
                    
                </div>
            </div>
        </script>

        <script type="text/x-template" id="v-gallery-template">
            <div class="flex gap-[30px] max-1180:hidden h-max sticky top-[30px]">
				<div class="flex gap-[30px] max-w-[100px] max-h-[100px] flex-wrap">
					<img 
                        :class="`rounded-[12px] min-w-[100px] min-h-[100px] ${ hover ? 'cursor-pointer' : '' }`" 
                        v-for='image in mediaContents.images'
                        :src="image.small_image_url" 
                        alt="" 
                        title="" 
                        @mouseover='change(image)'
                    >

                    <!-- Need to Set Play Button  -->
                    <video 
                        class='rounded-[12px] min-w-[100px]'
                        v-for='video in mediaContents.videos'
                        @mouseover='change(video)'
                    >
                        <source 
                            :src="video.video_url" 
                            type="video/mp4"
                        >
                    </video>
				</div>
				<div class="max-h-[609px] max-w-[560px]">
					<img 
                        class="rounded-[12px] min-w-[450px]" 
                        :src="baseFile.path" 
                        alt="" 
                        title=""
                        v-if='baseFile.type == "image"'
                    >

                    <div class="rounded-[12px] min-w-[450px]" v-if='baseFile.type == "video"'>
                        <video  
                            controls                             
                            width='475'
                        >
                            <source 
                                :src="baseFile.path" 
                                type="video/mp4"
                            >
                        </video>    
                    </div>
                    
				</div>
			</div>

			<div class="flex gap-[30px] 1180:hidden overflow-auto scrollbar-hide">
                <img 
                    class="min-w-[450px] max-sm:min-w-full" 
                    v-for='mediaContent in mediaContents' 
                    :src="mediaContent.path" 
                    alt="" 
                    title=""
                >
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

                        qty: 1
                    }
                },

                methods: {
                    addToCart(buyNow) {
                        this.$axios.post('{{ route("shop.customers.cart.store") }}' ,{
                            'quantity': this.qty,
                            'product_id': this.productId,
                        }).then(response => {
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
                            'rating' : '4',
                            'title'  : this.$refs.review.title.value,
                        }).then(response => {
                            if (response.status == 200) alert(response.data.message); this.$refs.review.reset();
                        }).catch(error => { alert('Something went wrong')});
                    },

                    updateQty(qty) {
                        this.qty = qty;
                    }
                }
            })
        </script>
        
        <script type="module">
            app.component('v-gallery', {
                template: '#v-gallery-template',

                data() {
                    return {
                        mediaContents: {
                            images: @json(product_image()->getGalleryImages($product)),

                            videos: @json(product_video()->getVideos($product)),
                        },

                        baseFile: {
                            type: '',

                            path: ''
                        },

                        hover: false,
                    }
                },

                mounted() {
                    if (this.mediaContents.images.length) {
                        this.baseFile.type = 'image';
                        this.baseFile.path = this.mediaContents.images[0].large_image_url;
                    } else {
                        this.baseFile.type = this.mediaContents.videos[0].type;
                        this.baseFile.path = this.mediaContents.videos[0].video_url;
                    }
                },

                methods: {
                    change(file) {
                        if (file.type == 'video') {
                            this.baseFile.type = file.type;
                            this.baseFile.path = file.video_url;
                        } else {
                            this.baseFile.type = 'image';
                            this.baseFile.path = file.large_image_url;
                        }
                        
                        this.hover = true;
                    }
                }
            })
        </script>
    @endPushOnce
</x-shop::layouts>
