<div id ="mini-cart" class="bottom-0 left-0 pointer-events-none fixed right-0 top-0 z-[9999]">
    <div class="bg-[#00000025] bottom-0 left-0 pointer-events-auto absolute right-0 top-0 z-[1000]"></div>
    <div class="absolute right-0 top-0 bottom-0 z-[1000] bg-white w-[500px] max-w-[500px] overflow-hidden max-sm:w-full">
        <div class="bg-white h-full pointer-events-auto w-full overflow-auto">
            <div class="flex flex-col h-full w-full ">
                <div class="overflow-auto flex-1 min-h-0 min-w-0">
                    <div class="flex flex-col h-full">

                        {{-- Cart Header with offer message --}}
                        <div class="grid gap-y-[10px] p-[25px] pb-[20px]">
                            <div class="flex justify-between items-center">
                                <p class="text-[26px] font-medium">
                                    @lang('shop::app.checkout.cart.shopping-cart')
                                </p>

                                <span class="icon-cancel text-[30px] cursor-pointer" onclick="closeCart()"></span>
                            </div>

                            <p class="text-[16px]">
                                @lang('shop::app.checkout.cart.offer-on-orders')
                            </p>
                        </div>

                        {{-- Product Listing in cart --}}
                        <div class="px-[25px] overflow-auto flex-1" v-if="cartProduct > 0">
                            <div class="grid gap-[50px] mt-[35px]">
                                <v-mini-cart {{ $attributes }} :cartProduct="cartProduct"></v-mini-cart>
                            </div>
                        </div>

                        <div class="pb-[30px]" v-if="cartProduct > 0">
                            <div class="flex justify-between items-center mt-[60px] mb-[30px] pb-[8px] border-b-[1px] border-[#E9E9E9] px-[25px]">
                                <p class="text-[14px] font-medium text-[#7D7D7D]">
                                    @lang('shop::app.checkout.cart.subtotal')
                                </p>

                                <p class="text-[30px] font-semibold">
                                    $20.00
                                </p>
                            </div>

                            <div class="px-[25px]">
                                <div class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-full font-medium py-[15px] px-[43px] rounded-[18px] text-center cursor-pointer max-sm:px-[20px]">
                                    @lang('shop::app.checkout.cart.continue-to-checkout')
                                </div>
                            </div>
                        </div>

                        {{-- Empty Cart page --}}
                        <div class="pb-[30px]" v-else>
                            <div class="grid gap-y-[20px] b-0 place-items-center">
                                <img 
                                    class="" 
                                    src="{{ bagisto_asset('images/thank-you.png') }}" 
                                    alt="" 
                                    title=""
                                >

                                <p class="text-[20px]">
                                    @lang('shop::app.checkout.cart.empty-cart')
                                </p>

                                <div class="m-auto block mx-auto bg-navyBlue text-white text-base w-max font-medium py-[11px] px-[43px] rounded-[18px] text-center cursor-pointer">
                                    @lang('shop::app.checkout.cart.return-to-shop')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function closeCart() {
        document.getElementById('mini-cart').style.display = 'none';
    }
</script>

@pushOnce('scripts')
    <script type="text/x-template" id="v-mini-cart-template">        
        <div class="flex gap-x-[20px]">
            <div class="">
                <img 
                    class="max-w-[110px] max-h-[110px] rounded-[12px]"
                    src="{{ bagisto_asset('images/wishlist-user.png')}}" 
                    alt="" 
                    title=""
                >
            </div>

            <div class="grid gap-y-[10px]">
                <p class="text-[16px] font-medium">
                    @{{ cartProduct.name }}
                </p>

                <div class="flex gap-x-[10px] gap-y-[6px] flex-wrap">
                    <p class="text-[14px]">
                        @lang('shop::app.checkout.cart.item.color') Red
                    </p>

                    <p class="text-[14px]">
                        @lang('shop::app.checkout.cart.item.size') XL
                    </p>
                </div>

                <div class="flex gap-[20px] items-center flex-wrap">
                    <div class="flex gap-x-[20px] border rounded-[54px] border-navyBlue py-[5px] px-[14px] items-center max-w-[108px] max-h-[36px]">
                        <span class="bg-[position:-5px_-69px] bs-main-sprite w-[14px] h-[14px] cursor-pointer"></span>

                        <p>2</p>

                        <span class="bg-[position:-172px_-44px] bs-main-sprite w-[14px] h-[14px] cursor-pointer"></span>
                    </div>

                    <a class="text-[16px] text-[#4D7EA8]" href="/">
                        @lang('shop::app.checkout.cart.remove')
                    </a>
                </div>
            </div>
        </div>
    </script>
@endpushOnce
