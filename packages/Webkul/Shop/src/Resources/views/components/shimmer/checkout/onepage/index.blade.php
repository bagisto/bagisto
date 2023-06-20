<div class="container px-[60px] max-lg:px-[30px] max-sm:px-[15px]">
    {{-- Breadcrumb --}}
    <div class="flex justify-start mt-[30px] max-lg:hidden">
        <div class="flex gap-x-[14px] items-center">
            <p class="flex items-center gap-x-[14px] text-[16px] font-medium">
                {{-- @translations --}}
                @lang('Home')
                <span class="icon-arrow-right text-[24px]"></span>
            </p>
            <p class="text-[#7D7D7D] text-[12px] flex items-center gap-x-[16px] font-medium  after:content[' '] after:bg-[position:-7px_-41px] after:bs-main-sprite after:w-[9px] after:h-[20px] after:last:hidden">
                {{-- @translations --}}
                @lang('Checkout')
            </p>
        </div>
    </div>

    <div class="grid grid-cols-[1fr_auto] gap-[30px] max-lg:grid-cols-[1fr]">
        <div class="grid gap-[30px] mt-[30px]">
            {{-- Billing Address --}}
            <div>
                <div class="flex justify-between items-center">
                    <h2 class="w-[180px] h-[39px] shimmer"></h2>
                    <span class="w-[24px] h-[24px] shimmer"></span>
                </div>

                <div>
                    <div class="grid mt-[30px] gap-[20px] grid-cols-2 max-1060:grid-cols-[1fr] max-lg:grid-cols-2 max-sm:grid-cols-1 max-sm:mt-[15px]">
                        {{-- Single card addredd --}}
                        <div class="border border-[#e5e5e5] rounded-[12px] p-[20px] max-w-[414px] max-sm:flex-wrap">
                            <div class="flex justify-between items-center gap-[10px]">
                                <p class="w-[50px] h-[24px] shimmer"></p>
                                <div class="flex gap-[25px] items-center">
                                    <div class="w-[106px] h-[28px] shimmer m-0 ml-[0px] block rounded-[10px]"></div>
                                </div>
                            </div>
                            <p class="w-[100%] h-[24px] shimmer mt-[25px]"></p>
                            <p class="w-[70%] h-[24px] shimmer mt-[10px]"></p>
                        </div>

                        {{-- Single card addredd --}}
                        <div
                            class="flex justify-center items-center border border-[#e5e5e5] rounded-[12px] p-[20px] max-w-[414px] max-sm:flex-wrap">
                            <div class="flex gap-x-[10px] items-center cursor-pointer">
                                <span class="w-[52px] h-[52px] shimmer rounded-full"></span>
                                <p class="w-[110px] h-[24px] shimmer"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="w-[40%] h-[21px] shimmer mt-[20px]"></p>
            </div>

            {{-- Shipping method --}}
            <div>
                <div class="flex justify-between items-center">
                    <h2 class="w-[30%] h-[39px] shimmer"></h2>
                    <span class="w-[24px] h-[24px] shimmer"></span>
                </div>

                <div>
                    <div class="flex flex-wrap gap-[30px] mt-[30px]">
                        <div class="relative max-w-[218px] select-none max-sm:max-w-full max-sm:flex-auto ">
                            <input type="radio" id="flat-rate-shipping" name="shipping" value=""
                                class="hidden peer">
                            <span
                                class="w-[24px] h-[24px] shimmer  rounded-full absolute right-[20px] top-[20px]"></span>
                            <label class="block border border-[#E9E9E9] p-[20px] rounded-[12px] ">
                                <span class="block w-[60px] h-[60px] shimmer"></span>
                                <p class="w-[60%] h-[37.5px] shimmer mt-[5px]"></p>
                                <p class="w-[180px] h-[18px] shimmer mt-[10px]"></p>
                            </label>
                        </div>

                        <div class="relative max-w-[218px] select-none max-sm:max-w-full max-sm:flex-auto ">
                            <input type="radio" id="flat-rate-shipping" name="shipping" value=""
                                class="hidden peer">
                            <span
                                class="w-[24px] h-[24px] shimmer  rounded-full absolute right-[20px] top-[20px]"></span>
                            <label class="block border border-[#E9E9E9] p-[20px] rounded-[12px] ">
                                <span class="block w-[60px] h-[60px] shimmer"></span>
                                <p class="w-[60%] h-[37.5px] shimmer mt-[5px]"></p>
                                <p class="w-[180px] h-[18px] shimmer mt-[10px]"></p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment method --}}
            <div>
                <div class="flex justify-between items-center">
                    <h2 class="w-[50%] h-[39px] shimmer"></h2>
                    <span class="w-[24px] h-[24px] shimmer"></span>
                </div>

                <div>
                    <div class="flex flex-wrap gap-[29px] mt-[30px]">
                        <div class="relative max-sm:max-w-full max-sm:flex-auto">
                            <input type="radio" id="paypal" value="" name="payment-method"
                                class="hidden peer">
                            <span
                                class="block w-[24px] h-[24px] shimmer rounded-full absolute right-[20px] top-[20px]"></span>
                            <label for="paypal"
                                class="block border border-[#E9E9E9] p-[20px] rounded-[12px] w-[190px] max-sm:w-full ">
                                <div class="w-[45px] h-[45px] shimmer"></div>
                                <p class="w-[100%] h-[21px] shimmer mt-[5px]"></p>
                                <p class="w-[70%] h-[18px] shimmer mt-[10px]"></p>
                            </label>
                        </div>

                        <div class="relative max-sm:max-w-full max-sm:flex-auto">
                            <input type="radio" id="paypal" value="" name="payment-method"
                                class="hidden peer">
                            <span
                                class="block w-[24px] h-[24px] shimmer rounded-full absolute right-[20px] top-[20px]"></span>
                            <label for="paypal"
                                class="block border border-[#E9E9E9] p-[20px] rounded-[12px] w-[190px] max-sm:w-full ">
                                <div class="w-[45px] h-[45px] shimmer"></div>
                                <p class="w-[100%] h-[21px] shimmer mt-[5px]"></p>
                                <p class="w-[70%] h-[18px] shimmer mt-[10px]"></p>
                            </label>
                        </div>

                        <div class="relative max-sm:max-w-full max-sm:flex-auto">
                            <input type="radio" id="paypal" value="" name="payment-method"
                                class="hidden peer">
                            <span
                                class="block w-[24px] h-[24px] shimmer rounded-full absolute right-[20px] top-[20px]"></span>
                            <label for="paypal"
                                class="block border border-[#E9E9E9] p-[20px] rounded-[12px] w-[190px] max-sm:w-full ">
                                <div class="w-[45px] h-[45px] shimmer"></div>
                                <p class="w-[100%] h-[21px] shimmer mt-[5px]"></p>
                                <p class="w-[70%] h-[18px] shimmer mt-[10px]"></p>
                            </label>
                        </div>

                        <div class="relative max-sm:max-w-full max-sm:flex-auto">
                            <input type="radio" id="paypal" value="" name="payment-method"
                                class="hidden peer">
                            <span
                                class="block w-[24px] h-[24px] shimmer rounded-full absolute right-[20px] top-[20px]"></span>
                            <label for="paypal"
                                class="block border border-[#E9E9E9] p-[20px] rounded-[12px] w-[190px] max-sm:w-full ">
                                <div class="w-[45px] h-[45px] shimmer"></div>
                                <p class="w-[100%] h-[21px] shimmer mt-[5px]"></p>
                                <p class="w-[70%] h-[18px] shimmer mt-[10px]"></p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{--Bottom Buttons --}}
            <div class="flex justify-between items-center flex-wrap gap-[15px] mb-[60px] max-sm:mb-[10px]">
                <div class="w-[40%] h-[24px] shimmer"></div>
                <div class="w-[30%] h-[46px] shimmer rounded-[18px] "></div>
            </div>
        </div>
        <div
            class="max-w-full w-[442px] pl-[30px] h-max sticky top-[30px] max-lg:w-auto max-lg:max-w-[442px] max-lg:pl-0 ">
            <h2 class="w-[50%] h-[39px] shimmer"></h2>
            <div class="grid border-b-[1px] border-[#E9E9E9] mt-[40px] max-sm:mt-[20px]">

                {{-- single card --}}
                <div class="flex gap-x-[15px] pb-[20px]">
                    <div class="w-[90px] h-[90px] rounded-md shimmer"></div>
                    <div>
                        <p class="w-[180px] h-[24px] shimmer"></p>
                        <p class="w-[80px] h-[27px] shimmer mt-[10px]"></p>
                    </div>
                </div>

                {{-- single card --}}
                <div class="flex gap-x-[15px] pb-[20px]">
                    <div class="w-[90px] h-[90px] rounded-md shimmer"></div>
                    <div>
                        <p class="w-[180px] h-[24px] shimmer"></p>
                        <p class="w-[80px] h-[27px] shimmer mt-[10px]"></p>
                    </div>
                </div>

                {{-- single card --}}
                <div class="flex gap-x-[15px] pb-[20px]">
                    <div class="w-[90px] h-[90px] rounded-md shimmer"></div>
                    <div>
                        <p class="w-[180px] h-[24px] shimmer"></p>
                        <p class="w-[80px] h-[27px] shimmer mt-[10px]"></p>
                    </div>
                </div>
            </div>

            <div class="grid gap-[15px] mt-[25px] mb-[30px]">
                <div class="flex text-right justify-between">
                    <p class="w-[20%] h-[24px] shimmer"></p>
                    <p class="w-[25%] h-[24px] shimmer"></p>
                </div>
                <div class="flex text-right justify-between">
                    <p class="w-[20] h-[24px] shimmer"></p>
                    <p class="w-[15%] h-[24px] shimmer"></p>
                </div>
                <div class="flex text-right justify-between">
                    <p class="w-[25%] h-[24px] shimmer"></p>
                    <p class="w-[35%] h-[24px] shimmer"></p>
                </div>
                <div class="flex text-right justify-between">
                    <p class="w-[35%] h-[24px] shimmer"></p>
                    <p class="w-[30%] h-[38px] shimmer"></p>
                </div>
            </div>
            
            <div class="w-[50%] h-[46px] shimmer rounded-[18px] max-sm:mb-[40px]"></div>
        </div>
    </div>
</div>
