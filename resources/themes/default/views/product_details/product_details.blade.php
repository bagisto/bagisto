<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type="number"] {
    -moz-appearance: textfield;
}
</style>



<section class="max-w-7xl mx-auto px-4 py-24">

    <!-- Top Section -->
    <div class="grid grid-cols-12 gap-10">

        <!-- Thumbnails -->
        <div class="col-span-1 flex flex-col gap-4">
            @foreach($otherImages as $galleryImage)
                <img 
                    src="{{ $galleryImage->url }}" 
                    class="w-16 h-16 object-cover rounded-lg cursor-pointer border"
                    alt="Product Image"
                >
            @endforeach
        </div>

        <!-- Main Image -->
        <div class="col-span-5">
            <div class="relative">
                <img 
                    src="{{ $productFlat->product->base_image_url ?? asset('images/main-product.jpg') }}"
                    alt="{{ $productFlat->name }}"
                    class="w-full h-[420px] object-cover rounded-xl"
                >

                <!-- Wishlist Icon -->
                <div class="absolute top-4 right-4 bg-white p-2 rounded-full shadow">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M20.8 4.6a5.5 5.5 0 00-7.8 0L12 5.6l-1-1a5.5 5.5 0 10-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 000-7.8z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-span-6 flex flex-col justify-start">

            <!-- Product Name -->
            <h1 class="font-oswald font-normal uppercase 
                       text-[60px] leading-[100%] tracking-[0%] 
                       text-[#371E0F] mb-6">
                {{ $productFlat->name }}
            </h1>

            <!-- Price -->
            <div class="mb-2">
                <span class="font-oswald font-normal uppercase 
                             text-[36px] leading-[100%] tracking-[0%] 
                             text-[#DFAA8B]">
                    {{ core()->currency($productFlat->price) }}
                </span>

                @if($stockQty < 1)
                    <span class="text-xs bg-gray-200 text-gray-600 px-3 py-1 rounded-full">
                        Sold out
                    </span>
                @endif

            </div>

            <!-- Tax Text -->
            <p class="font-roboto font-normal 
                      text-[12px] leading-[24px] tracking-[0.02em] 
                      text-[#371E0F] mb-10">
                All prices include tax
            </p>

            <!-- Info Boxes -->

            <div class="grid grid-cols-2 gap-8 mb-12">

    <!-- No Address Hassle -->
    <div class="bg-[#F1F1F1] rounded-[14px] 
                px-6 py-5 
                flex items-center gap-4">

        <!-- Icon -->
        <div class="text-[#E6A57E]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M12 21s-6-5.4-6-10a6 6 0 1112 0c0 4.6-6 10-6 10z"/>
                <circle cx="12" cy="11" r="2"/>
            </svg>
        </div>

        <!-- Text -->
        <div>
            <p class="font-oswald text-[18px] leading-[100%] text-black">
                No Address Hassle
            </p>
            <p class="font-roboto text-[14px] leading-[24px] tracking-[0.02em] text-black/70">
                We will collect the address for you
            </p>
        </div>
    </div>


    <!-- Free Delivery -->
    <div class="bg-[#F1F1F1] rounded-[14px] 
                px-6 py-5 
                flex items-center gap-4">

        <!-- Icon -->
        <div class="text-[#E6A57E]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
                viewBox="0 0 24 24">
                <path d="M3 7h13v8H3z"/>
                <path d="M16 10h3l2 3v2h-5z"/>
                <circle cx="7.5" cy="18.5" r="1.5"/>
                <circle cx="17.5" cy="18.5" r="1.5"/>
            </svg>
        </div>

        <!-- Text -->
        <div>
            <p class="font-oswald text-[18px] leading-[100%] text-black">
                Free delivery
            </p>
            <p class="font-roboto text-[14px] leading-[24px] tracking-[0.02em] text-black/70">
                on orders over AED 300
            </p>
        </div>
    </div>

</div>

   @if($stockQty > 0)
<form action="{{ route('shop.add.cart',$productFlat->url_key) }}" method="POST" class="flex items-center gap-6">
    @csrf
    <div class="flex items-center border-2 border-[#DFAA8B] rounded-[50px] overflow-hidden h-[47px]">
        <button type="button"
                onclick="this.parentNode.querySelector('input[type=number]').stepDown()"
                class="px-4 text-[#371E0F] text-xl font-semibold">
            −
        </button>

        <input type="number"
               name="quantity"
               value="1"
               min="1"
               class="w-14 text-center outline-none text-[#371E0F] font-roboto bg-transparent">

        <button type="button"
                onclick="this.parentNode.querySelector('input[type=number]').stepUp()"
                class="px-4 text-[#371E0F] text-xl font-semibold">
            +
        </button>
    </div>

    <!-- Add To Cart Button -->
    <button type="submit"
        class="w-[228px] h-[47px] 
               bg-[#DFAA8B] text-[#371E0F] 
               rounded-[50px] 
               px-[32px] 
               font-roboto text-sm tracking-wide 
               transition-all duration-300 
               hover:opacity-90 hover:scale-[1.02]">
        Add to Cart
    </button>
</form>
@else
<button
    class="w-[228px] h-[47px] 
           bg-gray-400 text-white 
           rounded-[50px] 
           px-[32px] 
           font-roboto text-sm tracking-wide 
           cursor-not-allowed"
    disabled>
    Out of Stock
</button>
@endif

        </div>

    </div>

    <!-- Description Section -->
    <div class="mt-20">

        <h2 class="font-oswald uppercase text-[28px] text-[#371E0F] mb-6">
            Description
        </h2>

        <p class="text-[#371E0F]/80 text-sm leading-relaxed mb-10 max-w-3xl">
            {{ Str::limit(strip_tags($productFlat->short_description), 200) }}
        </p>

    </div>

</section>