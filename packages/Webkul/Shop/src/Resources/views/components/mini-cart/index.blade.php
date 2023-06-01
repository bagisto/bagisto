<div id ="mini-cart" class="bottom-0 left-0 pointer-events-none fixed right-0 top-0 z-[9999]">
    <div class="bg-[#00000025] bottom-0 left-0 pointer-events-auto absolute right-0 top-0 z-[1000]"></div>
    <div class="absolute right-0 top-0 bottom-0 z-[1000] bg-white w-[500px] max-w-[500px] overflow-hidden max-sm:w-full">
        <div class="bg-white h-full pointer-events-auto w-full overflow-auto">
            <div class="flex flex-col h-full w-full ">
                <div class="overflow-auto flex-1 min-h-0 min-w-0">
                    <div class="flex flex-col  h-full">
                        <div class="grid gap-y-[10px] p-[25px] pb-[20px]">
                            <div class="flex justify-between items-center">
                                <p class="text-[26px] font-medium">
                                    @lang('shop::app.components.mini-cart.shopping-cart')
                                </p>
                                <span class="icon-cancel text-[30px] cursor-pointer" onclick="closeCart()"></span>
                            </div>
                            
                            <p class="text-[16px]">
                                @lang('shop::app.components.mini-cart.offer-on-orders')
                            </p>
                        </div>
                        <div class="px-[25px] overflow-auto flex-1">
                            <div class="grid gap-[50px] mt-[35px]">
                                <div class="flex gap-x-[20px]">
                                    <div class="">
                                        <img 
                                            class="max-w-[110px] max-h-[110px] rounded-[12px]"
                                            src="{{ bagisto_asset('images/wishlist-user.png')}}" 
                                            alt="" title=""
                                        >
                                    </div>
                                    <div class="grid gap-y-[10px]">
                                        <p class="text-[16px] font-medium">Slim High Ankle Jeans</p>
                                        <div class="flex gap-x-[10px] gap-y-[6px] flex-wrap">
                                            <p class="text-[14px]">Color: black</p>
                                            <p class="text-[14px]">Size: Xl</p>
                                        </div>
                                        <div class="flex gap-[20px] items-center flex-wrap">
                                            <div
                                                class="flex gap-x-[20px] border rounded-[54px] border-navyBlue py-[5px] px-[14px] items-center max-w-[108px] max-h-[36px]">
                                                <span
                                                    class="bg-[position:-5px_-69px] bs-main-sprite w-[14px] h-[14px] cursor-pointer"></span>
                                                <p>2</p>
                                                <span
                                                    class="bg-[position:-172px_-44px] bs-main-sprite w-[14px] h-[14px] cursor-pointer"></span>
                                            </div>
                                            <a class="text-[16px] text-[#4D7EA8]" href="/">Remove</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-x-[20px]">
                                    <div class="">
                                        <img class="max-w-[110px] max-h-[110px] rounded-[12px]"
                                            src="{{ bagisto_asset('images/wishlist-user.png')}}" alt="" title="">
                                    </div>
                                    <div class="grid gap-y-[10px]">
                                        <p class="text-[16px] font-medium">Slim High Ankle Jeans</p>
                                        <div class="flex gap-x-[10px] gap-y-[6px] flex-wrap">
                                            <div class="">
                                                <p class=" flex gap-x-[15px] text-[16px] items-center">See Details
                                                    <span class="icon-arrow-down text-[24px]"></span>
                                                </p>
                                            </div>
                                            <div class="grid gap-[8px]">
                                                <div class="">
                                                    <p class="text-[14px] font-medium">Sprite Stasis Ball:</p>
                                                    <p class="text-[14px]"> Sprite Stasis Ball 55 cm $23.00</p>
                                                </div>
                                                <div class="">
                                                    <p class="text-[14px] font-medium">Sprite Stasis Ball:</p>
                                                    <p class="text-[14px]"> Sprite Stasis Ball 55 cm $23.00</p>
                                                </div>
                                                <div class="">
                                                    <p class="text-[14px] font-medium">Sprite Stasis Ball:</p>
                                                    <p class="text-[14px]"> Sprite Stasis Ball 55 cm $23.00</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-[20px] items-center flex-wrap">
                                            <div
                                                class="flex gap-x-[20px] border rounded-[54px] border-navyBlue py-[5px] px-[14px] items-center max-w-[108px] max-h-[36px]">
                                                <span
                                                    class="bg-[position:-5px_-69px] bs-main-sprite w-[14px] h-[14px]"></span>
                                                <p>2</p>
                                                <span
                                                    class="bg-[position:-172px_-44px] bs-main-sprite w-[14px] h-[14px]"></span>
                                            </div>
                                            <a class="text-[16px] text-[#4D7EA8]" href="/">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pb-[30px]">
                            <div class="flex justify-between items-center mt-[60px] mb-[30px] pb-[8px] border-b-[1px] border-[#E9E9E9] px-[25px]">
                                <p class="text-[14px] font-medium text-[#7D7D7D]">
                                    @lang('shop::app.components.mini-cart.subtotal')
                                </p>

                                <p class="text-[30px] font-semibold">
                                    $20.00
                                </p>
                            </div>

                            <div class="px-[25px]">
                                <div class="m-0 ml-[0px] block mx-auto bg-navyBlue text-white text-base w-full font-medium py-[15px] px-[43px] rounded-[18px] text-center cursor-pointer max-sm:px-[20px]">
                                    @lang('shop::app.components.mini-cart.continue-to-checkout')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>